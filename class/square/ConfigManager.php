<?php
require_once(__DIR__ . '/../../includes/connection.php');

class ConfigManager {
    private $connection;

    public function __construct($connection = null) {
        if ($connection === null) {
            global $connection;
        }
        $this->connection = $connection;
    }
    private $defaultMappings = [
        [
            'key' => 'name',
            'local_field' => 'product',
            'square_field' => 'item_data.name',
            'required' => true,
            'description' => 'Product name'
        ],
        [
            'key' => 'sku',
            'local_field' => 'product_sku',
            'square_field' => 'item_variation_data.sku',
            'required' => true,
            'description' => 'Product SKU'
        ],
        [
            'key' => 'price',
            'local_field' => 'product_price',
            'square_field' => 'item_variation_data.price_money.amount',
            'required' => true,
            'description' => 'Product price'
        ],
        [
            'key' => 'quantity',
            'local_field' => 'qty',
            'square_field' => 'inventory.quantity',
            'required' => true,
            'description' => 'Product quantity'
        ],
        [
            'key' => 'description',
            'local_field' => 'product_desc',
            'square_field' => 'item_data.description',
            'required' => false,
            'description' => 'Product description'
        ],
        [
            'key' => 'category',
            'local_field' => 'category_title',
            'square_field' => 'item_data.category_title',
            'required' => false,
            'description' => 'Product category'
        ]
    ];

    public function initializeTables() {
        try {
            // Create settings table
            $query = "CREATE TABLE IF NOT EXISTS square_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(255) NOT NULL,
                setting_value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_key (setting_key)
            )";
            if (!$this->connection->query($query)) {
                throw new \Exception("Failed to create settings table");
            }

            // Create product mappings table
            $query = "CREATE TABLE IF NOT EXISTS square_product_mappings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(255) NOT NULL,
                local_field VARCHAR(255) NOT NULL,
                square_field VARCHAR(255) NOT NULL,
                is_required BOOLEAN DEFAULT FALSE,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_key (setting_key)
            )";
            if (!$this->connection->query($query)) {
                throw new \Exception("Failed to create product mappings table");
            }

            // Create webhook logs table
            $query = "CREATE TABLE IF NOT EXISTS square_webhook_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_type VARCHAR(255) NOT NULL,
                event_data TEXT,
                status ENUM('SUCCESS', 'ERROR', 'TEST', 'PENDING') NOT NULL DEFAULT 'PENDING',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            if (!$this->connection->query($query)) {
                throw new \Exception("Failed to create webhook logs table");
            }

