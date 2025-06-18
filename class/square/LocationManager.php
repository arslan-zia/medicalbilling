<?php
require_once(__DIR__ . '/BaseSquareModel.php');

use Square\Exceptions\ApiException;

class LocationManager extends BaseSquareModel {
    public function getLocations() {
        try {
            if (empty($this->accessToken)) {
                error_log("Square API Error: Access token is empty");
                throw new \Exception("Access token is not configured");
            }

            $locationsApi = $this->client->getLocationsApi();
            $result = $locationsApi->listLocations();
            
            if (!$result || !$result->getResult()) {
                throw new \Exception("Failed to retrieve Square locations");
            }
            
            if ($result->getErrors()) {
                $errors = $result->getErrors();
                $errorMessage = !empty($errors) ? $errors[0]->getDetail() : "Unknown error";
                error_log("Square API Error: " . $errorMessage);
                throw new \Exception($errorMessage);
            }
            
            $locations = $result->getResult()->getLocations();
            if (empty($locations)) {
                error_log("Square API Warning: No locations found for this account");
            }
            return $locations;
        } catch (\Exception $e) {
            error_log("Square API Exception: " . $e->getMessage());
            throw new \Exception("Error fetching Square locations: " . $e->getMessage());
        }
    }

    public function mapLocation($localLocationId, $squareLocationId) {
        try {
            // Verify Square location exists
            $locationsApi = $this->client->getLocationsApi();
            $result = $locationsApi->retrieveLocation($squareLocationId);
            
            if (!$result || !$result->getResult()) {
                throw new \Exception("Failed to retrieve Square location");
            }
            
            if ($result->getErrors()) {
                $errors = $result->getErrors();
                throw new \Exception(!empty($errors) ? $errors[0]->getDetail() : "Invalid Square location ID");
            }

            // Get Square location name
            $squareLocation = $result->getResult()->getLocation();
            $squareLocationName = $squareLocation->getName();

            // Verify local location exists
            $stmt = $this->connection->prepare("SELECT location_id FROM inv_qne_locations WHERE location_id = ?");
            if (!$stmt) {
                throw new \Exception("Failed to prepare local location query");
            }

            $stmt->bind_param('s', $localLocationId);
            if (!$stmt->execute()) {
                throw new \Exception("Failed to execute local location query");
            }

            $result = $stmt->get_result();
            if (!$result->fetch_assoc()) {
                $stmt->close();
                throw new \Exception("Invalid local location ID");
            }
            $stmt->close();

            // Initialize tables first
            $initResult = $this->initializeLocationMappingTable();
            if (!$initResult['success']) {
                throw new \Exception("Failed to initialize tables: " . $initResult['message']);
            }
            
            // Verify tables exist
            $result = $this->connection->query("SHOW TABLES LIKE 'square_location_mappings'");
            if (!$result || $result->num_rows === 0) {
                throw new \Exception("Location mappings table was not created");
            }
            
            $result = $this->connection->query("SHOW TABLES LIKE 'square_sku_mappings'");
            if (!$result || $result->num_rows === 0) {
                throw new \Exception("SKU mappings table was not created");
            }
            

            // Create or update mapping
            $query = "INSERT INTO square_location_mappings (local_location_id, square_location_id, square_location_name) 
                     VALUES (?, ?, ?) 
                     ON DUPLICATE KEY UPDATE square_location_id = VALUES(square_location_id), 
                                         square_location_name = VALUES(square_location_name)";
            
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new \Exception("Failed to prepare mapping query");
            }

            $stmt->bind_param('sss', $localLocationId, $squareLocationId, $squareLocationName);
            if (!$stmt->execute()) {
                throw new \Exception("Failed to save location mapping");
            }
            $stmt->close();

                // After mapping is saved, fetch and store catalog items for this location
                try {
                    // Get catalog items for this location
                    $catalogApi = $this->client->getCatalogApi();
                    $cursor = null;
                    
                    do {
                        $searchRequest = [
                            'product_types' => ['REGULAR'],
                            'enabled_location_ids' => [$squareLocationId]
                        ];
                        if ($cursor) {
                            $searchRequest['cursor'] = $cursor;
                        }
                        
                        $body = new \Square\Models\SearchCatalogItemsRequest($searchRequest);
                        $apiResponse = $catalogApi->searchCatalogItems($body);
                        
                        if (!$apiResponse || !$apiResponse->getResult()) {
                            error_log("Failed to retrieve catalog items");
                            break;
                        }
                        
                        if ($apiResponse->getErrors()) {
                            error_log("Failed to fetch catalog items: " . json_encode($apiResponse->getErrors()));
                            break;
                        }
                        
                        $items = $apiResponse->getResult()->getItems();
                        if ($items) {
                            foreach ($items as $item) {
                                    // For item variations, we can get the SKU directly
                                    if ($item->getType() === 'ITEM_VARIATION') {
                                        $variationData = $item->getItemVariationData();
                                        if ($variationData && $variationData->getSku()) {
                                            $sku = $variationData->getSku();
                                            try {
                                                
                                                // Store SKU mapping
                                                $stmt = $this->connection->prepare(
                                                    "INSERT IGNORE INTO square_sku_mappings 
                                                    (square_location_id, square_sku, local_sku) 
                                                    VALUES (?, ?, ?)"
                                                );
                                                
                                                if (!$stmt) {
                                                    throw new \Exception("Failed to prepare SKU mapping statement: " . $this->connection->error);
                                                }
                                                
                                                $stmt->bind_param('sss', $squareLocationId, $sku, $sku);
                                                
                                                if (!$stmt->execute()) {
                                                    throw new \Exception("Failed to execute SKU mapping statement: " . $stmt->error);
                                                }
                                                
                                                $stmt->close();
                                            } catch (\Exception $e) {
                                                throw $e;
                                            }
                                        }
                                    }
                                    // For regular items, check variations
                                    else if ($item->getType() === 'ITEM') {
                                        $itemData = $item->getItemData();
                                        if ($itemData) {
                                            $variations = $itemData->getVariations();
                                            if ($variations) {
                                                foreach ($variations as $variation) {
                                                    $variationData = $variation->getItemVariationData();
                                                    if ($variationData && $variationData->getSku()) {
                                                        $sku = $variationData->getSku();
                                        try {
                                                            
                                                            // Store SKU mapping
                                                            $stmt = $this->connection->prepare(
                                                                "INSERT IGNORE INTO square_sku_mappings 
                                                                (square_location_id, square_sku, local_sku) 
                                                                VALUES (?, ?, ?)"
                                                            );
                                                            
                                                            if (!$stmt) {
                                                                throw new \Exception("Failed to prepare SKU mapping statement: " . $this->connection->error);
                                                            }
                                                            
                                                            $stmt->bind_param('sss', $squareLocationId, $sku, $sku);
                                                            
                                                            if (!$stmt->execute()) {
                                                                throw new \Exception("Failed to execute SKU mapping statement: " . $stmt->error);
                                                            }
                                                            
                                                            $stmt->close();
                                                        } catch (\Exception $e) {
                                                            throw $e;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                            }
                        }
                        
                        // Get cursor for next page
                        $cursor = $apiResponse->getResult()->getCursor();
                    } while ($cursor);
                    
                } catch (\Exception $e) {
                    // Log error but don't fail the mapping
                    error_log("Error storing SKU mappings: " . $e->getMessage());
                }

            return [
                'success' => true,
                'message' => "Location mapping saved successfully"
            ];

        } catch (ApiException $e) {
            error_log("Square API Error in mapLocation: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Square API Error: " . $e->getMessage()
            ];
        } catch (\Exception $e) {
            error_log("Error in mapLocation: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function unmapLocation($localLocationId) {
        try {
            // First get the Square location ID
            $stmt = $this->connection->prepare("SELECT square_location_id FROM square_location_mappings WHERE local_location_id = ?");
            if (!$stmt) {
                throw new \Exception("Failed to prepare select query");
            }

            $stmt->bind_param('s', $localLocationId);
            $stmt->execute();
            $result = $stmt->get_result();
            $mapping = $result->fetch_assoc();
            $stmt->close();

            if ($mapping) {
                // Delete SKU mappings for this location
                $stmt = $this->connection->prepare("DELETE FROM square_sku_mappings WHERE square_location_id = ?");
                $stmt->bind_param('s', $mapping['square_location_id']);
                $stmt->execute();
                $stmt->close();
            }

            // Delete location mapping
            $stmt = $this->connection->prepare("DELETE FROM square_location_mappings WHERE local_location_id = ?");
            if (!$stmt) {
                throw new \Exception("Failed to prepare delete query");
            }

            $stmt->bind_param('s', $localLocationId);
            if (!$stmt->execute()) {
                throw new \Exception("Failed to remove location mapping");
            }
            $stmt->close();

            return [
                'success' => true,
                'message' => "Location mapping removed successfully"
            ];

        } catch (\Exception $e) {
            error_log("Error in unmapLocation: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getMappedLocations() {
        try {
            $query = "SELECT m.*, l.location_name as local_location_name 
                     FROM square_location_mappings m 
                     INNER JOIN inv_qne_locations l ON m.local_location_id = l.location_id";
            
            $result = $this->connection->query($query);
            if (!$result) {
                throw new \Exception("Failed to retrieve location mappings");
            }

            $mappings = [];
            while ($row = $result->fetch_assoc()) {
                $mappings[] = $row;
            }

            return [
                'success' => true,
                'mappings' => $mappings
            ];

        } catch (\Exception $e) {
            error_log("Error in getMappedLocations: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function initializeLocationMappingTable() {
        try {
            // Check if tables already exist
            $result = $this->connection->query("SHOW TABLES LIKE 'square_location_mappings'");
            $locationMappingsExists = $result && $result->num_rows > 0;
            
            $result = $this->connection->query("SHOW TABLES LIKE 'square_sku_mappings'");
            $skuMappingsExists = $result && $result->num_rows > 0;
            
            // Only create tables if they don't exist
            if (!$locationMappingsExists) {
                // Create location mappings table
                $query = "CREATE TABLE square_location_mappings (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    local_location_id VARCHAR(255) NOT NULL,
                    square_location_id VARCHAR(255) NOT NULL,
                    square_location_name VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_local_location (local_location_id),
                    UNIQUE KEY unique_square_location (square_location_id)
                )";

                if (!$this->connection->query($query)) {
                    throw new \Exception("Failed to create location mappings table: " . $this->connection->error);
                }
            }
            
            if (!$skuMappingsExists) {
                // Create SKU mappings table
                $query = "CREATE TABLE square_sku_mappings (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    square_location_id VARCHAR(255) NOT NULL,
                    square_sku VARCHAR(255) NOT NULL,
                    local_sku VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_square_sku (square_location_id, square_sku)
                )";

                if (!$this->connection->query($query)) {
                    throw new \Exception("Failed to create SKU mappings table: " . $this->connection->error);
                }

                // Verify newly created tables
                $tables = [];
                $result = $this->connection->query("SHOW TABLES");
                while ($row = $result->fetch_array()) {
                    $tables[] = $row[0];
                }
                
                if (!in_array('square_location_mappings', $tables)) {
                    throw new \Exception("Failed to create square_location_mappings table");
                }
                if (!in_array('square_sku_mappings', $tables)) {
                    throw new \Exception("Failed to create square_sku_mappings table");
                }
            }

            return [
                'success' => true,
                'message' => "Location and SKU mappings tables verified successfully"
            ];

        } catch (\Exception $e) {
            error_log("Error in initializeLocationMappingTable: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
