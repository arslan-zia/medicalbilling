<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

use Square\SquareClient;
use Square\Environment;
use Square\Exceptions\ApiException;

abstract class BaseSquareModel {
    protected $client;
    protected $connection;
    protected $currency;
    protected $accessToken;

    public function __construct($connection = null) {
        try {
            if ($connection === null) {
                require_once(__DIR__ . '/../../includes/connection.php');
                global $connection;
            }
            $this->connection = $connection;
            
            // Include Square settings
            require_once(__DIR__ . '/../../includes/square_settings.php');
            
            if (!$this->isSquareConfigured()) {
                error_log("Square API Error: Square is not properly configured");
                throw new \Exception("Square is not properly configured. Please configure it in Square Settings at square_config.php");
            }

            // Initialize tables if they don't exist
            $query = "CREATE TABLE IF NOT EXISTS square_location_mappings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                local_location_id VARCHAR(255) NOT NULL,
                square_location_id VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_local_id (local_location_id),
                UNIQUE KEY unique_square_id (square_location_id)
            )";
            $this->connection->query($query);

            $this->accessToken = SQUARE_SETTINGS['access_token'];
            
            try {
                $this->client = new SquareClient([
                    'accessToken' => $this->accessToken,
                    'environment' => SQUARE_SETTINGS['environment'] === 'production' ? Environment::PRODUCTION : Environment::SANDBOX
                ]);
                
                // Test API connection
                $merchantResult = $this->client->getMerchantsApi()->retrieveMerchant('me');
                if (!$merchantResult->isSuccess()) {
                    throw new \Exception("Failed to retrieve merchant information");
                }
                
                // Set merchant's currency as default
                $merchant = $merchantResult->getResult()->getMerchant();
                $this->currency = $merchant->getCurrency();
                
            } catch (ApiException $e) {
                error_log("Square API Error: " . $e->getMessage());
                throw new \Exception("Failed to connect to Square API: " . $e->getMessage());
            }
        } catch (\Exception $e) {
            error_log("Square API Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    protected function isSquareConfigured() {
        return !empty(SQUARE_SETTINGS['access_token']) && 
               !empty(SQUARE_SETTINGS['environment']) && 
               in_array(SQUARE_SETTINGS['environment'], ['sandbox', 'production']);
    }

    protected function convertPriceToMinorUnits($price) {
        if (empty($price)) return 0;
        
        $price = floatval($price);
        if ($price < 0) {
            throw new \Exception("Price cannot be negative: $price");
        }
        
        // Validate the price is not unreasonably high (e.g., over 999,999.99)
        if ($price > 999999.99) {
            throw new \Exception("Price exceeds maximum allowed value: $price");
        }
        
        // Convert to minor units, handling floating point precision
        $minorUnits = (int)round($price * 100);
        
        // Validate the conversion didn't cause overflow
        if ($minorUnits < 0) {
            throw new \Exception("Price conversion overflow: $price");
        }
        
        return $minorUnits;
    }

    protected function logSyncOperation($operation, $status, $message, $details = []) {
        $logData = [
            'operation' => $operation,
            'status' => $status,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'details' => json_encode($details)
        ];
        
        error_log(json_encode($logData));
    }

    protected function getSquareLocationId($localLocationId) {
        $query = "SELECT square_location_id FROM square_location_mappings WHERE local_location_id = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            throw new \Exception("Failed to prepare location mapping query: " . mysqli_error($this->connection->_connection));
        }
        $stmt->bind_param('s', $localLocationId);
        if (!$stmt->execute()) {
            throw new \Exception("Failed to execute location mapping query: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $stmt->close();
        
        if (!$result) {
            throw new \Exception("Failed to get location mapping: " . mysqli_error($this->connection->_connection));
        }
        $mapping = $result->fetch_assoc();
        
        if (!$mapping) {
            throw new \Exception("No Square location mapping found for local location ID: $localLocationId");
        }

        return $mapping['square_location_id'];
    }
}
