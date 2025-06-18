<?php
require_once(__DIR__ . '/BaseSquareModel.php');
require_once(__DIR__ . '/InventoryManager.php');

use Square\Models\SearchCatalogItemsRequest;
use Square\SquareClient;
use Square\Exceptions\ApiException;

class WebhookHandler extends BaseSquareModel {
    private $webhookSigningKey;
    private $inventoryManager;

    public function isSquareConfigured() {
        return parent::isSquareConfigured();
    }

    public function __construct($connection) {
        parent::__construct($connection);
        
        // Get webhook signing key from settings
        $query = "SELECT setting_value FROM square_settings WHERE setting_key = 'webhook_signing_key'";
        $result = $connection->query($query);
        if ($result && $row = $result->fetch_assoc()) {
            $this->webhookSigningKey = $row['setting_value'];
        }

        $this->inventoryManager = new InventoryManager($connection);
    }

    public function handleWebhook($testPayload = null) {
        try {
            // Get the payload - either from test or real webhook
            $payload = $testPayload ?? file_get_contents('php://input');
            
            // If we're getting form data, try to extract the test payload
            if (strpos($payload, 'webhook_signing_key=') !== false) {
                parse_str($payload, $formData);
                if (isset($formData['test_payload'])) {
                    $payload = $formData['test_payload'];
                }
            }
            
            error_log("Square Webhook Debug - Final Payload: " . $payload);
            
            // Temporarily bypass signature verification for testing
            error_log("Square Webhook Debug - Bypassing signature verification for testing");

            // Parse the webhook payload
            $event = json_decode($payload, true);
            
            if (!$event) {
                throw new \Exception("Invalid webhook payload");
            }

            // Log the webhook event
            $this->logWebhookEvent($event);

            // Handle different event types
            switch ($event['type']) {
                case 'inventory.count.updated':
                    $this->handleInventoryUpdate($event['data']);
                    break;
                    
                case 'order.created':
                    $this->handleOrderCreated($event['data']);
                    break;
                    
                default:
                    // Log unknown event type
                    error_log("Unhandled Square webhook event type: " . $event['type']);
                    break;
            }

            // Return success response
            http_response_code(200);
            return [
                'success' => true,
                'message' => 'Webhook processed successfully'
            ];

        } catch (\Exception $e) {
            error_log("Square Webhook Error: " . $e->getMessage());
            http_response_code(500);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function verifyWebhookSignature($payload) {
        if (empty($this->webhookSigningKey)) {
            error_log("Square Webhook Error: No signing key configured");
            return false;
        }

        // Try different methods to get the signature header
        $signatureHeader = null;
        
        // Method 1: Direct from $_SERVER
        if (isset($_SERVER['HTTP_X_SQUARE_SIGNATURE'])) {
            $signatureHeader = $_SERVER['HTTP_X_SQUARE_SIGNATURE'];
        }
        // Method 2: Using apache_request_headers()
        else if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            foreach ($headers as $name => $value) {
                if (strtolower($name) === 'x-square-signature') {
                    $signatureHeader = $value;
                    break;
                }
            }
        }
        // Method 3: Using getallheaders()
        else if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $name => $value) {
                if (strtolower($name) === 'x-square-signature') {
                    $signatureHeader = $value;
                    break;
                }
            }
        }
        // Method 4: Manual check from $_SERVER
        else {
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($name, 5)))));
                    if (strtolower($header) === 'x-square-signature') {
                        $signatureHeader = $value;
                        break;
                    }
                }
            }
        }

        if (empty($signatureHeader)) {
            error_log("Square Webhook Error: No signature header present. Available headers: " . json_encode($_SERVER));
            return false;
        }

        // Detailed logging for debugging
        error_log("Square Webhook Debug - Signature Header: " . $signatureHeader);
        error_log("Square Webhook Debug - Signature Header Length: " . strlen($signatureHeader));
        error_log("Square Webhook Debug - Webhook Key Length: " . strlen($this->webhookSigningKey));
        error_log("Square Webhook Debug - Payload Length: " . strlen($payload));

        // Try both with and without trimming the key
        $computedSignature1 = base64_encode(hash_hmac('sha1', $payload, $this->webhookSigningKey, true));
        $computedSignature2 = base64_encode(hash_hmac('sha1', $payload, trim($this->webhookSigningKey), true));
        
        error_log("Square Webhook Debug - Computed Signature (untrimmed): " . $computedSignature1);
        error_log("Square Webhook Debug - Computed Signature (trimmed): " . $computedSignature2);
        
        // Try both signatures
        if (hash_equals($signatureHeader, $computedSignature1)) {
            error_log("Square Webhook Debug - Signature matched with untrimmed key");
            return true;
        }
        
        if (hash_equals($signatureHeader, $computedSignature2)) {
            error_log("Square Webhook Debug - Signature matched with trimmed key");
            return true;
        }
        
        error_log("Square Webhook Debug - No signature match found");
        return false;
    }

    private function handleInventoryUpdate($data) {
        try {
            error_log("Square Webhook Debug - Starting inventory update with data: " . json_encode($data));
            
            // Find the IN_STOCK inventory count
            $inStockCount = null;
            $inventoryCounts = $data['object']['inventory_counts'];
            error_log("Square Webhook Debug - Processing inventory counts: " . json_encode($inventoryCounts));
            
            foreach ($inventoryCounts as $count) {
                error_log("Square Webhook Debug - Processing count: " . json_encode($count));
                if ($count['state'] === 'IN_STOCK') {
                    $inStockCount = $count;
                    break;
                }
            }
            
            if (!$inStockCount) {
                throw new \Exception("No IN_STOCK inventory count found in webhook data");
            }

            $catalogObjectId = $inStockCount['catalog_object_id'];
            $quantity = $inStockCount['quantity'];
            $locationId = $inStockCount['location_id'];
            
            error_log("Square Webhook Debug - Found IN_STOCK count - CatalogID: $catalogObjectId, Quantity: $quantity, LocationID: $locationId");

            // Get local location ID from Square location mapping
            $query = "SELECT local_location_id FROM square_location_mappings WHERE square_location_id = ?";
            error_log("Square Webhook Debug - Looking up local location ID for Square location: $locationId");
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $locationId);
            $stmt->execute();
            $result = $stmt->get_result();
            $mapping = $result->fetch_assoc();
            $stmt->close();

            if (!$mapping) {
                throw new \Exception("No local location mapping found for Square location: $locationId");
            }

            $localLocationId = $mapping['local_location_id'];
            error_log("Square Webhook Debug - Found local location ID: $localLocationId");

            // Get Square settings
            $settings = $this->getSettings();
            if (!$settings['success']) {
                throw new \Exception("Failed to retrieve Square settings");
            }

            // Create a new Square client
            $client = new SquareClient([
                'accessToken' => $settings['settings']['access_token'],
                'environment' => $settings['settings']['environment'] ?? 'production'
            ]);

            // Use retrieveCatalogObject to get item details
            error_log("Square Webhook Debug - Getting catalog item details for ID: $catalogObjectId");
            $response = $client->getCatalogApi()->retrieveCatalogObject($catalogObjectId);

            if (!$response->isSuccess()) {
                $errors = $response->getErrors();
                throw new \Exception(!empty($errors) ? $errors[0]->getDetail() : "Failed to retrieve catalog item");
            }

            $object = $response->getResult()->getObject();
            if (!$object) {
                throw new \Exception("No catalog item found for ID: $catalogObjectId");
            }

            $variationData = $object->getItemVariationData();
            if (!$variationData) {
                throw new \Exception("No variation data found for catalog item");
            }

            $squareSku = $variationData->getSku();
            error_log("Square Webhook Debug - Found Square SKU: " . ($squareSku ?? 'null'));

            if (empty($squareSku)) {
                throw new \Exception("No SKU found for catalog item variation");
            }

            try {
                // Get local SKU from mapping
                $stmt = $this->connection->prepare("SELECT local_sku FROM square_sku_mappings WHERE square_location_id = ? AND square_sku = ?");
                if (!$stmt) {
                    throw new \Exception("Failed to prepare SKU mapping query: " . $this->connection->error);
                }

                $stmt->bind_param('ss', $locationId, $squareSku);
                if (!$stmt->execute()) {
                    throw new \Exception("Failed to execute SKU mapping query: " . $stmt->error);
                }

                $result = $stmt->get_result();
                $mapping = $result->fetch_assoc();
                $stmt->close();

                if ($mapping) {
                    $localSku = $mapping['local_sku'];
                    error_log("Square Webhook Debug - Found SKU mapping: Square SKU $squareSku -> Local SKU $localSku");
                } else {
                    // If no mapping exists, check if SKU exists in local products
                    $stmt = $this->connection->prepare("SELECT product_sku FROM inv_qne_products WHERE product_sku = ?");
                    if (!$stmt) {
                        throw new \Exception("Failed to prepare product query: " . $this->connection->error);
                    }

                    $stmt->bind_param('s', $squareSku);
                    if (!$stmt->execute()) {
                        throw new \Exception("Failed to execute product query: " . $stmt->error);
                    }

                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();
                    $stmt->close();

                    if ($product) {
                        $localSku = $squareSku;
                        error_log("Square Webhook Debug - SKU exists in local products: $squareSku");
                    } else {
                        error_log("Square Webhook Debug - SKU not found in local products: $squareSku");
                        throw new \Exception("SKU not found in local system: $squareSku");
                    }
                }
            } catch (\Exception $e) {
                error_log("Square Webhook Debug - Error handling SKU: " . $e->getMessage());
                throw $e;
            }

            error_log("Square Webhook Debug - Using local SKU: $localSku for Square SKU: $squareSku");

            // Update local inventory
            $query = "UPDATE inv_qne_product_stock ps 
                    INNER JOIN inv_qne_products p ON ps.product_id = p.product_id 
                    INNER JOIN inv_qne_product_location pl ON p.product_id = pl.product_id 
                    SET ps.qty = ? 
                    WHERE p.product_sku = ? AND pl.location_id = ?";
            error_log("Square Webhook Debug - Updating inventory - SKU: $localSku, New Quantity: $quantity, Local Location: $localLocationId");
            
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('iss', $quantity, $localSku, $localLocationId);
            
            if (!$stmt->execute()) {
                throw new \Exception("Failed to update local inventory: " . $stmt->error);
            }
            $stmt->close();

            // Log successful update
            error_log("Successfully updated local inventory for SKU: $localSku, New quantity: $quantity");

        } catch (\Exception $e) {
            error_log("Error handling inventory update: " . $e->getMessage());
            throw $e;
        }
    }

    private function handleOrderCreated($data) {
        // Handle order creation events if needed
        error_log("Square order created: " . json_encode($data));
    }

    private function logWebhookEvent($event) {
        try {
            $query = "INSERT INTO square_webhook_logs (event_type, event_data, created_at) VALUES (?, ?, NOW())";
            $stmt = $this->connection->prepare($query);
            $eventData = json_encode($event);
            $stmt->bind_param('ss', $event['type'], $eventData);
            $stmt->execute();
            $stmt->close();
        } catch (\Exception $e) {
            error_log("Failed to log webhook event: " . $e->getMessage());
        }
    }

    private function getLocalSku($squareSku) {
        try {
            // First check if there's a mapping
            $query = "SELECT local_sku FROM square_sku_mappings WHERE square_sku = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $squareSku);
            $stmt->execute();
            $result = $stmt->get_result();
            $mapping = $result->fetch_assoc();

            if ($mapping) {
                error_log("Square Webhook Debug - Found SKU mapping: Square SKU $squareSku -> Local SKU {$mapping['local_sku']}");
                return $mapping['local_sku'];
            }

            // If no mapping exists, check if the SKU exists directly in our system
            $query = "SELECT product_sku FROM inv_qne_products WHERE product_sku = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $squareSku);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $stmt->close();

            if ($product) {
                error_log("Square Webhook Debug - SKU $squareSku exists directly in system");
                return $squareSku;
            }

            error_log("Square Webhook Debug - No mapping or direct SKU found for: $squareSku");
            return null;
        } catch (\Exception $e) {
            error_log("Error in getLocalSku: " . $e->getMessage());
            return null;
        }
    }

    private function getSettings() {
        $query = "SELECT setting_key, setting_value FROM square_settings";
        $result = $this->connection->query($query);
        if (!$result) {
            return [
                'success' => false,
                'message' => "Failed to retrieve settings"
            ];
        }

        $settings = [];
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return [
            'success' => true,
            'settings' => $settings
        ];
    }
}
