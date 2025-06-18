<?php
    class Warehouse extends DB_connection 
    {
        var $connection;
        var $product_id;

        public function __construct()
        {
            $this->store_url        = 'https://thevintagebazar.com';
            $this->consumer_key     = 'ck_6ab43c1502cb2cea7e5181766a687eb7c8e58870'; // TVB Live Keys
            $this->consumer_secret  = 'cs_dfbe7a0cd20f1f99a61708882125bae9c9ba73bf';
            $this->tags_woo_id      = 0;
            $this->woo_size_id      = 0;
        }

        public function allProductListing($start=0, $limit=0)
        {
            $DB = new DB_connection();            

            $where = "";      
            $join  = "";
            $groupBy = "";

            $search = " WHERE iqp.status = 1 AND iqp.is_delete = '0'";
            
            if($_GET['product_title'])
            {
                $search .= " AND `product` LIKE '%" . addslashes($_GET['product_title']) . "%'";
            }
            if($_GET['sku_code'])
            {
                $search .= " AND `product_sku` LIKE '%" . addslashes($_GET['sku_code']) . "%'";
            }
            if($_GET['price'])
            {
                $search .= " AND `product_price` = '" . addslashes($_GET['price']) . "'";
            }
            if($_GET['location'])
            {
                $search .= " AND `product_location` = '" . addslashes($_GET['location']) . "'";
            }
            if($_GET['category'])
            {
                $search .= " AND iqpc.category_id = '" . addslashes($_GET['category']) . "'";
                $join  = " LEFT JOIN `inv_qne_product_category_map` iqcp ON iqp.product_id = iqcp.product_id JOIN `inv_qne_product_category` iqpc ON iqcp.category_id = iqpc.category_woo_id";
                //$join  = " JOIN `inv_qne_product_category_map` iqcp ON iqp.product_id = iqcp.product_id";
                $groupBy = " GROUP BY iqcp.product_id";
            } 

            //$join  = " LEFT JOIN `brand` iqb ON iqp.brand = iqb.id LEFT JOIN `inv_qne_company` iqc ON iqp.company = iqc.company_id LEFT JOIN `inv_qne_product_sku` iqps ON iqp.product_id = iqps.product_id";
            //$DB->query("SET SQL_BIG_SELECTS=1");  //Set it before your main query

            if($start == 0 && $limit == 0)
            {
                //$select = "SELECT count(*) as rowCount  FROM `inv_qne_products` iqp LEFT JOIN `inv_qne_product_stock` ips ON iqp.product_id = ips.product_id LEFT JOIN `inv_qne_product_price` ipp  ON iqp.product_id = ipp.product_id " . $join . " WHERE ipp.status = 1 AND iqp.is_delete = '0'" . $groupBy;
                $select = "SELECT count(*) as rowCount  FROM `inv_qne_products` iqp " . $join . $search;// . $groupBy;
                $conn   = $DB->query($select);
                
                if(mysqli_num_rows($conn) > 0)
                {
                    $fetch = mysqli_fetch_object($conn);
                    return $fetch->rowCount;
                }
                return 0;
            }
            else
            {
                $select = "SELECT iqp.* FROM `inv_qne_products` iqp " . $join . $search . $groupBy . " ORDER BY iqp.product_id DESC LIMIT " . $start . ", " . $limit;
                $conn   = $DB->query($select);

                $products = array();
                if(mysqli_num_rows($conn) > 0)
                {
                    $c = 0;
                    while($fetch = mysqli_fetch_object($conn))
                    {	
                        $products[$c]					=	new Warehouse();
                        $products[$c]->product_id		=	$fetch->product_id;
                        $products[$c]->woo_product_id	=	$fetch->woo_product_id;
                        $products[$c]->product			=	$fetch->product;
                        $products[$c]->product_sku		=	$fetch->product_sku;
                        $products[$c]->product_type		=	$fetch->product_type;
                        $products[$c]->product_size		=	$fetch->product_size;
                        $products[$c]->product_category	=	$fetch->product_category;
                        $products[$c]->product_location	=	$fetch->product_location;
                        $products[$c]->product_bin  	=	$fetch->product_bin;
                        $products[$c]->tax_type			=	$fetch->tax_type;
                        $products[$c]->status			=	$fetch->status;
                        $products[$c]->datetime			=	$fetch->datetime;
                        $products[$c]->product_qty		=	$fetch->product_quantity;
                        $products[$c]->product_msrp		=	$fetch->product_msrp;
                        $products[$c]->product_wholesale=	$fetch->product_wholesale;
                        $products[$c]->product_price	=	$fetch->product_price;
                        $c++;
                    }
                }
                return $products;
            }
        }

        public function allImportProduct($start=0, $limit=0)
        {
            $DB = new DB_connection();            

            $where = "";      
            $join  = "";
            $groupBy = "";

            $search = "";//s WHERE iqp.status = 1 AND iqp.is_delete = '0'";
            
            if($start == 0 && $limit == 0)
            {
                //$select = "SELECT count(*) as rowCount  FROM `inv_qne_products` iqp LEFT JOIN `inv_qne_product_stock` ips ON iqp.product_id = ips.product_id LEFT JOIN `inv_qne_product_price` ipp  ON iqp.product_id = ipp.product_id " . $join . " WHERE ipp.status = 1 AND iqp.is_delete = '0'" . $groupBy;
                $select = "SELECT count(*) as rowCount  FROM `inv_qne_product_import` iqp " . $join . $search;// . $groupBy;
                $conn   = $DB->query($select);
                
                if(mysqli_num_rows($conn) > 0)
                {
                    $fetch = mysqli_fetch_object($conn);
                    return $fetch->rowCount;
                }
                return 0;
            }
            else
            {
                $select = "SELECT iqp.* FROM `inv_qne_product_import` pi JOIN `inv_qne_products` iqp ON pi.product_id = iqp.product_id " . $join . $search . $groupBy . " ORDER BY pi.import_id ASC LIMIT " . $start . ", " . $limit;
                $conn   = $DB->query($select);

                if(mysqli_num_rows($conn) > 0)
                {
                    $products 	= 	array();
                    $c			=	0;
                    while($fetch = mysqli_fetch_object($conn))
                    {	
                        $products[$c]					=	new Warehouse();
                        $products[$c]->product_id		=	$fetch->product_id;
                        $products[$c]->woo_product_id	=	$fetch->woo_product_id;
                        $products[$c]->product			=	$fetch->product;
                        $products[$c]->product_sku		=	$fetch->product_sku;
                        $products[$c]->product_type		=	$fetch->product_type;
                        $products[$c]->product_size		=	$fetch->product_size;
                        $products[$c]->product_category	=	$fetch->product_category;
                        $products[$c]->product_location	=	$fetch->product_location;
                        $products[$c]->product_bin  	=	$fetch->product_bin;
                        $products[$c]->tax_type			=	$fetch->tax_type;
                        $products[$c]->status			=	$fetch->status;
                        $products[$c]->datetime			=	$fetch->datetime;
                        $products[$c]->product_qty		=	$fetch->product_quantity;
                        $products[$c]->product_msrp		=	$fetch->product_msrp;
                        $products[$c]->product_wholesale=	$fetch->product_wholesale;
                        $products[$c]->product_price	=	$fetch->product_price;
                        $c++;
                    }
                    return $products;
                }
            }
        }

        public function deleteProduct($productID, $wooProductID)
        {
            $DB = new DB_connection();
            
            $delete = "UPDATE inv_qne_products SET `is_delete` = '1' WHERE product_id = '" . $productID . "'";
    	    $DB->query($delete);

            $endpoint           = $this->store_url . "/wp-json/wc/v3/products/" . $wooProductID . "?force=true";
            $consumer_key       = $this->consumer_key;
            $consumer_secret    = $this->consumer_secret;

            $this->delete_product_woocommerce($wooProductID, $consumer_key, $consumer_secret);

            return true;
        }
        
        public function getProductDetails($product_id=0)
        {
		    $DB	= new DB_connection();
		    $product = array();
		    
            if($product_id != 0)
            {
                $where .= " iqp.product_id = " . $product_id;
            }   

            $join = " LEFT JOIN `inv_qne_product_type` qpt ON iqp.product_type = qpt.type_id LEFT JOIN `inv_qne_product_size` qps ON iqp.product_size = qps.size_id LEFT JOIN `inv_qne_product_category` qpc ON iqp.product_category = qpc.category_id LEFT JOIN `inv_qne_product_location` qpl ON iqp.product_id = qpl.product_id LEFT JOIN `inv_qne_locations` ql ON ql.location_id = qpl.location_id LEFT JOIN `inv_qne_bin_locations` qbl ON qbl.bin_id = qpl.bin_location_id";
            //$select 	= 	"SELECT iqp.*, ips.qty as product_qty, ipp.product_msrp, ipp.product_wholesale, ipp.product_price, qpt.product_type, qps.product_size, qpc.category_title, ql.location_name, qbl.bin_location, qpt.type_id, qps.size_id, qpc.category_id, qpl.location_id, qpl.bin_location_id FROM `inv_qne_products` iqp LEFT JOIN `inv_qne_product_stock` ips ON iqp.product_id = ips.product_id LEFT JOIN `inv_qne_product_price` ipp  ON iqp.product_id = ipp.product_id " . $join . " WHERE iqp.product_id = " . $product_id . " AND ipp.status = 1";
            $select 	= 	"SELECT iqp.*, iqp.product_quantity as product_qty, iqp.product_msrp, iqp.product_wholesale, iqp.product_price, qpt.product_type, qps.product_size, qpc.category_title, ql.location_name, qbl.bin_location, qpt.type_id, qps.size_id, qpc.category_id, qpl.location_id, qpl.bin_location_id FROM `inv_qne_products` iqp " . $join . " WHERE iqp.product_id = " . $product_id . " AND iqp.status = 1";
            //die('in');
            $conn		= 	$DB->query($select);            

            if(mysqli_num_rows($conn) > 0)
            {
                $fetch                          =   mysqli_fetch_object($conn);                
                $product['product_id']		    =	$fetch->product_id;
                $product['woo_product_id']	    =	$fetch->woo_product_id;
                $product['product']			    =	$fetch->product;
                $product['product_image']		=	$fetch->product_image;
                $product['product_sku']		    =	$fetch->product_sku;
                $product['type_id']		        =	$fetch->type_id;
                $product['size_id']		        =	$fetch->size_id;
                $product['product_category']	=	$fetch->product_category;
                $product['location_id']	        =	$fetch->location_id;
                $product['bin_location_id']     =	$fetch->bin_location_id;
                $product['product_bin']  	    =	$fetch->product_bin;
                $product['tax_type']			=	$fetch->tax_type;
                $product['status']			    =	$fetch->status;
                $product['datetime']			=	$fetch->datetime;
                $product['product_qty']		    =	$fetch->product_qty;
                $product['product_msrp']		=	$fetch->product_msrp;
                $product['product_wholesale']   =	$fetch->product_wholesale;
                $product['product_price']	    =	$fetch->product_price;                 
                $product['product_type']		=	$fetch->product_type;
                $product['product_size']		=	$fetch->product_size;
                $product['category_title']      =	$fetch->category_title;
                $product['product_location']	=	$fetch->product_location; 
                $product['product_bin']	        =	$fetch->product_bin; 
                $product['location_name']	    =	$fetch->location_name; 
                $product['bin_location']	    =	$fetch->bin_location; 
            }
    		return $product;
    	}
        
        public function searchProduct($title,$sku=0)
        {
		    $DB		= 	new DB_connection();
    		$select = 	"SELECT p.`product_id`, p.`product`, p.`product_sku`, ps.`qty` FROM inv_qne_products p LEFT JOIN inv_qne_product_stock ps ON p.product_id = ps.product_id WHERE (`product` LIKE '" . $title . "%' || product_sku LIKE '%" . $title . "%')";
    	    $conn	=	$DB->query($select);
            $products = array();
            
    		if(mysqli_num_rows($conn) > 0)
    		{
    		    $c = 0;
    			while($row = mysqli_fetch_array($conn))
    			{
    			    $products[$c]['product_id']		=	$row['product_id'];
                    $products[$c]['product']		=	$row['product'];
                    $products[$c]['product_sku']	=	$row['product_sku'];
                    $products[$c]['product_qty']	=	$row['qty'];
    				$c++;
    	    	}
    		}
    		return $products;
    	}

        public function getProductImages($productID)
        {
            $DB		= 	new DB_connection();
    		$select = 	"SELECT * FROM inv_qne_product_images WHERE `product_id` = '" . $productID . "'";
    	    $conn	=	$DB->query($select);
            $products = array();
            
    		if(mysqli_num_rows($conn) > 0)
    		{
    		    $c = 0;
    			while($row = mysqli_fetch_array($conn))
    			{
    			    $products[$c]['product_id']	= $row['product_id'];
                    $products[$c]['image']		= $row['image'];
                    $products[$c]['featured']	= $row['featured'];
                    $products[$c]['date']	    = $row['date'];
    				$c++;
    	    	}
    		}
    		return $products;
        }

        public function productByID($product_id=0)
        {
            $DB	= new DB_connection();
            if($product_id != 0)
            {
                $where .= " iqp.product_id = " . $product_id;
            }   
            //$join = " LEFT JOIN `inv_qne_product_type` qpt ON iqp.product_type = qpt.type_id LEFT JOIN `inv_qne_product_size` qps ON iqp.product_size = qps.size_id LEFT JOIN `inv_qne_product_category` qpc ON iqp.product_category = qpc.category_id LEFT JOIN `inv_qne_product_location` qpl ON iqp.product_location = qpl.location_id  LEFT JOIN `inv_qne_product_bin` qpb ON iqp.product_bin = qpb.bin_id";
            $join = " LEFT JOIN `inv_qne_product_type` qpt ON iqp.product_type = qpt.type_id LEFT JOIN `inv_qne_product_size` qps ON iqp.product_size = qps.size_id LEFT JOIN `inv_qne_product_category` qpc ON iqp.product_category = qpc.category_id LEFT JOIN `inv_qne_product_location` qpl ON iqp.product_id = qpl.product_id LEFT JOIN `inv_qne_locations` ql ON ql.location_id = qpl.location_id LEFT JOIN `inv_qne_bin_locations` qbl ON qbl.bin_id = qpl.bin_location_id";
            //$DB->query("SET SQL_BIG_SELECTS=1");  //Set it before your main query
            //$select 	= 	"SELECT iqp.*, ips.qty as product_qty, ipp.product_msrp, ipp.product_wholesale, ipp.product_price, qpt.product_type, qps.product_size, qpc.category_title, ql.location_name, qbl.bin_location FROM `inv_qne_products` iqp LEFT JOIN `inv_qne_product_stock` ips ON iqp.product_id = ips.product_id LEFT JOIN `inv_qne_product_price` ipp  ON iqp.product_id = ipp.product_id " . $join . " WHERE iqp.product_id = " . $product_id . " AND ipp.status = 1";
            $select 	= 	"SELECT iqp.*, iqp.product_quantity as product_qty, iqp.product_msrp, iqp.product_wholesale, iqp.product_price, qpt.product_type, qps.product_size, qpc.category_title, ql.location_name, qbl.bin_location FROM `inv_qne_products` iqp " . $join . " WHERE iqp.product_id = " . $product_id . " AND iqp.status = 1";
            //die('in');
            $conn		= 	$DB->query($select);            

            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);                

                $this->product_id		=	$fetch->product_id;
                $this->product			=	$fetch->product;
                $this->product_image	=	$fetch->product_image;
                $this->product_sku		=	$fetch->product_sku;
                $this->product_type		=	$fetch->product_type;
                $this->product_size		=	$fetch->product_size;
                $this->product_category	=	$fetch->product_category;
                $this->product_location	=	$fetch->product_location;
                $this->product_bin  	=	$fetch->product_bin;
                $this->tax_type			=	$fetch->tax_type;
                $this->status			=	$fetch->status;
                $this->datetime			=	$fetch->datetime;
                $this->product_qty		=	$fetch->product_qty;
                $this->product_msrp		=	$fetch->product_msrp;
                $this->product_wholesale=	$fetch->product_wholesale;
                $this->product_price	=	$fetch->product_price;                 
                $this->product_type		=	$fetch->product_type;
                $this->product_size		=	$fetch->product_size;
                $this->category_title   =	$fetch->category_title;
                $this->product_location	=	$fetch->product_location; 
                $this->product_bin	    =	$fetch->product_bin; 
                $this->location_name	=	$fetch->location_name; 
                $this->bin_location	    =	$fetch->bin_location; 
            }
        }


        public function productCategory($category_id=0,$all_categories=0)
        {
            $DB = new DB_connection();
            
            $status = " 1 = 1 ";
            if( isset($all_categories) && !empty($all_categories) && $all_categories >= 0 )
            {
                $status = " 1 = 1 ";
            } else {
                $status = " `status` = 1 ";
            }
            
            if( isset($category_id) && !empty($category_id) && $category_id >= 0 )
            {
                $select 	= 	"SELECT * FROM `inv_qne_product_category` WHERE ". $status ." AND `category_id` = ". $category_id ." ORDER BY category_id DESC";
                $conn		= 	$DB->query($select);    
            } else {
                $select 	= 	"SELECT * FROM `inv_qne_product_category` WHERE ". $status ." ORDER BY category_id DESC";
                $conn		= 	$DB->query($select);
            }

            if(mysqli_num_rows($conn) > 0)
            {
                $products 	= 	array();
                $c			=	0;
                while($fetch = mysqli_fetch_object($conn))
                {	
                    $products[$c]					= new Warehouse();
                    $products[$c]->category_id	    = $fetch->category_id;
                    $products[$c]->category_woo_id  = $fetch->category_woo_id;
                    $products[$c]->category_title	= $fetch->category_title;
                    $products[$c]->status		    = $fetch->status;
                    $products[$c]->date		        = $fetch->date;
                    $c++;
                }
                return $products;
            }
        }

        public function getProductCategory($category_id=0)
        {
            $DB = new DB_connection();
            
            $status = " 1 = 1 ";
            
            if( isset($category_id) && !empty($category_id) )
            {
                $select 	= 	"SELECT * FROM `inv_qne_product_category` WHERE `category_woo_id` IN (" . $category_id . ") ORDER BY weight DESC LIMIT 1";
                $conn		= 	$DB->query($select);    
            }
            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                	
                $this->category_id	    = $fetch->category_id;
                $this->category_woo_id  = $fetch->category_woo_id;
                $this->category_title	= $fetch->category_title;
                $this->weight		    = $fetch->weight;
                $this->length		    = $fetch->length;
                $this->width		    = $fetch->width;
                $this->height		    = $fetch->height;
                $this->status		    = $fetch->status;
                $this->date		        = $fetch->date;
                    
            }
        }

        public function allProductCategory($product_id=0)
        {
            $DB = new DB_connection();
            
            $productSize = array();
            if( isset($product_id) && !empty($product_id) && $product_id >= 0 )
            {
                $select = "SELECT pc.*, cm.category_title FROM `inv_qne_product_category_map` pc JOIN `inv_qne_product_category` cm ON pc.category_id = cm.category_woo_id WHERE `product_id` = " . $product_id . " ORDER BY weight ASC";
                $conn	= $DB->query($select);    
            }
            if(mysqli_num_rows($conn) > 0)
            {
                $c = 0;
                while($fetch = mysqli_fetch_object($conn))
                {	
                    $productSize[$c]			    = new Warehouse();
                    $productSize[$c]->category_id	= $fetch->category_id;
                    $productSize[$c]->product_id    = $fetch->product_id;
                    $productSize[$c]->category_title= $fetch->category_title;
                    $c++;
                } 
            }
            return $productSize;
        }

        function insert_category_into_woocommerce($endpoint, $consumer_key, $consumer_secret, $product_data) {
            // WooCommerce API endpoint for creating products
            $endpoint = $endpoint;
        
            // Initialize cURL session
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_POSTFIELDS => json_encode($product_data),
                CURLOPT_USERPWD => $consumer_key . ":" . $consumer_secret, // Basic Authentication
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);
        
            // Execute request
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
        
            // Check if the request was successful (201 means created)
            if ($httpcode == 201) {
                $response_data = json_decode($response, true);
                return $response_data['id'];
            } else {
                return 'Failed to add product. HTTP Status Code: ' . $httpcode . ' Error: ' . $error . ' Response: ' . $response;
            }
        }

        function insert_product_into_woocommerce($store_url, $consumer_key, $consumer_secret, $product_data) {
            // WooCommerce API endpoint for creating products
            $endpoint = $store_url . '/wp-json/wc/v3/products';
        
            // Initialize cURL session
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_POSTFIELDS => json_encode($product_data),
                CURLOPT_USERPWD => $consumer_key . ":" . $consumer_secret, // Basic Authentication
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);
        
            // Execute request
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
        
            // Check if the request was successful (201 means created)
            if ($httpcode == 201) {
                $response_data = json_decode($response, true);
                return $response_data['id'];
            } else {
                return 'Failed to add product. HTTP Status Code: ' . $httpcode . ' Error: ' . $error . ' Response: ' . $response;
            }
        }

        function delete_product_woocommerce($product_id, $consumer_key, $consumer_secret)
        {
            // Endpoint to delete the product
            //$endpoint = $store_url . "/wp-json/wc/v3/products/" . $product_id . "?force=true"; 
            $endpoint = $this->store_url . "/wp-json/wc/v3/products/" . $product_id . "?force=true";
            // 'force=true' ensures permanent deletion (without moving to trash)
            
            // Initialize cURL
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "DELETE", // Use DELETE method
                CURLOPT_SSL_VERIFYPEER => true, // Enable SSL verification
                CURLOPT_USERPWD => $consumer_key . ":" . $consumer_secret, // Basic Authentication
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);
            
            // Execute request
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            
            // Close cURL
            curl_close($curl);
            
            // Handle response
            if ($httpcode === 200) {
                $result = json_decode($response, true);
                echo "Product with ID $product_id has been deleted successfully.\n";
                echo "Deleted Product Name: " . $result['name'] . "\n";
            } elseif ($httpcode === 404) {
                echo "Product with ID $product_id does not exist.\n";
            } else {
                echo "Failed to delete product. HTTP Code: $httpcode. Error: $error\n";
            }
            //exit();
        }

        function sync_data_into_woocommerce($end_point, $consumer_key, $consumer_secret, $product_data) {
            // WooCommerce API endpoint for creating products
            $endpoint = $end_point;//$store_url . '/wp-json/wc/v3/products';
        
            // Initialize cURL session
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_POSTFIELDS => json_encode($product_data),
                CURLOPT_USERPWD => $consumer_key . ":" . $consumer_secret, // Basic Authentication
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);
        
            // Execute request
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
        
            // Check if the request was successful (201 means created)
            if ($httpcode == 201) {
                $response_data = json_decode($response, true);
                return $response_data['id'];
            } else {
                return 'Failed to add product. HTTP Status Code: ' . $httpcode . ' Error: ' . $error . ' Response: ' . $response;
            }
        }

        function insert_tags_into_woocommerce($store_url, $consumer_key, $consumer_secret, $product_data) {
            // WooCommerce API endpoint for creating products
            $endpoint = $store_url . '/wp-json/wc/v3/products/tags';
        
            // Initialize cURL session
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_POSTFIELDS => json_encode($product_data),
                CURLOPT_USERPWD => $consumer_key . ":" . $consumer_secret, // Basic Authentication
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);
        
            // Execute request
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
        
            // Check if the request was successful (201 means created)
            if ($httpcode == 201) {
                $response_data = json_decode($response, true);
                return $response_data['id'];
            } else {
                return 'Failed to add product. HTTP Status Code: ' . $httpcode . ' Error: ' . $error . ' Response: ' . $response;
            }
        }

        function insert_attributes_into_woocommerce($store_url, $consumer_key, $consumer_secret, $product_data) {
            // WooCommerce API endpoint for creating products
            $endpoint = $store_url . '/wp-json/wc/v3/products/attributes';
        
            // Initialize cURL session
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_POSTFIELDS => json_encode($product_data),
                CURLOPT_USERPWD => $consumer_key . ":" . $consumer_secret, // Basic Authentication
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);
        
            // Execute request
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
        
            // Check if the request was successful (201 means created)
            if ($httpcode == 201) {
                $response_data = json_decode($response, true);
                return $response_data['id'];
            } else {
                return 'Failed to add product. HTTP Status Code: ' . $httpcode . ' Error: ' . $error . ' Response: ' . $response;
            }
        }

        public function updateWooCategory($categoryID, $wooCategoryID)
        {
            $DB = new DB_connection();
            
            $update = "UPDATE `inv_qne_product_category` SET `category_woo_id`  = '". $wooCategoryID . "' WHERE `category_id` = " . $categoryID;
            $DB->query($update);  
        }

        public function updateWooSize($size_id, $wooSizeID)
        {
            $DB = new DB_connection();
            
            $update = "UPDATE `inv_qne_product_size` SET `woo_size_id`  = '". $wooSizeID . "' WHERE `size_id` = " . $size_id;
            $DB->query($update);  
        }

        public function updateWooProduct($product_id, $wooProductID)
        {
            $DB = new DB_connection();
            
            $update = "UPDATE `inv_qne_products` SET `woo_product_id`  = '". $wooProductID . "' WHERE `product_id` = " . $product_id;
            $DB->query($update);  
        }

        public function updateWooType($type_id, $tags_woo_id)
        {
            $DB = new DB_connection();
            
            $update = "UPDATE `inv_qne_product_type` SET `tags_woo_id`  = '". $tags_woo_id . "' WHERE `type_id` = " . $type_id;
            $DB->query($update);  
        }
        
        public function addproductCategory($productCategory="")
        {
            $DB = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            
            if(isset($productCategory) && !empty($productCategory)){
                $product_cate_name = $productCategory;
            }
            
            if( empty($productCategory) && (isset($product_category) && !empty($product_category)) ){
                $product_cate_name = $mySQLi->real_escape_string($product_category);
            }
            
            $select 	= 	"SELECT category_id  FROM `inv_qne_product_category` WHERE `category_title` = '". $product_cate_name ."' ";
            $conn		= 	$DB->query($select);
            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                $category_id = $fetch->category_id ;
            } else {
                $insert_type = "INSERT INTO `inv_qne_product_category`(`category_title`, `weight`, `length`, `width`, `height`, `status`, `date`, `added_by`) 
                            VALUES('" . $product_cate_name . "', '" . $product_weight . "', '" . $product_length . "', '" . $product_width . "', '" . $product_height . "', '1', '" . date('Y-m-d') . "', '" . $_SESSION['sess_user_id'] . "')";
                $conn = $DB->query($insert_type);
                $category_id = mysqli_insert_id($mySQLi);

                //Sync with WooCommerce Starts Here
                $data = [
                    'name' => $product_cate_name, // Category name
                    'slug' => $this->sanitizeString($product_cate_name), // Optional: Custom slug
                    'description' => $product_cate_name, // Optional: Category description
                    'parent' => 0
                ];

                $endPoint = $this->store_url . '/wp-json/wc/v3/products/categories';
                $wooCategoryID = $this->insert_category_into_woocommerce($endPoint, $this->consumer_key, $this->consumer_secret, $data);
                $this->updateWooCategory($category_id, $wooCategoryID);
                //Sync with WooCommerce Ends Here
                
                $DB->done();
            }
            
            return $category_id;
        }
        
        public function editProductCategory($category_id=0)
        {   
            if( !isset($category_id) || empty($category_id) || $category_id <= 0 )
            {
                return false;
            }
            
            $DB = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            //echo "<pre>"; print_r($_POST);die;
            
            if(isset($productCategory) && !empty($productCategory)){
                $product_size_name = $productCategory;
            }
            
            if( empty($productSize) && (isset($product_category) && !empty($product_category)) ){
                $product_category_name = $mySQLi->real_escape_string($product_category);
            }
                        
            $update = "UPDATE `inv_qne_product_category` SET category_title = '". $product_category_name ."', weight = '". $product_weight ."', length = '". $product_length ."', width = '". $product_width ."', height = '". $product_height ."', status = '". $product_category_status ."' WHERE category_id = '". $category_id ."'  ";
            $conn = $DB->query($update);
    
            $DB->done();
            
            return $category_id;
        }

        public function getProductSize($size_id=0)
        {
            $DB = new DB_connection();
            
            $status = " 1 = 1 ";
            
            if( isset($size_id) && !empty($size_id) )
            {
                $select 	= 	"SELECT * FROM `inv_qne_product_size` WHERE `size_id` = ". $size_id;
                $conn		= 	$DB->query($select);    
            }

            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                	
                $this->size_id	     = $fetch->size_id;
                $this->woo_size_id   = $fetch->woo_size_id;
                $this->product_size	 = $fetch->product_size;
                $this->status		 = $fetch->status;
                $this->date		     = $fetch->date;
                
            }
        }

        
        public function getCategoryDetails($category_id=0)
        {
            $DB = new DB_connection();
            
            if( isset($category_id) && !empty($category_id) && $category_id >= 0 )
            {
                $select = "SELECT * FROM `inv_qne_product_category` WHERE `category_id` = " . $category_id;
                $conn   = $DB->query($select);    

                if(mysqli_num_rows($conn) > 0)
                { 
                    $fetch = mysqli_fetch_object($conn);
                        
                    $this->category_id	    = $fetch->category_id;
                    $this->category_woo_id  = $fetch->category_woo_id;
                    $this->category_title	= $fetch->category_title;
                    $this->weight		    = $fetch->weight;
                    $this->length		    = $fetch->length;
                    $this->width		    = $fetch->width;
                    $this->height		    = $fetch->height;
                    $this->status		    = $fetch->status;
                    $this->date		        = $fetch->date;
                    
                }
            }            
        }

        public function productSize($size_id=0,$all_sizes=0)
        {
            $DB = new DB_connection();
            
            $status = " 1 = 1 ";
            if( isset($all_sizes) && !empty($all_sizes) && $all_sizes >= 0 )
            {
                $status = " 1 = 1 ";
            } else {
                $status = " `status` = 1 ";
            }
            
            if( isset($size_id) && !empty($size_id) && $size_id >= 0 )
            {
                $select 	= 	"SELECT * FROM `inv_qne_product_size` WHERE ". $status ." AND `size_id` = ". $size_id ." ORDER BY size_id DESC";
                $conn		= 	$DB->query($select);    
            } else {
                $select 	= 	"SELECT * FROM `inv_qne_product_size` WHERE ". $status ." ORDER BY size_id DESC";
                $conn		= 	$DB->query($select);
            }

            if(mysqli_num_rows($conn) > 0)
            {
                $productSize 	= 	array();
                $c			=	0;
                while($fetch = mysqli_fetch_object($conn))
                {	
                    $productSize[$c]					= new Warehouse();
                    $productSize[$c]->size_id	        = $fetch->size_id;
                    $productSize[$c]->woo_size_id       = $fetch->woo_size_id;
                    $productSize[$c]->product_size	    = $fetch->product_size;
                    $productSize[$c]->status		    = $fetch->status;
                    $productSize[$c]->date		        = $fetch->date;
                    $c++;
                }
                return $productSize;
            }
        }
        
        public function addproductSize($productSize="")
        {
            $DB = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            
            if(isset($productSize) && !empty($productSize)){
                $product_size_name = $productSize;
            }
            
            if( empty($productSize) && (isset($product_size) && !empty($product_size)) ){
                $product_size_name = $mySQLi->real_escape_string($product_size);
            }
            
            $select 	= 	"SELECT size_id FROM `inv_qne_product_size` WHERE `product_size` = '". $product_size_name ."' ";
            $conn		= 	$DB->query($select);
            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                $size_id = $fetch->size_id;
            } else {
                $insert_type = "INSERT INTO `inv_qne_product_size`(`product_size`, `status`, `date`, `added_by`) 
                            VALUES('" . $product_size_name . "', '1', '" . date('Y-m-d') . "', '" . $_SESSION['sess_user_id'] . "')";
                $conn = $DB->query($insert_type);
                $size_id = mysqli_insert_id($mySQLi);

                //Sync with WooCommerce Starts Here
                $data = [
                    'name' => $product_size_name, // Category name
                    'slug' => $this->sanitizeString($product_size_name),
                    'type' => 'select', // Optional: Attribute type ('select', 'text', etc.)
                    'order_by' => 'menu_order', // Optional: How to sort ('menu_order', 'name', 'name_num', or 'id')
                    'has_archives' => true, // Optional: Enable archives for this attribute
                ];
                $wooCategoryID = $this->insert_attributes_into_woocommerce($this->store_url, $this->consumer_key, $this->consumer_secret, $data);
                $this->updateWooSize($size_id, $wooCategoryID);
                //Sync with WooCommerce Ends Here
                $DB->done();
            }
            
            return $size_id;
        }

        public function sanitizeString($string) {
            // Remove special characters and replace spaces with hyphens
            $sanitized = preg_replace('/[^a-zA-Z0-9\s]/', '', $string); // Remove special characters
            $sanitized = preg_replace('/\s+/', '-', $sanitized);       // Replace spaces with hyphens
            return strtolower($sanitized);                            // Convert to lowercase
        } 
        
        public function editProductSize($size_id=0)
        {   
            if( !isset($size_id) || empty($size_id) || $size_id <= 0 )
            {
                return false;
            }
            
            $DB = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            //echo "<pre>"; print_r($_POST);die;
            
            if(isset($productSize) && !empty($productSize)){
                $product_size_name = $productSize;
            }
            
            if( empty($productSize) && (isset($product_size) && !empty($product_size)) ){
                $product_size_name = $mySQLi->real_escape_string($product_size);
            }
                        
            $update = "UPDATE `inv_qne_product_size` SET product_size = '". $product_size_name ."', status = '". $product_size_status ."' WHERE size_id = '". $size_id ."'  ";
            $conn = $DB->query($update);
    
            $DB->done();
            
            return $size_id;
        }
    
        public function addproductType($productType="")
        {
            $DB = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            //echo "<pre>"; print_r($_POST);die;
            
            if(isset($productType) && !empty($productType)){
                $product_type_name = $productType;
            }
            
            if( empty($productType) && (isset($product_type) && !empty($product_type)) ){
                $product_type_name = $mySQLi->real_escape_string($product_type);
            }
            
            $select 	= 	"SELECT type_id FROM `inv_qne_product_type` WHERE `product_type` = '". $product_type_name ."' ";
            $conn		= 	$DB->query($select);
            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                $product_id = $fetch->type_id;
            } else {
                $insert_type = "INSERT INTO `inv_qne_product_type`(`product_type`, `status`, `date`, `added_by`) 
                            VALUES('" . $product_type_name . "', '1', '" . date('Y-m-d') . "', '" . $_SESSION['sess_user_id'] . "')";
                $conn = $DB->query($insert_type);
                $product_id = mysqli_insert_id($mySQLi);

                $data = [
                    'name' => $product_type_name, // Category name
                    'slug' => $this->sanitizeString($product_type_name), // Optional: Custom slug
                    'description' => $product_type_name, // Optional: Category description
                ];
                //echo "<pre>"; print_r($data); echo "</pre>";
                $wooCategoryID = $this->insert_tags_into_woocommerce($this->store_url, $this->consumer_key, $this->consumer_secret, $data);
                
                $this->updateWooType($product_id, $wooCategoryID);
    
                $DB->done();
            }
            
            return $product_id;
        }
        
        public function editProductType($type_id=0)
        {
            if( !isset($type_id) || empty($type_id) || $type_id <= 0 )
            {
                return false;
            }
            
            $DB = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            //echo "<pre>"; print_r($_POST);die;
            
            if(isset($productType) && !empty($productType)){
                $product_type_name = $productType;
            }
            
            if( empty($productType) && (isset($product_type) && !empty($product_type)) ){
                $product_type_name = $mySQLi->real_escape_string($product_type);
            }
                        
            $update = "UPDATE `inv_qne_product_type` SET product_type = '". $product_type_name ."', status = '". $product_type_status ."' WHERE type_id = '". $type_id ."'  ";
            $conn = $DB->query($update);
    
            $DB->done();
            
            return $type_id;
        }
        
        public function productType($type_id=0,$all_types=0)
        {
            $DB = new DB_connection();         
            
            $status = " 1 = 1 ";
            if( isset($all_types) && !empty($all_types) && $all_types >= 0 )
            {
                $status = " 1 = 1 ";
            } else {
                $status = " `status` = 1 ";
            }
            
            if( isset($type_id) && !empty($type_id) && $type_id >= 0 )
            {
                $select 	= 	"SELECT * FROM `inv_qne_product_type` WHERE ". $status ." AND `type_id` = ". $type_id ." ORDER BY type_id DESC";
                $conn		= 	$DB->query($select);    
            } else {
                $select 	= 	"SELECT * FROM `inv_qne_product_type` WHERE ". $status ." ORDER BY type_id DESC";
                $conn		= 	$DB->query($select);
            }

            if(mysqli_num_rows($conn) > 0)
            {
                $products 	= 	array();
                $c			=	0;
                while($fetch = mysqli_fetch_object($conn))
                {	
                    $products[$c]					= new Warehouse();
                    $products[$c]->type_id	        = $fetch->type_id;
                    $products[$c]->tags_woo_id      = $fetch->tags_woo_id;
                    $products[$c]->product_type		= $fetch->product_type;
                    $products[$c]->status		    = $fetch->status;
                    $products[$c]->date		        = $fetch->date;
                    $c++;
                }
                return $products;
            }
        }

        public function getProductType($type_id=0)
        {
            $DB = new DB_connection();         
            
            if( isset($type_id) && !empty($type_id) && $type_id >= 0 )
            {
                $select 	= 	"SELECT * FROM `inv_qne_product_type` WHERE `type_id` = ". $type_id;
                $conn		= 	$DB->query($select);    
            }

            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                
                $this->type_id	        = $fetch->type_id;
                $this->tags_woo_id      = $fetch->tags_woo_id;
                $this->product_type		= $fetch->product_type;
                $this->status		    = $fetch->status;
                $this->date		        = $fetch->date;
                
            }
        }
        
        public function getLocationDetails($location_id=0)
        {
            $DB = new DB_connection();
            
            $select 	= 	"SELECT * FROM `inv_qne_locations` WHERE `location_id` = " . $location_id;
            $conn		= 	$DB->query($select);
            
            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                $locations 	= 	array();
                
                $locations					= new Warehouse();
                $locations->location_id	    = $fetch->location_id;
                $locations->location_name	= $fetch->location_name;
                $locations->status		    = $fetch->status;
                $locations->date		    = $fetch->date;
                
                return $locations;
            } else {
                return false;
            }
        }
        public function getBinLocationDetails($bin_location_id=0)
        {
            $DB = new DB_connection();
            
            $select 	= 	"SELECT * FROM `inv_qne_bin_locations` WHERE `bin_id` = " . $bin_location_id;
            $conn		= 	$DB->query($select);
            
            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                $locations 	= 	array();
                
                $locations					= new Warehouse();
                $locations->bin_id          = $fetch->bin_id;
                $locations->location_id	    = $fetch->location_id;
                $locations->bin_location	= $fetch->bin_location;
                $locations->status		    = $fetch->status;
                $locations->date		    = $fetch->date;
                
                return $locations;
            } else {
                return false;
            }
        }

        public function allLocation($location_id=0, $status=0, $start=0, $limit=0)
        {
            $DB = new DB_connection();
            
            $where = "";
            if( isset($location_id) && $location_id > 0 )
            {
                $where .= " AND `location_id` = " . $location_id;
            }
            
            if( isset($status) && $status > 0 )
            {
                $where .= " AND `status` = " . $status;
            }
            
            if($start == 0 && $limit == 0) 
            {
                $select 	= 	"SELECT * FROM `inv_qne_locations` WHERE 1=1 ". $where;
                $conn		= 	$DB->query($select);   
            } else {
                $select 	= 	"SELECT * FROM `inv_qne_locations` WHERE 1=1 ". $where ." LIMIT " . $start . ", " . $limit;
                $conn		= 	$DB->query($select);
            }

            if(mysqli_num_rows($conn) > 0)
            {
                $products 	= 	array();
                $c			=	0;
                while($fetch = mysqli_fetch_object($conn))
                {	
                    $products[$c]					= new Warehouse();
                    $products[$c]->location_id	    = $fetch->location_id;
                    $products[$c]->location_name	= $fetch->location_name;
                    $products[$c]->status		    = $fetch->status;
                    $products[$c]->date		        = $fetch->date;
                    $c++;
                }
                return $products;
            } else {
                return false;
            }
        }
        
        public function allBinLocation($location_id=0, $status=0, $start=0, $limit=0)
        {
            $DB = new DB_connection();
            
            $where = "";
            if( isset($location_id) && $location_id > 0 )
            {
                $where .= " AND l.`location_id` = " . $location_id;
            }
            
            if( isset($status) && $status > 0 )
            {
                $where .= " AND l.`status` = " . $status;
            }
            
            if($start == 0 && $limit == 0) 
            {
                $select 	= 	"SELECT * FROM `inv_qne_bin_locations` bl LEFT JOIN `inv_qne_locations` l ON bl.location_id = l.location_id WHERE 1=1 ". $where;
                $conn		= 	$DB->query($select);   
            } else {
                $select 	= 	"SELECT * FROM `inv_qne_bin_locations` bl LEFT JOIN `inv_qne_locations` l ON bl.location_id = l.location_id WHERE 1=1 ". $where ." LIMIT " . $start . ", " . $limit;
                $conn		= 	$DB->query($select);
            }

            if(mysqli_num_rows($conn) > 0)
            {
                $products 	= 	array();
                $c			=	0;
                while($fetch = mysqli_fetch_object($conn))
                {	
                    $products[$c]					= new Warehouse();
                    $products[$c]->bin_id   	    = $fetch->bin_id;
                    $products[$c]->location_id	    = $fetch->location_id;
                    $products[$c]->location_name	= $fetch->location_name;
                    $products[$c]->bin_location	    = $fetch->bin_location;
                    $products[$c]->status		    = $fetch->status;
                    $products[$c]->date		        = $fetch->date;
                    $c++;
                }
                return $products;
            } else {
                return false;
            }
        }

        public function productBinLocationByLocation($locationID)
        {
            $DB = new DB_connection();            

            $select 	= 	"SELECT * FROM `inv_qne_bin_locations` WHERE `status` = 1 AND `location_id` = " . $locationID;
            $conn		= 	$DB->query($select);
            $products 	= 	array();

            if(mysqli_num_rows($conn) > 0)
            {
                $c			=	0;
                while($fetch = mysqli_fetch_object($conn))
                {	
                    $products[$c]				    = new Warehouse();
                    $products[$c]->bin_id	        = $fetch->bin_id;
                    $products[$c]->location_id	    = $fetch->location_id;
                    $products[$c]->bin_location	    = $fetch->bin_location;
                    $products[$c]->status		    = $fetch->status;
                    $products[$c]->date		        = $fetch->date;
                    $c++;
                }
                
            }
            return $products;
        }

        public function productBinLocation()
        {
            $DB = new DB_connection();            

            $select 	= 	"SELECT * FROM `inv_qne_bin_locations` WHERE `status` = 1";
            $conn		= 	$DB->query($select);

            if(mysqli_num_rows($conn) > 0)
            {
                $products 	= 	array();
                $c			=	0;
                while($fetch = mysqli_fetch_object($conn))
                {	
                    $products[$c]				    = new Warehouse();
                    $products[$c]->bin_id	        = $fetch->bin_id;
                    $products[$c]->location_id	    = $fetch->location_id;
                    $products[$c]->bin_location	    = $fetch->bin_location;
                    $products[$c]->status		    = $fetch->status;
                    $products[$c]->date		        = $fetch->date;
                    $c++;
                }
                return $products;
            }
        }
        
        public function addLocation()
        {
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            
            $location_name	    =   $mySQLi->real_escape_string($location_name);
            $location_type		=   (int) $mySQLi->real_escape_string($location_type);
            $parent_location	=   (int) $mySQLi->real_escape_string($parent_location);            
            $location_status	=   (int) $mySQLi->real_escape_string($location_status);
            
            if($location_type === 1)        // Its a Parent Location Entry ...
            {
                $insert = "INSERT INTO `inv_qne_locations`(`location_name`, `status`, `date`, `added_by`) 
                        VALUES('" . $location_name . "', '" . $location_status . "', '" . date('Y-m-d H:i:s') . "', '" . $_SESSION['sess_user_id'] . "')";
                $DB->query($insert) or die($insert);
                $location_id	= mysqli_insert_id($mySQLi);
            } else          // Its a Bin Location Entry ...
            {  
                $insert = "INSERT INTO `inv_qne_bin_locations`(`location_id`, `bin_location`, `status`, `date`, `added_by`) 
                        VALUES('" . $parent_location . "', '" . $location_name . "', '" . $location_status . "', '" . date('Y-m-d H:i:s') . "', '" . $_SESSION['sess_user_id'] . "')";
                $DB->query($insert) or die($insert);
                $location_id	= mysqli_insert_id($mySQLi);
            }
            return $location_id;
        }
        
        public function editLocation($location_id=0, $loc_type="")
        {
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            
            $location_name	    =   $mySQLi->real_escape_string($location_name);
            $location_type		=   (int) $mySQLi->real_escape_string($location_type);
            $parent_location	=   (int) $mySQLi->real_escape_string($parent_location);            
            $location_status	=   (int) $mySQLi->real_escape_string($location_status);
            
            if($loc_type === "location")        // Its a Parent Location Entry ...
            {
                $update 	= 	"UPDATE `inv_qne_locations` SET `location_name` = '" . $location_name . "', `status` = '" . $location_status . "', `date` = '" . date('Y-m-d H:i:s') . "', `added_by` = '" . $_SESSION['sess_user_id'] . "' WHERE `location_id` = '" . $location_id . "'";
                $DB->query($update) or die($update);
                return true;
            } else if($loc_type === "bin_location")          // Its a Bin Location Entry ...
            {  
                $update 	= 	"UPDATE `inv_qne_bin_locations` SET `location_id` = '" . $parent_location . "', `bin_location` = '" . $location_name . "',  `status` = '" . $location_status . "', `date` = '" . date('Y-m-d H:i:s') . "', `added_by` = '" . $_SESSION['sess_user_id'] . "' WHERE `bin_id` = '" . $location_id . "'";
                $DB->query($update) or die($update);
                return true;
            }
            return false;
        }
        
        public function moveProductLocation($productID, $locationID, $binID)
        { 
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	
            
            $product_ids	    = $mySQLi->real_escape_string($productID);
            $parent_location	= $mySQLi->real_escape_string($locationID);
            $bin_location		= $mySQLi->real_escape_string($binID);
            
            if( !isset($parent_location) || empty($parent_location) )
    		{
    		    return "Error: Please Select Location";
    		}
    		
    		if( !isset($bin_location) || empty($bin_location) )
    		{
    		    return "Error: Please Select Bin Location";
    		}
    		
    		if( isset($product_ids) && !empty($product_ids) )
    		{
                $select = "SELECT * FROM `inv_qne_product_location` WHERE `product_id` = '" . $product_ids . "'";
                $conn = $DB->query($select);
                $unsynWebsite = "0";
                if(mysqli_num_rows($conn) > 0)
                {
                    $unsynWebsite = "1";
                }

                $delete = "DELETE FROM `inv_qne_product_location` WHERE `product_id` = '" . $product_ids . "'";
                $DB->query($delete);
                $insert = "INSERT INTO `inv_qne_product_location`(`product_id`, `location_id`, `bin_location_id`, `moved_by`, `date`) 
                           VALUES('" . $product_ids . "', '" . $parent_location . "', '" . $bin_location . "', '" . $_SESSION['sess_user_id'] . "', '" . date('Y-m-d') . "')";
                $DB->query($insert);

                $update = "UPDATE `inv_qne_products` SET `product_location` = '" . $parent_location . "', `product_bin` = '" . $bin_location . "' WHERE `product_id` = '" . $product_ids . "'";
                $DB->query($update);

                if($parent_location == 6)
                {
                    $this->syncProductWooCommerce($product_ids);
                }
                else
                if($parent_location != 6 && $unsynWebsite == "1")
                {
                    $this->unsyncProductWooCommerce($product_ids);
                }
    		    /*$productIDs = explode(",", $product_ids);
    		    
    		    if(sizeof($productIDs) > 0)
    		    {
    		        for($a=0; $a < sizeof($productIDs); $a++)
    		        {
    		            //echo $productIDs[$a];
    		            $insert = "";
    		            $insert = "INSERT INTO `inv_qne_product_location`(`product_id`, `location_id`, `bin_location_id`, `moved_by`, `date`) 
                        VALUES('" . $productIDs[$a] . "', '" . $product_location . "', '" . $product_bin . "', '" . $_SESSION['sess_user_id'] . "', '" . date('Y-m-d') . "')";
                        $DB->query($insert);
    		        }
    		        return true;
    		    }*/
    		}
    		
    		return "Error: Something went wrong. Please try again later";
		
        }
        
        public function moveProducts()
        { 
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            
            $product_ids	    = $mySQLi->real_escape_string($product_ids);
            $parent_location	= $mySQLi->real_escape_string($product_location);
            $bin_location		= $mySQLi->real_escape_string($product_bin);
            
            if( !isset($parent_location) || empty($parent_location) )
    		{
    		    return "Error: Please Select Location";
    		}
    		
    		if( !isset($bin_location) || empty($bin_location) )
    		{
    		    return "Error: Please Select Bin Location";
    		}
    		
    		if( isset($product_ids) && !empty($product_ids) )
    		{
    		    $productIDs = explode(",", $product_ids);
    		    
    		    if(sizeof($productIDs) > 0)
    		    {
    		        for($a=0; $a < sizeof($productIDs); $a++)
    		        {
    		            //echo $productIDs[$a];
    		            $insert = "";
    		            $insert = "INSERT INTO `inv_qne_product_location`(`product_id`, `location_id`, `bin_location_id`, `moved_by`, `date`) 
                        VALUES('" . $productIDs[$a] . "', '" . $product_location . "', '" . $product_bin . "', '" . $_SESSION['sess_user_id'] . "', '" . date('Y-m-d') . "')";
                        $DB->query($insert);
    		        }
    		        return true;
    		    }
    		}
    		
    		return "Error: Something went wrong. Please try again later";
		
        }

        public function webProductListing($start=0, $limit=100)
        {
            $DB    = new DB_connection();

            $search = " WHERE `order_status` = '1'";
            if($status != '')
            {
                $search .= " AND `status` IN (" . $status . ")"; 
            }
            
            if($start == 0 && $limit == 0)
            {
                $select = "SELECT count(id) as totalRows FROM `inv_qne_products`" . $search;// ORDER BY `category_id` DESC";
                $conn	= $DB->query($select);
                
                $fetch  = mysqli_fetch_object($conn);
                
                return $fetch->totalRows;
            }
            else
            {
                $query	        = "SELECT * FROM `inv_qne_products`" . $search . " ORDER BY `id` DESC LIMIT " . $start . ", " . $limit;
                $conn	        = $DB->query($query);
                $orderDetails 	= 	array();
                if(mysqli_num_rows($conn) > 0)
                {
                    $c = 0;
                    while($fetch = mysqli_fetch_object($conn))
                    {
                        $orderDetails[$c]			        = new Warehouse();
                        $orderDetails[$c]->product_id       = $fetch->product_id;
                        $orderDetails[$c]->product          = $fetch->product;
                        $orderDetails[$c]->product_sku      = $fetch->product_sku;//Woocommerce Product ID
                        $orderDetails[$c]->product_type		= $fetch->product_type;
                        $orderDetails[$c]->product_size     = $fetch->product_size;
                        $orderDetails[$c]->product_category = $fetch->product_category;
                        $orderDetails[$c]->product_location = $fetch->product_location;
                        $orderDetails[$c]->product_bin	    = $fetch->product_bin;
                        $orderDetails[$c]->tax_type	        = $fetch->tax_type;
                        $orderDetails[$c]->status		    = $fetch->status;
                        $c++;
                    }
                }
            }
            return $orderDetails;
        }

        function checkProductSKU($productSKU='')
        {
            $DB     = new DB_connection();

            $select = "SELECT * FROM `inv_qne_products` WHERE `product_sku` = '" . $productSKU . "'";
            $conn	= $DB->query($select) or die($select);
            if(mysqli_num_rows($conn) > 0)
            {
                echo "0";
            }
            else
            {
                echo "1";
            }
        }

        function addProduct()
        {
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            //echo "<pre>"; print_r($_POST); echo "</pre>";
            $product_sku		= $mySQLi->real_escape_string($product_sku);//mysqli_real_escape_string($DB->_connection, $product_sku);
            $product_name		= mysqli_real_escape_string($DB->_connection, $product_name);//$mySQLi->real_escape_string($product_name);
            $product_type		= (int) $mySQLi->real_escape_string($product_type);
            $product_size		= (int) $mySQLi->real_escape_string($product_size);
            //$product_category	= (int) $mySQLi->real_escape_string($product_category);
            $product_location	= $mySQLi->real_escape_string($product_location);
            $product_bin		= $mySQLi->real_escape_string($product_bin);
            $product_qty		= $mySQLi->real_escape_string($product_qty);            
            $product_msrp		= $mySQLi->real_escape_string($product_msrp);
            $product_wholesale	= $mySQLi->real_escape_string($product_wholesale);
            $product_price	    = $mySQLi->real_escape_string($product_price);
            
            $product_type_other		    =   $mySQLi->real_escape_string($product_type_other);
            $product_size_other		    =   $mySQLi->real_escape_string($product_size_other);
            $product_category_other		=   $mySQLi->real_escape_string($product_category_other);
            
            
            if( $product_type === 0 && !empty($product_type_other) ){ 
               $product_type = $this->addproductType($product_type_other);
            }
            
            if( $product_size === 0 && !empty($product_size_other) ){ 
               $product_size = $this->addproductSize($product_size_other);
            }
            
            if( $product_category === 0 && !empty($product_category_other) ){ 
               $product_category = $this->addproductCategory($product_category_other);
            }

            $select = "SELECT * FROM `inv_qne_products` WHERE `product_sku` = '" . $product_sku . "'";
            $conn	= $DB->query($select) or die($select);
            if(mysqli_num_rows($conn) > 0)
            {
                return "0";
            }
            $insert = "INSERT INTO `inv_qne_products`(`product`, `product_sku`, `product_type`, `product_size`, `product_msrp`, `product_wholesale`, `product_price`, `product_quantity`, `product_location`, `product_bin`, `status`, `datetime`, `added_by`) 
                        VALUES('" . $product_name . "', '" . $product_sku . "', '" . $product_type . "', '" . $product_size . "', '" . (float)$product_msrp . "', '" . (float)$product_wholesale . "', '" . (float)$product_price . "', '" . $product_qty . "', '" . $product_location . "', '" . $product_bin . "', '1', '" . date('Y-m-d H:i:s') . "', '" . $_SESSION['sess_user_id'] . "')";
            $DB->query($insert) or die($insert);
            $product_id	= mysqli_insert_id($mySQLi);
            $categoryArr = array();
            $product_category_id = '';
            if(sizeof($product_category) > 0)
            {
                $c = 0;
                foreach($product_category as $category_id)
                {
                    $insert = "INSERT INTO `inv_qne_product_category_map`(`product_id`, `category_id`, `datetime`, `added_by`) VALUES('" . $product_id . "', '" . $category_id . "', '" . date('Y-m-d H:i:s') . "', '" . $_SESSION['sess_user_id'] . "')";
                    $DB->query($insert) or die($insert);
                    $categoryArr[$c] = ['id' => $category_id];
                    if($c > 0)
                    {
                        $product_category_id .= ',';
                    }
                    $product_category_id .= $category_id;

                    $c++;
                }
            }

            $insert_2 = "INSERT INTO `inv_qne_product_price`(`product_id`, `product_msrp`, `product_wholesale`, `product_price`, `product_discount`, `status`, `modify_date`, `added_date`) 
                        VALUES('" . $product_id . "', '" . (float)$product_msrp . "', '" . (float)$product_wholesale . "', '" . (float)$product_price . "', '0.00', '1', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "')";
            $DB->query($insert_2);

            $insert_3 = "INSERT INTO `inv_qne_product_stock`(`product_id`, `sku_id`, `qty`, `sold`, `available`, `hold`, `date`) 
                        VALUES('" . $product_id . "', '0', '" . $product_qty . "', '0', '0', '0', '" . date('Y-m-d') . "')";
            $DB->query($insert_3);
            
            // Add Product Location & Bin Location ...
            $insert_4 = "INSERT INTO `inv_qne_product_location`(`product_id`, `location_id`, `bin_location_id`, `moved_by`, `date`) 
                        VALUES('" . $product_id . "', '" . $product_location . "', '" . $product_bin . "', '" . $_SESSION['sess_user_id'] . "', '" . date('Y-m-d') . "')";
            $DB->query($insert_4);
            
            // Capture Image From Camera ...
            $server = "https://inventory.thevintagebazar.com/";
            if ( isset($_POST['capturedImage']) && !empty($_POST['capturedImage']))
            {
                // Handle captured image
                $capturedData = $_POST['capturedImage'];
                $decodedData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $capturedData));
                $fileName = $product_id . "_" . $product_name . '_captured' . '.png';
                $target_dir =   "assets/img/product_img/";
                $targetFile = $target_dir . $fileName;
                $uploaded_images[] = $server . $targetFile;
                if (file_put_contents($targetFile, $decodedData)) 
                {
                    $update = "UPDATE `inv_qne_products` SET `product_image` = '". $fileName ."' WHERE `product_id` = " . $product_id;
                    $DB->query($update);
                } else 
                {
                    //echo "Failed to upload captured image.";
                }
            }
            
            // Add Product Image ...            
            $upload_dir = "assets/img/product_img/";
            $img_count = 1;
            // Loop through uploaded files
            foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) 
            {
                $file_name  = $_FILES['product_images']['name'][$key];
                $file_tmp   = $_FILES['product_images']['tmp_name'][$key];
                $file_size  = $_FILES['product_images']['size'][$key];
                $file_error = $_FILES['product_images']['error'][$key];

                // Ensure there were no errors
                if ($file_error === UPLOAD_ERR_OK) {
                    // Generate a unique file name to avoid overwriting
                    //$unique_file_name = uniqid() . '-' . basename($file_name);
                    //$file_path = $upload_dir . $unique_file_name;

                    $target_file        = $product_id . "_" . $img_count . "_" . basename($file_name);
                    $file_path          = $upload_dir . $target_file;
                    $imageFileType      = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                    // Move the uploaded file to the upload directory
                    if (move_uploaded_file($file_tmp, $file_path)) {
                        $uploaded_images[] = $server . $file_path;

                        // Insert the file path into the database
                        $sql = "INSERT INTO `inv_qne_product_images` (`product_id`, `image`, `featured`, `date`) VALUES ('" . $product_id . "', '" . $target_file . "', '0', '" . date('Y-m-d') . "')";
                        if (!$DB->query($sql)) {
                            echo "Error inserting image path into database: " . $conn->error;
                        }

                        if($img_count == 1)
                        {
                            $update = "UPDATE `inv_qne_products` SET `product_image` = '". $target_file ."' WHERE `product_id` = " . $product_id;
                            $DB->query($update); 
                        }
                    } else {
                        echo "Failed to move file: " . $file_name;
                    }
                } else {
                    //echo "Error uploading file: " . $file_name;
                }
                $img_count++;
            } 
            
            /* Sync Product with WooCommerce Starts Here */
            $this->getProductSize($product_size);
            $this->getProductCategory($product_category_id);
            $product_weight = $this->weight;
            $product_length	= $this->length;
            $product_width	= $this->width;
            $product_height	= $this->height;
            $this->getProductType($product_type);
            $attribute_options[] = 'Size:' . $this->product_size;
            
            if($product_location == 6)
            {
                $product_images = [];
                foreach ($uploaded_images as $index => $image_url) {
                    $product_images[] = [
                        'src' => $image_url,
                        'position' => $index
                    ];
                }
                
                // Data for the new product
                $product_data = [
                    'name' => $product_name,
                    'sku' => $product_sku, // Add SKU here
                    'type' => 'simple',
                    'regular_price' => $product_price,
                    'description' => $product_name,
                    'weight' => $product_weight, // Product weight in kilograms (or in pounds based on WooCommerce settings)
                    'dimensions' => [
                        'length' => $product_length, // Length in centimeters (or inches based on WooCommerce settings)
                        'width'  => $product_width, // Width in centimeters
                        'height' => $product_height   // Height in centimeters
                    ],
                    'short_description' => $product_name,
                    'categories' => $categoryArr/*[
                        [
                            'id' => $this->category_woo_id
                        ]
                    ]*/,
                    'tags' => [
                        [
                            'id' => $this->tags_woo_id
                        ]
                    ],
                    'attributes' => [
                        [
                            'id' => $this->woo_size_id,
                            'options' => $attribute_options,
                            'visible' => true,
                            'variation' => false
                        ]
                    ],
                    'images' => $product_images, // Dynamically added images
                    'manage_stock' => true, // Enable stock management
                    'stock_quantity' => $product_qty, // Set stock quantity
                    'stock_status' => 'instock' // Mark the product as in stock
                ];
                //echo "<pre>"; print_r($product_data); echo "</pre>";exit();
                $wooCategoryID = $this->insert_product_into_woocommerce($this->store_url, $this->consumer_key, $this->consumer_secret, $product_data);
                    
                $this->updateWooProduct($product_id, $wooCategoryID);
            }
            /* Sync Product with WooCommerce Ends Here */
            
            $url = "https://api.qrserver.com/v1/create-qr-code/?data=". $product_sku ."&size=1000x1000";
            $image = file_get_contents($url);
            file_put_contents("qrcodes/". $product_id . "-" .$product_sku .".png", $image);
            //echo "<pre>"; print_r($this); echo "</pre>";exit();
            //exit();
            $DB->done();

            return $product_id;
        }

        function editProduct()
        {
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);

            $product_id		    = $mySQLi->real_escape_string($product_id);
            $woo_product_id	    = $mySQLi->real_escape_string($woo_product_id);
            $product_sku		= $mySQLi->real_escape_string($product_sku);
            $product_name		= mysqli_real_escape_string($DB->_connection, $product_name);
            $product_type		= (int) $mySQLi->real_escape_string($product_type);
            $product_size		= (int) $mySQLi->real_escape_string($product_size);
            //$product_category	= (int) $mySQLi->real_escape_string($product_category);
            $product_location	= $mySQLi->real_escape_string($product_location);
            $product_bin		= $mySQLi->real_escape_string($product_bin);
            $product_qty		= $mySQLi->real_escape_string($product_qty);            
            $product_msrp		= $mySQLi->real_escape_string($product_msrp);
            $product_wholesale	= $mySQLi->real_escape_string($product_wholesale);
            $product_price	    = $mySQLi->real_escape_string($product_price);
            
            $product_type_other		    =   $mySQLi->real_escape_string($product_type_other);
            $product_size_other		    =   $mySQLi->real_escape_string($product_size_other);
            $product_category_other		=   $mySQLi->real_escape_string($product_category_other);
            
            
            if( $product_type === 0 && !empty($product_type_other) ){ 
               $product_type = $this->addproductType($product_type_other);
            }
            
            if( $product_size === 0 && !empty($product_size_other) ){ 
               $product_size = $this->addproductSize($product_size_other);
            }
            
            if( $product_category === 0 && !empty($product_category_other) ){ 
               $product_category = $this->addproductCategory($product_category_other);
            }

            $insert = "UPDATE `inv_qne_products` SET `product` = '" . $product_name . "', `product_sku` = '" . $product_sku . "', `product_type` = '" . $product_type . "', `product_size` = '" . $product_size . "', `product_msrp` = '" . $product_msrp . "', `product_wholesale` = '" . $product_wholesale . "', `product_price` = '" . $product_price . "', `product_quantity` = '" . $product_qty . "', `product_location` = '" . $product_location . "', `product_bin` = '" . $product_bin . "', `status` = '1', `datetime` = '" . date('Y-m-d H:i:s') . "', `added_by` = '" . $_SESSION['sess_user_id'] . "' WHERE `product_id` = " . $product_id;
            $DB->query($insert) or die($insert);


            $categories = [];
            $product_category_id = '';
            if(sizeof($product_category) > 0)
            {
                $delete = "DELETE FROM `inv_qne_product_category_map` WHERE `product_id` = '" . $product_id . "'";
                $DB->query($delete) or die($delete);
                $c = 0;
                foreach($product_category as $category)
                {
                    $insert = "INSERT INTO `inv_qne_product_category_map`(`product_id`, `category_id`, `datetime`, `added_by`) VALUES('" . $product_id . "', '" . $category . "', '" . date('Y-m-d H:i:s') . "', '" . $_SESSION['sess_user_id'] . "')";
                    $DB->query($insert) or die($insert);
                    $categories[] = ['id' => $category];
                    if($c > 0)
                    {
                        $product_category_id .= ',';
                    }
                    $product_category_id .= $category;
                    $c++;
                }
            }
            
            $insert_2 = "UPDATE `inv_qne_product_price` SET `product_msrp` = '" . $product_msrp . "', `product_wholesale` = '" . $product_wholesale . "', `product_price` = '" . $product_price . "', `product_discount` = '0.00', `status` = '1', `modify_date` = '" . date('Y-m-d H:i:s') . "' WHERE `product_id` = '" . $product_id . "'";
            $DB->query($insert_2);

            $insert_3 = "UPDATE `inv_qne_product_stock` SET `sku_id` = '0', `qty` = '" . $product_qty . "', `sold` = '0', `available` = '0', `hold` = '0', `date` = '" . date('Y-m-d') . "' WHERE `product_id` = '" . $product_id . "'";
            $DB->query($insert_3);
            
            // Add Product Location & Bin Location ...
            $insert_4 = "UPDATE `inv_qne_product_location` SET `location_id` = '" . $product_location . "', `bin_location_id` = '" . $product_bin . "', `moved_by` = '" . $_SESSION['sess_user_id'] . "', `date` = '" . date('Y-m-d') . "' WHERE `product_id` = '" . $product_id . "'";
            $DB->query($insert_4);

            $server = "https://inventory.thevintagebazar.com/";
            
            // Capture Image From Camera ...
            if ( isset($_POST['capturedImage']) && !empty($_POST['capturedImage']))
            {
                // Handle captured image
                $capturedData = $_POST['capturedImage'];
                $decodedData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $capturedData));
                $fileName = $product_id . "_" . $product_name . '_captured' . '.png';
                $target_dir =   "assets/img/product_img/";
                $targetFile = $target_dir . $fileName;
        
                $uploaded_images[] = $server . $targetFile;
                if (file_put_contents($targetFile, $decodedData)) 
                {
                    $update = "UPDATE `inv_qne_products` SET `product_image` = '". $fileName ."' WHERE `product_id` = " . $product_id;
                    $DB->query($update);
                }
            }
            
            // Add Product Image ...
            // Add Product Image ...            
            $upload_dir = "assets/img/product_img/";
            $img_count = 1;
            
            // Loop through uploaded files
            //echo "<pre>"; print_r($_FILES['product_images']); echo "</pre>";
            if(sizeof($_FILES['product_images']) > 0)
            {
                foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) 
                {
                    $file_name  = $_FILES['product_images']['name'][$key];
                    $file_tmp   = $_FILES['product_images']['tmp_name'][$key];
                    $file_size  = $_FILES['product_images']['size'][$key];
                    $file_error = $_FILES['product_images']['error'][$key];

                    // Ensure there were no errors
                    if ($file_error === UPLOAD_ERR_OK) {
                        // Generate a unique file name to avoid overwriting
                        //$unique_file_name = uniqid() . '-' . basename($file_name);
                        //$file_path = $upload_dir . $unique_file_name;

                        $target_file        = $product_id . "_" . $img_count . "_" . basename($file_name);
                        $file_path          = $upload_dir . $target_file;
                        $imageFileType      = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                        // Move the uploaded file to the upload directory
                        if (move_uploaded_file($file_tmp, $file_path)) {
                            $uploaded_images[] = $server . $file_path;

                            // Insert the file path into the database
                            if($img_count == 1)
                            {
                                $delete = "DELETE FROM `inv_qne_product_images` WHERE `product_id` = '" . $product_id . "'";
                                $DB->query($delete);
                            }

                            $sql = "INSERT INTO `inv_qne_product_images` (`product_id`, `image`, `featured`, `date`) VALUES ('" . $product_id . "', '" . $target_file . "', '0', '" . date('Y-m-d') . "')";
                            if (!$DB->query($sql)) {
                                echo "Error inserting image path into database: " . $conn->error;
                            }

                            if($img_count == 1)
                            {
                                $update = "UPDATE `inv_qne_products` SET `product_image` = '". $target_file ."' WHERE `product_id` = " . $product_id;
                                $DB->query($update); 

                                
                            }
                        } else {
                            echo "Failed to move file: " . $file_name;
                        }
                    } else {
                        echo "Error uploading file: " . $file_name;
                    }
                    $img_count++;
                } 
            }
            
            /* Sync Product with WooCommerce Starts Here */
            if($product_location == 6)
            {
                $this->getProductSize($product_size);
                $this->getProductCategory($product_category_id);
                $product_weight = $this->weight;
                $product_length	= $this->length;
                $product_width	= $this->width;
                $product_height	= $this->height;
                $this->getProductType($product_type);
                $attribute_options[] = 'Size:' . $this->product_size;
                
                $productImages = $this->getProductImages($product_id);
                $productURL = "https://inventory.thevintagebazar.com/assets/img/product_img/";
                $product_images = [];
                if(sizeof($productImages) > 0)
                {
                    foreach($productImages as $index => $image_url)
                    {
                        $product_images[] = [
                            'src' => $productURL . $image_url['image'],
                            'position' => $index
                        ];
                    }
                }  
                //echo "<pre>"; print_r($product_images); echo "</pre>";
                /*$product_images = [];
                foreach ($uploaded_images as $index => $image_url) {
                    $product_images[] = [
                        'src' => $image_url,
                        'position' => $index
                    ];
                }*/
                //echo "<pre>"; print_r($product_images); echo "</pre>";
                // Data for the new product
                $product_data = [
                    'name' => $product_name,
                    'sku' => $product_sku, // Add SKU here
                    'type' => 'simple',
                    'regular_price' => $product_price,
                    'description' => $product_name,
                    'weight' => $product_weight, // Product weight in kilograms (or in pounds based on WooCommerce settings)
                    'dimensions' => [
                        'length' => $product_length, // Length in centimeters (or inches based on WooCommerce settings)
                        'width'  => $product_width, // Width in centimeters
                        'height' => $product_height   // Height in centimeters
                    ],
                    'short_description' => $product_name,
                    'categories' => $categories/*[
                        [
                            'id' => $this->category_woo_id
                        ]
                    ]*/,
                    'tags' => [
                        [
                            'id' => $this->tags_woo_id
                        ]
                    ],
                    'attributes' => [
                        [
                            'id' => $this->woo_size_id,
                            'options' => $attribute_options,
                            'visible' => true,
                            'variation' => false
                        ]
                    ],
                    'images' => $product_images, // Dynamically added images
                    'manage_stock' => true, // Enable stock management
                    'stock_quantity' => $product_qty, // Set stock quantity
                    'stock_status' => 'instock' // Mark the product as in stock
                ];
                //echo "<pre>"; print_r($product_data); echo "</pre>";exit();
                if($woo_product_id != 0)
                {
                    $end_point = $this->store_url . '/wp-json/wc/v3/products/' . $woo_product_id;
                    $wooCategoryID = $this->sync_data_into_woocommerce($end_point, $this->consumer_key, $this->consumer_secret, $product_data);
                }
                else
                {
                    $wooCategoryID = $this->insert_product_into_woocommerce($this->store_url, $this->consumer_key, $this->consumer_secret, $product_data);
                    $this->updateWooProduct($product_id, $wooCategoryID);
                }
            }
            /* Sync Product with WooCommerce Ends Here */

            /*if( isset($_FILES["product_image"]["name"]) && !empty($_FILES["product_image"]["name"]) )
            {
                $target_dir         =   "assets/img/product_img/";
                $target_file        =   $product_id . "_" . basename($_FILES["product_image"]["name"]);
                $target_file_path   =   $target_dir . $target_file;
                $imageFileType      =   strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                
                if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" || $imageFileType == "webp" ) 
                {
                    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file_path)) 
                    {
                        $update = "UPDATE `inv_qne_products` SET `product_image` = '". $target_file ."' WHERE `product_id` = " . $product_id;
                        $DB->query($update);
                    }
                }
            }*/
            
            $url = "https://api.qrserver.com/v1/create-qr-code/?data=". $product_sku ."&size=1000x1000";
            $image = file_get_contents($url);
            file_put_contents("qrcodes/". $product_id . "-" .$product_sku .".png", $image);
            
            //exit();
            $DB->done();

            return "1"; 
        }

        public function maxWeightProductCategory($productID)
        {
            $DB = new DB_connection();
            
            $status = " 1 = 1 ";
            
            $select 	= 	"SELECT pc.* FROM `inv_qne_product_category` pc JOIN `inv_qne_product_category_map` cm ON pc.category_woo_id = cm.category_id WHERE `product_id` = " . $productID . " ORDER BY weight DESC LIMIT 1";
            $conn		= 	$DB->query($select);    
            
            if(mysqli_num_rows($conn) > 0)
            {
                $fetch = mysqli_fetch_object($conn);
                	
                $this->category_id	    = $fetch->category_id;
                $this->category_woo_id  = $fetch->category_woo_id;
                $this->category_title	= $fetch->category_title;
                $this->weight		    = $fetch->weight;
                $this->length		    = $fetch->length;
                $this->width		    = $fetch->width;
                $this->height		    = $fetch->height;
                $this->status		    = $fetch->status;
                $this->date		        = $fetch->date;
                    
            }
        }

        public function allProductImages($productID)
        {
            $DB             = new DB_connection();            
            $select 	    = "SELECT * FROM `inv_qne_product_images` WHERE `product_id` = " . $productID;
            $conn		    = $DB->query($select);    
            $orderDetails 	= array();
            $c              = 0;
            if(mysqli_num_rows($conn) > 0)
            {
                while($fetch = mysqli_fetch_object($conn))
                {
                    $orderDetails[$c]			    = new Warehouse();
                    $orderDetails[$c]->product_id   = $fetch->product_id;
                    $orderDetails[$c]->image        = $fetch->image;
                    $orderDetails[$c]->featured     = $fetch->featured;
                    $orderDetails[$c]->date		    = $fetch->date;
                    $c++;
                }                    
            }
            return $orderDetails;
        }

        function syncProductWooCommerce($productID)
        {
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	

            $select = "SELECT iqp.*, ips.qty as product_quantity, qpp.product_price FROM `inv_qne_products` iqp JOIN `inv_qne_product_stock` ips ON iqp.product_id = ips.product_id JOIN `inv_qne_product_price` qpp ON iqp.product_id = qpp.product_id WHERE iqp.product_id = '" . $productID . "'";
            $conn	= $DB->query($select);
            $rows	= mysqli_num_rows($conn);
             
            if($rows > 0)
            {
                $fetch = mysqli_fetch_object($conn);

                $product_name   = $fetch->product;
                $product_sku    = $fetch->product_sku;
                $product_price  = $fetch->product_price;
                $product_qty    = $fetch->product_quantity;
                $categories     = [];
                $category_woo_id= 0;
                $product_weight = 0;
                $product_length	= 0;
                $product_width	= 0;
                $product_height	= 0;
                /* Sync Product with WooCommerce Starts Here */
                if($fetch->product_location == 6 && $fetch->woo_product_id == 0)
                {
                    $this->getProductSize($fetch->product_size);
                    $allProductCat = $this->allProductCategory($fetch->product_id);
                    $categories = [];
                    if(sizeof($allProductCat) > 0)
                    {
                        foreach($allProductCat as $productCat)
                        {
                            $categories[] = ['id' => $productCat->category_id];
                            $category_woo_id = $productCat->category_id;
                        }
                    }
                    
                    if($category_woo_id != 0)
                    {
                        $this->getProductCategory($category_woo_id);
                        $product_weight = $this->weight;
                        $product_length	= $this->length;
                        $product_width	= $this->width;
                        $product_height	= $this->height;
                    }
                    
                    $this->getProductType($fetch->product_type);
                    $attribute_options[] = 'Size:' . $this->product_size;
                    $product_images = [];
                    $server     = "https://inventory.thevintagebazar.com/";
                    $upload_dir = "assets/img/product_img/";
                    $allProductImage = $this->allProductImages($fetch->product_id);
                    if(sizeof($allProductImage) > 0)
                    {
                        $index = 0;                       
                        foreach($allProductImage as $productImg)
                        {
                            $image_url = $server . $upload_dir . $productImg->image;
                            $product_images[] = [
                                'src' => $image_url,
                                'position' => $index
                            ];
                            $index++;
                        }
                    }
                    //echo "<pre>"; print_r($product_images); echo "</pre>";
                    
                    
                    /*if(sizeof($product_category) > 0)
                    {
                        foreach($product_category as $category)
                        {
                            $categories[] = ['id' => $category];                            
                        }
                    }*/
                    //echo "<pre>"; print_r($categories); echo "</pre>";
                    // Data for the new product
                    $product_data = [
                        'name' => $product_name,
                        'sku' => $product_sku, // Add SKU here
                        'type' => 'simple',
                        'regular_price' => $product_price,
                        'description' => $product_name,
                        'weight' => $product_weight, // Product weight in kilograms (or in pounds based on WooCommerce settings)
                        'dimensions' => [
                            'length' => $product_length, // Length in centimeters (or inches based on WooCommerce settings)
                            'width'  => $product_width, // Width in centimeters
                            'height' => $product_height   // Height in centimeters
                        ],
                        'short_description' => $product_name,
                        'categories' => $categories/*[
                            [
                                'id' => $this->category_woo_id
                            ]
                        ]*/,
                        'tags' => [
                            [
                                'id' => $this->tags_woo_id
                            ]
                        ],
                        'attributes' => [
                            [
                                'id' => $this->woo_size_id,
                                'options' => $attribute_options,
                                'visible' => true,
                                'variation' => false
                            ]
                        ],
                        'images' => $product_images, // Dynamically added images
                        'manage_stock' => true, // Enable stock management
                        'stock_quantity' => $product_qty, // Set stock quantity
                        'stock_status' => 'instock' // Mark the product as in stock
                    ];
                    //echo "<pre>"; print_r($product_data); echo "</pre>"; //exit();
                    $wooCategoryID = $this->insert_product_into_woocommerce($this->store_url, $this->consumer_key, $this->consumer_secret, $product_data);
                    $this->updateWooProduct($productID, $wooCategoryID);
                }
            }
            
            $DB->done();

            return "1"; 
        }

        function unsyncProductWooCommerce($productID)
        {
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	

            $select = "SELECT * FROM `inv_qne_products` WHERE product_id = '" . $productID . "'";
            $conn	= $DB->query($select);
            $rows	= mysqli_num_rows($conn);
             
            if($rows > 0)
            {
                $fetch = mysqli_fetch_object($conn);

                $woo_product_id = $fetch->woo_product_id;
                
                // WooCommerce API credentials
                $store_url          = $this->store_url; // Replace with your WooCommerce store URL
                $consumer_key       = $this->consumer_key; // Replace with your WooCommerce API consumer key
                $consumer_secret    = $this->consumer_secret; // Replace with your WooCommerce API consumer secret
                
                // Product ID to update
                $product_id         = $woo_product_id; // Replace with the ID of the product you want to set as inactive

                // Endpoint to update the product
                $endpoint = $store_url . "/wp-json/wc/v3/products/" . $product_id;

                // Data to set the product as inactive
                $product_data = [
                    "status" => "draft" // Set the product status to 'draft' or 'pending'
                ];

                // Initialize cURL
                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => $endpoint,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => "PUT", // Use PUT method
                    CURLOPT_SSL_VERIFYPEER => true, // Enable SSL verification
                    CURLOPT_POSTFIELDS => json_encode($product_data),
                    CURLOPT_USERPWD => $consumer_key . ":" . $consumer_secret, // Basic Authentication
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                ]);

                // Execute request
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $error = curl_error($curl);

                // Close cURL
                curl_close($curl);

                // Handle response
                if ($httpcode === 200) {
                    $result = json_decode($response, true);
                    echo "Product with ID $product_id has been set to 'inactive' successfully.\n";
                    echo "Product Name: " . $result['name'] . "\n";
                } elseif ($httpcode === 404) {
                    echo "Product with ID $product_id does not exist.\n";
                } else {
                    echo "Failed to update product status. HTTP Code: $httpcode. Error: $error\n";
                }

            }
            
            $DB->done();

            return "1"; 
        }
        
        public function getOrderByID($order_id=0)
        {
            $DB = new DB_connection();

            $orders 	= 	array();
            
            if($order_id > 0)
            {
                $select = 	"SELECT * FROM `inv_qne_orders` o LEFT JOIN `inv_qne_users` u ON o.created_by = u.user_id WHERE `order_id` = ". $order_id;
                $conn	= 	$DB->query($select);

                if(mysqli_num_rows($conn) > 0)
                {
                    $fetch = mysqli_fetch_array($conn);
                    
                    $orders['order_id']		    =	$fetch['order_id'];
                    $orders['sub_total']		=	$fetch['sub_total'];
                    $orders['discount']		    =	$fetch['discount'];
                    $orders['tax_amount']		=  	$fetch['tax_amount'];
                    $orders['shipping_rate']	=	$fetch['shipping_rate'];
                    $orders['order_total']	    =	$fetch['order_total'];
                    $orders['created_date']	    =	$fetch['created_date'];
                    $orders['created_by']  	    =	$fetch['created_by'];
                    $orders['status']			=	$fetch['status'];
                    $orders['user']			    =	$fetch['f_name'] . " " . $fetch['l_name'];
                        
                    
                }
                
                // Get Order Items ...
                $select = 	"SELECT * FROM `inv_qne_orders_items` oi LEFT JOIN `inv_qne_products` p ON oi.product_id = p.product_id WHERE `order_id` = ". $order_id;
                $conn	= 	$DB->query($select);

                if(mysqli_num_rows($conn) > 0)
                {
                    $c = 0;
                    while($fetch = mysqli_fetch_array($conn))
                    {
                        $orders['items'][$c]['product_id']		    =	$fetch['product_id'];
                        $orders['items'][$c]['product']		        =	$fetch['product'];
                        $orders['items'][$c]['product_sku']		    =	$fetch['product_sku'];
                        $orders['items'][$c]['product_price']		=  	$fetch['product_price'];
                        $orders['items'][$c]['product_qty']	        =	$fetch['product_qty'];
                        $orders['items'][$c]['product_sub_total']	=	$fetch['product_sub_total'];
                        $c++;
                    }   
                    
                } else {
                    $orders['items']    =	array();
                }
            }
            return $orders;
        }
        
        public function getAllOrders($start=0, $limit=0)
        {
            $DB = new DB_connection();

            if($start == 0 && $limit == 0)
            {
                $select = "SELECT count(*) as rowCount  FROM `inv_qne_orders` o LEFT JOIN `inv_qne_users` u ON o.created_by = u.user_id ";
                $conn   = $DB->query($select);
                return mysqli_num_rows($conn);
            }
            else
            {
                $select = 	"SELECT * FROM `inv_qne_orders` o LEFT JOIN `inv_qne_users` u ON o.created_by = u.user_id WHERE 1=1 ORDER BY o.order_id DESC LIMIT " . $start . ", " . $limit;
                $conn	= 	$DB->query($select);

                if(mysqli_num_rows($conn) > 0)
                {
                    $orders 	= 	array();
                    $c			=	0;
                    while($fetch = mysqli_fetch_array($conn))
                    {	
                        $orders[$c]['order_id']		    =	$fetch['order_id'];
                        $orders[$c]['sub_total']		=	$fetch['sub_total'];
                        $orders[$c]['discount']		    =	$fetch['discount'];
                        $orders[$c]['tax_amount']		=  	$fetch['tax_amount'];
                        $orders[$c]['shipping_rate']	=	$fetch['shipping_rate'];
                        $orders[$c]['order_total']	    =	$fetch['order_total'];
                        $orders[$c]['created_date']	    =	$fetch['created_date'];
                        $orders[$c]['created_by']  	    =	$fetch['created_by'];
                        $orders[$c]['status']			=	$fetch['status'];
                        $orders[$c]['user']			    =	$fetch['f_name'] . " " . $fetch['l_name'];
                        $c++;
                    }
                    return $orders;
                }
            }
        }
        
        public function createOrder()
        {
            $DB     = new DB_connection();
            $mySQLi = $DB->_connection;	
            extract($_POST);
            
            $products;
            $order_sub_total	=   (float) $mySQLi->real_escape_string($order_sub_total);
            $order_tax_amount	=   (float) $mySQLi->real_escape_string($order_tax_amount);
            $order_total	    =   (float) $mySQLi->real_escape_string($order_total);
            
            
            $insert = "INSERT INTO `inv_qne_orders` (`sub_total`, `tax_amount`, `order_total`, `created_date`, `created_by`, `status`) 
                        VALUES('" . $order_sub_total . "', '" . $order_tax_amount . "', '" . $order_total . "', '" . date('Y-m-d H:i:s') . "', '" . $_SESSION['sess_user_id'] . "', '1')";
            $DB->query($insert) or die($insert);
            $order_id	= mysqli_insert_id($mySQLi);
            
            if( isset($products) && sizeof($products) > 0 )
            {
                foreach($products as $key => $product)
                {
                    $insert = "";
                    $insert = "INSERT INTO `inv_qne_orders_items` (`order_id`, `product_id`, `product_price`, `product_qty`, `product_sub_total`, `added_date`) 
                        VALUES('" . $order_id . "', '" . $product['product_id'] . "', '" . $product['product_price'] . "', '" . $product['product_qty'] . "', '" . $product['product_sub_total'] . "', '". date('Y-m-d H:i:s') ."')";
                    $DB->query($insert) or die($insert);
                    
                    $select = "SELECT qty FROM `inv_qne_product_stock` WHERE `product_id` = " . $product['product_id'];
                    $conn   = $DB->query($select);
                    $fetch  = mysqli_fetch_array($conn);
                    
                    $item_qty = $fetch['qty'];
                    $rem_qty = $item_qty-$product['product_qty'];
                    
                    $update = "";
                    $update = "UPDATE `inv_qne_product_stock` SET `qty` = '". $rem_qty ."' WHERE `product_id` = " . $product['product_id'];
                    $DB->query($update);
                    
                }
            }
            return true;
        }
        
    }   
?>