<?php
require_once(__DIR__ . '/BaseSquareModel.php');

use Square\Models\CatalogObject;
use Square\Models\CatalogItem;
use Square\Models\CatalogItemVariation;
use Square\Models\Money;
use Square\Models\SearchCatalogItemsRequest;
use Square\Models\SearchCatalogObjectsRequest;
use Square\Models\UpsertCatalogObjectRequest;
use Square\Exceptions\ApiException;

class CatalogManager extends BaseSquareModel {
    public function __construct($connection = null) {
        parent::__construct($connection);
        
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
    public function createOrUpdateProduct($product, $squareLocationId) {
        try {
            $catalogApi = $this->client->getCatalogApi();
            
            // First check if product exists in Square using SKU
            $searchRequest = new SearchCatalogItemsRequest([
                'product_types' => ['REGULAR'],
                'text_filter' => $product['product_sku']
            ]);
            
            error_log("Square Debug - Searching for product with SKU: " . $product['product_sku']);
            
            $searchResult = $catalogApi->searchCatalogItems($searchRequest);
            if (!$searchResult->isSuccess()) {
                $errors = $searchResult->getErrors();
                throw new \Exception(!empty($errors) ? $errors[0]->getDetail() : "Failed to search catalog items");
            }

            // First check if we have a mapping for this SKU
            $stmt = $this->connection->prepare("SELECT catalog_object_id FROM square_catalog_mappings WHERE square_sku = ?");
            $existingItem = null;
            
            if ($stmt) {
                $stmt->bind_param('s', $product['product_sku']);
                $stmt->execute();
                $mappingResult = $stmt->get_result();
                
                if ($mapping = $mappingResult->fetch_assoc()) {
                    // We found a mapping, try to retrieve the catalog object directly
                    try {
                        $catalogResult = $catalogApi->retrieveCatalogObject($mapping['catalog_object_id']);
                        if ($catalogResult->isSuccess()) {
                            $existingItem = $catalogResult->getResult()->getObject();
                            error_log("Square Debug - Found existing product from mapping, updating...");
                            return $this->updateProduct($product, $existingItem, $squareLocationId);
                        }
                    } catch (\Exception $e) {
                        error_log("Square Debug - Failed to retrieve mapped object: " . $e->getMessage());
                    }
                }
                $stmt->close();
            }
            
            // If no mapping found or retrieval failed, search by SKU
            $items = $searchResult->getResult()->getItems();
            if (!empty($items)) {
                foreach ($items as $item) {
                    $itemData = $item->getItemData();
                    $variations = $itemData ? $itemData->getVariations() : null;
                    $variation = $variations && !empty($variations) ? $variations[0] : null;
                    $variationData = $variation ? $variation->getItemVariationData() : null;
                    $foundSku = $variationData ? $variationData->getSku() : null;
                    
                    if ($foundSku === $product['product_sku']) {
                        error_log("Square Debug - Found existing product in Square with matching SKU, updating...");
                        // Store the mapping for future use
                        $stmt = $this->connection->prepare(
                            "INSERT INTO square_catalog_mappings (catalog_object_id, square_sku) 
                            VALUES (?, ?) 
                            ON DUPLICATE KEY UPDATE catalog_object_id = VALUES(catalog_object_id)"
                        );
                        if ($stmt) {
                            $catalogId = $item->getId();
                            $sku = $product['product_sku'];
                            $stmt->bind_param('ss', $catalogId, $sku);
                            $stmt->execute();
                            $stmt->close();
                            error_log("Square Debug - Stored catalog mapping: SKU={$sku}, ID={$catalogId}");
                        }
                        return $this->updateProduct($product, $item, $squareLocationId);
                    }
                }
            }
            
            error_log("Square Debug - No matching product found in Square, creating new...");
            return $this->createProduct($product, $squareLocationId);
        } catch (ApiException $e) {
            throw new \Exception("Square API Error: " . $e->getMessage());
        }
    }

    private function createProduct($product, $squareLocationId) {
        try {
            $catalogApi = $this->client->getCatalogApi();

            error_log("Square Debug - Creating variation for SKU: " . $product['product_sku']);
            
            // First check if we already have a mapping for this SKU
            $stmt = $this->connection->prepare("SELECT catalog_object_id FROM square_catalog_mappings WHERE square_sku = ?");
            if ($stmt) {
                $stmt->bind_param('s', $product['product_sku']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($mapping = $result->fetch_assoc()) {
                    // We found an existing mapping, try to retrieve the catalog object
                    try {
                        $existingObject = $catalogApi->retrieveCatalogObject($mapping['catalog_object_id']);
                        if ($existingObject->isSuccess()) {
                            error_log("Square Debug - Found existing catalog object from mapping");
                            return $existingObject->getResult()->getObject();
                        }
                    } catch (\Exception $e) {
                        error_log("Square Debug - Failed to retrieve mapped object: " . $e->getMessage());
                        // Continue with creation if retrieval fails
                    }
                }
                $stmt->close();
            }
            
            // Create variation data
            $itemVariation = new CatalogItemVariation();
            $itemVariation->setSku($product['product_sku']);
            $itemVariation->setName('Regular');
            $itemVariation->setPricingType('FIXED_PRICING');
            $itemVariation->setTrackInventory(true);
            
            error_log("Square Debug - Set variation data: SKU=" . $product['product_sku'] . ", Name=Regular, TrackInventory=true");

            // Validate and set price
            $price = floatval($product['product_price']);
            if ($price <= 0) {
                throw new \Exception("Invalid price for SKU {$product['product_sku']}: Price must be greater than 0");
            }

            // Set price money in CAD
            $priceMoney = new Money();
            $priceMoney->setAmount($this->convertPriceToMinorUnits($price));
            $priceMoney->setCurrency('CAD');
            $itemVariation->setPriceMoney($priceMoney);

            // Create variation object with a unique temporary ID
            $tempVariationId = '#' . uniqid('VAR_');
            $variation = new CatalogObject('ITEM_VARIATION', $tempVariationId);
            $variation->setVersion(1);
            $variation->setItemVariationData($itemVariation);
            $variation->setPresentAtAllLocations(false);
            $variation->setPresentAtLocationIds([$squareLocationId]);

            // Create category in Square if needed
            $categoryId = null;
            if (!empty($product['product_category'])) {
                $categoryId = $this->getOrCreateCategory($product['product_category']);
            }

            // Create main catalog object
            $tempItemId = '#' . uniqid('ITEM_');
            $object = new CatalogObject('ITEM', $tempItemId);

            // Create item data
            $itemData = new CatalogItem();
            $itemData->setName($product['product']);
            $itemData->setAbbreviation($product['product_sku']);
            $itemData->setVariations([$variation]);
            
            if (!empty($product['product_desc'])) {
                $itemData->setDescription($product['product_desc']);
            }
            if ($categoryId !== null) {
                $itemData->setCategoryId($categoryId);
            }

            // Set the item data
            $object->setVersion(1);
            $object->setItemData($itemData);
            $object->setPresentAtAllLocations(false);
            $object->setPresentAtLocationIds([$squareLocationId]);

            // Create proper UpsertCatalogObjectRequest
            $request = new UpsertCatalogObjectRequest(
                uniqid(), // idempotencyKey
                $object   // object
            );

            error_log("Square Debug - Upserting catalog object...");
            
            $result = $catalogApi->upsertCatalogObject($request);
            
            if (!$result->isSuccess()) {
                $errors = $result->getErrors();
                $errorMsg = !empty($errors) ? $errors[0]->getDetail() : "Failed to create item in Square";
                error_log("Square Debug - Upsert failed: " . $errorMsg);
                throw new \Exception($errorMsg);
            }

            $catalogObject = $result->getResult()->getCatalogObject();
            error_log("Square Debug - Successfully created/updated item. ID: " . $catalogObject->getId());
            
            return $catalogObject;
        } catch (ApiException $e) {
            throw new \Exception("Square API Error: " . $e->getMessage());
        }
    }

    private function updateProduct($product, $existingItem, $squareLocationId) {
        try {
            $catalogApi = $this->client->getCatalogApi();

            // Get the existing variation with proper null checks
            $itemData = $existingItem->getItemData();
            if (!$itemData) {
                throw new \Exception("Item data is null for existing Square item");
            }

            $variations = $itemData->getVariations();
            if (!$variations || empty($variations)) {
                throw new \Exception("No variations found for existing Square item");
            }

            $variation = $variations[0];
            if (!$variation) {
                throw new \Exception("First variation is null for existing Square item");
            }

            $variationData = $variation->getItemVariationData();
            if (!$variationData) {
                throw new \Exception("Variation data is null for existing Square item");
            }

            // Validate and update price if different
            $price = floatval($product['product_price']);
            if ($price <= 0) {
                throw new \Exception("Invalid price for SKU {$product['product_sku']}: Price must be greater than 0");
            }

            $currentPrice = $variationData->getPriceMoney()->getAmount();
            $newPrice = $this->convertPriceToMinorUnits($price);

            if ($currentPrice !== $newPrice) {
                error_log("Square Debug - Updating price for SKU {$product['product_sku']} from {$currentPrice} to {$newPrice}");
                // Create new money object
                $priceMoney = new Money();
                $priceMoney->setAmount($newPrice);
                $priceMoney->setCurrency('CAD');
                $variationData->setPriceMoney($priceMoney);
                
                // Update the variation data
                $variation->setItemVariationData($variationData);
                
                // Update variations array
                $variations = [$variation];
                $itemData->setVariations($variations);
                
                // Update the item data
                $existingItem->setItemData($itemData);
            }

            // Update other fields if needed
            $itemData = $existingItem->getItemData();
            $itemData->setName($product['product']);
            if (!empty($product['product_desc'])) {
                $itemData->setDescription($product['product_desc']);
            }

            // Update category if needed
            if (!empty($product['product_category'])) {
                $categoryId = $this->getOrCreateCategory($product['product_category']);
                if ($categoryId !== null) {
                    $itemData->setCategoryId($categoryId);
                }
            }

            // Create update request
            $request = new UpsertCatalogObjectRequest(
                uniqid(),
                $existingItem
            );

            $result = $catalogApi->upsertCatalogObject($request);
            
            if (!$result->isSuccess()) {
                $errors = $result->getErrors();
                throw new \Exception(!empty($errors) ? $errors[0]->getDetail() : "Failed to update item in Square");
            }

            $catalogObject = $result->getResult()->getCatalogObject();
            
            // Store the mapping
            $stmt = $this->connection->prepare(
                "INSERT INTO square_catalog_mappings (catalog_object_id, square_sku) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE catalog_object_id = VALUES(catalog_object_id)"
            );
            if ($stmt) {
                $catalogId = $catalogObject->getId();
                $sku = $product['product_sku'];
                $stmt->bind_param('ss', $catalogId, $sku);
                $stmt->execute();
                $stmt->close();
                error_log("Square Debug - Stored catalog mapping: SKU={$sku}, ID={$catalogId}");
            }
            
            return $catalogObject;
        } catch (ApiException $e) {
            throw new \Exception("Square API Error: " . $e->getMessage());
        }
    }

    private function getOrCreateCategory($categoryId) {
        try {
            $catalogApi = $this->client->getCatalogApi();

            // Get category name from database
            $stmt = $this->connection->prepare("SELECT category_title FROM inv_qne_product_category WHERE category_id = ?");
            if (!$stmt) {
                throw new \Exception("Failed to prepare category query");
            }

            $stmt->bind_param('s', $categoryId);
            if (!$stmt->execute()) {
                throw new \Exception("Failed to execute category query");
            }

            $result = $stmt->get_result();
            $categoryData = $result->fetch_assoc();
            $stmt->close();

            if (!$categoryData) {
                return null;
            }

            // Search for existing category in Square
            $cursor = null;
            do {
                $searchRequest = new SearchCatalogObjectsRequest();
                $searchRequest->setObjectTypes(['CATEGORY']);
                if ($cursor !== null) {
                    $searchRequest->setCursor($cursor);
                }
                
                $result = $catalogApi->searchCatalogObjects($searchRequest);
                if (!$result->isSuccess()) {
                    throw new \Exception("Failed to search categories");
                }
                
                $objects = $result->getResult()->getObjects();
                if ($objects !== null) {
                    foreach ($objects as $object) {
                        if ($object->getType() === 'CATEGORY' && 
                            $object->getCategoryData()->getName() === $categoryData['category_title']) {
                            return $object->getId();
                        }
                    }
                }
                
                $cursor = $result->getResult()->getCursor();
            } while ($cursor !== null);

            // Create new category if not found
            $catalogCategory = new \Square\Models\CatalogCategory();
            $catalogCategory->setName($categoryData['category_title']);

            $categoryObject = new CatalogObject('CATEGORY', '#' . uniqid('CAT_'));
            $categoryObject->setCategoryData($catalogCategory);

            $request = new UpsertCatalogObjectRequest(
                uniqid(),
                $categoryObject
            );

            $result = $catalogApi->upsertCatalogObject($request);
            if (!$result->isSuccess()) {
                throw new \Exception("Failed to create category in Square");
            }

            return $result->getResult()->getCatalogObject()->getId();
        } catch (ApiException $e) {
            throw new \Exception("Square API Error: " . $e->getMessage());
        }
    }
}
