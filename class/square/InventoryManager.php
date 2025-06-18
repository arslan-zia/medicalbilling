<?php
require_once(__DIR__ . '/BaseSquareModel.php');
require_once(__DIR__ . '/CatalogManager.php');

use Square\Models\InventoryAdjustment;
use Square\Models\SearchCatalogItemsRequest;
use Square\Models\BatchRetrieveInventoryCountsRequest;
use Square\Models\BatchChangeInventoryRequest;
use Square\Exceptions\ApiException;

class InventoryManager extends BaseSquareModel {
    private $batchSize = 50; // Process products in batches to avoid rate limits
    private $catalogManager;

    public function __construct($connection = null) {
        parent::__construct($connection);
        $this->catalogManager = new CatalogManager($connection);
        
        // Ensure catalog mappings table exists
        $query = "CREATE TABLE IF NOT EXISTS square_catalog_mappings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            catalog_object_id VARCHAR(255) NOT NULL,
            square_sku VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_catalog_id (catalog_object_id),
            UNIQUE KEY unique_square_sku (square_sku)
        )";
        $this->connection->query($query);
    }

    public function syncInventoryToSquare($locationId) {
        try {
            // Ensure location mapping exists
            $stmt = $this->connection->prepare("SELECT square_location_id FROM square_location_mappings WHERE local_location_id = ?");
            if (!$stmt) {
                throw new \Exception("Failed to prepare location mapping query");
            }
            $stmt->bind_param('s', $locationId);
            if (!$stmt->execute()) {
                throw new \Exception("Failed to execute location mapping query");
            }
            $result = $stmt->get_result();
            $mapping = $result->fetch_assoc();
            $stmt->close();
            
            if (!$mapping) {
                // Create mapping table if it doesn't exist
                $this->connection->query("CREATE TABLE IF NOT EXISTS square_location_mappings (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    local_location_id VARCHAR(255) NOT NULL,
                    square_location_id VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_local_id (local_location_id),
                    UNIQUE KEY unique_square_id (square_location_id)
                )");
                
                // Get Square locations
                $locationsApi = $this->client->getLocationsApi();
                $result = $locationsApi->listLocations();
                if (!$result->isSuccess()) {
                    throw new \Exception("Failed to retrieve Square locations");
                }
                
                $locations = $result->getResult()->getLocations();
                if (empty($locations)) {
                    throw new \Exception("No locations found in Square account");
                }
                
                // Use first location if no mapping exists
                $squareLocationId = $locations[0]->getId();
                
                // Store the mapping
                $stmt = $this->connection->prepare(
                    "INSERT INTO square_location_mappings (local_location_id, square_location_id) VALUES (?, ?)"
                );
                if (!$stmt) {
                    throw new \Exception("Failed to prepare location mapping insert");
                }
                $stmt->bind_param('ss', $locationId, $squareLocationId);
                if (!$stmt->execute()) {
                    throw new \Exception("Failed to store location mapping");
                }
                $stmt->close();
                
                error_log("Square Debug - Created location mapping: Local={$locationId}, Square={$squareLocationId}");
            } else {
                $squareLocationId = $mapping['square_location_id'];
                error_log("Square Debug - Found existing location mapping: Local={$locationId}, Square={$squareLocationId}");
            }
            
            // Get products from local database using prepared statement
            $query = "SELECT p.*, ps.qty, 
                    COALESCE(pp.product_price, p.product_msrp) as product_price,
                    pc.category_id,
                    pc.category_title
                    FROM inv_qne_products p 
                    INNER JOIN inv_qne_product_stock ps ON p.product_id = ps.product_id 
                    INNER JOIN inv_qne_product_location pl ON p.product_id = pl.product_id
                    LEFT JOIN inv_qne_product_price pp ON p.product_id = pp.product_id AND pp.status = 1
                    LEFT JOIN inv_qne_product_category pc ON p.product_category = pc.category_id
                    WHERE pl.location_id = ? AND p.status = 1";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new \Exception("Failed to prepare products query: " . mysqli_error($this->connection->_connection));
            }
            $stmt->bind_param('s', $locationId);
            if (!$stmt->execute()) {
                throw new \Exception("Failed to execute products query: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $stmt->close();
            if (!$result) {
                throw new \Exception("Failed to get products: " . mysqli_error($this->connection->_connection));
            }

            $products = [];
            while ($product = $result->fetch_assoc()) {
                $products[] = $product;
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            
            // Process products in batches
            foreach (array_chunk($products, $this->batchSize) as $batch) {
                try {
                    // Add delay between batches to respect rate limits
                    if ($successCount + $errorCount > 0) {
                        sleep(1);
                    }
                    
                    foreach ($batch as $product) {
                        try {
                            $this->updateSquareInventory($product, $squareLocationId);
                            $successCount++;
                        } catch (\Exception $e) {
                            $errors[] = "Error updating product {$product['product_sku']}: " . $e->getMessage();
                            $errorCount++;
                            error_log("Square sync error for SKU {$product['product_sku']}: " . $e->getMessage());
                            
                            // If we hit a rate limit, pause for a longer time
                            if (strpos($e->getMessage(), 'rate limit') !== false) {
                                sleep(5);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    error_log("Batch processing error: " . $e->getMessage());
                    throw $e;
                }
            }

            $this->logSyncOperation(
                'sync_to_square',
                'success',
                "Inventory sync completed",
                [
                    'location_id' => $locationId,
                    'success_count' => $successCount,
                    'error_count' => $errorCount,
                    'errors' => $errors
                ]
            );
            
            return [
                'success' => true,
                'message' => "Inventory synced to Square successfully. Updated: $successCount, Errors: $errorCount",
                'details' => [
                    'updated' => $successCount,
                    'errors' => $errorCount,
                    'error_details' => $errors
                ]
            ];
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (strpos($message, 'Square is not properly configured') !== false) {
                $message .= "\n\nPlease ensure Square is configured properly:\n";
                $message .= "1. Go to square_config.php\n";
                $message .= "2. Enter your Square access token\n";
                $message .= "3. Select your environment (sandbox/production)\n";
                $message .= "4. Save the configuration\n";
                $message .= "5. Test the connection using the 'Test Connection' button";
            } elseif (strpos($message, 'No Square location mapping') !== false) {
                $message .= "\n\nPlease ensure location mapping exists:\n";
                $message .= "1. Go to square_config.php\n";
                $message .= "2. Configure Square settings if not already done\n";
                $message .= "3. The system will automatically map your first Square location\n";
                $message .= "4. Or use square_location_mapping.php to manually map locations";
            }
            
            $this->logSyncOperation(
                'sync_to_square',
                'error',
                $message,
                ['location_id' => $locationId]
            );
            return ['success' => false, 'message' => $message];
        }
    }

    public function syncInventoryFromSquare($locationId) {
        try {
            $squareLocationId = $this->getSquareLocationId($locationId);
            
            // Get all inventory from Square
            $inventoryApi = $this->client->getInventoryApi();
            $catalogApi = $this->client->getCatalogApi();
            
            $request = new BatchRetrieveInventoryCountsRequest();
            $request->setLocationIds([$squareLocationId]);
            
            $result = $inventoryApi->batchRetrieveInventoryCounts($request);
            if (!$result || !$result->getResult()) {
                throw new \Exception("Failed to retrieve Square inventory");
            }
            
            if ($result->getErrors()) {
                $errors = $result->getErrors();
                throw new \Exception(!empty($errors) ? $errors[0]->getDetail() : "Failed to retrieve Square inventory");
            }
            
            $counts = $result->getResult()->getCounts();
            if (empty($counts)) {
                return [
                    'success' => true,
                    'message' => "No inventory found in Square location",
                    'details' => ['updated' => 0, 'errors' => 0]
                ];
            }
            
            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            
            foreach ($counts as $count) {
                try {
                    $catalogId = $count->getCatalogObjectId();
                    $quantity = $count->getQuantity();
                    
                    error_log("Square Webhook Debug - Getting catalog item details for ID: " . $catalogId);
                    
                    // Try to get item by variation ID first
                    $catalogResult = $catalogApi->retrieveCatalogObject($catalogId);
                    
                    // If the direct lookup fails or item is invalid, try to find by SKU and recreate if needed
                    if (!$catalogResult || !$catalogResult->getResult() || !$catalogResult->getResult()->getObject() || 
                        !$catalogResult->getResult()->getObject()->getItemData()) {
                        error_log("Square Webhook Debug - Direct lookup failed or invalid item, trying to find by SKU...");
                        
                        // Get the SKU from the variation data
                        $variationResult = $catalogApi->retrieveCatalogObject($catalogId);
                        if ($variationResult && $variationResult->getResult() && $variationResult->getResult()->getObject()) {
                            $variation = $variationResult->getResult()->getObject();
                            if ($variation && $variation->getItemVariationData()) {
                                $sku = $variation->getItemVariationData()->getSku();
                                if ($sku) {
                                    error_log("Square Webhook Debug - Found SKU from variation: " . $sku);
                                    
                                    // Look up product in local database
                                    $stmt = $this->connection->prepare(
                                        "SELECT p.*, ps.qty, 
                                        COALESCE(pp.product_price, p.product_msrp) as product_price,
                                        pc.category_title
                                        FROM inv_qne_products p 
                                        LEFT JOIN inv_qne_product_stock ps ON p.product_id = ps.product_id
                                        LEFT JOIN inv_qne_product_price pp ON p.product_id = pp.product_id AND pp.status = 1
                                        LEFT JOIN inv_qne_product_category pc ON p.product_category = pc.category_id
                                        WHERE p.product_sku = ?"
                                    );
                                    
                                    if ($stmt) {
                                        $stmt->bind_param('s', $sku);
                                        if ($stmt->execute()) {
                                            $result = $stmt->get_result();
                                            if ($product = $result->fetch_assoc()) {
                                                error_log("Square Webhook Debug - Found local product, recreating in Square...");
                                                
                                                // Recreate the item in Square
                                                $catalogResult = $this->catalogManager->createOrUpdateProduct($product, $this->getSquareLocationId($locationId));
                                            }
                                        }
                                        $stmt->close();
                                    }
                                }
                            }
                        }
                    }
                    if (!$catalogResult || !$catalogResult->getResult() || !$catalogResult->getResult()->getObject()) {
                        throw new \Exception("Failed to retrieve catalog item details");
                    }
                    
                    $item = $catalogResult->getResult()->getObject();
                    if (!$item) {
                        throw new \Exception("No catalog object found for ID: " . $catalogId);
                    }
                    
                    // Check if item has variations
                    $itemData = $item->getItemData();
                    if (!$itemData) {
                        throw new \Exception("Item data is null for catalog item: " . $catalogId);
                    }

                    $variations = $itemData->getVariations();
                    if (!$variations || empty($variations)) {
                        throw new \Exception("No variations found for catalog item: " . $catalogId);
                    }

                    $variation = $variations[0];
                    if (!$variation) {
                        throw new \Exception("First variation is null for catalog item: " . $catalogId);
                    }

                    $variationData = $variation->getItemVariationData();
                    if (!$variationData) {
                        throw new \Exception("Variation data is null for catalog item: " . $catalogId);
                    }

                    $sku = $variationData->getSku();
                    if (!$sku) {
                        throw new \Exception("SKU is null for catalog item: " . $catalogId);
                    }
                    
                    error_log("Square Webhook Debug - Found Square SKU: " . $sku);
                    
                    // Verify SKU exists in local system
                    $verifyQuery = "SELECT COUNT(*) as count FROM inv_qne_products WHERE product_sku = ?";
                    $verifyStmt = $this->connection->prepare($verifyQuery);
                    if (!$verifyStmt) {
                        throw new \Exception("Failed to prepare SKU verification query");
                    }
                    $verifyStmt->bind_param('s', $sku);
                    if (!$verifyStmt->execute()) {
                        throw new \Exception("Failed to execute SKU verification query");
                    }
                    $verifyResult = $verifyStmt->get_result();
                    $verifyData = $verifyResult->fetch_assoc();
                    $verifyStmt->close();
                    
                    if ($verifyData['count'] == 0) {
                        throw new \Exception("SKU not found in local system: " . $sku);
                    }
                    
                    error_log("Square Webhook Debug - SKU exists in local products: " . $sku);
                    error_log("Square Webhook Debug - Using local SKU: " . $sku . " for Square SKU: " . $sku);
                    
                    // Update local inventory using prepared statement
                    $query = "UPDATE inv_qne_product_stock ps 
                            INNER JOIN inv_qne_products p ON ps.product_id = p.product_id 
                            INNER JOIN inv_qne_product_location pl ON p.product_id = pl.product_id 
                            SET ps.qty = ? 
                            WHERE p.product_sku = ? AND pl.location_id = ?";
                    
                    $stmt = $this->connection->prepare($query);
                    if (!$stmt) {
                        throw new \Exception("Failed to prepare update query: " . mysqli_error($this->connection->_connection));
                    }
                    $stmt->bind_param('iss', $quantity, $sku, $locationId);
                    if ($stmt->execute()) {
                        $stmt->close();
                        $successCount++;
                        error_log("Successfully updated local inventory for SKU: $sku, New quantity: $quantity");
                    } else {
                        throw new \Exception("Failed to update local inventory: " . mysqli_error($this->connection->_connection));
                    }
                    
                } catch (\Exception $e) {
                    // Initialize SKU as unknown if we couldn't get it
                    $errorSku = isset($sku) ? $sku : "Unknown";
                    $catalogIdMsg = isset($catalogId) ? " (Catalog ID: $catalogId)" : "";
                    
                    $errors[] = "Error updating SKU $errorSku$catalogIdMsg: " . $e->getMessage();
                    $errorCount++;
                    error_log("Square sync error for SKU $errorSku$catalogIdMsg: " . $e->getMessage());
                }
            }
            
            $this->logSyncOperation(
                'sync_from_square',
                'success',
                "Inventory sync completed",
                [
                    'location_id' => $locationId,
                    'success_count' => $successCount,
                    'error_count' => $errorCount,
                    'errors' => $errors
                ]
            );
            
            return [
                'success' => true,
                'message' => "Inventory synced from Square successfully. Updated: $successCount, Errors: $errorCount",
                'details' => [
                    'updated' => $successCount,
                    'errors' => $errorCount,
                    'error_details' => $errors
                ]
            ];
            
        } catch (\Exception $e) {
            $this->logSyncOperation(
                'sync_from_square',
                'error',
                $e->getMessage(),
                ['location_id' => $locationId]
            );
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function updateSquareInventory($product, $squareLocationId) {
        try {
            $inventoryApi = $this->client->getInventoryApi();
            $catalogApi = $this->client->getCatalogApi();
            
            // First check if we have a mapping for this SKU
            $stmt = $this->connection->prepare("SELECT catalog_object_id FROM square_catalog_mappings WHERE square_sku = ?");
            $squareItem = null;
            
            if ($stmt) {
                $stmt->bind_param('s', $product['product_sku']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($mapping = $result->fetch_assoc()) {
                    // We found a mapping, try to retrieve the catalog object directly
                    try {
                        $catalogResult = $catalogApi->retrieveCatalogObject($mapping['catalog_object_id']);
                        if ($catalogResult->isSuccess()) {
                            $squareItem = $catalogResult->getResult()->getObject();
                        }
                    } catch (\Exception $e) {
                        error_log("Failed to retrieve mapped catalog object: " . $e->getMessage());
                        // Continue to fallback if retrieval fails
                    }
                }
                $stmt->close();
            }
            
            if (!$squareItem) {
                // Fallback to SKU search if no mapping or retrieval failed
                $searchRequest = new SearchCatalogItemsRequest();
                $searchRequest->setProductTypes(['REGULAR']);
                $searchRequest->setTextFilter($product['product_sku']);
                
                $result = $catalogApi->searchCatalogItems($searchRequest);
                if ($result && $result->isSuccess() && !empty($result->getResult()->getItems())) {
                    $squareItem = $result->getResult()->getItems()[0];
                    
                    // Verify the found item has matching SKU
                    $itemData = $squareItem->getItemData();
                    $variations = $itemData ? $itemData->getVariations() : null;
                    $variation = $variations && !empty($variations) ? $variations[0] : null;
                    $variationData = $variation ? $variation->getItemVariationData() : null;
                    $foundSku = $variationData ? $variationData->getSku() : null;
                    
                    if ($foundSku === $product['product_sku']) {
                        // Store the mapping for future use
                        $stmt = $this->connection->prepare(
                            "INSERT INTO square_catalog_mappings (catalog_object_id, square_sku) 
                            VALUES (?, ?) 
                            ON DUPLICATE KEY UPDATE catalog_object_id = VALUES(catalog_object_id)"
                        );
                        if ($stmt) {
                            $catalogId = $squareItem->getId();
                            $sku = $product['product_sku'];
                            $stmt->bind_param('ss', $catalogId, $sku);
                            $stmt->execute();
                            $stmt->close();
                            error_log("Stored catalog mapping: SKU={$sku}, ID={$catalogId}");
                        }
                    } else {
                        error_log("Found Square item SKU mismatch. Expected: {$product['product_sku']}, Found: {$foundSku}");
                        $squareItem = null;
                    }
                }
                
            if (!$squareItem) {
                // Product doesn't exist in Square or SKU mismatch, create it
                $squareItem = $this->catalogManager->createOrUpdateProduct($product, $squareLocationId);
            } else {
                // Verify and update price if needed
                $itemData = $squareItem->getItemData();
                if ($itemData && !empty($itemData->getVariations())) {
                    $variation = $itemData->getVariations()[0];
                    $variationData = $variation->getItemVariationData();
                    if ($variationData && $variationData->getPriceMoney()) {
                        $currentPrice = $variationData->getPriceMoney()->getAmount();
                        $newPrice = $this->catalogManager->convertPriceToMinorUnits(floatval($product['product_price']));
                        
                        if ($currentPrice !== $newPrice) {
                            error_log("Square Debug - Price mismatch detected. Current: {$currentPrice}, New: {$newPrice}");
                            // Update the product with new price
                            $squareItem = $this->catalogManager->createOrUpdateProduct($product, $squareLocationId);
                        }
                    }
                }
            }
            }

            // Get the variation ID from the item with proper null checks
            $itemData = $squareItem->getItemData();
            if (!$itemData) {
                throw new \Exception("Item data is null for Square item");
            }

            $variations = $itemData->getVariations();
            if (!$variations || empty($variations)) {
                throw new \Exception("No variations found for Square item");
            }

            $variation = $variations[0];
            if (!$variation) {
                throw new \Exception("First variation is null for Square item");
            }

            $variationId = $variation->getId();
            if (!$variationId) {
                throw new \Exception("Variation ID is null for Square item");
            }
            
            // Get current inventory count using variation ID
            $countRequest = new BatchRetrieveInventoryCountsRequest();
            $countRequest->setCatalogObjectIds([$variationId]);
            $countRequest->setLocationIds([$squareLocationId]);
            
            $countResult = $inventoryApi->batchRetrieveInventoryCounts($countRequest);
            if (!$countResult || !$countResult->getResult()) {
                throw new \Exception("Failed to retrieve inventory count");
            }
            
            if ($countResult->getErrors()) {
                $errors = $countResult->getErrors();
                throw new \Exception(!empty($errors) ? $errors[0]->getDetail() : "Failed to retrieve inventory count");
            }
            
            $counts = $countResult->getResult()->getCounts();
            $currentQty = !empty($counts) ? intval($counts[0]->getQuantity()) : 0;
            $targetQty = intval($product['qty']);
            
            // Only adjust if quantities differ
            if ($currentQty !== $targetQty) {
                // Create inventory adjustment
                $adjustment = new InventoryAdjustment();
                $adjustment->setCatalogObjectId($variationId);
                $adjustment->setLocationId($squareLocationId);
                $adjustment->setQuantity((string)abs($targetQty - $currentQty));
                $adjustment->setOccurredAt(date('Y-m-d\TH:i:s.v\Z'));

                if ($targetQty < $currentQty) {
                    $adjustment->setFromState('IN_STOCK');
                    $adjustment->setToState('SOLD');
                } else {
                    $adjustment->setFromState('NONE');
                    $adjustment->setToState('IN_STOCK');
                }

                // Create BatchChangeInventoryRequest
                $request = new BatchChangeInventoryRequest(
                    uniqid() // idempotencyKey
                );
                $request->setChanges([
                    [
                        'type' => 'ADJUSTMENT',
                        'adjustment' => $adjustment
                    ]
                ]);
                
                $adjustResult = $inventoryApi->batchChangeInventory($request);
                
                if (!$adjustResult || !$adjustResult->getResult()) {
                    throw new \Exception("Failed to adjust inventory");
                }
                
                if ($adjustResult->getErrors()) {
                    $errors = $adjustResult->getErrors();
                    throw new \Exception(!empty($errors) ? $errors[0]->getDetail() : "Failed to adjust inventory");
                }
            }
        } catch (ApiException $e) {
            throw new \Exception("Square API Error: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("Error updating Square inventory: " . $e->getMessage());
        }
    }
}
