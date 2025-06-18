<?php

class Product extends DB_connection 

{

	var $connection;

	var $product_id;



	public function __construct()

	{

		//$this->connection =  new DB_connection();

		//$this->product_id	=	0;

	}

	
    public function updateProductStatus($productID)
	{
		$DB		= new DB_connection();
		$select = "SELECT product_id, status FROM `inv_qne_products` WHERE `product_id` = " . $productID;
		$conn	= $DB->query($select);
		$rows	= mysqli_num_rows($conn);

        if($rows > 0)
		{
			$fetch	= mysqli_fetch_object($conn);
            $status = 1;
            if($fetch->status == 1)
            {
                $status = 0;
                $adminQry	= "INSERT INTO `log` (`id`, `task_performed`, `username`, `date`, `product_id`, `value`) VALUES('', 'Product ID: " . $productID . " Status: In-Active', '" . $_SESSION['sess_username'] . "', '" . date('Y-m-d H:i:s') . "', '" . $productID . "', '0')";
                $DB->query($adminQry);
            }
            else
            {
                $adminQry	= "INSERT INTO `log` (`id`, `task_performed`, `username`, `date`, `product_id`, `value`) VALUES('', 'Product ID: " . $productID . " Status: Active', '" . $_SESSION['sess_username'] . "', '" . date('Y-m-d H:i:s') . "', '" . $productID . "', '1')";
                $DB->query($adminQry);
            }
            
            $update = "UPDATE `inv_qne_products` SET `status` = '" . $status . "' WHERE `product_id` = " . $productID;
            $DB->query($update);
            
            $update2 = "UPDATE `product` SET `productstatus` = '" . $status . "' WHERE `productid` = " . $productID;
            $DB->query($update2);
            
            
        }
    }

	public function getLatestSKUCode()

	{

		$DB		= new DB_connection();

		$select = "SELECT sku_code FROM `inv_qne_product_sku` ORDER BY sku_id DESC LIMIT 1";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);



		if($rows > 0)

		{

			$fetch	= mysqli_fetch_object($conn);

			

			return $fetch->sku_code;

		}

		else

		{

			return 1000;

		}

	}

	

	public function getInventoryStockClosing($date)

	{

		$DB		= new DB_connection();

		$selStk = "SELECT id FROM `inv_qne_product_closing_stock` WHERE `date` LIKE '" . $date . "%'";

		$connStk= $DB->query($selStk) or die(mysqli_error());

		$rowStk	= mysqli_num_rows($connStk);

		

		if($rowStk <= 0)

		{

			//$select = "SELECT id, productid, stock_qty, hold_qty FROM `product_attributes` ORDER BY productid ASC";

			$select = "SELECT product_id, sku_id, available, hold, damage, returned, expired FROM `inv_qne_product_stock` ORDER BY product_id ASC";

			$conn	= $DB->query($select) or die(mysqli_error());

			$rows	= mysqli_num_rows($conn);

			

			if($rows > 0)

			{

				while($fetch = mysqli_fetch_object($conn))

				{

					$select2	= "SELECT qty, net_total, sale_price FROM `inv_qne_grn_po_details` WHERE `product_id` = '" . $fetch->product_id . "' AND `sku_id` = '" . $fetch->sku_id . "' ORDER BY id DESC LIMIT 1";

					$conn2		= $DB->query($select2) or die(mysqli_error());

					

					if(mysqli_num_rows($conn2) > 0)

					{

						$fet2 	= mysqli_fetch_object($conn2);

						$netTp 	= number_format($fet2->net_total / $fet2->qty, 2, '.', ''); 

						$salePrc= $fet2->sale_price;

					}

					else

					{

						$netTp 	= 0; 

						$salePrc= 0;

					}

					

					$insert = 	"INSERT INTO `inv_qne_product_closing_stock`(`id`, `product_id`, `sku_id`, `stock`, `hold`, `damage`, `returned`, `expired`, `tp`, `sp`, `date`) VALUES ('', '" . $fetch->product_id . "', '" . $fetch->sku_id . "', '" . $fetch->available . "', '" . $fetch->hold . "', '" . $fetch->damage . "', '" . $fetch->returned . "', '" . $fetch->expired . "', '" . $netTp . "', '" . $salePrc . "', '" . date('Y-m-d H:i:s') . "')";

					$DB->query($insert) or die(mysqli_error());

				}

				return true;

			}

			else

			{

				return false;

			}

		}

		else

		{
			return false;
		}			
	}
    
    public function searchInStockProductByDistributor($title, $distributor_id=0, $num=0)
	{
		$DB		= 	new DB_connection();
        if($distributor_id != 0)
        {
		    $select = "SELECT qp.product_id, qp.product FROM inv_qne_products qp JOIN `inv_qne_product_distributor` pd ON qp.product_id = pd.product_id WHERE `distributor_id` = '" . $distributor_id . "' AND (qp.product LIKE '%" . $title . "%' || qp.product_id LIKE '%" . $title . "%') AND qp.status = 1";
        }
        else
        {
            $select = "SELECT qp.product_id, qp.product FROM inv_qne_products qp WHERE (qp.product LIKE '%" . $title . "%' || qp.product_id LIKE '%" . $title . "%') AND qp.status = 1";
        }
        $conn	=	$DB->query($select);

        if(mysqli_num_rows($conn) > 0)
		{
			while($row = mysql_fetch_array($conn))
			{
				echo '<li onclick="set_item(\''.str_replace("'", "\'", $row['product_id'] . "_" . $row['product']).'\',' . $num . ')">'.$row['product'].'</li>';
	    	}
		}
	}

    public function searchProductByDistributor($title, $distributor_id=0, $num=0)
	{
		$DB		= 	new DB_connection();
        if($distributor_id != 0)
        {
		    $select = "SELECT qp.product_id, qp.product FROM inv_qne_products qp JOIN `inv_qne_product_distributor` pd ON qp.product_id = pd.product_id WHERE `distributor_id` = '" . $distributor_id . "' AND (qp.product LIKE '%" . $title . "%' || qp.product_id LIKE '%" . $title . "%')";
        }
        else
        {
            $select = "SELECT qp.product_id, qp.product FROM inv_qne_products qp WHERE (qp.product LIKE '%" . $title . "%' || qp.product_id LIKE '%" . $title . "%')";
        }
        $conn	=	$DB->query($select);

        if(mysqli_num_rows($conn) > 0)
		{
			while($row = mysql_fetch_array($conn))
			{
				echo '<li onclick="set_item(\''.str_replace("'", "\'", $row['product_id'] . "_" . $row['product']).'\',' . $num . ')">'.$row['product'].'</li>';
	    	}
		}
	}

    public function searchProduct($title,$num=0)

	{

		$DB					= 	new DB_connection();

		$select = 	"SELECT `product_id`, `product` FROM inv_qne_products WHERE (`product` LIKE '" . $title . "%' || product_id LIKE '%" . $title . "%')";

	    $conn	=	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			while($row = mysql_fetch_array($conn))

			{

				echo '<li onclick="set_item(\''.str_replace("'", "\'", $row['product_id'] . "_" . $row['product']).'\',' . $num . ')">'.$row['product'].'</li>';

	    	}

		}

	}

	

	public function getProductTax($product_id)

	{

		$DB					= 	new DB_connection();

		$select = 	"SELECT qp.product_id, qt.tax_id, qt.tax_type, qt.tax_value FROM `inv_qne_products` qp JOIN `inv_qne_tax` qt ON qp.tax_type = qt.tax_id WHERE `product_id` = '" . $product_id . "'";

		$conn	=	$DB->query($select);

		if(mysqli_num_rows($conn) > 0)

		{

			$fet	=	mysqli_fetch_object($conn);

			

			$this->product_id	=	$fet->product_id;

			$this->tax_id		=	$fet->tax_id;

			$this->tax_type		=	$fet->tax_type;

			$this->tax_value	=	$fet->tax_value;

		}

	}

    function addProductSKUPrice($post)
	{
		$DB					= 	new DB_connection();

        $tax_type			=	explode("_", $post['tax_type']);
		$tax_type			=	$tax_type[0];
		$category			=	mysql_real_escape_string($post['category']);
		$sub_category		=	mysql_real_escape_string($post['sub_category']);
		$sub_sub_category	=	mysql_real_escape_string($post['sub_sub_category']);
		$status				=	mysql_real_escape_string($post['status']);

        $allDistributors	=	$this->allDistributor($company);
		$cost_price			=	$post['cost_price'];
		$retail				=	$post['retail'];
		$sku_title			=	$post['sku_title'];
		$sku_id				=	$post['sku_id'];
		$product_id			=	$post['product_id'];

		if(sizeof($_POST['sku_id']) > 0)
		{
			$cntj	=	0;
			foreach($sku_id as $sku)
			{
				if(is_numeric($sku))
				{
					$taxVal			=	$this->allTaxes($tax_type);
					$tax_value		=	$taxVal[0]->tax_value;
					$tax_type_val	=	$taxVal[0]->tax_type;

                    if(sizeof($_POST['distributor_id'][$cntj]) > 0)
					{
						$cntI = 0;

                        foreach($_POST['distributor_id'][$cntj] as $distri)
						{
							if($cost_price[$cntj][$cntI] != '' && $cost_price[$cntj][$cntI] != 0)
							{
								switch($tax_type_val)
								{
									case 'Normal':
										$taxAmount			=	($retail[$cntj][$cntI] * $tax_value) / 100;
										$retail_price_tax	=	$retail[$cntj][$cntI] + $taxAmount;
										$taxAmount			=	($cost_price[$cntj][$cntI] * $tax_value) / 100;
										$cost_price_tax		=	$cost_price[$cntj][$cntI] + $taxAmount;
									break;

                                    case 'Fixed':
										$taxAmount			=	($retail[$cntj][$cntI] * $tax_value) / 100;
										$cost_price_tax		=	$cost_price[$cntj][$cntI] + $taxAmount;
										$retail_price_tax	=	$retail[$cntj][$cntI] + $taxAmount;
									break;

                                    default:
                                        $taxAmount          =   0;
										$cost_price_tax		=	$cost_price[$cntj][$cntI];
										$retail_price_tax	=	$retail[$cntj][$cntI];
									break;
								}

                                if($status == 1)
								{
									$update	=	"UPDATE `inv_qne_product_price` SET status = '0' WHERE `sku_id` = '" . $sku . "' AND `distributor_id` = '" . $distri . "'";
									$DB->query($update);
								}

								$insert = 	"INSERT INTO `inv_qne_product_price`(`price_id`, `product_id`, `sku_id`, `distributor_id`, `cost_price`, `cost_price_tax`, `retail_price`, `retail_price_tax`, `status`, `modify_date`, `date`) 

											VALUES('', '" . $product_id . "', '" . $sku . "', '" . $distri . "', '" . $cost_price[$cntj][$cntI] . "', '" . $cost_price_tax . "', '" . $retail[$cntj][$cntI] . "', '" . $retail_price_tax . "', '" . $status . "', '" . date('Y-m-d') . "', '" . date('Y-m-d') . "')";
								$DB->query($insert);

                                $update2	=	"UPDATE `product_attributes` SET `price` = '" . $retail_price_tax . "', `tax_value` = '" . $taxAmount . "', `price_final` = '" . $retail_price_tax . "', `price_without_discount` = '" . $retail_price_tax . "' WHERE `id` = '" . $sku . "'";

								$DB->query($update2);

							}	

							$cntI++;

						}

					}	

				}

				$cntj++;

			}

		}	

	}

    function addProductSKUPriceGRN()
	{
        $DB					= 	new DB_connection();
        extract($_POST);
        $tax_type			=	$tax_type;
		$cost_price			=	$unit_price;
		$retail_price_tax   =	$mrp;
		$sku_id				=	$sku_id;
		$product_id			=	$product_id;
        $distributor		=	$distributor;

        $taxVal			=	$this->allTaxes($tax_type);
        $tax_value		=	$taxVal[0]->tax_value;
        $tax_type_val	=	$taxVal[0]->tax_type;

        switch($tax_type_val)
        {
            case 'Normal':
                $retail_price	= $retail_price_tax / (1 + $tax_value / 100);
                $taxAmount		= ($cost_price * $tax_value) / 100;
                $cost_price_tax	= $cost_price + $taxAmount;
            break;

            case 'Fixed':
                $cost_price_tax	= $cost_price;
                $retail_price	= $retail_price_tax / (1 + $tax_value / 100);
                $taxAmount		= $retail_price_tax - $retail_price;
                $cost_price_tax	= $cost_price + $taxAmount;
            break;

            default:
                $cost_price_tax	= $cost_price;
                $retail_price	= $retail_price_tax;
                $taxAmount      = 0;
            break;
        }
        
        /*switch($tax_type_val)
        {
            case 'Normal':
                $taxAmount			=	($retail[$cntj][$cntI] * $tax_value) / 100;
                $retail_price_tax	=	$retail[$cntj][$cntI] + $taxAmount;
                $taxAmount			=	($cost_price[$cntj][$cntI] * $tax_value) / 100;
                $cost_price_tax		=	$cost_price[$cntj][$cntI] + $taxAmount;
            break;

            case 'Fixed':
                $taxAmount			=	($retail[$cntj][$cntI] * $tax_value) / 100;
                $cost_price_tax		=	$cost_price[$cntj][$cntI] + $taxAmount;
                $retail_price_tax	=	$retail[$cntj][$cntI] + $taxAmount;
            break;

            default:
                $taxAmount          =   0;
                $cost_price_tax		=	$cost_price[$cntj][$cntI];
                $retail_price_tax	=	$retail[$cntj][$cntI];
            break;
        }*/
        $status = 1;
        if($status == 1)
        {
            $update	=	"UPDATE `inv_qne_product_price` SET status = '0' WHERE `sku_id` = '" . $sku . "' AND `distributor_id` = '" . $distri . "'";
            $DB->query($update);
        }

        $insert = 	"INSERT INTO `inv_qne_product_price`(`price_id`, `product_id`, `sku_id`, `distributor_id`, `cost_price`, `cost_price_tax`, `retail_price`, `retail_price_tax`, `status`, `modify_date`, `date`) VALUES('', '" . $product_id . "', '" . $sku_id . "', '" . $distributor . "', '" . $cost_price . "', '" . $cost_price_tax . "', '" . $retail_price . "', '" . $retail_price_tax . "', '" . $status . "', '" . date('Y-m-d') . "', '" . date('Y-m-d') . "')";
        $DB->query($insert);

        $update2	=	"UPDATE `product_attributes` SET `price` = '" . $retail_price_tax . "', `tax_value` = '" . $taxAmount . "', `price_final` = '" . $retail_price_tax . "', `price_without_discount` = '" . $retail_price_tax . "' WHERE `id` = '" . $sku_id . "'";
        $DB->query($update2);
        return $taxAmount;
	}
	

	public function productNearExpiry($expirySpan, $start, $limit)

	{

		$DB		= new DB_connection();

		

		if($start == 0 && $limit == 0)

		{

			$select = "SELECT gd.*, qp.product, ps.sku_title, ps.sku_code FROM `inv_qne_grn_po_details` gd LEFT JOIN `inv_qne_products` qp ON gd.product_id = qp.product_id LEFT JOIN `inv_qne_product_sku` ps ON gd.sku_id = ps.sku_id WHERE gd.`expiry_date` <= '" . $expirySpan . "' AND (gd.`status` != 'close' || gd.`status` != 'Close')";

		}

		else

		{

			$select = "SELECT gd.*, qp.product, ps.sku_title, ps.sku_code FROM `inv_qne_grn_po_details` LEFT JOIN `inv_qne_products` qp ON gd.product_id = qp.product_id LEFT JOIN `inv_qne_product_sku` ps ON gd.sku_id = ps.sku_id WHERE gd.`expiry_date` <= '" . $expirySpan . "' AND (gd.`status` != 'close' || gd.`status` != 'Close') LIMIT " . $start . " " . $limit;

		}

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);



		if($rows > 0)

		{

			$c = 0;

			$productSKU = array();



			while($fetch = mysqli_fetch_object($conn))

			{

				$productSKU[$c]						=	new Purchase();

				$productSKU[$c]->id					=	$fetch->id;

				$productSKU[$c]->product_id			=	$fetch->product_id;

				$productSKU[$c]->product			=	$fetch->product;

				$productSKU[$c]->sku_id				=	$fetch->sku_id;

				$productSKU[$c]->sku_title			=	$fetch->sku_title;

				$productSKU[$c]->sku_code			=	$fetch->sku_code;

				$productSKU[$c]->grn_po_id			=	$fetch->grn_po_id;

				$productSKU[$c]->qty				=	$fetch->qty;

				$productSKU[$c]->price				=	$fetch->price;

				$productSKU[$c]->sub_total			=	$fetch->sub_total;

				$productSKU[$c]->tax				=	$fetch->tax;

				$productSKU[$c]->discount			=	$fetch->discount;

				$productSKU[$c]->discount_type		=	$fetch->discount_type;

				$productSKU[$c]->net_total			=	$fetch->net_total;

				$productSKU[$c]->batch				=	$fetch->batch;

				$productSKU[$c]->manufacturing_date	=	$fetch->manufacturing_date;

				$productSKU[$c]->expiry_date		=	$fetch->expiry_date;

				$productSKU[$c]->product_type		=	$fetch->product_type;

				$productSKU[$c]->mrp_price			=	$fetch->mrp_price;

				$productSKU[$c]->sale_price			=	$fetch->sale_price;

				$productSKU[$c]->added_date			=	$fetch->added_date;

				$c++;

			}

			return $productSKU;

		}

	}

	

	function addIssuanceNote($post)

	{

		$DB					= 	new DB_connection();

		

		extract($post);

		

		$adjustment_notes	=	mysql_real_escape_string($adjustment_notes);

		$issuance_type		=	mysql_real_escape_string($issuance_type);

		

		switch($issuance_type)

		{

			case 1:

				$iss_type	=	"Return";

			break;

			

			case 2:

				$iss_type	=	"Damaged";

			break;

			

			case 3:

				$iss_type	=	"Expire";

			break;

			

			case 4:

				$iss_type	=	"Replace";

			break;

			

			default:

				$iss_type	=	"Return";

			break;	

				

		}

		

		$insert = 	"INSERT INTO `inv_qne_issuance_note` (`issuance_id`, `issuance_notes`, `issuance_type`, `distributor`, `status`, `created_by`, `verified_by`, `datetime`) 

					VALUES('', '" . $adjustment_notes . "', '" . $issuance_type . "', '" . $distributor . "', '0', '" . $_SESSION['sess_username'] . "', '', '" . date('Y-m-d H:i:s') . "')";

		$DB->query($insert);

		

		$issuance_id	=	mysql_insert_id();

					

		if(sizeof($product_id) > 0)

		{

			$cnti			=	0;

			$totalAmount	=	0;

			foreach($product_id as $product)

			{

				$amount = 0;

				if($product != '' && is_numeric($product))

				{

					$unit_price	=   $product_unit_price[$cnti];

					

					

					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $product . " AND `sku_id` = " . $product_sku[$cnti];

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty			=	mysqli_fetch_object($connQty);

						$stockAvailable	=	$fetQty->available;

						

						if($product_available[$cnti] <= $stockAvailable)

						{

							$productSkuQty	=	$product_available[$cnti];

							$amount 		=   $product_unit_price[$cnti] * $productSkuQty;

						}

						else

						{

							$productSkuQty	=	$stockAvailable;

							$amount 		=   $product_unit_price[$cnti] * $productSkuQty;

						}

						

						//$insertRow 	= 	"INSERT INTO `inv_qne_debit_detail`(`id`, `debit_id`, `grn_po_detail_id`, `product_id`, `sku_id`, `debit_reason`, `qrn_remaining_qty`, `debit_qty`, `amount`, `datetime`) VALUES('', '" . $debit_id . "', '" . $grn_sku[$cnti] . "',  '" . $product . "', '" . $product_sku[$cnti] . "', '" . $adjust_reason[$cnti] . "', '" . $grn_qty[$cnti] . "', '" . $product_available[$cnti] . "', '" . $amount . "', '" . date('Y-m-d H:i:s') . "')";

						$insertRow 	= 	"INSERT INTO `inv_qne_issuance_details`(`id`, `issuance_id`, `product_id`, `sku_id`, `sku_qty`, `grn_id`, `grn_po_detail_id`, `unit_price`, `total`, `status_id`, `status`, `reason`, `datetime`) 

										VALUES('', '" . $issuance_id . "',  '" . $product . "', '" . $product_sku[$cnti] . "', '" . $productSkuQty . "', '" . $grn_id[$cnti] . "', '" . $grn_sku[$cnti] . "', '" . $unit_price . "', '" . $amount . "', '" . $issuance_type . "', '" . $iss_type . "', '" . $adjust_reason[$cnti] . "', '" . date('Y-m-d H:i:s') . "')";

						$DB->query($insertRow);

						$totalAmount += $amount;

						

						/*$selectSKU 	= 	"SELECT returned FROM `inv_qne_product_stock` WHERE `sku_id` = " . $product_sku[$cnti];

						$conn 		= 	$DB->query($selectSKU);

	

						if(mysqli_num_rows($conn) > 0)

						{

							$fet 		= mysqli_fetch_object($conn);

							$returned 	= $product_available[$cnti] + $fet->returned;

							

							$updStock 	= "UPDATE `inv_qne_product_stock` SET `returned` = '" . $returned . "' WHERE `sku_id` = " . $product_sku[$cnti];

							$DB->query($updStock);

						}	*/

					}	

				}	

				$cnti++;

			}

			

			$update = 	"UPDATE `inv_qne_issuance_note` SET `amount` = '" . $totalAmount . "' WHERE `issuance_id` = '" . $issuance_id . "'";

			$DB->query($update);

		}



		$email 		= 	"info@thevintagebazar.com";

		$headers 	= 	"MIME-Version: 1.0\n";

		$headers   .= 	"Content-type: text/html; charset=iso-8859-1\n";

		$headers   .= 	"From:Warehouse<info@thevintagebazar.com>\n";

		$headers   .= 	"X-Mailer: PHP's mail() Function\n";

		$subject    =	"New Debit Note created at Warehouse Inventory System, Debit Note # " . $issuance_id;

		$body 		=	'<style type="text/css" charset="utf-8">

