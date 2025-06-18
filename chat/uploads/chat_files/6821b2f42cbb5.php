<?php
	error_reporting(0); 
	header("Connection: Keep-alive");
	include(dirname(dirname(__FILE__)) . '/class/class.php'); 

	$generalModel	= 	new General();
	$leadModel  	= 	new Lead();
	$loginModel = new Login();

	$Work	=	$_REQUEST['work'];

	function generateUniqueSku($length = 12) {
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$sku = '';
		for ($i = 0; $i < $length; $i++) {
			$sku .= $characters[rand(0, $charactersLength - 1)];
		}
		return $sku;
	}
	
	switch($Work)
	{
		case 'getProductsNameSKU':		    
		    $productNameSKU	= $_REQUEST['nameSKU'];
			$products 	= $warehouseModel->searchProduct($productNameSKU);
			
			$productsHTML = "";
			
			if( isset($products) && sizeof($products) > 0 )
			{
			    foreach($products as $key => $product)
			    {
			        $productsHTML .= '<tr>
						<td>
						    <div><b>'. $product['product'] .'</b></div><div>SKU: '. $product['product_sku'] .'</div><div>Qty: '. $product['product_qty'] .'</div>
						</td>
						<td style="vertical-align: middle; text-align: center; width: 15%;">
						    <button class="btn btn-sm fw-bold btn-primary" onclick="add_sku('. $product['product_sku'] .', '. $product['product_id'] .'); return false;">Add</button>
					    </td>
					</tr>';
			       // $productsHTML .= '<li onclick="set_item()">'. $product['product_sku'] ." &dash; ". $product['product'] .'</li>';
			    }
			} else {
			    $productsHTML .= '<tr><td>Product/SKU Not Found ...</td></tr>';
			}
		    
		    echo $productsHTML;		    
		break;

		case 'deleteIPSetting':
			$ipSettingID	= $_REQUEST['ipSettingID'];
			$status			= $generalModel->deleteIPSetting($ipSettingID);
			if($status)
			{
				echo "success";
			}
			else
			{
				echo "error";
			}
		break;

		case 'addIPAddress':
			$ipAddress	= $_REQUEST['ipAddress'];
			$status		= $_REQUEST['status'];
			$addedBy	= $_SESSION['sess_user_id'];
			$status		= $generalModel->addIPAddress($ipAddress, $status, $addedBy);
			if($status)
			{
				echo "success";
			}
			else
			{
				echo "error";
			}
		break;

		case 'deleteLead':
			$leadId		= $_REQUEST['leadId'];
			$status		= $leadModel->deleteLead($leadId);
			if($status)
			{
				echo "success";
			}
			else
			{
				echo "error";
			}
		break;
		
		case 'listChats':
			$listChats = $loginModel->listChats();
			
			echo $listChats['data'];
		break;

		case 'searchUsers':
			$search = $_GET['search'];
			
			$users = $loginModel->searchUsers($search);
			
			echo $users['data'];
		break;

		case 'singleChat':
			$chatId = $_REQUEST['chat_id'];
			
			$singleChat = $loginModel->singleChat($chatId);
			
			echo json_encode($singleChat['data']);
		break;

		case 'sendMessage':
			$sendMessage = $loginModel->sendMessage($_POST);
			
			echo json_encode($sendMessage);
		break;

		case 'lastChatMessage':
			$chatId = $_REQUEST['chat_id'];
			
			$lastChatMessage = $loginModel->lastChatMessage($chatId);

			echo json_encode($lastChatMessage['data']);
		break;

		case 'deleteChat':
			$chatId = $_REQUEST['chat_id'];
			
			$deleteChat = $loginModel->deleteChat($chatId);

			echo json_encode($deleteChat);
		break;
		
		case 'toggleIPRestriction':
			$ipRestrictionsStatus = $_REQUEST['ipRestrictionsStatus'];
			$status = $generalModel->toggleIPRestriction($ipRestrictionsStatus);
			echo $status;
		break;

		case 'selectBinLocation':
			$locationID	= $_REQUEST['location'];
			$binLocations = $warehouseModel->productBinLocationByLocation($locationID);

			if(sizeof($binLocations) > 0)
			{
				foreach($binLocations as $binLoc)
				{
?>					<option value="<?php echo $binLoc->bin_id; ?>"><?php echo $binLoc->bin_location; ?></option>
<?php			}
			}
			else
			{
?>					<option value="">No Bin Location</option>
<?php		}
		break;	

		case 'moveProductLocation':
			$locationID	= $_REQUEST['location_id'];
			$binID		= $_REQUEST['bin_location'];
			$productID	= $_REQUEST['productID'];
			$warehouseModel->moveProductLocation($productID, $locationID, $binID);
		break;	

		case 'syncWooProduct':
			$productID	= $_REQUEST['productID'];
			$warehouseModel->syncProductWooCommerce($productID);
		break;	

		case 'generareSKUCode':
			echo $newSku = generateUniqueSku();
		break;	

		case 'checkProductSKU':
			$productSKU = $_REQUEST['productSKU'];
			echo $warehouseModel->checkProductSKU($productSKU);
		break;

		case 'removeImage':
		    
		    $product_id	= $_REQUEST['product_id'];
		    $image	= $_REQUEST['image'];
		    $path = "assets/img/product_img/";
		    
		    $status = $warehouseModel->removeProductImage($product_id, $image);
			
			unlink($path.$image);
			
		    echo $status;
		    
		break;
		
		case 'editCategory':
		    
		    $category_id	= $_REQUEST['category_id'];
		    
			$warehouseModel->getCategoryDetails($category_id);

			echo $warehouseModel->weight . "=====" . $warehouseModel->length . "=====" . $warehouseModel->width . "=====" . $warehouseModel->height;
		break;
		
		case 'getProductDetails':
		    
		    $product_id	= $_REQUEST['product_id'];
			$product 	= $warehouseModel->getProductDetails($product_id);
			//print_r($product); die;
			
			$productsHTML = "";
			
			if( isset($product) && sizeof($product) > 0 )
			{
				$productImg = "assets/img/product-placeholder.png";
				if($product['product_image'] != '')
				{
					$productImg = "assets/img/product_img/" . $product['product_image'];
				}
		        ?>
		        <tr class="pro_details" id="product_<?php echo $product['product_id']; ?>" product_id="<?php echo $product['product_id']; ?>" >
                    <input type="hidden" name="products[<?php echo $product['product_id']; ?>][product_id]" id="product_id" value="<?php echo $product['product_id']; ?>" />
                    <td class="text-center">
                        <img class="media-object" src="<?php echo $productImg; ?>" style="width: 72px; height: 72px;">
					</td>
                    <td class="text-center">
                        <div class="media-body">
							<h4 class="media-heading"><a href="#"><?php echo $product['product']; ?></a></h4>
							<h5 class="media-heading"> SKU: <a href="javascript:void();"> <?php echo $product['product_sku']; ?></a></h5>
						</div>
                    </td>
                    <td class="text-center">
                        <input type="number" id="product_qty_<?php echo $product['product_id']; ?>" name="products[<?php echo $product['product_id']; ?>][product_qty]" class="form-control input-number pro_qty" value="1" min="1" max="100">
                    </td>
                    <td class="text-center">
                        <strong>$<?php echo $product['product_price']; ?></strong>
                        <input type="hidden" name="products[<?php echo $product['product_id']; ?>][product_price]" id="product_price_<?php echo $product['product_id']; ?>" value="<?php echo $product['product_price']; ?>" />
                    </td>
                    <td class="text-center">
                        <strong id="product_total_price_sh_<?php echo $product['product_id']; ?>">$<?php echo $product['product_price']*1; ?></strong>
                        <input type="hidden" name="products[<?php echo $product['product_id']; ?>][product_sub_total]" id="product_total_price_<?php echo $product['product_id']; ?>" value="<?php echo $product['product_price']*1; ?>" />
                    </td>
                    <td class="w10" style="width: 10%">
                        <a href="javascript:;" class="btn btn-sm btn-icon p-0 w-20px h-20px rounded-1">
							<i class="ki-duotone ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>        
						</a>
                    </td>
                </tr>
		        <?php
			} else {
			    $productsHTML = 'Error';
			}
		    
		    echo $productsHTML;
		    
		break;
	}
?>