            // Create sync settings table
            $query = "CREATE TABLE IF NOT EXISTS square_sync_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(255) NOT NULL,
                setting_value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_key (setting_key)
            )";
            if (!$this->connection->query($query)) {
                throw new \Exception("Failed to create sync settings table");
            }

            // Insert default mappings
            $this->insertDefaultMappings();

            return [
                'success' => true,
                'message' => "All Square tables initialized successfully"
            ];

        } catch (\Exception $e) {
            error_log("Error in initializeTables: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function insertDefaultMappings() {
        foreach ($this->defaultMappings as $mapping) {
            $query = "INSERT IGNORE INTO square_product_mappings 
                    (setting_key, local_field, square_field, is_required, description)
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->connection->prepare($query);
            if ($stmt) {
                $isRequired = (int)$mapping['required'];
                $stmt->bind_param('sssis', 
                    $mapping['key'],
                    $mapping['local_field'],
                    $mapping['square_field'],
                    $isRequired,
                    $mapping['description']
                );
                
                if (!$stmt->execute()) {
                    error_log("Failed to insert mapping {$mapping['key']}: " . $stmt->error);
                }
                $stmt->close();
            }
        }
    }

    public function updateSettings($settings) {
        try {
            // Only require access_token and environment if they are being updated
            if (isset($settings['access_token']) || isset($settings['environment'])) {
                if (empty($settings['access_token']) || empty($settings['environment'])) {
                    throw new \Exception("Access token and environment are required when updating API settings");
                }

                if (!in_array($settings['environment'], ['sandbox', 'production'])) {
                    throw new \Exception("Invalid environment. Must be 'sandbox' or 'production'");
                }
            }

            $this->connection->begin_transaction();

            try {
                foreach ($settings as $key => $value) {
                    $stmt = $this->connection->prepare(
                        "INSERT INTO square_settings (setting_key, setting_value) 
                         VALUES (?, ?) 
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
                    );
                    
                    if (!$stmt) {
                        throw new \Exception("Failed to prepare statement");
                    }

                    $stmt->bind_param('ss', $key, $value);
                    if (!$stmt->execute()) {
                        throw new \Exception("Failed to update setting: $key");
                    }
                    $stmt->close();
                }

                $this->connection->commit();
                return [
                    'success' => true,
                    'message' => "Settings updated successfully"
                ];

            } catch (\Exception $e) {
                $this->connection->rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            error_log("Error in updateSettings: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getSettings() {
        try {
            $query = "SELECT setting_key, setting_value FROM square_settings";
            $result = $this->connection->query($query);
            if (!$result) {
                throw new \Exception("Failed to retrieve settings");
            }

            $settings = [];
            while ($row = $result->fetch_assoc()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }

            return [
                'success' => true,
                'settings' => $settings
            ];

        } catch (\Exception $e) {
            error_log("Error in getSettings: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function resetConfiguration() {
        try {
            $this->connection->begin_transaction();
            
            try {
                // Delete all Square-related data
                $tables = [
                    'square_settings',
                    'square_product_mappings',
                    'square_location_mappings',
                    'square_webhook_logs',
                    'square_sync_settings'
                ];

                foreach ($tables as $table) {
                    if (!$this->connection->query("DELETE FROM $table")) {
                        throw new \Exception("Failed to clear table: $table");
                    }
                }

                $this->connection->commit();
                return [
                    'success' => true,
                    'message' => "Square configuration and all related data has been reset"
                ];

            } catch (\Exception $e) {
                $this->connection->rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            error_log("Error in resetConfiguration: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateWebhookSettings($signingKey) {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO square_settings (setting_key, setting_value) 
                 VALUES ('webhook_signing_key', ?) 
                 ON DUPLICATE KEY UPDATE setting_value = ?"
            );
            
            if (!$stmt) {
                throw new \Exception("Failed to prepare statement");
            }

            $stmt->bind_param('ss', $signingKey, $signingKey);
            if (!$stmt->execute()) {
                throw new \Exception("Failed to update webhook signing key");
            }
            $stmt->close();

            return [
                'success' => true,
                'message' => "Webhook settings updated successfully"
            ];

        } catch (\Exception $e) {
            error_log("Error in updateWebhookSettings: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function testApiConnection() {
        try {
            require_once(__DIR__ . '/../../vendor/autoload.php');
            
            // Get current settings
            $settingsResult = $this->getSettings();
            if (!$settingsResult['success']) {
                throw new \Exception("Failed to retrieve settings");
            }
            
            $settings = $settingsResult['settings'];
            if (empty($settings['access_token']) || empty($settings['environment'])) {
                throw new \Exception("API settings not configured");
            }

            // Initialize Square client
            $client = new \Square\SquareClient([
                'accessToken' => $settings['access_token'],
                'environment' => $settings['environment'] === 'production' ? 
                    \Square\Environment::PRODUCTION : 
                    \Square\Environment::SANDBOX
            ]);

            // Test connection by getting locations (a common API endpoint)
            $response = $client->getLocationsApi()->listLocations();
            if (!$response->isSuccess()) {
                $errors = $response->getErrors();
                throw new \Exception($errors[0]->getDetail());
            }

            $locations = $response->getResult()->getLocations();
            $locationName = count($locations) > 0 ? $locations[0]->getName() : 'Unknown';
            
            return [
                'success' => true,
                'message' => "Successfully connected to Square API. Found location: " . $locationName
            ];

        } catch (\Exception $e) {
            error_log("Error testing API connection: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function testWebhook() {
        try {
            // Get current settings
            $settingsResult = $this->getSettings();
            if (!$settingsResult['success']) {
                throw new \Exception("Failed to retrieve settings");
            }
            
            $settings = $settingsResult['settings'];
            if (empty($settings['webhook_signing_key'])) {
                throw new \Exception("Webhook signing key not configured");
            }

            // Log a test webhook event
            $testEvent = [
                'type' => 'test.webhook',
                'data' => ['test' => true],
                'timestamp' => date('Y-m-d H:i:s')
            ];

            // First, ensure the table has the correct structure
            $this->connection->query("ALTER TABLE square_webhook_logs MODIFY COLUMN status ENUM('SUCCESS', 'ERROR', 'TEST', 'PENDING') NOT NULL DEFAULT 'PENDING'");
            
            $stmt = $this->connection->prepare(
                "INSERT INTO square_webhook_logs (event_type, event_data, status) VALUES (?, ?, 'TEST')"
            );
            
            if (!$stmt) {
                throw new \Exception("Failed to prepare statement");
            }

            $eventData = json_encode($testEvent);
            $stmt->bind_param('ss', $testEvent['type'], $eventData);
            
            if (!$stmt->execute()) {
                throw new \Exception("Failed to log test webhook");
            }
            
            return [
                'success' => true,
                'message' => "Successfully logged test webhook event"
            ];

        } catch (\Exception $e) {
            error_log("Error testing webhook: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