.contentborder { background: #ffffff; background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNlNWU1ZTUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+); background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5)); background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); background: linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#ffffff", endColorstr="#e5e5e5",GradientType=0 ); border-top: 3px solid #EB5E00; border-radius: 0px 0px 8px 8px; }

.curl { height: 100%; background: #FFF; border: 1px solid #ccc; padding-bottom: 5%; }

h1 { margin: 0px auto; width: 100%; text-align:center; color: #808080; }

h2 { margin-top: 0; margin-right: 40%; margin-bottom: 0; margin-left: 30%; width: 100%; font-size: 30px; }

h3 { margin-top: 0; margin-right: 40%; margin-bottom: 0; margin-left: 30%; width: 100%; color: #808080; }

p { margin: 0px 10% 0px 10%; width: 100%; color: #808080; font-weight: bold; }

.effect2 { position: relative; }

.effect2:before, .effect2:after { z-index: -1; position: absolute; content: ""; bottom: 15px; left: 10px; top: 80%; max-width: 300px; background: #777; -webkit-box-shadow: 0 15px 10px #777; -moz-box-shadow: 0 15px 10px #777; box-shadow: 0 15px 10px #777; -webkit-transform: rotate(-3deg); -moz-transform: rotate(-3deg); -o-transform: rotate(-3deg); -ms-transform: rotate(-3deg); transform: rotate(-3deg); }

.effect2:after { -webkit-transform: rotate(3deg); -moz-transform: rotate(3deg); -o-transform: rotate(3deg); -ms-transform: rotate(3deg); transform: rotate(3deg); right: 10px; left: auto; }

</style>

<table cellpadding="5" class="mainTable" align="center" cellspacing="5" width="700" style="font-family:Segoe UI;border:1px solid #cccccc;margin-top:10px;-moz-box-shadow:0px 0 4px -2px #888;-webkit-box-shadow:0px 0 4px -2px #888;box-shadow:0px 0 4px -2px #888;">

		<tr>

			<td>

				<table cellpadding="5" cellspacing="5" class="header" style="background-color:#F7F7F7;border:1px solid #cccccc; width:680px;min-height:120px;">

					<tr>

						<td>

							<img src="' . IMAGES . 'logo.png" alt="Qne Logo" title="Warehouse Logo" />

						</td>

					</tr>

				</table>

			</td>

		</tr>

		

		<tr>

			<td>								

				<div class="curl effect2">

					<br />

					<h1>Debit Note #'.$issuance_id.'</h1>

					

					<div style="border-bottom: 1px solid #808080; width: 100%; padding-top: 10px;"></div>

					

					<div style="">

						<table cellpadding="5" cellspacing="5" border="0">

							<tr>

								<td colspan="2">Dear Admin,</td>

							</tr>

							

							<tr>

								<td colspan="2">A New Debit Note is created on Inventory System.</td>

							</tr>



							<tr>

								<td colspan="2">Please follow the link below for Approval or Disapproval.</td>

							</tr>

							

							<tr>

								<td colspan="2"><b>Debit Note</b><br/>' . $adjustment_notes . '</td>

							</tr>

							

							<tr>

								<td colspan="2"><a href="https://inventory.thevintagebazar.com/debit_details.php?debit_id=' . $issuance_id . '" target="_blank">Click here to view Debit Note</a></td>

							</tr>

							<tr>

								<td colspan="2">For any questions, concerns and your valuable feedback please contact us at <br /><a href="mailto:info@thevintagebazar.com">info@thevintagebazar.com</a></td>

							</tr>

							

							<tr>

								<td colspan="2">&nbsp;</td>

							</tr>

							

							<tr>

								<td colspan="2">Thank You!<br/><a href="' . SERVER . '" target="blank" style="text-decoration:none; color:#61C250">Warehouse Team</a></td>

							</tr>

						</table>

					</div>

				</div>

			</td>

		</tr>

	</table>



	<table cellpadding="0" class="mainTable" align="center" cellspacing="0" width="700">

		<tr>

			<td>

				<div style="padding: 0px 0px 0px 0px;background-color:#EEAF00;height:10px;width:702px;margin:0px auto;" class="greenLine"></div>

				<div style="padding: 0px 0px 0px 0px;background-color:#61C250;height:40px;width:702px;margin:0px auto;margin-bottom:20px;-moz-box-shadow:0 4px 2px -2px gray;box-shadow:0 4px 2px -2px gray;-webkit-box-shadow:0 4px 2px -2px gray;" class="blackLine">

					<div style="padding:10px 0px 0px 10px; color:#fff; font-family:Verdana; font-size:12px; width:48%; float:left !important;">Copyright &copy; ' . date('Y') . ' <a href="' . SERVER . '" target="_blank" style="color:#fff;text-decoration:none;">Warehouse</a>. All Rights Reserved. </div>

					<div style="padding:10px 10px 0px 0px; font-family:Verdana; color:#fff; font-size:12px; width:48%; text-align:right; float:right !important;"><a href="' . ABOUT_US_PAGE . '" target="_blank" style="color:#fff;text-decoration:none;">About Us</a> | <a href="' . CONTACT_US_PAGE . '" target="_blank" style="color:#fff;text-decoration:none;">Contact Us</a> | <a href="' . TERMS_CONDITION_PAGE . '" target="_blank" style="color:#fff;text-decoration:none;">Terms and Conditions</a></div>

					<br clear="all" />

				</div>

			</td>

		</tr>

	</table>';

		mail($emial,$subject,$body,$headers);

	}

	

	function confirmIssuanceNote($issuance_id, $status)

	{

		$DB		= 	new DB_connection();



		if($status == 'approve')

		{

			$select = 	"SELECT * FROM `inv_qne_issuance_details` WHERE `issuance_id` = " . $issuance_id;

			$conn	=	$DB->query($select);

			

			if(mysqli_num_rows($conn) > 0)

			{

				while($fetch = mysqli_fetch_object($conn))

				{

					//$diff_qty 	= 	$fetch->diff_qty;



					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $fetch->product_id . " AND `sku_id` = " . $fetch->sku_id;

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty			=	mysqli_fetch_object($connQty);

						$stockAvailable	=	$fetQty->available;

						$stockDiff		=	$stockAvailable - $fetch->sku_qty;

						$returned 		= 	$fetQty->returned + $fetch->sku_qty;

						

						$updateQty	=	"UPDATE `inv_qne_product_stock` SET `available` = '" . $stockDiff . "', `returned` = '" . $returned . "' WHERE `sku_id` = " . $fetQty->sku_id;

						$DB->query($updateQty);

						

						$updateQty2	=	"UPDATE `product_attributes` SET `stock_qty` = '" . $stockDiff . "' WHERE `id` = " . $fetQty->sku_id;

						$DB->query($updateQty2);

						

						$selectGRN	=	"SELECT * FROM `inv_qne_grn_po_details` WHERE `id` = " . $fetch->grn_po_detail_id;

						$connGRN	=	$DB->query($selectGRN);

						if(mysqli_num_rows($connGRN) > 0)

						{

							$fetGRN	=	mysqli_fetch_object($connGRN);

							

							$remianing_qty	=	$fetGRN->qty - $fetGRN->sold_qty;

							//$soldQty	=	$fetGRN->qty;

							if((int)$fetch->sku_qty == (int)$remianing_qty)

							{

								$updateGRNDetail	=	"UPDATE `inv_qne_grn_po_details` SET `status` = 'Close' WHERE `id` = " . $fetch->grn_po_detail_id;

								$DB->query($updateGRNDetail);

							}

						}	

					}	

				}

			}

		}	



		$status_id = 0;

		switch($status)

		{

			case 'approve':

				$status_id = 1;

			break;

			

			case 'reject':

				$status_id = 2;

			break;

			

			default:

				$status_id = 0;

			break;

		}

		$update = "UPDATE `inv_qne_issuance_note` SET `status` = '" . $status_id . "', `verified_by` = '" . $_SESSION['sess_username'] . "' WHERE `issuance_id` = " . $issuance_id;

		$DB->query($update);

	}



	public function allIssuanceNotes($issuance_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where	=	'';

		if($issuance_id != 0)

		{

			$where = " WHERE `issuance_type` IN ('" . $issuance_id . "')";

		}

		//echo "<pre>"; print_r($_SESSION); echo "</pre>";

		$select 	= 	"SELECT * FROM `inv_qne_issuance_note` " . $where . " ORDER BY issuance_id DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$issuanceNotes	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{		

				$issuanceNotes[$c]					=	new Product();

				$issuanceNotes[$c]->issuance_id		=	$fetch->issuance_id;

				$issuanceNotes[$c]->issuance_notes	=	$fetch->issuance_notes;

				$issuanceNotes[$c]->amount			=	$fetch->amount;

				$issuanceNotes[$c]->status			=	$fetch->status;

				$issuanceNotes[$c]->created_by		=	$fetch->created_by;

				$issuanceNotes[$c]->verified_by		=	$fetch->verified_by;

				$issuanceNotes[$c]->datetime		=	$fetch->datetime;

				$c++;

			}

			return $issuanceNotes;

		}	

	}

	

	public function issuanceDetails($issuance_id)

	{

		$DB			= 	new DB_connection();

		

		$select 	= 	"SELECT ad.*, qp.product, ps.sku_title, qgp.grn_id as grn_ids FROM `inv_qne_issuance_details` ad LEFT JOIN inv_qne_products qp ON ad.product_id = qp.product_id LEFT JOIN inv_qne_product_sku ps ON ad.sku_id = ps.sku_id LEFT JOIN `inv_qne_grn_po` qgp ON qgp.grn_po_id = ad.grn_id WHERE `issuance_id` = " . $issuance_id;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$adjustments 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$adjustments[$c]					=	new Product();

				$adjustments[$c]->detail_id			=	$fetch->id;

				$adjustments[$c]->issuance_id		=	$fetch->issuance_id;

				$adjustments[$c]->product_id		=	$fetch->product_id;

				$adjustments[$c]->sku_id			=	$fetch->sku_id;

				$adjustments[$c]->product			=	$fetch->product;

				$adjustments[$c]->sku_title			=	$fetch->sku_title;

				$adjustments[$c]->debit_qty			=	$fetch->sku_qty;	

				$adjustments[$c]->grn_id			=	$fetch->grn_id;
				
				$adjustments[$c]->grn_ids			=	$fetch->grn_ids;
				
				$adjustments[$c]->grn_po_detail_id	=	$fetch->grn_po_detail_id;

				$adjustments[$c]->unit_price		=	$fetch->unit_price;

				$adjustments[$c]->total				=	$fetch->total;

				$adjustments[$c]->status_id			=	$fetch->status_id;

				$adjustments[$c]->status			=	$fetch->status;

				$adjustments[$c]->reason			=	$fetch->reason;

				$adjustments[$c]->datetime			=	$fetch->datetime;



				$c++;

			}

			return $adjustments;

		}	

	}

	

	public function issuanceNotesByID($issuance_id)

	{

		$DB			= 	new DB_connection();

		

		$where	=	'';

		if($issuance_id != 0)

		{

			$where = " WHERE `issuance_id` = " . $issuance_id;

		}

		

		$select 	= 	"SELECT qin.*, qd.distributor as vendor FROM `inv_qne_issuance_note` qin LEFT JOIN `inv_qne_distributor` qd ON qin.distributor = qd.distributor_id " . $where . " ORDER BY issuance_id DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch = mysqli_fetch_object($conn);

			$this->issuance_id		=	$fetch->issuance_id;

			$this->issuance_notes	=	$fetch->issuance_notes;

			$this->amount			=	$fetch->amount;

			$this->status			=	$fetch->status;

			$this->created_by		=	$fetch->created_by;

			$this->verified_by		=	$fetch->verified_by;

			$this->datetime			=	$fetch->datetime;

			$this->distributor		=	$fetch->vendor;

		}	

	}

    /* Credit Note Starts Here */
	function addCreditNote($post)
	{
		$DB = new DB_connection();
		extract($post);

        $adjustment_notes	=	mysql_real_escape_string($adjustment_notes);

        $insert = 	"INSERT INTO `inv_qne_credit_note` (`credit_id`, `credit_notes`, `status`, `created_by`, `verified_by`, `datetime`) 
					VALUES('', '" . $adjustment_notes . "', '0', '" . $_SESSION['sess_username'] . "', '', '" . date('Y-m-d H:i:s') . "')";
		$DB->query($insert);
		$credit_id	=	mysql_insert_id();

        if(sizeof($product_id) > 0)
		{
			$cnti			=	0;
			$totalAmount	=	0;
			foreach($product_id as $product)
			{
				$amount = 0;

                if($product != '' && is_numeric($product))
				{
					$amount 	=   $product_unit_price[$cnti] * $product_available[$cnti];
					$insertRow 	= 	"INSERT INTO `inv_qne_credit_detail`(`id`, `credit_id`, `debit_id`, `product_id`, `sku_id`, `credit_reason`, `credit_qty`, `amount`, `type`, `datetime`) VALUES('', '" . $credit_id . "', '" . $debit_id[$cnti] . "',  '" . $product . "', '" . $product_sku[$cnti] . "', '" . $adjust_reason[$cnti] . "', '" . $product_available[$cnti] . "', '" . $amount . "', '" . $type[$cnti] . "', '" . date('Y-m-d H:i:s') . "')";
					$DB->query($insertRow);
					$totalAmount += $amount;
				}	
				$cnti++;
			}

            $update = 	"UPDATE `inv_qne_credit_note` SET `amount` = '" . $totalAmount . "' WHERE `credit_id` = '" . $credit_id . "'";
			$DB->query($update);
		}

        $email 		= 	"info@thevintagebazar.com";
		$headers 	= 	"MIME-Version: 1.0\n";
		$headers   .= 	"Content-type: text/html; charset=iso-8859-1\n";
		$headers   .= 	"From:Warehouse<info@thevintagebazar.com>\n";
		$headers   .= 	"X-Mailer: PHP's mail() Function\n";
		$subject    =	"New Credit Note created at Warehouse Inventory System, Credit Note # " . $credit_id;
		$body 		=	'<style type="text/css" charset="utf-8">
.contentborder { background: #ffffff; background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNlNWU1ZTUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+); background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5)); background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); background: linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#ffffff", endColorstr="#e5e5e5",GradientType=0 ); border-top: 3px solid #EB5E00; border-radius: 0px 0px 8px 8px; }

.curl { height: 100%; background: #FFF; border: 1px solid #ccc; padding-bottom: 5%; }

h1 { margin: 0px auto; width: 100%; text-align:center; color: #808080; }

h2 { margin-top: 0; margin-right: 40%; margin-bottom: 0; margin-left: 30%; width: 100%; font-size: 30px; }

h3 { margin-top: 0; margin-right: 40%; margin-bottom: 0; margin-left: 30%; width: 100%; color: #808080; }

p { margin: 0px 10% 0px 10%; width: 100%; color: #808080; font-weight: bold; }

.effect2 { position: relative; }

.effect2:before, .effect2:after { z-index: -1; position: absolute; content: ""; bottom: 15px; left: 10px; top: 80%; max-width: 300px; background: #777; -webkit-box-shadow: 0 15px 10px #777; -moz-box-shadow: 0 15px 10px #777; box-shadow: 0 15px 10px #777; -webkit-transform: rotate(-3deg); -moz-transform: rotate(-3deg); -o-transform: rotate(-3deg); -ms-transform: rotate(-3deg); transform: rotate(-3deg); }

.effect2:after { -webkit-transform: rotate(3deg); -moz-transform: rotate(3deg); -o-transform: rotate(3deg); -ms-transform: rotate(3deg); transform: rotate(3deg); right: 10px; left: auto; }

</style>

<table cellpadding="5" class="mainTable" align="center" cellspacing="5" width="700" style="font-family:Segoe UI;border:1px solid #cccccc;margin-top:10px;-moz-box-shadow:0px 0 4px -2px #888;-webkit-box-shadow:0px 0 4px -2px #888;box-shadow:0px 0 4px -2px #888;">

		<tr>

			<td>

				<table cellpadding="5" cellspacing="5" class="header" style="background-color:#F7F7F7;border:1px solid #cccccc; width:680px;min-height:120px;">

					<tr>

						<td>

							<img src="' . IMAGES . 'logo.png" alt="Qne Logo" title="Warehouse Logo" />

						</td>

					</tr>

				</table>

			</td>

		</tr>

		

		<tr>

			<td>								

				<div class="curl effect2">

					<br />

					<h1>Credit Note #'.$credit_id.'</h1>

					

					<div style="border-bottom: 1px solid #808080; width: 100%; padding-top: 10px;"></div>

					

					<div style="">

						<table cellpadding="5" cellspacing="5" border="0">

							<tr>

								<td colspan="2">Dear Admin,</td>

							</tr>

							

							<tr>

								<td colspan="2">A New Credit Note is created on Inventory System.</td>

							</tr>



							<tr>

								<td colspan="2">Please follow the link below for Approval or Disapproval.</td>

							</tr>

							

							<tr>

								<td colspan="2"><b>Credit Note</b><br/>' . $adjustment_notes . '</td>

							</tr>

							

							<tr>

								<td colspan="2"><a href="https://inventory.thevintagebazar.com/credit_details.php?credit_id=' . $credit_id . '" target="_blank">Click here to view Credit Note</a></td>

							</tr>

							<tr>

								<td colspan="2">For any questions, concerns and your valuable feedback please contact us at <br /><a href="mailto:info@thevintagebazar.com">info@thevintagebazar.com</a></td>

							</tr>

							

							<tr>

								<td colspan="2">&nbsp;</td>

							</tr>

							

							<tr>

								<td colspan="2">Thank You!<br/><a href="' . SERVER . '" target="blank" style="text-decoration:none; color:#61C250">Warehouse Team</a></td>

							</tr>

						</table>

					</div>

				</div>

			</td>

		</tr>

	</table>



	<table cellpadding="0" class="mainTable" align="center" cellspacing="0" width="700">

		<tr>

			<td>

				<div style="padding: 0px 0px 0px 0px;background-color:#EEAF00;height:10px;width:702px;margin:0px auto;" class="greenLine"></div>

				<div style="padding: 0px 0px 0px 0px;background-color:#61C250;height:40px;width:702px;margin:0px auto;margin-bottom:20px;-moz-box-shadow:0 4px 2px -2px gray;box-shadow:0 4px 2px -2px gray;-webkit-box-shadow:0 4px 2px -2px gray;" class="blackLine">

					<div style="padding:10px 0px 0px 10px; color:#fff; font-family:Verdana; font-size:12px; width:48%; float:left !important;">Copyright &copy; ' . date('Y') . ' <a href="' . SERVER . '" target="_blank" style="color:#fff;text-decoration:none;">Warehouse</a>. All Rights Reserved. </div>

					<div style="padding:10px 10px 0px 0px; font-family:Verdana; color:#fff; font-size:12px; width:48%; text-align:right; float:right !important;"><a href="' . ABOUT_US_PAGE . '" target="_blank" style="color:#fff;text-decoration:none;">About Us</a> | <a href="' . CONTACT_US_PAGE . '" target="_blank" style="color:#fff;text-decoration:none;">Contact Us</a> | <a href="' . TERMS_CONDITION_PAGE . '" target="_blank" style="color:#fff;text-decoration:none;">Terms and Conditions</a></div>

					<br clear="all" />

				</div>

			</td>

		</tr>

	</table>';

		mail($emial,$subject,$body,$headers);



	}

	

	function confirmCreditNote($credit_id, $status)

	{

		$DB		= 	new DB_connection();



		if($status == 'approve')

		{

			$select = 	"SELECT * FROM `inv_qne_credit_detail` WHERE `credit_id` = " . $credit_id;

			$conn	=	$DB->query($select);

			

			if(mysqli_num_rows($conn) > 0)

			{

				while($fetch = mysqli_fetch_object($conn))

				{

					$diff_qty 	= 	$fetch->diff_qty;



					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $fetch->product_id . " AND `sku_id` = " . $fetch->sku_id;

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty			=	mysqli_fetch_object($connQty);

						$stockAvailable	=	$fetQty->available;

						$stockDiff		=	$stockAvailable + $fetch->credit_qty;

						

						$stockQty		=	$fetQty->qty;

						$stockAdd		=	$stockQty + $fetch->credit_qty;

						

						$updateQty	=	"UPDATE `inv_qne_product_stock` SET `qty` = '" . $stockAdd . "', `available` = '" . $stockDiff . "' WHERE `sku_id` = " . $fetQty->sku_id;

						$DB->query($updateQty);

						

						$updateQty2	=	"UPDATE `product_attributes` SET `stock_qty` = '" . $stockDiff . "' WHERE `id` = " . $fetQty->sku_id;

						$DB->query($updateQty2);

						

						if((int)$fetch->credit_qty == (int)$fetch->qrn_remaining_qty)

						{

							//$updateGRNDetail	=	"UPDATE `inv_qne_grn_po_details` SET `status` = 'Close' WHERE `id` = " . $fetch->grn_po_detail_id;

							//$DB->query($updateGRNDetail);

						}

					}	

				}

			}

		}	



		$status_id = 0;

		switch($status)

		{

			case 'approve':

				$status_id = 1;

			break;

			

			case 'reject':

				$status_id = 2;

			break;

			

			default:

				$status_id = 0;

			break;

		}

		$update = "UPDATE `inv_qne_credit_note` SET `status` = '" . $status_id . "', `verified_by` = '" . $_SESSION['sess_username'] . "' WHERE `credit_id` = " . $credit_id;

		$DB->query($update);

	}



	public function allCreditNotes($credit_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where	=	'';

		if($credit_id != 0)

		{

			$where = " WHERE `credit_id` = " . $credit_id;

		}

		

		$select 	= 	"SELECT * FROM `inv_qne_credit_note` " . $where . " ORDER BY credit_id DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$creditNotes	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{		

				$creditNotes[$c]				=	new Product();

				$creditNotes[$c]->credit_id		=	$fetch->credit_id;

				$creditNotes[$c]->credit_notes	=	$fetch->credit_notes;

				$creditNotes[$c]->amount		=	$fetch->amount;

				$creditNotes[$c]->status		=	$fetch->status;

				$creditNotes[$c]->created_by	=	$fetch->created_by;

				$creditNotes[$c]->verified_by	=	$fetch->verified_by;

				$creditNotes[$c]->datetime		=	$fetch->datetime;

				$c++;

			}

			return $creditNotes;

		}	

	}

	

	public function creditDetails($credit_id)

	{

		$DB			= 	new DB_connection();

		

		$select 	= 	"SELECT ad.*, qp.product, ps.sku_title FROM `inv_qne_credit_detail` ad LEFT JOIN inv_qne_products qp ON ad.product_id = qp.product_id LEFT JOIN inv_qne_product_sku ps ON ad.sku_id = ps.sku_id WHERE `credit_id` = " . $credit_id;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$adjustments 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$adjustments[$c]					=	new Product();

				$adjustments[$c]->detail_id			=	$fetch->detail_id;

				$adjustments[$c]->credit_id			=	$fetch->credit_id;

				$adjustments[$c]->product_id		=	$fetch->product_id;

				$adjustments[$c]->sku_id			=	$fetch->sku_id;

				$adjustments[$c]->product			=	$fetch->product;

				$adjustments[$c]->sku_title			=	$fetch->sku_title;

				$adjustments[$c]->credit_reason		=	$fetch->credit_reason;

				$adjustments[$c]->credit_qty		=	$fetch->credit_qty;	

				$adjustments[$c]->debit_id			=	$fetch->debit_id;	

				$adjustments[$c]->amount			=	$fetch->amount;
                
                $adjustments[$c]->type			    =	$fetch->type;

				$adjustments[$c]->datetime			=	$fetch->datetime;

				$c++;

			}

			return $adjustments;

		}	

	}

	

	public function creditNotesByID($credit_id)

	{

		$DB			= 	new DB_connection();

		

		$where	=	'';

		if($credit_id != 0)

		{

			$where = " WHERE `credit_id` = " . $credit_id;

		}

		

		$select 	= 	"SELECT * FROM `inv_qne_credit_note` " . $where . " ORDER BY credit_id DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch = mysqli_fetch_object($conn);

			$this->credit_id	=	$fetch->credit_id;

			$this->credit_notes	=	$fetch->credit_notes;

			$this->amount		=	$fetch->amount;

			$this->status		=	$fetch->status;

			$this->created_by	=	$fetch->created_by;

			$this->verified_by	=	$fetch->verified_by;

			$this->datetime		=	$fetch->datetime;

		}	

	}

	/* Credit Note Ends Here */

	

	function addAdjustment($post)

	{

		$DB					= 	new DB_connection();

		

		extract($post);

		

		$adjustment_notes	=	mysql_real_escape_string($adjustment_notes);

		

		$insert = 	"INSERT INTO `inv_qne_adjustments` (`adjustment_id`, `adjustment_notes`, `status`, `created_by`, `verified_by`, `datetime`) 

					VALUES('', '" . $adjustment_notes . "', '0', '" . $_SESSION['sess_username'] . "', '', '" . date('Y-m-d H:i:s') . "')";

		$DB->query($insert);

		

		$adjustment_id	=	mysql_insert_id();

					

		if(sizeof($product_id) > 0)

		{

			$cnti	=	0;

			foreach($product_id as $product)

			{

				if($product != '' && is_numeric($product))

				{

					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $product . " AND `sku_id` = " . $product_sku[$cnti];

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty	=	mysqli_fetch_object($connQty);

						$stockAvailable	=	$fetQty->available;

						$stockHold		=	$fetQty->hold;

						

						$available 	= 	$product_available[$cnti] - (int)$stockHold;

						$diff_qty 	= 	(int)$available - (int)$stockAvailable;

						

						$insertRow 	= 	"INSERT INTO `inv_qne_adjustment_detail`(`detail_id`, `adjustment_id`, `product_id`, `sku_id`, `adjustment_reason`, `old_qty`, `adjust_qty`, `diff_qty`, `datetime`) 

										VALUES('', '" . $adjustment_id . "', '" . $product . "', '" . $product_sku[$cnti] . "', '" . $adjust_reason[$cnti] . "', '" . $stockAvailable . "', '" . $product_available[$cnti] . "', '" . $diff_qty . "', '" . date('Y-m-d H:i:s') . "')";

						$DB->query($insertRow);

						

						$updateQty	=	"UPDATE `inv_qne_product_stock` SET `available` = '" . $available . "' WHERE `product_id` = " . $product . " AND `sku_id` = " . $product_sku[$cnti];

						$DB->query($updateQty);

						

						$updateQty2	=	"UPDATE `product_attributes` SET `stock_qty` = '" . $available . "' WHERE `productid` = " . $product . " AND `id` = " . $product_sku[$cnti];

						$DB->query($updateQty2);

					}



					/*$qty 		= $old_qty[$cnti];

					$available 	= $product_available[$cnti];



					if($available < $qty)

					{

						$stockDiff	= 	$qty - $available;

						$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $product . " AND `sku_id` = " . $product_sku[$cnti];

						$connQty	=	$DB->query($selectQty);

						if(mysqli_num_rows($connQty) > 0)

						{

							$fetQty	=	mysqli_fetch_object($connQty);

							$stockAvailable	=	$fetQty->available;

							$stockHold		=	$fetQty->hold + $stockDiff;

							

							$updateQty	=	"UPDATE `inv_qne_product_stock` SET `available` = '" . $available . "', `hold` = '" . $stockHold . "' WHERE `product_id` = " . $product . " AND `sku_id` = " . $product_sku[$cnti];

							$DB->query($updateQty);

						}	

					}

					else

					if($available > $qty)

					{

						$stockDiff	= 	$available - $qty;

						$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $product . " AND `sku_id` = " . $product_sku[$cnti];

						$connQty	=	$DB->query($selectQty);

						if(mysqli_num_rows($connQty) > 0)

						{

							$fetQty	=	mysqli_fetch_object($connQty);

							$stockAvailable	=	$fetQty->available;

							$stockHold		=	$fetQty->hold + $stockDiff;

							

							$updateQty	=	"UPDATE `inv_qne_product_stock` SET `hold` = '" . $stockHold . "' WHERE `product_id` = " . $product . " AND `sku_id` = " . $product_sku[$cnti];

							$DB->query($updateQty);

						}	

					}*/

				}	

				$cnti++;

			}

		}

	}

	

	function confirmAdjustmentNote($adjustment_id, $status)

	{

		$DB		= 	new DB_connection();



		if($status == 'reject')

		{

			$select = 	"SELECT * FROM `inv_qne_adjustment_detail` WHERE `adjustment_id` = " . $adjustment_id;

			$conn	=	$DB->query($select);

			

			if(mysqli_num_rows($conn) > 0)

			{

				while($fetch = mysqli_fetch_object($conn))

				{

					$diff_qty 	= 	$fetch->diff_qty;



					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $fetch->product_id . " AND `sku_id` = " . $fetch->sku_id;

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty			=	mysqli_fetch_object($connQty);

						$stockAvailable	=	$fetQty->available;

						$stockDiff		=	$stockAvailable - $diff_qty;

						

						$updateQty	=	"UPDATE `inv_qne_product_stock` SET `available` = '" . $stockDiff . "' WHERE `sku_id` = " . $fetQty->sku_id;

						$DB->query($updateQty);

						

						$updateQty2	=	"UPDATE `product_attributes` SET `stock_qty` = '" . $stockDiff . "' WHERE `id` = " . $fetQty->sku_id;

						$DB->query($updateQty2);

					}	

				}

			}

		}	

		/*if($status == 'approve')

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$qty 		= $fetch->old_qty;

				$available 	= $fetch->adjust_qty;



				if($available < $qty)

				{

					$stockDiff	= 	$qty - $available;

					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $fetch->product_id . " AND `sku_id` = " . $fetch->sku_id;

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty		=	mysqli_fetch_object($connQty);

						$stockHold	=	$fetQty->hold - $stockDiff;

						

						$updateQty	=	"UPDATE `inv_qne_product_stock` SET `hold` = '" . $stockHold . "' WHERE `sku_id` = " . $fetQty->sku_id;

						$DB->query($updateQty);

					}	

				}

				else

				if($available > $qty)

				{

					$stockDiff	= 	$available - $qty;

					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $fetch->product_id . " AND `sku_id` = " . $fetch->sku_id;

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty			=	mysqli_fetch_object($connQty);

						$stockAvailable	=	$fetQty->available + $stockDiff;

						$stockHold		=	$fetQty->hold - $stockDiff;

						

						$updateQty	=	"UPDATE `inv_qne_product_stock` SET `available` = '" . $stockAvailable . "',`hold` = '" . $stockHold . "' WHERE `sku_id` = " . $fetQty->sku_id;

						$DB->query($updateQty);

					}	

				}

			}

		}	

		else 

		if($status == 'reject')

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$qty 		= $fetch->old_qty;

				$available 	= $fetch->adjust_qty;



				if($available < $qty)

				{

					$stockDiff	= 	$qty - $available;

					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $fetch->product_id . " AND `sku_id` = " . $fetch->sku_id;

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty	=	mysqli_fetch_object($connQty);

						$stockAvailable	=	$fetQty->available + $stockDiff;

						$stockHold		=	$fetQty->hold - $stockDiff;

						

						$updateQty	=	"UPDATE `inv_qne_product_stock` SET `available` = '" . $stockAvailable . "', `hold` = '" . $stockHold . "' WHERE `sku_id` = " . $fetQty->sku_id;

						$DB->query($updateQty);

					}	

				}

				else

				if($available > $qty)

				{

					$stockDiff	= 	$available - $qty;

					$selectQty	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = " . $fetch->product_id . " AND `sku_id` = " . $fetch->sku_id;

					$connQty	=	$DB->query($selectQty);

					if(mysqli_num_rows($connQty) > 0)

					{

						$fetQty	=	mysqli_fetch_object($connQty);

						$stockAvailable	=	$fetQty->available;

						$stockHold		=	$fetQty->hold - $stockDiff;

						

						$updateQty	=	"UPDATE `inv_qne_product_stock` SET `hold` = '" . $stockHold . "' WHERE `sku_id` = " . $fetQty->sku_id;

						$DB->query($updateQty);

					}	

				}

			}

		}*/

			

		$status_id = 0;

		switch($status)

		{

			case 'approve':

				$status_id = 1;

			break;

			

			case 'reject':

				$status_id = 2;

			break;

			

			default:

				$status_id = 0;

			break;

		}

		$update = "UPDATE `inv_qne_adjustments` SET `status` = '" . $status_id . "', `verified_by` = '" . $_SESSION['sess_username'] . "' WHERE `adjustment_id` = " . $adjustment_id;

		$DB->query($update);

	}

	

	public function allAdjustmentNotes($adjustment_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where	=	'';

		if($adjustment_id != 0)

		{

			$where = " WHERE `adjustment_id` = " . $adjustment_id;

		}

		

		$select 	= 	"SELECT * FROM `inv_qne_adjustments` " . $where . " ORDER BY adjustment_id DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$adjustments 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$adjustments[$c]					=	new Product();

				$adjustments[$c]->adjustment_id		=	$fetch->adjustment_id;

				$adjustments[$c]->adjustment_notes	=	$fetch->adjustment_notes;

				$adjustments[$c]->status			=	$fetch->status;

				$adjustments[$c]->created_by		=	$fetch->created_by;

				$adjustments[$c]->verified_by		=	$fetch->verified_by;

				$adjustments[$c]->datetime			=	$fetch->datetime;

				$c++;

			}

			return $adjustments;

		}	

	}

	

	public function adjustmentNotesByID($adjustment_id)

	{

		$DB			= 	new DB_connection();

		

		$where	=	'';

		if($adjustment_id != 0)

		{

			$where = " WHERE `adjustment_id` = " . $adjustment_id;

		}

		

		$select 	= 	"SELECT * FROM `inv_qne_adjustments` " . $where . " ORDER BY adjustment_id DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch = mysqli_fetch_object($conn);

			$this->adjustment_id	=	$fetch->adjustment_id;

			$this->adjustment_notes	=	$fetch->adjustment_notes;

			$this->status			=	$fetch->status;

			$this->created_by		=	$fetch->created_by;

			$this->verified_by		=	$fetch->verified_by;

			$this->datetime			=	$fetch->datetime;

		}	

	}

	

	public function adjustmentDetails($adjustment_id)

	{

		$DB			= 	new DB_connection();

		

		$select 	= 	"SELECT ad.*, qp.product, ps.sku_title FROM `inv_qne_adjustment_detail` ad LEFT JOIN inv_qne_products qp ON ad.product_id = qp.product_id LEFT JOIN inv_qne_product_sku ps ON ad.sku_id = ps.sku_id WHERE `adjustment_id` = " . $adjustment_id;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$adjustments 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$adjustments[$c]					=	new Product();

				$adjustments[$c]->detail_id			=	$fetch->detail_id;

				$adjustments[$c]->adjustment_id		=	$fetch->adjustment_id;

				$adjustments[$c]->product_id		=	$fetch->product_id;

				$adjustments[$c]->sku_id			=	$fetch->sku_id;

				$adjustments[$c]->product			=	$fetch->product;

				$adjustments[$c]->sku_title			=	$fetch->sku_title;

				$adjustments[$c]->adjustment_reason	=	$fetch->adjustment_reason;

				$adjustments[$c]->old_qty			=	$fetch->old_qty;

				$adjustments[$c]->adjust_qty		=	$fetch->adjust_qty;	

				$adjustments[$c]->datetime			=	$fetch->datetime;

				$c++;

			}

			return $adjustments;

		}	

	}

	

	function addProduct($post)

	{

		$DB					= 	new DB_connection();



		$product			=	mysql_real_escape_string($post['product_title']);

		$description		=	mysql_real_escape_string($post['description']);

		$company			=	mysql_real_escape_string($post['company']);

		$brand				=	mysql_real_escape_string($post['brand']);

		$batchable			=	mysql_real_escape_string($post['batchable']);

		$virtual			=	mysql_real_escape_string($post['virtual']);

		$product_type		=	mysql_real_escape_string($post['product_type']);

		$tax_type			=	explode("_", $post['tax_type']);

		$tax_type			=	$tax_type[0];

		$category			=	mysql_real_escape_string($post['category']);

		$sub_category		=	mysql_real_escape_string($post['sub_category']);

		$sub_sub_category	=	mysql_real_escape_string($post['sub_sub_category']);

		$status				=	mysql_real_escape_string($post['status']);

		

		$web_sub_sub_id		=	0;

		$web_sub_id			=	0;

		$web_cat_id			=	0;

		if($sub_sub_category != '' && $sub_sub_category != 0)

		{

			$category_id	=	$sub_sub_category;

		}

		else

		if($sub_category != '' && $sub_category != 0)

		{

			$category_id	=	$sub_category;

		}

		else

		{

			$category_id	=	$category;

		}

		

		if($sub_sub_category != '' && $sub_sub_category != 0)

		{

			$webSelect 	= "SELECT title FROM `inv_qne_category` WHERE `category_id` = " . $sub_sub_category;

			$webConn	= $DB->query($webSelect);

			$fetWeb		= mysqli_fetch_object($webConn);

			

			$webSelect2 = "SELECT id FROM `sub_sub_category` WHERE `title` = '" . $fetWeb->title . "'";

			$webConn2	= $DB->query($webSelect2);

			$fetWeb2	= mysqli_fetch_object($webConn2);

			$web_sub_sub_id	= $fetWeb2->id;

		}



		if($sub_category != '' && $sub_category != 0)

		{

			$webSelect 	= "SELECT title FROM `inv_qne_category` WHERE `category_id` = " . $sub_category;

			$webConn	= $DB->query($webSelect);

			$fetWeb		= mysqli_fetch_object($webConn);

			

			$webSelect2 = "SELECT sub_cat_id FROM `sub_category` WHERE `sub_cat_name` = '" . $fetWeb->title . "'";

			$webConn2	= $DB->query($webSelect2);

			$fetWeb2	= mysqli_fetch_object($webConn2);

			$web_sub_id	= $fetWeb2->sub_cat_id;

		}

		

		if($category != '' && $category != 0)

		{

			$web_cat_id		=	$category;

		}

		

		$webSelect = "SELECT productid FROM `product` ORDER BY productid DESC LIMIT 1";

		$webConn	=	$DB->query($webSelect) or die(mysqli_error($webInsert));



		$fetWeb	=	mysqli_fetch_object($webConn);

		$product_id	=	$fetWeb->productid + 1;

		$insert 		= 	"INSERT INTO `inv_qne_products`(`product_id`, `product`, `description`, `company`, `brand`, `category`, `batchable`, `is_virtual`, `product_type`, `tax_type`, `status`, `datetime`) 

		VALUES('" . $product_id . "', '" . $product . "', '" . $description . "', '" . $company . "', '" . $brand . "', '" . $category_id . "', '" . $batchable . "', '" . $virtual . "', '" . $product_type . "', '" . $tax_type . "', '1', '" . date('Y-m-d H:i:s') . "')";

		$DB->query($insert) or die($insert);



		$product_id	=	mysql_insert_id();

		

		$webInsert = "INSERT INTO `product` SET `productid` = '" . $product_id . "', `categoryid` = '" . $web_cat_id . "', `subcatid` = '" . $web_sub_id . "', `subsubcatid` = '" . $web_sub_sub_id . "', `productname` = '".$product."', productdes = '".$description."', product_manuficture='".$company."', brand_id='$brand', img_alt_des='".$description."', seotitle =  '".$product."', product_type = 'normal', productstatus ='0', `is_virtual` =  '".$virtual."'";

		$DB->query($webInsert) or die(mysqli_error($webInsert));



		if(sizeof($_POST['distributor']) > 0)

		{

			foreach($_POST['distributor'] as $dis)

			{

				$insert = 	"INSERT INTO `inv_qne_product_distributor`(`product_distributor_id`, `product_id`, `distributor_id`) VALUES('', '" . $product_id . "', '" . $dis . "')";

				$DB->query($insert);

			}

		}	

		

		$allDistributors	=	$this->allDistributor($company);

		

		if(sizeof($allDistributors) > 0)

		{

			$cnti	=	0;

			foreach($allDistributors as $distributor)

			{

				if($_POST['distributor_product_title'][$cnti] != '' && $_POST['distributor_product_title'][$cnti] != ' ')

				{

					$select = 	"SELECT * FROM `inv_qne_product_distributor` WHERE `product_id` = '" . $product_id . "' AND `distributor_id` = '" . $distributor->distributor_id . "'";

					$conn	=	$DB->query($select);

					if(mysqli_num_rows($conn) > 0)

					{

						$fet	=	mysqli_fetch_object($conn);

						$product_distributor_id	=	$fet->product_distributor_id;

						$update = 	"UPDATE `inv_qne_product_distributor` SET `product_title` = '" . mysql_real_escape_string($_POST['distributor_product_title'][$cnti]) . "' WHERE `product_distributor_id` = " . $product_distributor_id;

						$DB->query($update);

					}

					else

					{

						$insert = 	"INSERT INTO `inv_qne_product_distributor`(`product_distributor_id`, `product_id`, `distributor_id`, `product_title`) VALUES('', '" . $product_id . "', '" . $distributor->distributor_id . "', '" . mysql_real_escape_string($_POST['distributor_product_title'][$cnti]) . "')";

						$DB->query($insert);

					}

				}	

				$cnti++;

			}

		}



		$sku_title			=	$_POST['sku_title'];

		$sku_code			=	$post['sku_code'];

		$threshold			=	$post['threshold'];

		$shelf_life			=	$post['shelf_life'];

		$carton_size		=	$post['carton_size'];

		$reorder			=	$post['reorder'];

		$cost_price			=	$post['cost_price'];

		$retail				=	$post['retail'];



		if(sizeof($_POST['sku_title']) > 0)

		{

			$cntj	=	0;

			foreach($sku_title as $title)

			{

				if($title != '' && $title != ' ')

				{

					$insert = 	"INSERT INTO `inv_qne_product_sku`(`sku_id`, `product_id`, `sku_title`, `sku_code`, `threshold`, `shelf_life`, `carton_size`, `re_order_level`, `status`, `datetime`) 

								VALUES('', '" . $product_id . "', '" . mysql_real_escape_string($sku_title[$cntj]) . "', '" . mysql_real_escape_string($sku_code[$cntj]) . "', '" . mysql_real_escape_string($threshold[$cntj]) . "', '" . mysql_real_escape_string($shelf_life[$cntj]) . "', '" . mysql_real_escape_string($carton_size[$cntj]) . "', '" . mysql_real_escape_string($reorder[$cntj]) . "', '1', '" . date('Y-m-d H:i:s') . "')";

					$DB->query($insert);

					$sku_id	=	mysql_insert_id();



					$stock = 	"INSERT INTO `inv_qne_product_stock`(`stock_id`, `product_id`, `sku_id`, `qty`, `sold`, `available`, `hold`, `date`) 

								VALUES('', '" . $product_id . "', '" . $sku_id . "', '0', '0', '0', '0', '" . date('Y-m-d') . "')";

					$DB->query($stock);



					$max_id		=	$sku_id;

					$uploaddir 	= 	'../product_images/'; 

					

					$filename			= $_FILES["sku_image"]["name"][$cntj];

					$file 				= 	$uploaddir . "orgsize_" . $max_id . basename($_FILES['sku_image']['name'][$cntj]); 

					$targetFileUpload	= "../product_images/orgsize_" . $max_id . $filename;	

					$isUploadFile		= "false";

					if(move_uploaded_file($_FILES['sku_image']['tmp_name'][$cntj], $targetFileUpload))

					{

						$isUploadFile		= "true";

						$webInsert2 = "UPDATE `product` SET `org_img` = 'product_images/orgsize_" . $max_id . $filename . "' WHERE `productid` = '" . $product_id . "'";

						$DB->query($webInsert2) or die(mysqli_error($webInsert2));

					

						$random		= 	rand(222,123456);

				

						$image 		= new Resize_Image;

						$image->new_width 	= 83;

						$image->new_height 	= 83;

						

						$image->image_to_resize = $file;

						$image->ratio = true;

						

						$image->new_image_name = "thumb_1_".$random."_".$max_id;

						

						$image->save_folder = '../product_images/img_size1/';

						$process = $image->resize();



						$image_large = new Resize_Image;

						$image_large->new_width = 222;

						$image_large->new_height = 222;

						

						$image_large->image_to_resize = $file;

						$image_large->ratio = true;

					

						$image_large->new_image_name = "thumb_2_".$random."_".$max_id;

					

						$image_large->save_folder = '../product_images/img_size2/';

						$process_large = $image_large->resize();

						

						$image_large2 = new Resize_Image;

						$image_large2->new_width = 246;

						$image_large2->new_height = 246;

						

						$image_large2->image_to_resize = $file;

						$image_large2->ratio = true;

					

						$image_large2->new_image_name = "thumb_3_".$random."_".$max_id;

					

						$image_large2->save_folder = '../product_images/img_size3/';

						$process_large2 = $image_large2->resize();

						

						$image_large3 = new Resize_Image;

						$image_large3->new_width = 360;

						$image_large3->new_height = 360;

						$image_large3->image_to_resize = $file;

						$image_large3->ratio = true;

						$image_large3->new_image_name = "thumb_4_".$random."_".$max_id;

						$image_large3->save_folder = '../product_images/img_size4/';

						$process_large3 = $image_large3->resize();



						$filename			= $_FILES["sku_image"]["name"][$cntj];

						$targetFileUpload	= "../product_images/orgsize_" . $max_id . $filename;	



						$orignal_size = "product_images/orgsize_" . $max_id . $filename;

	

						$db_path		= str_replace('../','',$process['new_file_path']);

						$db_path_large	= str_replace('../','',$process_large['new_file_path']);

						$db_path_large2	= str_replace('../','',$process_large2['new_file_path']);

						$db_path_large3	= str_replace('../','',$process_large2['new_file_path']);



						$tupadte = "UPDATE `product` SET `org_img` = '" . $orignal_size . "',	`thumbnail1` = '" . $db_path . "', `thumbnail2` = '" . $db_path_large . "', `thumbnail3` = '" . $db_path_large2 . "', `thumbnail4` = '" . $db_path_large3 . "' WHERE `productid` = " . $product_id;

						@mysql_query($tupadte)	or mysqli_error();

						

						$targetfolder	= 	"assets/img/product_img/sku_img/";

						$filename		=	$_FILES['sku_image']['name'][$cntj];

						$ext 			= 	pathinfo($filename, PATHINFO_EXTENSION);

						

						$uploadFile     =   $product_id . "_" . $sku_id . "_sku_image." . $ext;

						$targetFile = $targetfolder . $uploadFile;

						

						if(copy($targetFileUpload, $targetFile))

						{

							$upd = 	"UPDATE `inv_qne_product_sku` SET `sku_image` = '" . $uploadFile . "' WHERE `sku_id` = " . $sku_id;

							$DB->query($upd);

						}

					}	

					

					$taxVal	=	$this->allTaxes($tax_type);

						

					$tax_value	=	$taxVal[0]->tax_value;

					$tax_type_val	=	$taxVal[0]->tax_type;

					

					switch($tax_type_val)

					{

						case 'Normal':

							$taxAmount			=	($retail[$cntj][0] * $tax_value) / 100;

							$retail_price_tax	=	$retail[$cntj][0] + $taxAmount;

							$attrTaxType		=	"GST";

						break;

						

						case 'Fixed':

							$taxAmount			=	($retail[$cntj][0] * $tax_value) / 100;

							$retail_price_tax	=	$retail[$cntj][0] + $taxAmount;

							$attrTaxType		=	"FGST";

						break;

						

						default:

							$retail_price_tax	=	$retail[$cntj][0];

							$attrTaxType		=	"Exempted";

						break;

					}

					$taxAmount 	= 	$retail_price_tax - $retail[$cntj][0];

					$attrPrice	=	$retail_price_tax - $taxAmount;

					$webAttrQry = "INSERT INTO `product_attributes` SET `id` = '" . $sku_id . "', `whearhouse_id` = '1', `attribute_title` = '" . mysql_real_escape_string($sku_title[$cntj]) . "', `attribute_code` = '" . mysql_real_escape_string($sku_code[$cntj]) . "', `price`='" . $attrPrice . "', `price_final`='" . $retail_price_tax . "', `price_without_discount`='" . $retail_price_tax . "', `stock_qty`='0', `minStock_qty`='5', `max_order_qty`='15', `discount_percent`='', `tax_type`='" . $attrTaxType . "', `gst_percent`='" . $tax_value . "', `tax_value`='" . $taxAmount . "', `productid` = '".$product_id."'";

					$DB->query($webAttrQry) or die(mysqli_error($webAttrQry));

					

					if($isUploadFile == "true")

					{

						$image = new Resize_Image;

						$image->new_width = 83;

						$image->new_height = 83;

						

						$image->image_to_resize = $file; // Full Path to the file

						$image->ratio = true; // Keep Aspect Ratio?

						

						$image->new_image_name = "thumb_1_".$random."_".$sku_id;

						

						$image->save_folder = '../product_images/sku_images/';

						$process = $image->resize();

						if($process['result'] && $image->save_folder){

						//echo 'The new image ('.$process['new_file_path'].') has been saved.';

						}

						

						$filename = $_FILES["sku_image"]["name"][$cntj];

						move_uploaded_file($_FILES["sku_image"]["tmp_name"][$cntj],'../product_images/sku_images/orgsize_'.$max_id.$filename);

						//rename("../product_images/sku_images/".$_FILES["skufile"]["name"][$cntj],"../product_images/sku_images/orgsize_".$max_id.$filename);

						$orignal_size = "product_images/sku_images/orgsize_".$max_id.$filename;

						$db_path=str_replace('../','',$process['new_file_path']);

						@unlink($file);

						echo $tupadte="UPDATE `product_attributes` SET `org_img` = '" . $orignal_size . "',	`thumbnail_img` = '" . $db_path . "' WHERE `id` = " . $max_id;

						@mysql_query($tupadte) or mysqli_error();

					}

					// Distributor Wise Price

					if(sizeof($_POST['distributor_id'][$cntj]) > 0)

					{

						$cntI = 0;

						foreach($_POST['distributor_id'][$cntj] as $distri)

						{

							switch($tax_type_val)

							{

								case 'Normal':

									$taxAmount			=	($retail[$cntj][$cntI] * $tax_value) / 100;

									$retail_price_tax	=	$retail[$cntj][$cntI] + $taxAmount;

									

									$taxAmount			=	($cost_price[$cntj][$cntI] * $tax_value) / 100;

									$cost_price_tax		=	$cost_price[$cntj][$cntI] + $taxAmount;

								break;

								

								case 'Fixed':

									$taxAmount			=	($retail[$cntj][$cntI] * $tax_value) / 100;

									$cost_price_tax		=	$cost_price[$cntj][$cntI] + $taxAmount;

									$retail_price_tax	=	$retail[$cntj][$cntI] + $taxAmount;

								break;

								

								default:

									$cost_price_tax		=	$cost_price[$cntj][$cntI];

									$retail_price_tax	=	$retail[$cntj][$cntI];

								break;

							}

							

							$insert = 	"INSERT INTO `inv_qne_product_price`(`price_id`, `product_id`, `sku_id`, `distributor_id`, `cost_price`, `cost_price_tax`, `retail_price`, `retail_price_tax`, `status`, `modify_date`, `date`) 

										VALUES('', '" . $product_id . "', '" . $sku_id . "', '" . $distri . "', '" . $cost_price[$cntj][$cntI] . "', '" . $cost_price_tax . "', '" . $retail[$cntj][$cntI] . "', '" . $retail_price_tax . "', '1', '" . date('Y-m-d') . "', '" . date('Y-m-d') . "')";

							$DB->query($insert);

							$cntI++;

						}

					}	

					// Distributor Wise Price Ends

				}

				$cntj++;

			}

		}	

	}

	

	function addProductSKU($post)

	{

		$DB					= 	new DB_connection();



		$product_id			=	$post['product_id'];

		$tax_type			=	explode("_", $post['tax_type']);

		$tax_type			=	$tax_type[0];

		$sku_title			=	$post['sku_title'];

		$sku_code			=	$post['sku_code'];

		$threshold			=	$post['threshold'];

		$shelf_life			=	$post['shelf_life'];

		$carton_size		=	$post['carton_size'];

		$reorder			=	$post['reorder'];

		$cost_price			=	$post['cost_price'];

		$retail				=	$post['retail'];



		if(sizeof($_POST['sku_title']) > 0)

		{

			$cntj	=	0;

			foreach($sku_title as $title)

			{

				if($title != '' && $title != ' ')

				{

					$insert = 	"INSERT INTO `inv_qne_product_sku`(`sku_id`, `product_id`, `sku_title`, `sku_code`, `threshold`, `shelf_life`, `carton_size`, `re_order_level`, `status`, `datetime`) 

								VALUES('', '" . $product_id . "', '" . mysql_real_escape_string($sku_title[$cntj]) . "', '" . mysql_real_escape_string($sku_code[$cntj]) . "', '" . mysql_real_escape_string($threshold[$cntj]) . "', '" . mysql_real_escape_string($shelf_life[$cntj]) . "', '" . mysql_real_escape_string($carton_size[$cntj]) . "', '" . mysql_real_escape_string($reorder[$cntj]) . "', '1', '" . date('Y-m-d H:i:s') . "')";

					$DB->query($insert);

					$sku_id	=	mysql_insert_id();

					

					$stock = 	"INSERT INTO `inv_qne_product_stock`(`stock_id`, `product_id`, `sku_id`, `qty`, `sold`, `available`, `hold`, `date`) 

								VALUES('', '" . $product_id . "', '" . $sku_id . "', '0', '0', '0', '0', '" . date('Y-m-d') . "')";

					$DB->query($stock);

					

					$targetfolder	= 	"assets/img/product_img/sku_img/";

					$filename		=	$_FILES['sku_image']['name'][$cntj];

					$ext 			= 	pathinfo($filename, PATHINFO_EXTENSION);

					

					$uploadFile     =   $product_id . "_" . $sku_id . "_sku_image." . $ext;

					$targetFile = $targetfolder . $uploadFile;

					

					if($cntj == 0)

					{

						$targetfolder2	= 	"../product_images/";

						$filename2		=	$_FILES['sku_image']['name'][$cntj];

						$ext2 			= 	pathinfo($filename2, PATHINFO_EXTENSION);

						$uploadFile2    =   $product_id . "_" . $sku_id . "_sku_image." . $ext2;

						$targetFile2 	= 	$targetfolder2 . $uploadFile2;

						

						if(move_uploaded_file($_FILES['sku_image']['tmp_name'][$cntj], $targetFile2))

						{

							$webInsert2 = "UPDATE `product` SET `org_img` = 'product_images/" . $uploadFile2 . "' WHERE `productid` = '" . $product_id . "'";

							$DB->query($webInsert2) or die(mysqli_error($webInsert2));

							

							$webAttr2 = "UPDATE `product_attributes` SET `org_img` = 'product_images/" . $uploadFile2 . "' WHERE `id` = '" . $sku_id . "'";

							$DB->query($webAttr2) or die(mysqli_error($webAttr2));

						}

					}

					else

					{

						$targetfolder2	= 	"../product_images/";

						$filename2		=	$_FILES['sku_image']['name'][$cntj];

						$ext2 			= 	pathinfo($filename2, PATHINFO_EXTENSION);

						$uploadFile2    =   $product_id . "_" . $sku_id . "_sku_image." . $ext2;

						$targetFile2 	= 	$targetfolder2 . $uploadFile2;

						

						if(move_uploaded_file($_FILES['sku_image']['tmp_name'][$cntj], $targetFile2))

						{

							$webAttr2 = "UPDATE `product_attributes` SET `org_img` = 'product_images/" . $uploadFile2 . "' WHERE `id` = '" . $sku_id . "'";

							$DB->query($webAttr2) or die(mysqli_error($webAttr2));

						}

					}

					

					if(copy($targetFile2, $targetFile))

					{

						$upd = 	"UPDATE `inv_qne_product_sku` SET `sku_image` = '" . $uploadFile . "' WHERE `sku_id` = " . $sku_id;

						$DB->query($upd);

					}

					

					$taxVal	=	$this->allTaxes($tax_type);

						

					$tax_value	=	$taxVal[0]->tax_value;

					$tax_type_val	=	$taxVal[0]->tax_type;

					

					switch($tax_type_val)

					{

						case 'Normal':

							$retail_price		=	$retail[$cntj][0];

							$taxAmount			=	($retail_price * $tax_value) / 100;

							$retail_price_tax	=	$retail_price + $taxAmount;

							$attrTaxType		=	"GST";

						break;

						

						case 'Fixed':

							$retail_price		=	$retail[$cntj][0];

							$taxAmount			=	($retail_price * $tax_value) / 100;

							$retail_price_tax	=	$retail_price + $taxAmount;

							$attrTaxType		=	"FGST";

						break;

						

						default:

							$retail_price		=	$retail[$cntj][0];

							$retail_price_tax	=	$retail_price;

							$attrTaxType		=	"Exempted";

						break;

					}

					$taxAmount	=	$retail_price_tax - $retail_price;

					$webAttrQry = "INSERT INTO `product_attributes` SET `id` = '" . $sku_id . "', `whearhouse_id` = '1', `attribute_title` = '" . mysql_real_escape_string($sku_title[$cntj]) . "', `attribute_code` = '" . mysql_real_escape_string($sku_code[$cntj]) . "', `price`='" . $retail[$cntj][0] . "', `price_final`='" . $retail_price_tax . "', `price_without_discount`='" . $retail_price_tax . "', `stock_qty`='0', `minStock_qty`='', `max_order_qty`='', `discount_percent`='', `tax_type`='" . $attrTaxType . "', `gst_percent`='" . $tax_value . "', `tax_value`='" . $taxAmount . "', `productid` = '".$product_id."'";

					$DB->query($webAttrQry) or die(mysqli_error($webAttrQry));



					if(sizeof($_POST['distributor_id'][$cntj]) > 0)

					{

						$cntI = 0;

						foreach($_POST['distributor_id'][$cntj] as $distri)

						{

							switch($tax_type_val)

							{

								case 'Normal':

									$taxAmount			=	($retail[$cntj][$cntI] * $tax_value) / 100;

									$retail_price_tax	=	$retail[$cntj][$cntI] + $taxAmount;

									

									$taxAmount			=	($cost_price[$cntj][$cntI] * $tax_value) / 100;

									$cost_price_tax		=	$cost_price[$cntj][$cntI] + $taxAmount;

								break;

								

								case 'Fixed':

									$taxAmount			=	($retail[$cntj][$cntI] * $tax_value) / 100;

									$cost_price_tax		=	$cost_price[$cntj][$cntI] + $taxAmount;

									$retail_price_tax	=	$retail[$cntj][$cntI] + $taxAmount;

								break;

								

								default:

									$cost_price_tax		=	$cost_price[$cntj][$cntI];

									$retail_price_tax	=	$retail[$cntj][$cntI];

								break;

							}

							

							$insert = 	"INSERT INTO `inv_qne_product_price`(`price_id`, `product_id`, `sku_id`, `distributor_id`, `cost_price`, `cost_price_tax`, `retail_price`, `retail_price_tax`, `status`, `modify_date`, `date`) 

										VALUES('', '" . $product_id . "', '" . $sku_id . "', '" . $distri . "', '" . $cost_price[$cntj][$cntI] . "', '" . $cost_price_tax . "', '" . $retail[$cntj][$cntI] . "', '" . $retail_price_tax . "', '1', '" . date('Y-m-d') . "', '" . date('Y-m-d') . "')";

							$DB->query($insert);

							$cntI++;

						}

					}	

				}

				$cntj++;

			}

		}	

	}

	

	

	function distributorSKUPrice($distributor_id, $sku_id)

	{

		$DB					= 	new DB_connection();

		

		$select = 	"SELECT * WHERE `distributor_id` = '" . $distributor_id . "' AND `sku_id` = '" . $sku_id . "'";

		$conn	=	$DB->query($select);

		if(mysqli_num_rows($conn) > 0)

		{

			$fet	=	mysqli_fetch_object($conn);

			

			$this->cost_price		=	$fet->cost_price;

			$this->cost_price_tax	=	$fet->cost_price_tax;

			$this->retail_price		=	$fet->retail_price;

			$this->retail_price_tax	=	$fet->retail_price_tax;

		}

		else

		{

			$this->cost_price		=	0;

			$this->cost_price_tax	=	0;

			$this->retail_price		=	0;

			$this->retail_price_tax	=	0;

		}

	}	

	

	function editProduct($post)
	{
		$DB					= 	new DB_connection();

        $product_id			=	mysql_real_escape_string($post['product_id']);
		$product			=	mysql_real_escape_string($post['product_title']);
		$description		=	mysql_real_escape_string($post['description']);
		$company			=	mysql_real_escape_string($post['company']);
		$brand				=	mysql_real_escape_string($post['brand']);
		$batchable			=	mysql_real_escape_string($post['batchable']);
		$virtual			=	mysql_real_escape_string($post['virtual']);
		$product_type		=	mysql_real_escape_string($post['product_type']);
		$tax_type			=	explode("_", $post['tax_type']);
		$taxTypo			=	$tax_type[1];
        $taxVal			    =	$tax_type[2];
        $tax_type			=	$tax_type[0];
		$status				=	mysql_real_escape_string($post['status']);

        $update 	= 	"UPDATE `inv_qne_products` SET `product` = '" . $product . "', `description` = '" . $description . "', `company` = '" . $company . "', `brand` = '" . $brand . "', `batchable` = '" . $batchable . "', `product_type` = '" . $product_type . "', `tax_type` = '" . $tax_type . "', `datetime` = '" . date('Y-m-d H:i:s') . "' WHERE `product_id` = '" . $product_id . "'";
		$DB->query($update);

        $update2 	= 	"UPDATE `product` SET `productname` = '" . $product . "', `productdes` = '" . $description . "' WHERE `productid` = '" . $product_id . "'";
		$DB->query($update2);

        if(sizeof($_POST['distributor']) > 0)
		{
			foreach($_POST['distributor'] as $dis)
			{
				$select = 	"SELECT * FROM `inv_qne_product_distributor` WHERE `product_id` = '" . $product_id . "' AND `distributor_id` = '" . $dis . "'";
				$conn	=	$DB->query($select);

                if(mysqli_num_rows($conn) > 0)
				{
				}
				else
				{
					$insert = 	"INSERT INTO `inv_qne_product_distributor`(`product_distributor_id`, `product_id`, `distributor_id`) VALUES('', '" . $product_id . "', '" . $dis . "')";
					$DB->query($insert);
				}
			}
		}	

        $allDistributors	=	$this->allDistributor($company);

        if(sizeof($allDistributors) > 0)
		{
			$cnti	=	0;

            foreach($allDistributors as $distributor)
			{
				if($_POST['distributor_product_title'][$cnti] != '' && $_POST['distributor_product_title'][$cnti] != ' ')
				{
					$select = 	"SELECT * FROM `inv_qne_product_distributor` WHERE `product_id` = '" . $product_id . "' AND `distributor_id` = '" . $distributor->distributor_id . "'";
					$conn	=	$DB->query($select);

					if(mysqli_num_rows($conn) > 0)
					{
						$fet	=	mysqli_fetch_object($conn);
						$product_distributor_id	=	$fet->product_distributor_id;

                        $update = 	"UPDATE `inv_qne_product_distributor` SET `product_title` = '" . $_POST['distributor_product_title'][$cnti] . "' WHERE `product_distributor_id` = " . $product_distributor_id;
						$DB->query($update);
					}
					else
					{
						$insert = 	"INSERT INTO `inv_qne_product_distributor`(`product_distributor_id`, `product_id`, `distributor_id`, `product_title`) VALUES('', '" . $product_id . "', '" . $distributor->distributor_id . "', '" . $_POST['distributor_product_title'][$cnti] . "')";
						$DB->query($insert);
					}
				}	
				$cnti++;
			}
		}

		$sku_id				=	$post['sku_id'];
		$sku_title			=	$_POST['sku_title'];
		$sku_code			=	$post['sku_code'];
		$threshold			=	$post['threshold'];
		$shelf_life			=	$post['shelf_life'];
		$carton_size		=	$post['carton_size'];
		$reorder			=	$post['reorder'];
		$cost_price			=	$post['cost_price'];
		$retail				=	$post['retail'];

        if(sizeof($_POST['sku_title']) > 0)
		{
			$cntj	=	0;
			foreach($sku_title as $title)
			{
				if($title != '' && $title != ' ')
				{
					$insert = 	"UPDATE `inv_qne_product_sku` SET `sku_title` = '" . mysql_real_escape_string($sku_title[$cntj]) . "', `sku_code` = '" . mysql_real_escape_string($sku_code[$cntj]) . "', `threshold` = '" . mysql_real_escape_string($threshold[$cntj]) . "', `shelf_life` = '" . mysql_real_escape_string($shelf_life[$cntj]) . "', `carton_size` = '" . mysql_real_escape_string($carton_size[$cntj]) . "', `re_order_level` = '" . mysql_real_escape_string($reorder[$cntj]) . "' WHERE `sku_id` = '" . $sku_id[$cntj] . "'";
					$DB->query($insert);
                    
                    
					//$sku_id	=	$sku_id[$cntj];

                    $targetfolder	= 	"assets/img/product_img/sku_img/";
					$filename		=	$_FILES['sku_image']['name'][$cntj];
					$ext 			= 	pathinfo($filename, PATHINFO_EXTENSION);
					$uploadFile     =   $product_id . "_" . $sku_id[$cntj] . "_sku_image." . $ext;
					$targetFile = $targetfolder . $uploadFile;

                    if(move_uploaded_file($_FILES['sku_image']['tmp_name'][$cntj], $targetFile))
					{
						$upd = 	"UPDATE `inv_qne_product_sku` SET `sku_image` = '" . $uploadFile . "' WHERE `sku_id` = " . $sku_id[$cntj];
						$DB->query($upd);
					}

                    $max_id		=	$sku_id[$cntj];
                    
                    $updateAttr = "UPDATE `product_attributes` SET `attribute_title` = '" . mysql_real_escape_string($sku_title[$cntj]) . "', `attribute_code` = '" . mysql_real_escape_string($sku_code[$cntj]) . "', `tax_type` = '" . $taxTypo . "',	`gst_percent` = '" . $taxVal . "' WHERE `id` = " . $max_id;
				    $DB->query($updateAttr) or mysqli_error();
                    
					$uploaddir 	= 	'../product_images/'; 

                    $file 		= 	$uploaddir . $uploadFile;//"orgsize_" . $max_id . $_FILES['sku_image']['name'][$cntj]; 
					if(copy($targetFile, $file))
					{
						//echo "<br />".$webInsert2 = "UPDATE `product` SET `org_img` = 'product_images/orgsize_" . $max_id . $_FILES['sku_image']['name'][$cntj] . "' WHERE `productid` = '" . $product_id . "'";
						$webInsert2 = "UPDATE `product` SET `org_img` = 'product_images/" . $uploadFile . "' WHERE `productid` = '" . $product_id . "'";
						$DB->query($webInsert2) or die(mysqli_error($webInsert2));

                        $random		= 	rand(222,123456);
						$image 		= 	new Resize_Image;
						$image->new_width 	= 83;
						$image->new_height 	= 83;
                        $image->image_to_resize = $file;
						$image->ratio = true;
						$image->new_image_name = "thumb_1_".$random."_".$max_id;
						$image->save_folder = '../product_images/img_size1/';
						$process = $image->resize();

                        $image_large = new Resize_Image;
						$image_large->new_width = 222;
						$image_large->new_height = 222;
						$image_large->image_to_resize = $file;
						$image_large->ratio = true;
						$image_large->new_image_name = "thumb_2_".$random."_".$max_id;
						$image_large->save_folder = '../product_images/img_size2/';
						$process_large = $image_large->resize();

                        $image_large2 = new Resize_Image;
						$image_large2->new_width = 246;
						$image_large2->new_height = 246;
						$image_large2->image_to_resize = $file;
						$image_large2->ratio = true;
						$image_large2->new_image_name = "thumb_3_".$random."_".$max_id;
						$image_large2->save_folder = '../product_images/img_size3/';
						$process_large2 = $image_large2->resize();

                        $image_large3 = new Resize_Image;
						$image_large3->new_width = 360;
						$image_large3->new_height = 360;
						$image_large3->image_to_resize = $file;
						$image_large3->ratio = true;
						$image_large3->new_image_name = "thumb_4_".$random."_".$max_id;
						$image_large3->save_folder = '../product_images/img_size4/';
						$process_large3 = $image_large3->resize();

                        $filename = $_FILES["sku_image"]["name"][$cntj];
						$db_path		= str_replace('../','',$process['new_file_path']);
						$db_path_large	= str_replace('../','',$process_large['new_file_path']);
						$db_path_large2	= str_replace('../','',$process_large2['new_file_path']);
						$db_path_large3	= str_replace('../','',$process_large2['new_file_path']);

                        $tupadte = "UPDATE `product` SET `thumbnail1` = '" . $db_path . "', `thumbnail2` = '" . $db_path_large . "', `thumbnail3` = '" . $db_path_large2 . "', `thumbnail4` = '" . $db_path_large3 . "' WHERE `productid` = " . $product_id;
						$DB->query($tupadte)	or mysqli_error();

                        $image = new Resize_Image;
						$image->new_width = 83;
						$image->new_height = 83;
						$image->image_to_resize = $file; // Full Path to the file
						$image->ratio = true; // Keep Aspect Ratio?
						$image->new_image_name = "thumb_1_".$random."_".$sku_id;
						$image->save_folder = '../product_images/sku_images/';
						$process = $image->resize();

                        $filename = $_FILES["sku_image"]["name"][$cntj];
						move_uploaded_file($_FILES["sku_image"]["tmp_name"][$cntj],'../product_images/sku_images/orgsize_'.$max_id.$filename);
						$orignal_size = "product_images/sku_images/orgsize_".$max_id.$filename;
						$db_path=str_replace('../','',$process['new_file_path']);
						@unlink($file);

						$tupadte="UPDATE `product_attributes` SET `org_img` = '" . $orignal_size . "',	`thumbnail_img` = '" . $db_path . "' WHERE `id` = " . $max_id;
						$DB->query($tupadte) or mysqli_error();
					}
				}
				$cntj++;
			}
		}	
	}

    public function productSKUPrice($sku_id, $distributor_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		

		if($sku_id != 0)

		{

			$where .= " AND iqps.sku_id = " . $sku_id;

		}

		if($distributor_id != 0)

		{

			$where .= " AND iqpp.distributor_id = " . $distributor_id;

		}

		$select 	= 	"SELECT iqps.*, iqpp.cost_price, iqpp.cost_price_tax, iqpp.retail_price, iqpp.retail_price_tax, iqpp.status as price_status, iqd.distributor FROM `inv_qne_product_sku` iqps LEFT JOIN `inv_qne_product_price` iqpp ON iqps.sku_id = iqpp.sku_id LEFT JOIN `inv_qne_distributor` iqd ON iqpp.distributor_id = iqd.distributor_id WHERE 1 = 1 " . $where . " ORDER BY iqpp.date DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$productSKU 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$productSKU[$c]						=	new Product();

				$productSKU[$c]->sku_id				=	$fetch->sku_id;

				$productSKU[$c]->product_id			=	$fetch->product_id;

				$productSKU[$c]->sku_title			=	$fetch->sku_title;

				$productSKU[$c]->sku_code			=	$fetch->sku_code;

				$productSKU[$c]->sku_image			=	$fetch->sku_image;

				$productSKU[$c]->threshold			=	$fetch->threshold;

				$productSKU[$c]->shelf_life			=	$fetch->shelf_life;

				$productSKU[$c]->carton_size		=	$fetch->carton_size;

				$productSKU[$c]->re_order_level		=	$fetch->re_order_level;

				$productSKU[$c]->status				=	$fetch->status;

				$productSKU[$c]->datetime			=	$fetch->datetime;

				

				$productSKU[$c]->cost_price			=	$fetch->cost_price;

				$productSKU[$c]->cost_price_tax		=	$fetch->cost_price_tax;

				$productSKU[$c]->retail_price		=	$fetch->retail_price;

				$productSKU[$c]->retail_price_tax	=	$fetch->retail_price_tax;

				$productSKU[$c]->price_status		=	$fetch->price_status;

				$productSKU[$c]->distributor		=	$fetch->distributor;

				$c++;

			}

			return $productSKU;

		}	

	}

	

	public function allProductListing($product='', $company_id=0, $brand_id=0, $start=0, $limit=0)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		

		$join  = " LEFT JOIN `brand` iqb ON iqp.brand = iqb.id LEFT JOIN `inv_qne_company` iqc ON iqp.company = iqc.company_id LEFT JOIN `inv_qne_product_sku` iqps ON iqp.product_id = iqps.product_id";

		if($product != '')

		{

			$where .= " AND iqp.product LIKE '%" . $product . "%'";

		}

		if($company_id != 0)

		{

			$where .= " AND iqc.company_id IN (" . $company_id . ")";

		}

		if($brand_id != 0)

		{

			$where .= " AND iqb.id IN (" . $brand_id . ")";

		}

		

		$DB->query("SET SQL_BIG_SELECTS=1");  //Set it before your main query

		if($start == 0 && $limit == 0)

		{

			$select 	= 	"SELECT count(*) as rowCount FROM `inv_qne_products` iqp " . $join . " WHERE 1 = 1 " . $where . " GROUP BY iqps.product_id ORDER BY iqp.product ASC";

			$conn		= 	$DB->query($select);

			return mysqli_num_rows($conn);

			/*if(mysqli_num_rows($conn) > 0)

			{

				$fetch = mysqli_fetch_object($conn);

				return $fetch->rowCount;

			}	*/

		}

		else

		{

			$select 	= 	"SELECT iqp.product_id, iqp.product, iqp.description, iqc.company_id, iqc.company, iqb.id as brand_id, iqb.title as brand, iqp.category, iqp.batchable, iqp.is_virtual, iqp.product_type, iqp.tax_type, iqp.status, iqp.datetime, count(iqps.product_id) as sku_count FROM `inv_qne_products` iqp " . $join . " WHERE 1 = 1 " . $where . " GROUP BY iqps.product_id ORDER BY iqp.product ASC LIMIT " . $start . ", " . $limit;

			$conn		= 	$DB->query($select);

			

			if(mysqli_num_rows($conn) > 0)

			{

				$products 	= 	array();

				$c			=	0;

				while($fetch = mysqli_fetch_object($conn))

				{	

					$products[$c]					=	new Product();

					$products[$c]->product_id		=	$fetch->product_id;

					$products[$c]->product			=	$fetch->product;

					$products[$c]->description		=	$fetch->description;

					$products[$c]->company_id		=	$fetch->company_id;

					$products[$c]->company			=	$fetch->company;

					$products[$c]->brand_id			=	$fetch->brand_id;

					$products[$c]->brand			=	$fetch->brand;

					$products[$c]->category			=	$fetch->category;

					$products[$c]->batchable		=	$fetch->batchable;

					$products[$c]->is_virtual		=	$fetch->is_virtual;

					$products[$c]->product_type		=	$fetch->product_type;

					$products[$c]->tax_type			=	$fetch->tax_type;

					$products[$c]->status			=	$fetch->status;

					$products[$c]->datetime			=	$fetch->datetime;

					$products[$c]->sku_count		=	$fetch->sku_count;

					$c++;

				}

				return $products;

			}

		}

	}

	

	public function allProducts($product_id=0, $company_id=0, $brand_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		//$join  = " LEFT JOIN `inv_qne_brand` iqb ON iqp.brand = iqb.brand_id LEFT JOIN `inv_qne_company` iqc ON iqp.company = iqc.company_id LEFT JOIN `inv_qne_product_sku` iqps ON iqp.product_id = iqps.product_id";

		$join  = " LEFT JOIN `inv_qne_brand` iqb ON iqp.brand = iqb.brand_id LEFT JOIN `inv_qne_company` iqc ON iqp.company = iqc.company_id LEFT JOIN `inv_qne_product_sku` iqps ON iqp.product_id = iqps.product_id LEFT JOIN `inv_qne_tax` iqt ON iqp.tax_type = iqt.tax_id";

		if($product_id != 0)

		{

			$where .= " AND iqp.product_id = " . $product_id;

		}

		if($company_id != 0)

		{

			$where .= " AND iqc.company_id IN (" . $company_id . ")";

		}

		if($brand_id != 0)

		{

			//$where .= " AND iqb.brand_id IN (" . $brand_id . ")";

			$where .= " AND iqb.brand_id IN (" . $brand_id . ")";

		}

		

		$DB->query("SET SQL_BIG_SELECTS=1");  //Set it before your main query

		$select 	= 	"SELECT iqp.product_id, iqp.product, iqp.description, iqc.company_id, iqc.company, iqb.brand_id as brand_id, iqb.brand as brand, iqp.category, iqp.batchable, iqp.is_virtual, iqp.product_type, iqp.tax_type, iqt.tax, iqp.status, iqp.datetime, count(iqps.product_id) as sku_count FROM `inv_qne_products` iqp " . $join . " WHERE 1 = 1 " . $where . " GROUP BY iqps.product_id ORDER BY iqp.product ASC LIMIT 0,10";

		//echo $select;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$products 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$products[$c]					=	new Product();

				$products[$c]->product_id		=	$fetch->product_id;

				$products[$c]->product			=	$fetch->product;

				$products[$c]->description		=	$fetch->description;

				$products[$c]->company_id		=	$fetch->company_id;

				$products[$c]->company			=	$fetch->company;

				$products[$c]->brand_id			=	$fetch->brand_id;

				$products[$c]->brand			=	$fetch->brand;

				$products[$c]->category			=	$fetch->category;

				$products[$c]->batchable		=	$fetch->batchable;

				$products[$c]->is_virtual		=	$fetch->is_virtual;

				$products[$c]->product_type		=	$fetch->product_type;

				$products[$c]->tax_type			=	$fetch->tax_type;
                
                $products[$c]->tax			   =	$fetch->tax;

				$products[$c]->status			=	$fetch->status;

				$products[$c]->datetime			=	$fetch->datetime;

				$products[$c]->sku_count		=	$fetch->sku_count;
                
				$c++;

			}

			return $products;

		}

	}

	

	public function productByID($product_id=0)

	{

		$DB			= 	new DB_connection();

		

		if($product_id != 0)

		{

			$where .= " iqp.product_id = " . $product_id;

		}

		

		$DB->query("SET SQL_BIG_SELECTS=1");  //Set it before your main query

		$select 	= 	"SELECT iqp.product_id, iqp.product, iqp.description, iqp.category, iqp.batchable, iqp.is_virtual, iqp.product_type, iqp.tax_type, iqp.status, iqp.datetime FROM `inv_qne_products` iqp WHERE " . $where . " ORDER BY iqp.product ASC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch = mysqli_fetch_object($conn);

			

			$this->product_id		=	$fetch->product_id;

			$this->product			=	$fetch->product;

			$this->description		=	$fetch->description;

			$this->category			=	$fetch->category;

			$this->batchable		=	$fetch->batchable;

			$this->is_virtual		=	$fetch->is_virtual;

			$this->product_type		=	$fetch->product_type;

			$this->tax_type			=	$fetch->tax_type;

			$this->status			=	$fetch->status;

			$this->datetime			=	$fetch->datetime;

			

		}

	}

	

	public function allProductsByDate($product_id=0, $company_id=0, $brand_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		$join  = " LEFT JOIN `inv_qne_brand` iqb ON iqp.brand = iqb.brand_id LEFT JOIN `inv_qne_company` iqc ON iqp.company = iqc.company_id LEFT JOIN `inv_qne_product_sku` iqps ON iqp.product_id = iqps.product_id";	

		if($product_id != 0)

		{

			$where .= " AND iqp.product_id = " . $product_id;

		}

		if($company_id != 0)

		{

			$where .= " AND iqc.company_id IN (" . $company_id . ")";

		}

		if($brand_id != 0)

		{

			$where .= " AND iqb.brand_id IN (" . $brand_id . ")";

		}

		

		$DB->query("SET SQL_BIG_SELECTS=1");  //Set it before your main query

		$select 	= 	"SELECT iqp.product_id, iqp.product, iqp.description, iqc.company_id, iqc.company, iqb.brand_id, iqb.brand, iqp.category, iqp.batchable, iqp.is_virtual, iqp.product_type, iqp.tax_type, iqp.status, iqp.datetime, count(iqps.product_id) as sku_count FROM `inv_qne_products` iqp " . $join . " WHERE 1 = 1 " . $where . " GROUP BY iqps.product_id ORDER BY iqp.product ASC";

		//echo $select;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$products 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$products[$c]					=	new Product();

				$products[$c]->product_id		=	$fetch->product_id;

				$products[$c]->product			=	$fetch->product;

				$products[$c]->description		=	$fetch->description;

				$products[$c]->company_id		=	$fetch->company_id;

				$products[$c]->company			=	$fetch->company;

				$products[$c]->brand_id			=	$fetch->brand_id;

				$products[$c]->brand			=	$fetch->brand;

				$products[$c]->category			=	$fetch->category;

				$products[$c]->batchable		=	$fetch->batchable;

				$products[$c]->is_virtual		=	$fetch->is_virtual;

				$products[$c]->product_type		=	$fetch->product_type;

				$products[$c]->tax_type			=	$fetch->tax_type;

				$products[$c]->status			=	$fetch->status;

				$products[$c]->datetime			=	$fetch->datetime;

				$products[$c]->sku_count		=	$fetch->sku_count;

				$c++;

			}

			return $products;

		}

	}

	

	public function productSKUCount($product_id)

	{

		$DB			= 	new DB_connection();



		$select 	= 	"SELECT count(*) as countee FROM `inv_qne_product_sku` WHERE 1 = 1 AND `product_id` = " . $product_id;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch	=	mysqli_fetch_object($conn);

			

			return $fetch->countee;

		}

		else

		{

			return 0;

		}

	}

	

	public function getProductStockDetails($product_id)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		

		if($product_id != 0)

		{

			$where .= " AND p.productid = " . $product_id;

			

		}

		$DB->query("SET SQL_BIG_SELECTS=1"); 

		$select 	= 	"SELECT p.productname, pa.* FROM `product` p JOIN `product_attributes` pa ON p.productid = pa.productid WHERE 1 = 1 " . $where . " ORDER BY id ASC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$productSKU 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$productSKU[$c]						=	new Product();

				$productSKU[$c]->productname		=	$fetch->productname;

				$productSKU[$c]->productid			=	$fetch->productid;

				$productSKU[$c]->sku_id				=	$fetch->id;

				$productSKU[$c]->attribute_title	=	$fetch->attribute_title;

				$productSKU[$c]->attribute_code		=	$fetch->attribute_code;

				$productSKU[$c]->stock_qty			=	$fetch->stock_qty;

				$productSKU[$c]->mrp_price			=	$fetch->price_without_discount;

				$productSKU[$c]->price_final		=	$fetch->price_final;

				

				$c++;

			}

			return $productSKU;

		}

	}

    public function productsByBrand($brand_id)
    {
		$DB			= 	new DB_connection();

        $where = "";

        if($brand_id != 0)
		{
			$where .= " WHERE `brand` = " . $brand_id;
		}

        //$DB->query("SET SQL_BIG_SELECTS=1"); 
		echo $select 	= 	"SELECT * FROM `inv_qne_products` " . $where . " ORDER BY product ASC";
		$conn		= 	$DB->query($select);

        if(mysqli_num_rows($conn) > 0)
		{
			$productSKU 	= 	array();
			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))
			{	
				$productSKU[$c]						=	new Product();
				$productSKU[$c]->product_id			=	$fetch->product_id;
				$productSKU[$c]->product			=	$fetch->product;
				$productSKU[$c]->company			=	$fetch->company;
				$productSKU[$c]->brand			    =	$fetch->brand;
				$productSKU[$c]->tax_type			=	$fetch->tax_type;
                $productSKU[$c]->is_virtual			=	$fetch->is_virtual;
                $productSKU[$c]->status				=	$fetch->status;
				
				$c++;
			}
			return $productSKU;
		}
    }

	public function productSKUs($product_id)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		

		if($product_id != 0)

		{

			$where .= " AND iqps.product_id = " . $product_id;

			//$where .= " AND iqpp.status = 1";

			$where2 .= " AND product_id = " . $product_id;

		}

		//SELECT * FROM (SELECT * FROM `inv_qne_purchase_order` WHERE `status` != 'Close' ORDER BY purchase_id DESC) `inv_qne_purchase_order` GROUP BY po_number

		$DB->query("SET SQL_BIG_SELECTS=1"); 

		$select 	= 	"SELECT iqps.*, iqpp.distributor_id, iqpp.cost_price, iqpp.cost_price_tax, iqpp.retail_price, iqpp.retail_price_tax, iqp.qty, iqp.sold, iqp.available, iqp.hold  FROM `inv_qne_product_sku` iqps LEFT JOIN `inv_qne_product_price` iqpp ON iqps.sku_id = iqpp.sku_id LEFT JOIN `inv_qne_product_stock` iqp ON iqps.sku_id = iqp.sku_id WHERE 1 = 1 " . $where . " GROUP BY iqps.sku_id ORDER BY iqps.sku_id DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$productSKU 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$productSKU[$c]						=	new Product();

				$productSKU[$c]->sku_id				=	$fetch->sku_id;

				$productSKU[$c]->product_id			=	$fetch->product_id;

				$productSKU[$c]->sku_title			=	$fetch->sku_title;

				$productSKU[$c]->sku_code			=	$fetch->sku_code;

				$productSKU[$c]->sku_image			=	$fetch->sku_image;

				$productSKU[$c]->threshold			=	$fetch->threshold;

				$productSKU[$c]->shelf_life			=	$fetch->shelf_life;

				$productSKU[$c]->carton_size		=	$fetch->carton_size;

				$productSKU[$c]->re_order_level		=	$fetch->re_order_level;

				$productSKU[$c]->status				=	$fetch->status;

				$productSKU[$c]->datetime			=	$fetch->datetime;

				

				$productSKU[$c]->distributor_id		=	$fetch->distributor_id;

				$productSKU[$c]->cost_price			=	$fetch->cost_price;

				$productSKU[$c]->cost_price_tax		=	$fetch->cost_price_tax;

				$productSKU[$c]->retail_price		=	$fetch->retail_price;

				$productSKU[$c]->retail_price_tax	=	$fetch->retail_price_tax;

				

				$productSKU[$c]->qty				=	$fetch->qty;

				$productSKU[$c]->sold				=	$fetch->sold;

				$productSKU[$c]->available			=	$fetch->available;

				$productSKU[$c]->hold				=	$fetch->hold;

				$c++;

			}

			return $productSKU;

		}

	}

	

	public function distributorPriceBySKU($sku_id)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		

		if($sku_id != 0)

		{

			$where .= " AND iqpp.sku_id = " . $sku_id;

		}

		$DB->query("SET SQL_BIG_SELECTS=1"); 

		$select 	= 	"SELECT iqpp.distributor_id, iqpp.cost_price, iqpp.cost_price_tax, iqpp.retail_price, iqpp.retail_price_tax, iqd.distributor FROM `inv_qne_product_price` iqpp RIGHT JOIN `inv_qne_distributor` iqd ON iqpp.distributor_id = iqd.distributor_id WHERE 1 = 1 " . $where . "";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$productSKU 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$productSKU[$c]						=	new Product();

				

				$productSKU[$c]->distributor_id		=	$fetch->distributor_id;

				$productSKU[$c]->cost_price			=	$fetch->cost_price;

				$productSKU[$c]->cost_price_tax		=	$fetch->cost_price_tax;

				$productSKU[$c]->retail_price		=	$fetch->retail_price;

				$productSKU[$c]->retail_price_tax	=	$fetch->retail_price_tax;

				$productSKU[$c]->distributor		=	$fetch->distributor;

				$c++;

			}

			return $productSKU;

		}

	}

	

	public function productSKUsWODistributor($product_id)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		

		if($product_id != 0)

		{

			$where .= " AND iqps.product_id = " . $product_id;

			$where2 .= " AND product_id = " . $product_id;

		}



		$DB->query("SET SQL_BIG_SELECTS=1"); 

		$select 	= 	"SELECT iqps.*, iqp.qty, iqp.sold, iqp.available, iqp.hold  FROM `inv_qne_product_sku` iqps LEFT JOIN `inv_qne_product_stock` iqp ON iqps.sku_id = iqp.sku_id WHERE 1 = 1 " . $where . " ORDER BY iqps.sku_id DESC";

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$productSKU 	= 	array();

			$c			=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$productSKU[$c]						=	new Product();

				$productSKU[$c]->sku_id				=	$fetch->sku_id;

				$productSKU[$c]->product_id			=	$fetch->product_id;

				$productSKU[$c]->sku_title			=	$fetch->sku_title;

				$productSKU[$c]->sku_code			=	$fetch->sku_code;

				$productSKU[$c]->sku_image			=	$fetch->sku_image;

				$productSKU[$c]->threshold			=	$fetch->threshold;

				$productSKU[$c]->shelf_life			=	$fetch->shelf_life;

				$productSKU[$c]->carton_size		=	$fetch->carton_size;

				$productSKU[$c]->re_order_level		=	$fetch->re_order_level;

				$productSKU[$c]->status				=	$fetch->status;

				$productSKU[$c]->datetime			=	$fetch->datetime;



				$productSKU[$c]->qty				=	$fetch->qty;

				$productSKU[$c]->sold				=	$fetch->sold;

				$productSKU[$c]->available			=	$fetch->available;

				$productSKU[$c]->hold				=	$fetch->hold;

				$c++;

			}

			return $productSKU;

		}

	}

	

	public function skuDetailById($sku_id)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		

		if($sku_id != 0)

		{

			$where .= " iqps.sku_id = " . $sku_id;

		}

		$select 	= 	"SELECT * FROM `inv_qne_product_sku` iqps WHERE " . $where;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch = mysqli_fetch_object($conn);

			

			$this->sku_id			=	$fetch->sku_id;

			$this->product_id		=	$fetch->product_id;

			$this->sku_title		=	$fetch->sku_title;

			$this->sku_code			=	$fetch->sku_code;

			$this->sku_image		=	$fetch->sku_image;

			$this->threshold		=	$fetch->threshold;

			$this->shelf_life		=	$fetch->shelf_life;

			$this->carton_size		=	$fetch->carton_size;

			$this->re_order_level	=	$fetch->re_order_level;

			$this->status			=	$fetch->status;

			$this->datetime			=	$fetch->datetime;

		}

	}
    
    public function latestProductPrice($sku_id)
	{
		$DB			= 	new DB_connection();

        $where = "";

        if($sku_id != 0)
		{
			$where .= " pp.sku_id = " . $sku_id;
		}

        $select 	= 	"SELECT * FROM `inv_qne_product_price` pp WHERE " . $where . " ORDER BY price_id DESC";
		$conn		= 	$DB->query($select);

        if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			$this->sku_id			=	$fetch->sku_id;
			$this->product_id		=	$fetch->product_id;
			$this->cost_price	    =	$fetch->cost_price;
            $this->cost_price_tax	=	$fetch->cost_price_tax;
            $this->qne_discount		=	$fetch->qne_discount;
			$this->company_discount	=	$fetch->company_discount;
			$this->retail_price		=	$fetch->retail_price;
			$this->retail_price_tax	=	$fetch->retail_price_tax;
		}
	}

	

	public function productSkuById($sku_id)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		

		if($sku_id != 0)

		{

			$where .= " AND iqps.sku_id = " . $sku_id;

		}

		$select 	= 	"SELECT * FROM `inv_qne_product_sku` iqps LEFT JOIN `inv_qne_product_price` iqpp ON iqps.sku_id = iqpp.sku_id LEFT JOIN `inv_qne_product_stock` iqp ON iqps.sku_id = iqp.sku_id WHERE 1 = 1 " . $where;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch = mysqli_fetch_object($conn);

			

			$this->sku_id			=	$fetch->sku_id;

			$this->product_id		=	$fetch->product_id;

			$this->sku_title		=	$fetch->sku_title;

			$this->sku_code			=	$fetch->sku_code;

			$this->sku_image		=	$fetch->sku_image;

			$this->threshold		=	$fetch->threshold;

			$this->shelf_life		=	$fetch->shelf_life;

			$this->carton_size		=	$fetch->carton_size;

			$this->re_order_level	=	$fetch->re_order_level;

			$this->status			=	$fetch->status;

			$this->datetime			=	$fetch->datetime;

			

			$this->cost_price		=	$fetch->cost_price;

			$this->cost_price_tax	=	$fetch->cost_price_tax;
            
            $this->qne_discount		=	$fetch->qne_discount;

			$this->company_discount	=	$fetch->company_discount;

			$this->retail_price		=	$fetch->retail_price;

			$this->retail_price_tax	=	$fetch->retail_price_tax;

			

			$this->qty				=	$fetch->qty;

			$this->sold				=	$fetch->sold;

			$this->available		=	$fetch->available;

			$this->hold				=	$fetch->hold;

		}

	}

    public function productAttrById($sku_id)
	{
		$DB       = new DB_connection();
		$where    = "";

        $select   = "SELECT * FROM `product_attributes` WHERE id = " . $sku_id;
		$conn		= 	$DB->query($select);

		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			$this->sku_id			= $fetch->id;
			$this->product_id		= $fetch->productid;
			$this->sku_title		= $fetch->attribute_title;
			$this->sku_code			= $fetch->attribute_code;
			$this->sku_image		= $fetch->org_img;
			$this->thumbnail_img	= $fetch->thumbnail_img;
			$this->mrp_price	    = $fetch->price_without_discount;
            $this->qne_discount		= $fetch->qne_discount;
			$this->company_discount	= $fetch->company_discount;			
            $this->other_discount	= $fetch->other_discount;
			$this->price_final		= $fetch->price_final;
			$this->available		= $fetch->stock_qty;
			$this->hold				= $fetch->hold_qty;
		}
	}

    public function productGRNBySkuId($sku_id)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_grn_po_details` WHERE `sku_id` = " . $sku_id . " AND `status` != 'Close'";
		$conn		= 	$DB->query($select);
		$GRNSkus 	= 	array();

        if(mysqli_num_rows($conn) > 0)
		{
			$c			=	0;
			while($fetch = mysqli_fetch_object($conn))
			{
				$GRNSkus[$c]					=	new Product();
				$GRNSkus[$c]->id				=	$fetch->id;
				$GRNSkus[$c]->grn_po_id			=	$fetch->grn_po_id;
				$GRNSkus[$c]->product_id		=	$fetch->product_id;
				$GRNSkus[$c]->sku_id			=	$fetch->sku_id;
				$GRNSkus[$c]->qty				=	$fetch->qty;
				$GRNSkus[$c]->sold_qty			=	$fetch->sku_image;
				$GRNSkus[$c]->batch				=	$fetch->batch;
				$GRNSkus[$c]->expiry_date		=	$fetch->expiry_date;
				$GRNSkus[$c]->status			=	$fetch->status;
				$GRNSkus[$c]->added_date		=	$fetch->added_date;
				$c++;
			}
		}
		return $GRNSkus;
	}

    public function getGRNQty($id)

	{

		$DB			= 	new DB_connection();

		

		$select 	= 	"SELECT * FROM `inv_qne_grn_po_details` WHERE `id` = " . $id;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch = mysqli_fetch_object($conn);

			

			$this->id			=	$fetch->id;

			$this->grn_po_id	=	$fetch->grn_po_id;

			$this->product_id	=	$fetch->product_id;

			$this->sku_id		=	$fetch->sku_id;

			$this->qty			=	$fetch->qty;

			$this->sold_qty		=	$fetch->sold_qty;

			$this->net_total	=	$fetch->net_total;

		}

	}

	

	public function productType($type_id=0)
	{
		$DB			= 	new DB_connection();
		$productTypes 	= 	array();
		$c			=	0;
		$Where		=	'';

		if($type_id != 0)
		{
			 $Where .= " AND `product_type_id` = " . $type_id;
		}

		$select 	= 	"SELECT * FROM `inv_qne_product_type` WHERE 1 = 1" . $Where;
		$conn		= 	$DB->query($select);
		$row		=	mysqli_num_rows($conn);

		if($row > 0)
		{
			while ($fetch = mysqli_fetch_object($conn))
			{
				$productTypes[$c]					=	new Product();
				$productTypes[$c]->product_type_id	=	$fetch->product_type_id;
				$productTypes[$c]->type_id 	        =	$fetch->type_id ;
				$productTypes[$c]->product_type		=	$fetch->product_type;
				$productTypes[$c]->status			=	$fetch->status;
				$productTypes[$c]->date				=	$fetch->date;
				$c++;
			}	
		}
		return $productTypes;
	}
	
	public function addProductType()
	{
		$DB			= 	new DB_connection();
		extract($_POST);
		
		$product_type_name	    =   $mySQLi->real_escape_string($product_type_name);
        $product_type_status	=   (int) $mySQLi->real_escape_string($product_type_status);
		
		return $productTypes;
	}

	public function allParentCategories($category_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		$join  = "";



		$category 	= 	array();

		$c			=	0;

		$parent_id	=	0;

			

		do 

		{

			$select 	= 	"SELECT * FROM `inv_qne_category` iqc WHERE 1 = 1 AND category_id = " . $category_id;//exit();

			$conn		= 	$DB->query($select);

			$fetch 		= 	mysqli_fetch_object($conn);

			

			$category[$c]					=	new Product();

			$category[$c]->category_id		=	$fetch->category_id;

			$category[$c]->parent_id		=	$fetch->parent_id;

			$category[$c]->title			=	$fetch->title;

			$category[$c]->url_title		=	$fetch->url_title;

			$category_id					=	$fetch->parent_id;

			$parent_id						=	$fetch->parent_id;

			$c++;

		}

		while ($parent_id != 0);



		return array_reverse($category);

	}

	

	public function allDistributor($company_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where = "";

		$join  = "";

		

		if($company_id != 0)

		{

			$join  .= " JOIN `inv_qne_company_distributor` idc ON iqd.distributor_id = idc.distributor_id";	

			$where .= " AND company_id = " . $company_id;

		}

		$select 	= 	"SELECT * FROM `inv_qne_distributor` iqd " . $join . " WHERE 1 = 1 " . $where;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$distributors 	= 	array();

			$c				=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$distributors[$c]					=	new Product();

				$distributors[$c]->distributor_id	=	$fetch->distributor_id;

				$distributors[$c]->distributor		=	$fetch->distributor;

				$distributors[$c]->website			=	$fetch->website;

				$distributors[$c]->email			=	$fetch->email;

				$distributors[$c]->ntn_number		=	$fetch->ntn_number;

				$distributors[$c]->strn_number		=	$fetch->strn_number;

				$distributors[$c]->status			=	$fetch->status;

				$distributors[$c]->date				=	$fetch->date;

				$c++;

			}

			return $distributors;

		}

	}

	

	public function allTaxes($tax_id=0)

	{

		$DB			= 	new DB_connection();

		

		$where 		= 	'';

		if($tax_id != 0)

		{

			$where .=	" AND tax_id = " . $tax_id;

		}

		$select 	= 	"SELECT * FROM `inv_qne_tax` WHERE 1 = 1 " . $where;

		$conn		= 	$DB->query($select);

		

		if(mysqli_num_rows($conn) > 0)

		{

			$taxes 	= 	array();

			$c				=	0;

			while($fetch = mysqli_fetch_object($conn))

			{	

				$taxes[$c]				=	new Product();

				$taxes[$c]->tax_id		=	$fetch->tax_id;

				$taxes[$c]->tax			=	$fetch->tax;

				$taxes[$c]->tax_type    =	$fetch->tax_type;

				$taxes[$c]->tax_value	=	$fetch->tax_value;

				$taxes[$c]->from_date	=	$fetch->from_date;

				$taxes[$c]->to_date		=	$fetch->to_date;

				$taxes[$c]->status		=	$fetch->status;

				$taxes[$c]->added_date	=	$fetch->added_date;

				$c++;

			}

			return $taxes;

		}

	}

}

?>
