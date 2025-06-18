<?php

class Purchase 

{

	public function __construct()

	{

	}



	public function purchaseOrderDetails($po_id)

	{

		$DB		= new DB_connection();



		$select = "SELECT * FROM inv_qne_purchase_order po WHERE `purchase_id` = '" . $po_id . "'";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		$fetch	= mysqli_fetch_object($conn);



		if($rows > 0)

		{

			$this->purchase_id	=	$fetch->purchase_id;

			$this->po_number	=	$fetch->po_number;

			$this->version		=	$fetch->version;

			$this->po_date		=	$fetch->po_date;

			$this->distributor	=	$fetch->distributor;

			$this->brand_id		=	$fetch->brand_id;

			$this->company		=	$fetch->company;

			$this->brand		=	$fetch->brand;

			$this->description	=	$fetch->description;

			$this->total_amount	=	$fetch->total_amount;

			$this->status		=	$fetch->status;

			$this->datetime		=	$fetch->datetime;

		}

	}

	

	public function addInvoiceImage()

	{

		$DB		= new DB_connection();

		extract($_REQUEST);

		if($grn_id != 0 && is_numeric($grn_id))

		{

			$directory	= "assets/img/grn_invoices/";

          	$filename	= $_FILES["file"]['name'];

			$extension	= pathinfo($filename, PATHINFO_EXTENSION);

			$newFile	= "invoice_" . $grn_id . "_" . date('YmdHis') . "." . $extension;

			if(move_uploaded_file($_FILES['file']['tmp_name'], $directory . $newFile))

			{

				$insert = "INSERT INTO `inv_qne_grn_invoices`(`invoice_id`, `grn_id`, `image`, `datetime`) VALUES('', '" . $grn_id . "', '" . $newFile . "', '" . date('Y-m-d H:i:s') . "')"; //`grn_po_id` != 2 for OPENING NOT INCLUDE

				$conn	= $DB->query($insert);

			}

			else

			{

				//$insert = "INSERT INTO `inv_qne_grn_invoices`(`invoice_id`, `grn_id`, `image`, `datetime`) VALUES('', '" . $grn_id . "', '" . $newFile . "', '" . date('Y-m-d H:i:s') . "')"; //`grn_po_id` != 2 for OPENING NOT INCLUDE

			}

		}	

		else

		{

			//$select = "SELECT qty, net_total, discount, discount_type, mrp_price, sale_price FROM inv_qne_grn_po_details WHERE `sku_id` = '" . $sku_id . "' AND `product_type` = 'Normal' ORDER BY id DESC LIMIT 1";

		}

	}

	

	public function getGRNInvoices($grn_number)

	{

		$DB			= new DB_connection();

		$select 	= "SELECT * FROM `inv_qne_grn_invoices` WHERE grn_id = " . $grn_number;

		$conn		= $DB->query($select);

		$rows		= mysqli_num_rows($conn);

		$c 			= 0;

		$invoiceImg = array();

		

		if($rows > 0)

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$invoiceImg[$c]				=	new Purchase();

				$invoiceImg[$c]->invoice_id	=	$fetch->invoice_id;

				$invoiceImg[$c]->grn_id		=	$fetch->grn_id;

				$invoiceImg[$c]->image		=	$fetch->image;

				$invoiceImg[$c]->datetime	=	$fetch->datetime;

				$c++;

			}

			return $invoiceImg;	

		}

	}

	

	public function removeGRNInvoice($invoice_id)

	{

		$DB			= new DB_connection();

		$delete 	= "DELETE FROM `inv_qne_grn_invoices` WHERE invoice_id = " . $invoice_id;

		if($DB->query($delete))

		{

			return true;

		}

		else

		{

			return false;

		}		

	}

	

	public function lastGRNItemDetails($sku_id, $grn_number=0)

	{

		$DB		= new DB_connection();



		if($grn_number != 0)

		{

			$select = "SELECT id, grn_po_id, qty, sub_total, net_total, discount, discount_type, mrp_price, sale_price FROM inv_qne_grn_po_details WHERE `grn_po_id` < '" . $grn_number . "' AND `sku_id` = '" . $sku_id . "' AND `product_type` = 'Normal' ORDER BY id DESC LIMIT 1"; //`grn_po_id` != 2 for OPENING NOT INCLUDE

		}	

		else

		{

			$select = "SELECT id, grn_po_id, qty, sub_total, net_total, discount, discount_type, mrp_price, sale_price FROM inv_qne_grn_po_details WHERE `sku_id` = '" . $sku_id . "' AND `product_type` = 'Normal' ORDER BY id DESC LIMIT 1";

		}

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

        if($rows > 0)
		{
			$fetch	= mysqli_fetch_object($conn);
            $this->grnItemID	= $fetch->id;
            $this->grn_po_id	= $fetch->grn_po_id;
            if($fetch->discount_type == 'percent')
			{
				$this->net_discount		=	$fetch->discount . " %";
                
                if($fetch->sub_total * $fetch->discount > 0)
                {
                    $discountAmount     = ($fetch->sub_total * $fetch->discount) / 100;
                }
                else
                {
                    $discountAmount     = 0;
                }
			}
			else
			{
				$this->net_discount		= $fetch->discount / $fetch->qty;
                $discountAmount         = $fetch->discount;
			}
			$this->net_tp				= $fetch->net_total / $fetch->qty;
			$gst_value                  = $fetch->sub_total - $discountAmount;  
            
            if($gst_value > 0)
            {
                $this->gst_value        = $gst_value / $fetch->qty;
            }
            else
            {
                $this->gst_value        = 0;
            }
			$this->mrp_price			=	$fetch->mrp_price;
			$this->sale_price			=	$fetch->sale_price;
		}
		else
		{
			$this->net_discount		=	"-";
			$this->net_tp			=	"-";
            $this->gst_value        =   0;
			$this->mrp_price		=	0;
			$this->sale_price		=	0;
		}
	}

    public function last3GRNDetails($sku_id)
	{
		$DB		= new DB_connection();

        $select = "SELECT id, grn_po_id, qty, sub_total, net_total, discount, discount_type, mrp_price, sale_price FROM inv_qne_grn_po_details WHERE `sku_id` = '" . $sku_id . "' AND `product_type` = 'Normal' ORDER BY id DESC LIMIT 3";
		$conn	= $DB->query($select);
		$rows	= mysqli_num_rows($conn);

        $c = 0;
        $POs = array();

        if($rows > 0)
		{
			while($fetch = mysqli_fetch_object($conn))
			{
				$POs[$c]    = new Purchase();
                
				$POs[$c]->grnItemID	= $fetch->id;
                $POs[$c]->grn_po_id	= $fetch->grn_po_id;
                if($fetch->discount_type == 'percent')
                {
                    $POs[$c]->net_discount	= $fetch->discount . " %";

                    if($fetch->sub_total * $fetch->discount > 0)
                    {
                        $discountAmount     = ($fetch->sub_total * $fetch->discount) / 100;
                    }
                    else
                    {
                        $discountAmount     = 0;
                    }
                }
                else
                {
                    $POs[$c]->net_discount	= $fetch->discount / $fetch->qty;
                    $discountAmount         = $fetch->discount;
                }
                $POs[$c]->net_tp			= $fetch->net_total / $fetch->qty;
                $gst_value                  = $fetch->sub_total - $discountAmount;  

                if($gst_value > 0)
                {
                    $POs[$c]->gst_value     = $gst_value / $fetch->qty;
                }
                else
                {
                    $POs[$c]->gst_value     = 0;
                }
                $POs[$c]->mrp_price			= $fetch->mrp_price;
                $POs[$c]->sale_price		= $fetch->sale_price;
				$c++;
			}
			return $POs;	
		}
	}

    public function currentGRNItemDetails($sku_id, $grn_number=0)

	{

		$DB		= new DB_connection();



		if($grn_number != 0)

		{

			$select = "SELECT id, grn_po_id, qty, net_total, discount, discount_type, mrp_price, sale_price FROM inv_qne_grn_po_details WHERE `grn_po_id` = '" . $grn_number . "' AND `sku_id` = '" . $sku_id . "' AND `product_type` = 'Normal' ORDER BY id DESC LIMIT 1"; //`grn_po_id` != 2 for OPENING NOT INCLUDE

		}	

		else

		{

			$select = "SELECT id, grn_po_id, qty, net_total, discount, discount_type, mrp_price, sale_price FROM inv_qne_grn_po_details WHERE `sku_id` = '" . $sku_id . "' AND `product_type` = 'Normal' ORDER BY id DESC LIMIT 1";

		}

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);



		if($rows > 0)

		{

			$fetch	= mysqli_fetch_object($conn);
            
            $this->id	          = $fetch->id;
            $this->grn_po_id	= $fetch->grn_po_id;    
			

			if($fetch->discount_type == 'percent')

			{

				$this->net_discount		=	$fetch->discount . " %";

			}

			else

			{

				$this->net_discount		=	$fetch->discount / $fetch->qty;

			}

			$this->net_tp				=	$fetch->net_total / $fetch->qty;

			

			$this->mrp_price			=	$fetch->mrp_price;

			$this->sale_price			=	$fetch->sale_price;

		}

		else

		{

			$this->net_discount		=	"-";

			$this->net_tp			=	"-";

			$this->mrp_price		=	0;

			$this->sale_price		=	0;

		}

	}

	

	public function lastMonthSale($sku_id, $days=30)

	{

		$DB		= new DB_connection();

		

		$nDate  = date('Y-m-d', strtotime('-' . $days . ' days'));

		

		$select = "SELECT sum(prd_qty) as Qty FROM z_orders_detail zod JOIN z_orders zo ON zod.ord_id = zo.order_id WHERE `size_id` = '" . $sku_id . "' AND `date_` >= '" . $nDate . "' AND `ord_status` IN (5,6,9) AND `prod_type` = 'normal'";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		if($rows > 0)

		{

			$fetch	= mysqli_fetch_object($conn);

			$Qty	=	$fetch->Qty;

		}

		else

		{

			$Qty	=	0;

		}

		

		//$select = "SELECT sum(prd_qty) as Qty FROM z_orders_detail zod JOIN z_orders zo ON zod.ord_id = zo.order_id WHERE `size_id` = '" . $sku_id . "' AND `date_` >= '" . $nDate . "' AND `ord_status` IN (5,9) AND `prod_type` = 'normal'";

		$select3 = "SELECT sum(offer_sku_qty * prd_qty) as Qty FROM product_offers_details pod JOIN z_orders_detail zod ON pod.offer_id = zod.prd_id JOIN z_orders zo ON zod.ord_id = zo.order_id WHERE pod.sku_id = '" . $sku_id . "' AND `date_` >= '" . $nDate . "' AND `ord_status` IN (5,6,9) AND `prod_type` = 'bundle'";

		$conn3	= $DB->query($select3);

		$rows3	= mysqli_num_rows($conn3);

		if($rows3 > 0)

		{

			$fetch3	= mysqli_fetch_object($conn3);

			$Qty3	=	$fetch3->Qty;

		}

		else

		{

			$Qty3	=	0;

		}

		

		

		$select = "SELECT sum(sku_qty) as Qty FROM z_orders_offer_details zod JOIN z_orders zo ON zod.orderId = zo.order_id WHERE `stock_sku_id` = '" . $sku_id . "' AND `date_` >= '" . $nDate . "' AND `ord_status` IN (5,6,9)";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		if($rows > 0)

		{

			$fetch	= mysqli_fetch_object($conn);

			$Qty2	=	$fetch->Qty;

		}

		else

		{

			$Qty2	=	0;

		}

		

		$total = $Qty + $Qty2 + $Qty3;

		return $total;	 

	}

	

	public function latestSale($sku_id, $poDate)

	{

		$DB		= new DB_connection();

		

		$nDate  	= $poDate;//date('Y-m-d', strtotime('-' . $days . ' days'));

		$normaldate	= "0000-00-00";

		$bundleDate = "0000-00-00";

		$mixDate	= "0000-00-00";

		

		$select = "SELECT zo.date_ FROM z_orders_detail zod JOIN z_orders zo ON zod.ord_id = zo.order_id WHERE `size_id` = '" . $sku_id . "' AND `date_` <= '" . $nDate . "' AND `ord_status` IN (6,9) AND `prod_type` = 'normal' ORDER BY zo.date_ DESC LIMIT 1";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		if($rows > 0)

		{

			$fetch		= mysqli_fetch_object($conn);

			$normaldate	= $fetch->date_;

		}

		

		$select3 = "SELECT zo.date_ FROM product_offers_details pod JOIN z_orders_detail zod ON pod.offer_id = zod.prd_id JOIN z_orders zo ON zod.ord_id = zo.order_id WHERE pod.sku_id = '" . $sku_id . "' AND `date_` <= '" . $nDate . "' AND `ord_status` IN (6,9) AND `prod_type` = 'bundle' ORDER BY zo.date_ DESC LIMIT 1";

		$conn3	= $DB->query($select3);

		$rows3	= mysqli_num_rows($conn3);

		if($rows3 > 0)

		{

			$fetch3		= mysqli_fetch_object($conn3);

			$bundleDate	= $fetch->date_;

		}

		

		$select = "SELECT zo.date_ FROM z_orders_offer_details zod JOIN z_orders zo ON zod.orderId = zo.order_id WHERE `stock_sku_id` = '" . $sku_id . "' AND `date_` <= '" . $nDate . "' AND `ord_status` IN (6,9) ORDER BY zo.date_ DESC LIMIT 1";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		if($rows > 0)

		{

			$fetch		= mysqli_fetch_object($conn);

			$mixDate	= $fetch->date_;

		}

		

		if(strtotime($normaldate) >= strtotime($bundleDate))

		{

			if(strtotime($normaldate) >= strtotime($mixDate))

			{

				return $normaldate;

			}

			else

			{

				return $mixDate;

			}

		}

		else

		if(strtotime($normaldate) < strtotime($bundleDate))

		{

			return $bundleDate;

		}

		else

		{

			return $mixDate;

		}

		//$total = $Qty + $Qty2 + $Qty3;

		//return $total;	 

	}



	public function openPurchaseOrders()

	{

		$DB		= new DB_connection();



		$select = "SELECT * FROM (SELECT * FROM `inv_qne_purchase_order` WHERE `status` != 'Close' AND `status` != 'Pending' ORDER BY purchase_id DESC) `inv_qne_purchase_order` GROUP BY po_number";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		

		$c = 0;

		$POs = array();

		

		if($rows > 0)

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$POs[$c]					=	new Purchase();

				$POs[$c]->purchase_id		=	$fetch->purchase_id;

				$POs[$c]->po_number			=	$fetch->po_number;

				$POs[$c]->version			=	$fetch->version;

				$POs[$c]->po_date			=	$fetch->po_date;

				$POs[$c]->distributor		=	$fetch->distributor;

				$POs[$c]->distributor_depo	=	$fetch->distributor_depo;

				$POs[$c]->company			=	$fetch->company;

				$POs[$c]->brand				=	$fetch->brand;

				$POs[$c]->description		=	$fetch->description;

				$POs[$c]->total_amount		=	$fetch->total_amount;

				$POs[$c]->status			=	$fetch->status;

				$POs[$c]->datetime			=	$fetch->datetime;

				$c++;

			}

			return $POs;	

		}

	}

	

	public function purchaseOrders($ids='', $status='', $start=0, $end=0)

	{

		$DB		= new DB_connection();



		$Where	= "";

		$limit  = "";

		

		if($status != '')

		{

			$Where	=	" AND `status` IN ('" . $status . "')";

		}

		

		if($ids != '')

		{

			$Where	.=	" AND `purchase_id` IN (" . $ids . ")";

		}

		

		if($start == 0 && $end == 0)

		{

			$select = "SELECT * FROM `inv_qne_purchase_order` WHERE 1 = 1 " . $Where . " GROUP BY po_number ORDER BY `purchase_id` DESC";

		}

		else

		{

			$limit	=	" LIMIT " . $start . ", " . $end;

			$select = "SELECT * FROM `inv_qne_purchase_order` WHERE 1 = 1 " . $Where . " GROUP BY po_number ORDER BY `purchase_id` DESC" . $limit;

		}

		//echo "<br />" . $select;

		//$select = "SELECT * FROM (SELECT * FROM `inv_qne_purchase_order` WHERE 1 = 1 " . $Where . " ORDER BY purchase_id DESC) `inv_qne_purchase_order` GROUP BY po_number ORDER BY `purchase_id` DESC" . $limit;

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		

		$c = 0;

		$POs = array();

		

		if($rows > 0)

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$POs[$c]					=	new Purchase();

				$POs[$c]->purchase_id		=	$fetch->purchase_id;

				$POs[$c]->po_number			=	$fetch->po_number;

				$POs[$c]->version			=	$fetch->version;

				$POs[$c]->po_date			=	$fetch->po_date;

				$POs[$c]->distributor		=	$fetch->distributor;

				$POs[$c]->distributor_depo	=	$fetch->distributor_depo;

				$POs[$c]->company			=	$fetch->company;

				$POs[$c]->brand				=	$fetch->brand;

				$POs[$c]->description		=	$fetch->description;

				$POs[$c]->total_amount		=	$fetch->total_amount;

				$POs[$c]->status			=	$fetch->status;

				$POs[$c]->datetime			=	$fetch->datetime;

				$c++;

			}

			return $POs;	

		}

	}

	

	public function pendingPurchaseOrders($start=0, $end=0)

	{

		$DB		= new DB_connection();



		$Where	= "";

		$limit  = "";

		

		$Where	=	" AND `status` != 'Close'";

		

		if($start == 0 && $end == 0)

		{

			//$select = "SELECT purchase_id FROM `inv_qne_purchase_order` WHERE 1 = 1 " . $Where . " GROUP BY po_number ORDER BY `purchase_id` DESC";

			$select = "SELECT purchase_id FROM `inv_qne_purchase_order` po JOIN `inv_qne_grn_po` gp ON po.purchase_id = gp.po_id WHERE 1 = 1 " . $Where . " GROUP BY po.po_number ORDER BY `purchase_id` DESC";

			

		}

		else

		{

			$limit	=	" LIMIT " . $start . ", " . $end;

			$select = "SELECT po.*, gp.grn_id FROM `inv_qne_purchase_order` po JOIN `inv_qne_grn_po` gp ON po.purchase_id = gp.po_id WHERE 1 = 1 " . $Where . " GROUP BY po.po_number ORDER BY `purchase_id` DESC" . $limit;

		}

		//echo "<br />" . $select;

		//$select = "SELECT * FROM (SELECT * FROM `inv_qne_purchase_order` WHERE 1 = 1 " . $Where . " ORDER BY purchase_id DESC) `inv_qne_purchase_order` GROUP BY po_number ORDER BY `purchase_id` DESC" . $limit;

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		

		$c = 0;

		$POs = array();

		

		if($rows > 0)

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$POs[$c]					=	new Purchase();

				$POs[$c]->purchase_id		=	$fetch->purchase_id;

				$POs[$c]->po_number			=	$fetch->po_number;

				$POs[$c]->version			=	$fetch->version;

				$POs[$c]->po_date			=	$fetch->po_date;

				$POs[$c]->distributor		=	$fetch->distributor;

				$POs[$c]->distributor_depo	=	$fetch->distributor_depo;

				$POs[$c]->company			=	$fetch->company;

				$POs[$c]->brand				=	$fetch->brand;

				$POs[$c]->description		=	$fetch->description;

				$POs[$c]->total_amount		=	$fetch->total_amount;

				$POs[$c]->status			=	$fetch->status;

				$POs[$c]->datetime			=	$fetch->datetime;

				

				$POs[$c]->grn_id			=	$fetch->grn_id;

				$c++;

			}

			return $POs;	

		}

	}

	

	public function forceClosePO($purchase_id, $po_number)

	{

		$DB		= new DB_connection();



		$select = "UPDATE `inv_qne_purchase_order` SET `status` = 'Close' WHERE `purchase_id` = '" . $purchase_id . "'";

		$DB->query($select);

		

		

		$insert = "INSERT INTO `inv_qne_purchase_order_force_close` (`id`, `purchase_id`, `po_number`, `status`, `user`, `datetime`) VALUES ('', '" . $purchase_id . "', '" . $po_number . "', 'Close', '" . $_SESSION['sess_username'] . "', '" . date('Y-m-d H:i:s') . "')";

		$DB->query($insert);

	}

	

	public function forceClose()

	{

		$DB		= new DB_connection();



		$select = "SELECT * FROM `inv_qne_purchase_order` WHERE `status` != 'Close' AND `status` != 'Pending' AND `datetime` <= '2016-10-31 23:59:59'";

		$conn   = $DB->query($select);

		$cnt    = 1;

		while($fetch = mysqli_fetch_object($conn))

		{	

			echo "<br />" . $cnt . ") Date = " . $fetch->datetime . ", Status = " . $fetch->status;

			$update = "UPDATE `inv_qne_purchase_order` SET `status` = 'Close' WHERE `purchase_id` = " . $fetch->purchase_id;

			//$DB->query($update);

			$insert = "INSERT INTO `inv_qne_purchase_order_force_close` (`id`, `purchase_id`, `po_number`, `status`, `user`, `datetime`) VALUES ('', '" . $fetch->purchase_id . "', '" . $fetch->po_number . "', 'Close', 'Superman', '" . date('Y-m-d H:i:s') . "')";

			//$DB->query($insert);

			$cnt++;

		}

	}

    public function purchaseOrderVersions($po_number=0)
	{
		$DB		= new DB_connection();
		$Where	= "";

        if($po_number != '')
		{
			$Where	=	" AND `po_number` = " . $po_number . "";
		}

        $select = "SELECT * FROM `inv_qne_purchase_order` WHERE 1=1 " . $Where . " ORDER BY purchase_id DESC";
		$conn	= $DB->query($select);
		$rows	= mysqli_num_rows($conn);
		$c = 0;
		$POs = array();

        if($rows > 0)
		{
			while($fetch = mysqli_fetch_object($conn))
			{
				$POs[$c]				= new Purchase();
				$POs[$c]->purchase_id	= $fetch->purchase_id;
				$POs[$c]->po_number		= $fetch->po_number;
				$POs[$c]->version		= $fetch->version;
				$POs[$c]->po_date		= $fetch->po_date;
				$POs[$c]->distributor	= $fetch->distributor;
				$POs[$c]->company		= $fetch->company;
				$POs[$c]->brand			= $fetch->brand;
				$POs[$c]->description	= $fetch->description;
				$POs[$c]->status		= $fetch->status;
				$POs[$c]->datetime		= $fetch->datetime;
				$c++;
			}
			return $POs;	
		}
	}

    function generatePO()
	{
		$conn 	= mysql_query("SELECT po_number FROM `inv_qne_purchase_order` ORDER BY po_number DESC LIMIT 1");
		$serNum = mysqli_num_rows($conn);
		if($serNum > 0)
		{
			$fet		= mysqli_fetch_object($conn);
			$po_number	= $fet->po_number;
			if(date('Ym') == substr($po_number,0,6))
			{
				$PONumber =	$fet->po_number + 1;
			}
			else
			{
				$PONumber = date('Y').date('m')."0001";
			}
		}
		else
		{
			$PONumber = date('Y').date('m')."0001";
		}
		return $PONumber;
	}
    
    public function encryptPassword($plainPass)
	{
		$newPass = array();
		for($i = 0; $i < strlen($plainPass); $i++)
		{
			array_push($newPass, ord($plainPass[$i]));
			array_push($newPass, ord(""));
		}		
		$encryptPass = "";
		foreach($newPass as $newP)
		{
			$encryptPass .= chr($newP);
		}
		return base64_encode($encryptPass);
	}

	public function decryptPassword($encPass)
	{
		$encPass 	= 	base64_decode($encPass);
		$plainPass 	= 	unpack("C*dPass",$encPass);
		$password	=	"";
		foreach($plainPass as $plainPas)
		{
			if($plainPas != 0)
			{
				$password .= chr($plainPas);
			}
		}
		return $password;
	}
    
    public function preApprovePO()
    {
        $DB = new DB_connection();
		extract($_GET);
        
        $pod   = $_GET['pod'];
        $po_id = $this->decryptPassword($pod);
		
        if(is_numeric($po_id))
        {
            $select   = "SELECT product_id, unit_price, order_qty, total_price FROM `inv_qne_purchase_order_items` WHERE `po_id` = " . $po_id;
            $conn     = $DB->query($select);
            $rows     = mysqli_num_rows($conn);
            if($rows > 0)
            {
                $totalAmt = 0;
                $totalQty = 0;
                while($fetch = mysqli_fetch_object($conn))
                {
                    $select_2   = "SELECT productid, categoryid, productname, detail_img FROM `product` WHERE `productid` = " . $fetch->product_id;
                    $conn_2	  = $DB->query($select_2);
                    $rows_2	  = mysqli_num_rows($conn_2);
                    $fetch_2  = mysqli_fetch_object($conn_2);
                    $poItems .= '<tr>
                                    <th style="border:1px solid #EEAF00 !important"><img src="https://inventory.thevintagebazar.com/' . $fetch_2->detail_img . '" width="100" alt="' . $fetch_2->productid . ' Image" /></th>
                                    <th style="border:1px solid #EEAF00 !important">' . $fetch_2->productname . '</th>
                                    <th style="border:1px solid #EEAF00 !important">Rs. ' . number_format($fetch->unit_price,2) . '</th>
                                    <th style="border:1px solid #EEAF00 !important">' . $fetch->order_qty . '</th>
                                    <th style="border:1px solid #EEAF00 !important">Rs. ' . number_format($fetch->total_price,2) . '</th>
                                </tr>';
                    $totalAmt+= $fetch->total_price;
                    $totalQty+= $fetch->order_qty;

                }
                $poItems .= '<tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</b></th>
                                    <th><b><a href="https://inventory.thevintagebazar.com/pre_approval_po.php?status=approval&type=secure&id=' . md5($po_id) . '">APPROVE PO!</a></b></th>
                                </tr>';

                $headers 	= "MIME-Version: 1.0\n";
                $headers   .= "Content-type: text/html; charset=iso-8859-1\n";
                $headers   .= "From:The Vintage Bazar<info@thevintagebazar.com>\n";
                $headers   .= "X-Mailer: PHP's mail() Function\n";
                $subject    = "New Purchase Order Initiate (PO # " . $po_number . ") at The Vintage Bazar";
                $email      = "info@thevintagebazar.com";
                $body       = file_get_contents("../template/email/purchase_order.html");
                $find       = array("PO_NUMBER", "CREATED_DATE", "CREATE_BY", "PURCHASE_ORDER_ITEMS");
                $replace    = array($po_number, date('d M, Y'), $_SESSION['sess_username'], $poItems);
                $body       = str_replace($find, $replace, $body);

                mail($email, $subject, $body, $headers);
                return $po_id; 
            }
        }
    }

    public function addPurchaseOrder($post)
	{
		$DB       = new DB_connection();
		extract($post);
		$po_number=$this->generatePO();
        $purchase = "INSERT INTO `inv_qne_purchase_order`(`purchase_id`, `po_number`, `version`, `po_date`, `distributor`, `distributor_depo`, `warehouse`, `company`, `brand`, `description`, `status`, `datetime`)VALUES('', '" . $po_number . "', '" . mysql_real_escape_string($version) . "', '" . mysql_real_escape_string($po_date) . "', '" . mysql_real_escape_string($distributor) . "', '" . mysql_real_escape_string($depo) . "', '" . mysql_real_escape_string($warehouse) . "', '" . mysql_real_escape_string($company) . "', '" . mysql_real_escape_string($brand) . "', '" . mysql_real_escape_string($description) . "', 'Pending', '" . date('Y-m-d H:i:s') . "')";
		$purchase	= 	$DB->query($purchase);
		$po_id		= 	mysql_insert_id();

		if(sizeof($post['product_sku_id']))
		{
			$product_sku_id	= $post['product_sku_id'];
			$k       = 0;
			$i       = 0;
			$total   = 0;
			$totalAmt= 0;
            $totalQty= 0;
            $poItems = '';
            $catArr  = array();
            $catItem = array();
            foreach($product_sku_id as $sku_id)
			{
				if(is_numeric($product_qty[$k]) && $product_qty[$k] > 0)
				{
					$un_price      = explode("_", $unit_price[$k]);
					$u_price	   = $un_price[0];
					$u_price_tax   = $un_price[1];
					$tot_price	   = $u_price_tax * $product_qty[$k];
					$totalAmt	  += $tot_price;
                    $totalQty     += $product_qty[$k];

                    $purchaseItems = "INSERT INTO `inv_qne_purchase_order_items`(`po_item_id`, `po_id`, `product_id`, `product_sku_id`, `order_qty`, `received_qty`, `unit_price`, `unit_price_tax`, `total_price`, `date`)VALUES('', '" . $po_id . "', '" . $product_id[$k] . "', '" . $product_sku_id[$k] . "', '" . $product_qty[$k] . "', '0', '" . $u_price . "', '" . $u_price_tax . "', '" . $tot_price . "', '" . date('Y-m-d') . "')";
					$DB->query($purchaseItems);
                    
                    $select   = "SELECT productid, categoryid, productname, detail_img FROM `product` WHERE `productid` = " . $product_id[$k];
                    $conn	  = $DB->query($select);
                    $rows	  = mysqli_num_rows($conn);
                    $fetch	  = mysqli_fetch_object($conn);
                    $poItems .= '<tr>
                                    <th style="border:1px solid #EEAF00 !important"><img src="https://inventory.thevintagebazar.com/' . $fetch->detail_img . '" width="100" alt="' . $fetch->productid . ' Image" /></th>
                                    <th style="border:1px solid #EEAF00 !important">' . $fetch->productname . '</th>
                                    <th style="border:1px solid #EEAF00 !important">Rs. ' . number_format($u_price,2) . '</th>
                                    <th style="border:1px solid #EEAF00 !important">' . $product_qty[$k] . '</th>
                                    <th style="border:1px solid #EEAF00 !important">Rs. ' . number_format($tot_price,2) . '</th>
								</tr>';
                    $categoryID = $fetch->categoryid;
                    if(!in_array($categoryID, $catArr))
                    {
                        $catArr = $categoryID;
                        $catItem[$catArr] .= '<tr>
                                    <th style="border:1px solid #EEAF00 !important"><img src="https://inventory.thevintagebazar.com/' . $fetch->detail_img . '" width="100" alt="' . $fetch->productid . ' Image" /></th>
                                    <th style="border:1px solid #EEAF00 !important">' . $fetch->productname . '</th>
                                    <th style="border:1px solid #EEAF00 !important">Rs. ' . number_format($u_price,2) . '</th>
                                    <th style="border:1px solid #EEAF00 !important">' . $product_qty[$k] . '</th>
                                    <th style="border:1px solid #EEAF00 !important">Rs. ' . number_format($tot_price,2) . '</th>
								</tr>';
                    }
                    else
                    {
                        $key = array_search($categoryID, $catArr);
                        $catItem[$catArr] .= '<tr>
                                    <th style="border:1px solid #EEAF00 !important"><img src="https://inventory.thevintagebazar.com/' . $fetch->detail_img . '" width="100" alt="' . $fetch->productid . ' Image" /></th>
                                    <th style="border:1px solid #EEAF00 !important">' . $fetch->productname . '</th>
                                    <th style="border:1px solid #EEAF00 !important">Rs. ' . number_format($u_price,2) . '</th>
                                    <th style="border:1px solid #EEAF00 !important">' . $product_qty[$k] . '</th>
                                    <th style="border:1px solid #EEAF00 !important">Rs. ' . number_format($tot_price,2) . '</th>
								</tr>';
                    }
					$i++;
				}
				$k++;
			}
            $poItems .= '<tr>
                            <th style="border:1px solid #EEAF00 !important"><b>TOTAL</b></th>
                            <th style="border:1px solid #EEAF00 !important">&nbsp;</th>
                            <th style="border:1px solid #EEAF00 !important">&nbsp;</th>
                            <th style="border:1px solid #EEAF00 !important"><b>' . $totalQty . '</b></th>
                            <th style="border:1px solid #EEAF00 !important"><b>Rs. ' . number_format($totalAmt,2) . '/=</b></th>
                        </tr>';
            
            $poItems .= '<tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</b></th>
                            <th><b><a href="https://inventory.thevintagebazar.com/pre_approval_po.php?status=approval&type=secure&pod=' . $this->encryptPassword($po_id) . '">APPROVE PO!</a></b></th>
                        </tr>';
            
            
            $purchaseUpd = "UPDATE `inv_qne_purchase_order` SET `total_amount` = '" . $totalAmt . "' WHERE `purchase_id` = " . $po_id;
			$DB->query($purchaseUpd);
            
            //echo "<pre>"; print_r($poItems); echo "</pre>";
            //echo "<pre>"; print_r($catItem); echo "</pre>";
            //exit();
            if(sizeof($catItem) > 0)
            {
                foreach($catItem as $key => $item)
                {
                    //echo "<pre>"; print_r($key); echo "</pre>";
                    //echo "Key: ".$key." Data: ".$item."<br />";
                    $catEmail = "";
                    switch($key)
                    {
                        case 1:
                            $catEmail = ", " . CATEGORY_1;    
                        break; 
                        
                        case 2:
                            $catEmail = ", " . CATEGORY_2;    
                        break;
                        
                        case 3:
                            $catEmail = ", " . CATEGORY_3;    
                        break; 
                            
                        case 4:
                            $catEmail = ", " . CATEGORY_4;    
                        break;  
                            
                        case 5:
                            $catEmail = ", " . CATEGORY_5;    
                        break; 
                        
                        case 6:
                            $catEmail = ", " . CATEGORY_6;    
                        break;
                        
                        case 7:
                            $catEmail = ", " . CATEGORY_7;    
                        break; 
                            
                        case 8:
                            $catEmail = ", " . CATEGORY_8;    
                        break;
                            
                        case 11:
                            $catEmail = ", " . CATEGORY_11;    
                        break; 
                        
                        case 12:
                            $catEmail = ", " . CATEGORY_12;    
                        break;
                        
                        case 14:
                            $catEmail = ", " . CATEGORY_14;    
                        break; 
                            
                        case 15:
                            $catEmail = ", " . CATEGORY_15;    
                        break;
                        
                        case 16:
                            $catEmail = ", " . CATEGORY_16;    
                        break;    
                    }
                    $headers 	= "MIME-Version: 1.0\n";
                    $headers   .= "Content-type: text/html; charset=iso-8859-1\n";
                    $headers   .= "From:The Vintage Bazar<info@thevintagebazar.com>\n";
                    $headers   .= "X-Mailer: PHP's mail() Function\n";
                    $subject    = "New Purchase Order (PO # " . $po_number . ") Created at The Vintage Bazar";
                    $email      = "info@thevintagebazar.com" . $catEmail;
                    $body       = file_get_contents("../template/email/purchase_order.html");
                    $find       = array("PO_NUMBER", "CREATED_DATE", "CREATE_BY", "PURCHASE_ORDER_ITEMS");
                    $replace    = array($po_number, date('d M, Y'), $_SESSION['sess_username'], $item);
                    $body       = str_replace($find, $replace, $body);

                    mail($email, $subject, $body, $headers);
                }
            }
            
            $headers 	= "MIME-Version: 1.0\n";
            $headers   .= "Content-type: text/html; charset=iso-8859-1\n";
            $headers   .= "From:The Vintage Bazar<info@thevintagebazar.com>\n";
            $headers   .= "X-Mailer: PHP's mail() Function\n";
            $subject    = "New Purchase Order (PO # " . $po_number . ") Created at The Vintage Bazar";
            $email      = "info@thevintagebazar.com";
            $body       = file_get_contents("../template/email/purchase_order.html");
            $find       = array("PO_NUMBER", "CREATED_DATE", "CREATE_BY", "PURCHASE_ORDER_ITEMS");
            $replace    = array($po_number, date('d M, Y'), $_SESSION['sess_username'], $poItems);
            $body       = str_replace($find, $replace, $body);
            
            mail($email, $subject, $body, $headers);
		}
		return $po_id;
	}

    public function addGoodsReturn($post)
	{
		$DB			= 	new DB_connection();
		extract($post);
		$purchase	=	"INSERT INTO `inv_qne_goods_return`(`grf_id`, `version`, `grf_date`, `distributor`, `company`, `brand`, `description`, `status`, `datetime`) VALUES('', '" . mysql_real_escape_string($version) . "', '" . mysql_real_escape_string($po_date) . "', '" . mysql_real_escape_string($distributor) . "', '" . mysql_real_escape_string($company) . "', '" . mysql_real_escape_string($brand) . "', '" . mysql_real_escape_string($description) . "', 'P', '" . date('Y-m-d H:i:s') . "')";
		$purchase	= 	$DB->query($purchase);
		$grf_id		= 	mysql_insert_id();

        if(sizeof($post['product_sku_id']))
		{
			$product_sku_id	=	$post['product_sku_id'];
			$k = 0;

            foreach($product_sku_id as $sku_id)
			{
				if(is_numeric($product_qty[$k]) && $product_qty[$k] > 0)
				{
					$purchaseItems	=	"INSERT INTO `inv_qne_goods_return_items`(`grf_item_id`, `grf_id`, `product_id`, `product_sku_id`, `order_qty`, `received_qty`, `status`, `date`) VALUES('', '" . $grf_id . "', '" . $product_id[$k] . "', '" . $product_sku_id[$k] . "', '" . $product_qty[$k] . "', '0', '" . $skuStatus[$k] . "', '" . date('Y-m-d') . "')";
					$DB->query($purchaseItems);
				}
				$k++;
			}
		}
		return $grf_id;
	}

	

	public function webSKUDetails($sku_id)

	{

		$DB		= new DB_connection();



		$select = "SELECT * FROM `product_attributes` WHERE `id` = '" . $sku_id . "'";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		$fetch	= mysqli_fetch_object($conn);



		if($rows > 0)

		{

			$this->id				=	$fetch->id;

			$this->productid		=	$fetch->productid;

			$this->whearhouse_id	=	$fetch->whearhouse_id;

			$this->attribute_title	=	$fetch->attribute_title;

			$this->attribute_code	=	$fetch->attribute_code;

			$this->price			=	$fetch->price;

			$this->stock_qty		=	$fetch->stock_qty;

			$this->minStock_qty		=	$fetch->minStock_qty;

			$this->hold_qty			=	$fetch->hold_qty;

			$this->max_order_qty	=	$fetch->max_order_qty;

			$this->discount_percent	=	$fetch->discount_percent;

			$this->tax_type			=	$fetch->tax_type;

			$this->gst_percent		=	$fetch->gst_percent;

			$this->tax_value		=	$fetch->tax_value;

			$this->price_final		=	$fetch->price_final;

			$this->price_mrp		=	$fetch->price_without_discount;

			$this->org_img			=	$fetch->org_img;

			$this->thumbnail_img	=	$fetch->thumbnail_img;

			$this->created			=	$fetch->created;

		}		

	}



	public function goodsReturnDetails($grf_id)

	{

		$DB		= new DB_connection();



		$select = "SELECT * FROM inv_qne_goods_return WHERE `grf_id` = '" . $grf_id . "'";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		$fetch	= mysqli_fetch_object($conn);



		if($rows > 0)

		{

			$this->grf_id		=	$fetch->grf_id;

			$this->version		=	$fetch->version;

			$this->grf_date		=	$fetch->grf_date;

			$this->distributor	=	$fetch->distributor;

			$this->brand_id		=	$fetch->brand_id;

			$this->company		=	$fetch->company;

			$this->brand		=	$fetch->brand;

			$this->description	=	$fetch->description;

			$this->status		=	$fetch->status;

			$this->datetime		=	$fetch->datetime;

		}

	}

		

	public function goodsReturnItems($grf_number, $productID, $skuID)

	{

		$DB		= new DB_connection();

		

		$Where = "";

		if($productID != '' && $productID != 0)

		{

			$Where .= " AND `product_id` = " . $productID;

		}

		

		if($skuID != '' && $skuID != 0)

		{

			$Where .= " AND `product_sku_id` = " . $skuID;

		}

		

		$select = "SELECT * FROM `inv_qne_goods_return_items` gri WHERE `grf_id` = " . $grf_number . $Where;

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);



		if($rows > 0)

		{

			$c = 0;

			$productSKU = array();



			while($fetch = mysqli_fetch_object($conn))

			{

				$productSKU[$c]						=	new Purchase();

				$productSKU[$c]->grf_item_id		=	$fetch->grf_item_id;

				$productSKU[$c]->grf_id				=	$fetch->grf_id;

				$productSKU[$c]->product_id			=	$fetch->product_id;

				$productSKU[$c]->product_sku_id		=	$fetch->product_sku_id;

				$productSKU[$c]->order_qty			=	$fetch->order_qty;

				$productSKU[$c]->received_qty		=	$fetch->received_qty;

				$productSKU[$c]->status				=	$fetch->status;

				$productSKU[$c]->date				=	$fetch->date;

				$c++;

			}

			return $productSKU;

		}

	}

    public function addGRN($post)
	{
		$DB			= 	new DB_connection();

		extract($post);

		$purchase	=	"INSERT INTO `inv_qne_grn`(`grn_id`, `invoice_number`, `sub_total`, `tax_amount`, `discount_amount`, `other_tax`, `other_discount`, `grand_total`, `status`, `invoice_date`, `datetime`) VALUES('', '" . mysql_real_escape_string($invoice_number) . "', '" . mysql_real_escape_string($sub_total) . "', '" . mysql_real_escape_string($tax_amount) . "', '" . mysql_real_escape_string($discount_amt) . "', '" . mysql_real_escape_string($other_tax) . "', '" . mysql_real_escape_string($other_discount) . "', '" . mysql_real_escape_string($grand_total) . "', '1', '" . mysql_real_escape_string($receive_date) . "', '" . date('Y-m-d H:i:s') . "')";
		$purchase	= 	$DB->query($purchase);
		$grn_id		= 	mysql_insert_id();

        if(sizeof($post['po_id']))
		{
			$po_id	=	$post['po_id'];
			$k = 0;

			foreach($po_id as $order_id)
			{

				$selectGRN	=	"SELECT * FROM `inv_qne_grn_po` WHERE `grn_id` = '" . $grn_id . "' AND `po_id` = '" . $order_id . "'";
				$selConn	=	$DB->query($selectGRN);

                if(mysqli_num_rows($selConn) > 0)
				{
					$fet	=	mysqli_fetch_object($selConn);
					$grn_po_id		= 	$fet->grn_po_id;
				}
				else
				{
					$purchaseItems	=	"INSERT INTO `inv_qne_grn_po`(`grn_po_id`, `grn_id`, `po_number`, `po_id`, `datetime`) VALUES('', '" . $grn_id . "', '" . $po_number[$k] . "', '" . $order_id . "', '" . date('Y-m-d H:i:s') . "')";
					$DB->query($purchaseItems);
					$grn_po_id		= 	mysql_insert_id();
				}

                if(sizeof($unit_price[$k]) > 0)
				{
					$j = 0;
					$isPOComplete	=	1;

					foreach($unit_price[$k] as $price)
					{
						if($received_qty[$k][$j] > 0)
						{
							$selectMRP = "SELECT * FROM `product_attributes` WHERE `id` = '" . $product_sku_id[$k] . "'";
							$connMRP	= $DB->query($selectMRP);
							$rowsMRP	= mysqli_num_rows($connMRP);

							if($rowsMRP > 0)
							{
								$fetchMRP	= mysqli_fetch_object($connMRP);
								$salePrice	=	$fetchMRP->price_final;
								$MRPPrice	=	$fetchMRP->price_without_discount;
							}
							else
							{
								$salePrice	=	0;
								$MRPPrice	=	0;
							}						

                            $sub_total_amt	=	$received_qty[$k][$j] * $price;
							$insertItems	=	"INSERT INTO `inv_qne_grn_po_details`(`id`, `grn_po_id`, `product_id`, `sku_id`, `qty`, `price`, `sub_total`, `tax`, `discount`, `discount_type`, `special_discount`, `special_discount_type`, `net_total`, `batch`, `manufacturing_date`, `expiry_date`, `product_type`, `mrp_price`, `sale_price`, `added_date`) VALUES('', '" . $grn_po_id . "', '" . $product_id[$k] . "', '" . $product_sku_id[$k] . "', '" . $received_qty[$k][$j] . "', '" . $price . "', '" . $sub_total_amt . "', '" . $tax[$k][$j] . "', '" . $discount[$k][$j] . "', '" . $discount_type[$k][$j] . "', '" . $special_discount[$k][$j] . "', '" . $special_discount_type[$k][$j] . "', '" . $total_price[$k][$j] . "', '" . $batch[$k][$j] . "', '" . date('Y-m-d', strtotime($manufacturing_date[$k][$j])) . "', '" . date('Y-m-d', strtotime($expiry_date[$k][$j])) . "', '" . $type[$k][$j] . "', '" . $MRPPrice . "', '" . $salePrice . "', '" . date('Y-m-d H:i:s') . "')";
							$DB->query($insertItems);

                            // Open New Price with GRN Starts Here
							$selectPrice	=	"SELECT * FROM `inv_qne_product_price` WHERE `product_id` = '" . $product_id[$k] . "' AND `sku_id` = '" . $product_sku_id[$k] . "' AND `cost_price` = '" . $price . "'";
							$connPrice		=	$DB->query($selectPrice);

							if(mysqli_num_rows($connPrice) <= 0)
							{
								$selectPOD	=	"SELECT * FROM `inv_qne_purchase_order` WHERE `purchase_id` = '" . $order_id . "'";
								$selConnPOD	=	$DB->query($selectPOD);

                                if(mysqli_num_rows($selConnPOD) > 0)
								{
									$fetPOD			=	mysqli_fetch_object($selConnPOD);
									$distributor	= 	$fetPOD->distributor;
								}

                                $selectOldPrice = 	"SELECT * FROM `inv_qne_product_price` WHERE `product_id` = '" . $product_id[$k] . "' AND `sku_id` = '" . $product_sku_id[$k] . "'";
								$connOldPrice	=	$DB->query($selectOldPrice);

								if(mysqli_num_rows($connOldPrice) > 0)
								{
									$fetehOldPrice		=	mysqli_fetch_object($connOldPrice);
									$retail_price		= 	$fetehOldPrice->retail_price;
									$retail_price_tax	= 	$fetehOldPrice->retail_price_tax;
								}

                                $updatePriceStatus = 	"UPDATE `inv_qne_product_price` SET `status` = '0' WHERE `product_id` = '" . $product_id[$k] . "' AND `sku_id` = '" . $product_sku_id[$k] . "'";
								$DB->query($updatePriceStatus);

                                $cost_price_tax_val	=	$price + $tax_value[$k];
								$insertPrice = 	"INSERT INTO `inv_qne_product_price`(`price_id`, `product_id`, `sku_id`, `distributor_id`, `cost_price`, `cost_price_tax`, `retail_price`, `retail_price_tax`, `status`, `modify_date`, `date`) VALUES('', '" . $product_id[$k] . "', '" . $product_sku_id[$k] . "', '" . $distributor . "', '" . $price . "', '" . $cost_price_tax_val . "', '" . $retail_price . "', '" . $retail_price_tax . "', '1', '" . date('Y-m-d') . "', '" . date('Y-m-d') . "')";
								$DB->query($insertPrice);
							}	

                            //Update Purchase Order Stock
							$selectOrder	=	"SELECT * FROM `inv_qne_purchase_order_items` WHERE `po_id` = '" . $order_id . "' AND `product_id` = '" . $product_id[$k] . "' AND `product_sku_id` = '" . $product_sku_id[$k] . "'";
							$connOrder		=	$DB->query($selectOrder);

							if(mysqli_num_rows($connOrder) > 0)
							{
								$fetchOrder	=	mysqli_fetch_object($connOrder);
								$stockQty2	=	$received_qty[$k][$j] + $fetchOrder->received_qty;

                                $updateOrder	=	"UPDATE `inv_qne_purchase_order_items` SET `received_qty` = '" . $stockQty2 . "' WHERE `po_id` = '" . $order_id . "' AND `product_id` = '" . $product_id[$k] . "' AND `product_sku_id` = '" . $product_sku_id[$k] . "'";
								$DB->query($updateOrder);

                                if($stockQty2 < $fetchOrder->order_qty)
								{
									$isPOComplete	=	0;

                                    if($stockQty2 > 0)
									{
										$updateOrder	=	"UPDATE `inv_qne_purchase_order_items` SET `status` = 'Partial' WHERE `po_id` = '" . $order_id . "' AND `product_id` = '" . $product_id[$k] . "' AND `product_sku_id` = '" . $product_sku_id[$k] . "'";
										$DB->query($updateOrder);
									}
								}	
								else
								{
									if($stockQty2 >= $fetchOrder->order_qty)
									{
										$updateOrder	=	"UPDATE `inv_qne_purchase_order_items` SET `status` = 'Close' WHERE `po_id` = '" . $order_id . "' AND `product_id` = '" . $product_id[$k] . "' AND `product_sku_id` = '" . $product_sku_id[$k] . "'";
										$DB->query($updateOrder);
									}
								}
							}
						}	
						$j++;
					}
				}

                $selectPO  = "SELECT order_qty, received_qty FROM `inv_qne_purchase_order_items` WHERE `po_id` = '" . $order_id . "'";
				$connPO	   = $DB->query($selectPO);
				$POComplete= 1;

				if(mysqli_num_rows($connPO) > 0)
				{
					while($fetPO = mysqli_fetch_object($connPO))
					{
						if($fetPO->received_qty < $fetPO->order_qty)
						{
							$POComplete	=	0;
							break;
						}
					}
				}

                if($POComplete == 0)
				{
					$updatePO	=	"UPDATE `inv_qne_purchase_order` SET `status` = 'Partial' WHERE `po_number` = '" . $po_number[$k]  . "'";
					$DB->query($updatePO);
				}
				else
				{
					$updatePO	=	"UPDATE `inv_qne_purchase_order` SET `status` = 'Close' WHERE `po_number` = '" . $po_number[$k]  . "'";
					$DB->query($updatePO);
				}
				$k++;	
			}
		}
		return $grn_id;
	}

    function allGRNCSV()
	{
		$DB			= 	new DB_connection();

        $selectGRN	=	"SELECT qg.is_post, qg.grn_id, qg.sub_total, qg.tax_amount, qg.discount_amount, qg.other_discount, qg.grand_total, grn_po_id FROM `inv_qne_grn` qg JOIN `inv_qne_grn_po` gp ON qg.grn_id = gp.grn_id WHERE qg.is_post = '1'";
		$connGRN	=	$DB->query($selectGRN);

        if(mysqli_num_rows($connGRN) > 0)
		{
            $filename       = "GRN";
            $csv_filename   = "allGRNData-" . date('Y-m-d-H:i:s') . ".csv";

            $today          = date('Y-m-d');
            $fileContent    = '';
            $sep            = "|";
            $fileContent   .= 'GRN #|GRN Item ID|Product ID|SKU ID|Product|SKU Title|Tax Type|Qty|G. Amount|Discount|Special Discount|GST|Net Amount|Sale Price|MRP|Tax Type|Tax Rate|Stock|Date' . "\r\n";
			while($fetch = mysqli_fetch_object($connGRN))
            {
                if($fetch->is_post == 1)
                {
                    $selectDetail	= "SELECT pod.id as grn_id, pod.product_id, pod.sku_id, pod.qty, sub_total, tax, discount, discount_type, special_discount, special_discount_type, net_total, qp.product, qp.tax_type, qp.is_virtual, ps.sku_title, pod.sale_price, pod.mrp_price, pod.added_date FROM `inv_qne_grn_po_details` pod LEFT JOIN `inv_qne_products` qp ON pod.product_id = qp.product_id LEFT JOIN `inv_qne_product_sku` ps ON pod.sku_id = ps.sku_id WHERE `grn_po_id` = '" . $fetch->grn_po_id . "'";
                    $connDetail	=	$DB->query($selectDetail);

                    if(mysqli_num_rows($connDetail) > 0)
                    {
                        
                        while($fetDetail = mysqli_fetch_object($connDetail))
                        {
                            $selectStock	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = '" . $fetDetail->product_id . "' AND `sku_id` = '" . $fetDetail->sku_id . "'";
                            $connStock		=	$DB->query($selectStock);

                            if(mysqli_num_rows($connStock) > 0)
                            {
                                $fetStock	   =	mysqli_fetch_object($connStock);
                                $stockQty	   =	$fetDetail->qty + $fetStock->qty;
                                $availableQty  =	$fetDetail->qty + $fetStock->available;

                                $specialDiscount = $fetDetail->special_discount;
                                if($fetDetail->special_discount_type == 'percent')
                                {
                                    $specialDiscount = number_format($fetDetail->sub_total * $fetDetail->special_discount/100,2);
                                }

                                switch($fetDetail->tax_type)
                                {
                                    case 1:
                                        $taxType    = "Exempt";
                                        $taxValue   = 0;
                                        $taxVal     = 0;
                                    break;

                                    case 2:
                                        $taxType    = "Normal";
                                        $taxValue   = 17;
                                        //$taxVal     = $offer_detail_rs->final_price * $taxValue / (100 + $taxValue);
                                    break;

                                    case 3:
                                        $taxType    = "Fixed";
                                        $taxValue   = 17;
                                        //$taxVal     = $offer_detail_rs->without_desc_price * $taxValue / (100 + $taxValue);
                                    break;

                                    case 4:
                                        $taxType    = "Zero";
                                        $taxValue   = 0;
                                        $taxVal     = 0;
                                    break;

                                    case 5:
                                        $taxType    = "Normal";
                                        $taxValue   = 10;
                                        //$taxVal     = $offer_detail_rs->final_price * $taxValue / (100 + $taxValue);
                                    break;

                                    case 6:
                                        $taxType    = "Normal";
                                        $taxValue   = 6;
                                        //$taxVal     = $offer_detail_rs->final_price * $taxValue / (100 + $taxValue);
                                    break;

                                    case 7:
                                        $taxType    = "Normal";
                                        $taxValue   = 16;
                                        //$taxVal     = $offer_detail_rs->final_price * $taxValue / (100 + $taxValue);
                                    break;

                                    default:
                                        $taxType    = "";
                                        $taxValue   = 0;
                                        $taxVal     = 0;
                                    break;    
                                }

                                if($fetDetail->is_virtual == 'n')
                                {
                                    $isVirtualStatus = "In-Stock";
                                }
                                else
                                {
                                    $isVirtualStatus = "Virtual";
                                }
                                $sep            = "|";
                                $fileContent   .= $fetch->grn_id . $sep . $fetDetail->grn_id . $sep . $fetDetail->product_id . $sep . $fetDetail->sku_id . $sep . $fetDetail->product . $sep . $fetDetail->sku_title . $sep . $taxType . '(' . $taxValue . ')' . $sep . $fetDetail->qty . $sep . $fetDetail->sub_total . $sep . $discountAmount . $sep . $specialDiscount . $sep . $fetDetail->tax . $sep . $fetDetail->net_total . $sep . $fetDetail->sale_price . $sep . $fetDetail->mrp_price . $sep . $taxType . $sep . $taxValue . $sep . $isVirtualStatus . $sep . date('m/d/Y', strtotime($fetDetail->added_date)) . "\r\n";
                            }
                        }

                        //$fileContent    = str_replace("\n\n","\n",$fileContent);
                        $fd             = fopen ("grn_data/".$csv_filename, "w");
                        fputs($fd, $fileContent);
                        // Uploading CSV File on Server
                    }	
                    //echo "Noman";exit();    
                    $updateGRN	=	"UPDATE `inv_qne_grn` SET is_post = '1' WHERE grn_id = '" . $grn_id . "'";
                    $DB->query($updateGRN);	
                }
            }
		}
	}
    
    function setInventoryStock()
	{
		$DB			= 	new DB_connection();

        echo "<br />".$selectGRN	=	"SELECT product_id, sku_id, sum(gpd.qty) as qty FROM `inv_qne_grn` gn JOIN `inv_qne_grn_po` qgp ON gn.grn_id = qgp.grn_id JOIN `inv_qne_grn_po_details` gpd ON qgp.grn_po_id = gpd.grn_po_id WHERE gn.is_post = 1 AND gn.status = 1 GROUP BY sku_id ORDER BY `qty` DESC";
		$connGRN	=	$DB->query($selectGRN);

        if(mysqli_num_rows($connGRN) > 0)
		{
            while($fetch = mysqli_fetch_object($connGRN))
            {
                echo "<br />".$selectStock = "SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = '" . $fetch->product_id . "' AND `sku_id` = '" . $fetch->sku_id . "'";
                $connStock   = $DB->query($selectStock);
                
                if(mysqli_num_rows($connStock) > 0)
                {
                    $fetStock = mysqli_fetch_object($connStock);
                    
                    echo "<br />Qty = ".$quantity   = $fetch->qty;
                    echo ", Sold = " . $soldQty = $fetStock->sold;
                    echo ", Hold = " . $holdQty = $fetStock->hold;
                    echo ", Damage = " . $damageQty = $fetStock->damage;
                    echo ", Return = " . $returnQty = $fetStock->returned;
                    echo ", Expired = " . $expireQty = $fetStock->expired;
                    $available  = $quantity - $soldQty - $holdQty - $damageQty - $returnQty - $expireQty;
                    
                    if($available < 0)
                    {
                        $available = 0;
                    }
                    echo "<br />Avaialbe = ".$available;
                    echo "<br />".$updStock   = "UPDATE `inv_qne_product_stock` SET `qty` = '" . $quantity . "', `available` = '" . $available . "' WHERE `product_id` = '" . $fetch->product_id . "' AND `sku_id` = '" . $fetch->sku_id . "'";
                    //$DB->query($updStock);
                    
                    echo "<br />".$updAttr   = "UPDATE `product_attributes` SET `stock_qty` = '" . $available . "' WHERE `productid` = '" . $fetch->product_id . "' AND `id` = '" . $fetch->sku_id . "'";
                    //$DB->query($updAttr);
                    echo "<hr />";
                }
            }
		}
	}

	function confirmPostGRN($grn_id)
	{
		$DB			= 	new DB_connection();

        $selectGRN	=	"SELECT qg.is_post, qg.grn_id, qg.sub_total, qg.tax_amount, qg.discount_amount, qg.other_discount, qg.grand_total, grn_po_id FROM `inv_qne_grn` qg JOIN `inv_qne_grn_po` gp ON qg.grn_id = gp.grn_id WHERE gp.`grn_id` = '" . $grn_id . "'";
		$connGRN	=	$DB->query($selectGRN);

        if(mysqli_num_rows($connGRN) > 0)
		{
			$fetch = mysqli_fetch_object($connGRN);

            if($fetch->is_post == 0)
			{
				//$selectDetail	=	"SELECT product_id, sku_id, qty, sub_total, tax, discount, discount_type, special_discount, special_discount_type, net_total FROM `inv_qne_grn_po_details` WHERE `grn_po_id` = '" . $fetch->grn_po_id . "'";
                $selectDetail	= "SELECT pod.product_id, pod.sku_id, pod.qty, sub_total, tax, discount, discount_type, special_discount, special_discount_type, net_total, qp.product, qp.tax_type, qp.is_virtual, ps.sku_title, pod.mrp_price FROM `inv_qne_grn_po_details` pod LEFT JOIN `inv_qne_products` qp ON pod.product_id = qp.product_id LEFT JOIN `inv_qne_product_sku` ps ON pod.sku_id = ps.sku_id WHERE `grn_po_id` = '" . $fetch->grn_po_id . "'";
				$connDetail	=	$DB->query($selectDetail);

                if(mysqli_num_rows($connDetail) > 0)
				{
                    // Uploading CSV File on Server
                    $filename       = "GRN";
                    //$csv_filename   = $filename . "_" . $grn_id . ".csv";
                    $csv_filename   = $grn_id . ".csv";

                    $today          = date('Y-m-d');
                    $fileContent    = '';
                    $sep            = "|";
                    $fileContent   .= 'GRN #|Product ID|SKU ID|Product|SKU Title|Tax Type|Qty|G. Amount|Discount|Special Discount|GST|Net Amount|MRP|Tax Type|Tax Rate|Stock|Date' . "\r\n";
					while($fetDetail = mysqli_fetch_object($connDetail))
					{
						$selectStock	=	"SELECT * FROM `inv_qne_product_stock` WHERE `product_id` = '" . $fetDetail->product_id . "' AND `sku_id` = '" . $fetDetail->sku_id . "'";
						$connStock		=	$DB->query($selectStock);
                        
                        $updStk = "UPDATE `inv_qne_products` SET status = 1 WHERE `product_id` = '" . $fetDetail->product_id . "'";
						$DB->query($updStk);
                        
                        $updStk2 = "UPDATE `product` SET productstatus = 1 WHERE `productid` = '" . $fetDetail->product_id . "'";
						$DB->query($updStk2);

						if(mysqli_num_rows($connStock) > 0)
						{
							$fetStock	   =	mysqli_fetch_object($connStock);
							$stockQty	   =	$fetDetail->qty + $fetStock->qty;
							$availableQty  =	$fetDetail->qty + $fetStock->available;

                            $updateStock   =	"UPDATE `inv_qne_product_stock` SET `qty` = '" . $stockQty . "', `available` = '" . $availableQty . "' WHERE `product_id` = '" . $fetDetail->product_id . "' AND `sku_id` = '" . $fetDetail->sku_id . "'";
							$DB->query($updateStock);

							$updateProdAttrStock	=	"UPDATE `product_attributes` SET `stock_qty` = '" . $availableQty . "' WHERE `productid` = '" . $fetDetail->product_id . "' AND `id` = '" . $fetDetail->sku_id . "'";
							$DB->query($updateProdAttrStock);
                            
                            $discountAmount = $fetDetail->discount;
                            if($fetDetail->discount_type == 'percent')
                            {
                                $discountAmount = number_format($fetDetail->sub_total * $fetDetail->discount/100,2);
                            }
                            
                            $specialDiscount = $fetDetail->special_discount;
                            if($fetDetail->special_discount_type == 'percent')
                            {
                                $specialDiscount = number_format($fetDetail->sub_total * $fetDetail->special_discount/100,2);
                            }
                            
                            switch($fetDetail->tax_type)
                            {
                                case 1:
                                    $taxType    = "Exempt";
                                    $taxValue   = 0;
                                    $taxVal     = 0;
                                break;

                                case 2:
                                    $taxType    = "Normal";
                                    $taxValue   = 17;
                                    //$taxVal     = $offer_detail_rs->final_price * $taxValue / (100 + $taxValue);
                                break;

                                case 3:
                                    $taxType    = "Fixed";
                                    $taxValue   = 17;
                                    //$taxVal     = $offer_detail_rs->without_desc_price * $taxValue / (100 + $taxValue);
                                break;

                                case 4:
                                    $taxType    = "Zero";
                                    $taxValue   = 0;
                                    $taxVal     = 0;
                                break;

                                case 5:
                                    $taxType    = "Normal";
                                    $taxValue   = 10;
                                    //$taxVal     = $offer_detail_rs->final_price * $taxValue / (100 + $taxValue);
                                break;

                                case 6:
                                    $taxType    = "Normal";
                                    $taxValue   = 6;
                                    //$taxVal     = $offer_detail_rs->final_price * $taxValue / (100 + $taxValue);
                                break;

                                case 7:
                                    $taxType    = "Normal";
                                    $taxValue   = 16;
                                    //$taxVal     = $offer_detail_rs->final_price * $taxValue / (100 + $taxValue);
                                break;

                                default:
                                    $taxType    = "";
                                    $taxValue   = 0;
                                    $taxVal     = 0;
                                break;    
                            }
                            
                            if($fetDetail->is_virtual == 'n')
                            {
                                $isVirtualStatus = "In-Stock";
                            }
                            else
                            {
                                $isVirtualStatus = "Virtual";
                            }
                            $sep            = "|";
                            $fileContent   .= $grn_id . $sep . $fetDetail->product_id . $sep . $fetDetail->sku_id . $sep . $fetDetail->product . $sep . $fetDetail->sku_title . $sep . $taxType . '(' . $taxValue . ')' . $sep . $fetDetail->qty . $sep . $fetDetail->sub_total . $sep . $discountAmount . $sep . $specialDiscount . $sep . $fetDetail->tax . $sep . $fetDetail->net_total . $sep . $fetDetail->mrp_price . $sep . $taxType . $sep . $taxValue . $sep . $isVirtualStatus . $sep . date('d/m/Y') . "\r\n";
						}
					}
                    
                    //$fileContent    = str_replace("\n\n","\n",$fileContent);
                    $fd             = fopen ("grn_data/".$csv_filename, "w");
                    fputs($fd, $fileContent);
                    // Uploading CSV File on Server
				}	
                //echo "Noman";exit();    
                $updateGRN	=	"UPDATE `inv_qne_grn` SET is_post = '1' WHERE grn_id = '" . $grn_id . "'";
				$DB->query($updateGRN);	
			}
		}
	}

	

	function deleteGRN($grn_id)

	{

		$DB			= 	new DB_connection();

		$select	=	"SELECT * FROM `inv_qne_grn_po` WHERE `grn_id` = " . $grn_id;

		$conn = $DB->query($select) or die(mysql_error());

		

		if(mysqli_num_rows($conn) > 0)

		{

			$fetch = mysqli_fetch_object($conn);

			$grn_po_details	=	"DELETE FROM `inv_qne_grn_po_details` WHERE `grn_po_id` = " . $fetch->grn_po_id;

			$DB->query($grn_po_details) or die(mysql_error());

			

			$grn_po	=	"DELETE FROM `inv_qne_grn` WHERE `grn_id` = " . $fetch->grn_id;

			$DB->query($grn_po) or die(mysql_error());

			

			$grn_po	=	"DELETE FROM `inv_qne_grn_po` WHERE `grn_po_id` = " . $grn_id;

			$DB->query($grn_po) or die(mysql_error());

			

			/*echo "<br />Order Items = ".$po_items	=	"DELETE FROM `inv_qne_purchase_order_items` WHERE `po_id` = " . $fetch->po_id;

			$DB->query($po_items) or die(mysql_error());

				

			echo "<br />Purchase Order = ".$purchase_order	=	"DELETE FROM `inv_qne_purchase_order` WHERE `purchase_id` = " . $fetch->po_id;

			$DB->query($purchase_order) or die(mysql_error());*/

		}

	}

	

	function approvePurchaseOrder($po_number)

	{

		$DB			= 	new DB_connection();

		$updatePO	=	"UPDATE `inv_qne_purchase_order` SET `status` = 'Approve' WHERE `po_number` = '" . $po_number  . "'";

		if($DB->query($updatePO))

		{

			return true;

		}

		else

		{

			return false;

		}

	}

	

	public function grnDetail($grn_id)

	{

		$DB		= new DB_connection();

		

		$select = "SELECT iqg.*, iqp.grn_po_id, iqd.distributor, iqd.ntn_number, iqd.strn_number FROM `inv_qne_grn` iqg JOIN `inv_qne_grn_po` iqp ON iqg.grn_id = iqp.grn_id LEFT JOIN `inv_qne_purchase_order` iqpo ON iqp.po_id = iqpo.purchase_id LEFT JOIN `inv_qne_distributor` iqd ON iqpo.distributor = iqd.distributor_id WHERE iqg.grn_id = " . $grn_id;

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);



		if($rows > 0)

		{

			$fetch = mysqli_fetch_object($conn);

		

			$this->grn_id			=	$fetch->grn_id;

			$this->grn_po_id		=	$fetch->grn_po_id;

			$this->invoice_number	=	$fetch->invoice_number;

			$this->sub_total		=	$fetch->sub_total;

			$this->tax_amount		=	$fetch->tax_amount;

			$this->discount_amount	=	$fetch->discount_amount;

			$this->other_tax		=	$fetch->other_tax;

			$this->other_discount	=	$fetch->other_discount;

			$this->grand_total		=	$fetch->grand_total;

			$this->is_post			=	$fetch->is_post;

			$this->status			=	$fetch->status;

			$this->invoice_date		=	$fetch->invoice_date;

			$this->datetime			=	$fetch->datetime;

			

			/*$this->grandSubTotal		=	$fetch->sub_total;

			$this->grandTaxAmount		=	$fetch->tax_amount;

			$this->grandDiscountAmount	=	$fetch->discount_amount;

			$this->grandOtherTax		=	$fetch->other_tax;

			$this->grandOtherDiscount	=	$fetch->other_discount;

			$this->grandTotal			=	$fetch->grand_total;*/

			

			$this->distributor		=	$fetch->distributor;

			$this->ntn_number		=	$fetch->ntn_number;

			$this->strn_number		=	$fetch->strn_number;

		}

	}

	

	public function allGRN($is_post='', $distributor='')

	{

		$DB		= new DB_connection();

		

		$where = "";

		if($is_post != '')

		{

			$where = " WHERE grn.is_post = " . $is_post;

		}

		

		if($distributor != '')

		{

			if($where == "")

			{

				$where = " WHERE (qd.distributor LIKE '%" . $distributor . "%' || grn.invoice_number LIKE '%" . $distributor . "%')";

			}

			else

			{

				$where .= " AND (qd.distributor LIKE '%" . $distributor . "%' || grn.invoice_number LIKE '%" . $distributor . "%')";

			}

		}

		$select = "SELECT grn.*, qd.distributor FROM `inv_qne_grn` grn JOIN `inv_qne_grn_po` po ON grn.grn_id = po.grn_id LEFT JOIN `inv_qne_purchase_order` pod ON po.po_id = pod.purchase_id LEFT JOIN `inv_qne_distributor` qd ON pod.distributor = qd.distributor_id" . $where . " ORDER BY grn.grn_id DESC";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		

		$c = 0;

		$GRNs = array();

		

		if($rows > 0)

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$GRNs[$c]					=	new Purchase();

				$GRNs[$c]->grn_id			=	$fetch->grn_id;

				$GRNs[$c]->invoice_number	=	$fetch->invoice_number;

				$GRNs[$c]->sub_total		=	$fetch->sub_total;

				$GRNs[$c]->tax_amount		=	$fetch->tax_amount;

				$GRNs[$c]->discount_amount	=	$fetch->discount_amount;

				$GRNs[$c]->other_tax		=	$fetch->other_tax;

				$GRNs[$c]->other_discount	=	$fetch->other_discount;

				$GRNs[$c]->grand_total		=	$fetch->grand_total;

				$GRNs[$c]->is_post			=	$fetch->is_post;

				$GRNs[$c]->status			=	$fetch->status;

				$GRNs[$c]->datetime			=	$fetch->datetime;

				$GRNs[$c]->distributor		=	$fetch->distributor;

				$c++;

			}

			return $GRNs;

		}

	}

	

	public function purchaseOrdersByGRN($grn=0)

	{

		$DB		= new DB_connection();



		$Where	= "";

		if($grn != 0)

		{

			$Where	=	" AND `grn_id` = " . $grn;

		}

		$select = "SELECT * FROM `inv_qne_purchase_order` iqpo RIGHT JOIN `inv_qne_grn_po` gpo ON iqpo.purchase_id = gpo.po_id WHERE 1=1 " . $Where;

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		

		$c = 0;

		$POs = array();

		

		if($rows > 0)

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$POs[$c]				=	new Purchase();

				$POs[$c]->purchase_id	=	$fetch->purchase_id;

				$POs[$c]->po_number		=	$fetch->po_number;

				$POs[$c]->po_date		=	$fetch->po_date;

				$POs[$c]->distributor	=	$fetch->distributor;

				$POs[$c]->company		=	$fetch->company;

				$POs[$c]->brand			=	$fetch->brand;

				$POs[$c]->description	=	$fetch->description;

				$POs[$c]->status		=	$fetch->status;

				$POs[$c]->datetime		=	$fetch->datetime;

				$c++;

			}

			return $POs;	

		}

	}



	public function GRNItemDetails($grn_number, $po_number)

	{

		$DB		= new DB_connection();

		

		$select = "SELECT * FROM `inv_qne_grn_po` qgp JOIN `inv_qne_grn_po_details` qgd ON qgp.grn_po_id = qgd.grn_po_id WHERE qgp.`grn_id` = '" . $grn_number . "' AND qgp.`po_id` = '" . $po_number . "'";

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

				$productSKU[$c]->sku_id				=	$fetch->sku_id;

				$productSKU[$c]->qty				=	$fetch->qty;

				$productSKU[$c]->price				=	$fetch->price;

				$productSKU[$c]->sub_total			=	$fetch->sub_total;

				$productSKU[$c]->tax				=	$fetch->tax;

				$productSKU[$c]->discount			=	$fetch->discount;

				$productSKU[$c]->discount_type		=	$fetch->discount_type;
                
                $productSKU[$c]->special_discount	=	$fetch->special_discount;

				$productSKU[$c]->special_discount_type	=	$fetch->special_discount_type;

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

	

	public function stockReport($start=0, $limit=0, $start_date, $end_date, $product='')

	{

		$DB		= new DB_connection();

		

		$where = '';

		if($start_date != '')

		{

			$where = " WHERE `added_date` >= '" . $start_date . "'";

		}

		

		if($end_date != '')

		{

			if($where == '')

			{

				$where .= " WHERE `added_date` <= '" . $end_date . "'";

			}

			else

			{

				$where .= " AND `added_date` <= '" . $end_date . "'";

			}

		}

		

		if($product != '')

		{

			if($where == '')

			{

				$where .= " WHERE qp.product LIKE '%" . $product . "%'";

			}

			else

			{

				$where .= " AND qp.product LIKE '%" . $product . "%'";

			}

		}

		

		if($start == 0 && $limit == 0)

		{

			$select = "SELECT ps.sku_id FROM `inv_qne_products` qp JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN `inv_qne_product_stock` qps ON ps.sku_id = qps.sku_id LEFT JOIN `inv_qne_grn_po_details` gd ON ps.sku_id = gd.sku_id " . $where . " GROUP BY ps.sku_id ORDER BY product ASC";

			$conn	= $DB->query($select);

			return mysqli_num_rows($conn);

		}

		else

		{

			$select = "SELECT qp.product_id, qp.product, ps.sku_id, ps.sku_title, sum(gd.qty) as Quantity, sum(gd.net_total) as netTotal, gd.batch, gd.product_type, gd.mrp_price, gd.sale_price, gd.added_date, qps.qty, qps.sold, qps.available, qps.hold, qps.damage, qps.returned, qps.expired FROM `inv_qne_products` qp JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN `inv_qne_product_stock` qps ON ps.sku_id = qps.sku_id LEFT JOIN `inv_qne_grn_po_details` gd ON ps.sku_id = gd.sku_id " . $where . " GROUP BY ps.sku_id ORDER BY product ASC LIMIT " . $start . ", " . $limit;

			$conn	= $DB->query($select);

			$rows	= mysqli_num_rows($conn);

			

			$c = 0;

			$allProducts = array();

			

			if($rows > 0)

			{

				while($fetch = mysqli_fetch_object($conn))

				{	

					$allProducts[$c]					=	new Purchase();

					if($start_date != '')

					{

						$selectStock = "SELECT stock FROM `inv_qne_product_closing_stock` WHERE `date` = '" . $start_date . "' AND `sku_id` = " . $fetch->sku_id;

						$connStock	= $DB->query($selectStock);

						$rowsStock	= mysqli_num_rows($connStock);

						if($rowsStock > 0)

						{

							$fetchStock 					= 	mysqli_fetch_object($connStock);

							$allProducts[$c]->openingStock	=	$fetchStock->stock;

						}

						else

						{

							$allProducts[$c]->openingStock	=	'-';

						}

					}

					else

					{

						$allProducts[$c]->openingStock	=	'-';

					}

					

					$allProducts[$c]->product_id		=	$fetch->product_id;

					$allProducts[$c]->product			=	$fetch->product;

					$allProducts[$c]->sku_id			=	$fetch->sku_id;

					$allProducts[$c]->sku_title			=	$fetch->sku_title;

					

					$allProducts[$c]->qty				=	$fetch->Quantity;

					$allProducts[$c]->net_total			=	$fetch->netTotal;

					$allProducts[$c]->batch				=	$fetch->batch;

					$allProducts[$c]->product_type		=	$fetch->product_type;

					$allProducts[$c]->mrp_price			=	$fetch->mrp_price;

					$allProducts[$c]->sale_price		=	$fetch->sale_price;

					$allProducts[$c]->net_tp			=	$fetch->netTotal / $fetch->Quantity;

					$allProducts[$c]->added_date		=	$fetch->added_date;

					

					$allProducts[$c]->totalQty			=	$fetch->qty;

					$allProducts[$c]->sold				=	$fetch->sold;

					$allProducts[$c]->available			=	$fetch->available;

					$allProducts[$c]->hold				=	$fetch->hold;

					$allProducts[$c]->damage			=	$fetch->damage;

					$allProducts[$c]->returned			=	$fetch->returned;

					$allProducts[$c]->expired			=	$fetch->expired;

					$c++;

				}

				return $allProducts;

			}

		}	

	}

	

	public function closingStockReport($start=0, $limit=0, $opening_date, $product='')

	{

		$DB		= new DB_connection();

		

		$where 	= '';

		$prdQry = '';

		

		if($opening_date != '')

		{

			$where = " WHERE `date` LIKE '" . $opening_date . "%'";

		}

		

		if($product != '')

		{

			$prdQry .= " WHERE p.productname LIKE '%" . $product . "%'";

		}

		

		if($start == 0 && $limit == 0)

		{

			$select = "SELECT pa.id as sku_id FROM `product` p JOIN `product_attributes` pa ON p.productid = pa.productid" . $prdQry;

			$conn	= $DB->query($select);

			return mysqli_num_rows($conn);

		}

		else

		{

			$select = "SELECT p.productid as product_id, p.productname as product, pa.id as sku_id, pa.attribute_title as sku_title  FROM `product` p JOIN `product_attributes` pa ON p.productid = pa.productid" . $prdQry . " LIMIT " . $start . ", " . $limit;

			$conn	= $DB->query($select);

			$rows	= mysqli_num_rows($conn);

			

			$c = 0;

			$allProducts = array();

			

			if($rows > 0)

			{

				while($fetch = mysqli_fetch_object($conn))

				{	

					$openingQry	= "SELECT * FROM `inv_qne_product_closing_stock`" . $where . " AND `product_id` = '" . $fetch->product_id . "' AND `sku_id` = '" . $fetch->sku_id . "'";

					$openingCon = $DB->query($openingQry);

					if(mysqli_num_rows($openingCon) > 0)

					{

						$fetOpening  = mysqli_fetch_object($openingCon); 

						$openingDate = $fetOpening->date;

						$openingQty  = $fetOpening->stock;

						$openingTP   = $fetOpening->tp;

						$openingSP   = $fetOpening->sp;

					}

					else

					{

						$openingDate = $opening_date . " 00:00:00";

						$openingQty  = 0;

						$openingTP   = 0;

						$openingSP   = 0;

					}

					

					$allProducts[$c]->product_id	=	$fetch->product_id;

					$allProducts[$c]->product		=	$fetch->product;

					$allProducts[$c]->sku_id		=	$fetch->sku_id;

					$allProducts[$c]->sku_title		=	$fetch->sku_title;

					

					$allProducts[$c]->openingStock	=	$openingQty;

					$allProducts[$c]->openingTP		=	$openingTP;

					$allProducts[$c]->openingSP		=	$openingSP;

					

					$purchaseQry	= "SELECT sum(qty) as purchaseQty FROM `inv_qne_grn_po_details` WHERE `added_date` >= '" . $openingDate . "' AND `product_id` = '" . $fetch->product_id . "' AND `sku_id` = '" . $fetch->sku_id . "'";

					$purchaseConn	= $DB->query($purchaseQry);

					

					if(mysqli_num_rows($purchaseConn) > 0)

					{

						$purchaseFet = mysqli_fetch_object($purchaseConn);

						$purchaseQty = $purchaseFet->purchaseQty;

					}

					else

					{

						$purchaseQty = 0;

					}

					

					$debitQry	= "SELECT sum(sku_qty) as debitQty FROM `inv_qne_issuance_note` qin JOIN `inv_qne_issuance_details` qid ON qin.issuance_id = qid.issuance_id  WHERE qin.status = 1 AND qid.`datetime` >= '" . $openingDate . "' AND `product_id` = '" . $fetch->product_id . "' AND `sku_id` = '" . $fetch->sku_id . "'";

					$debitConn	= $DB->query($debitQry);

					

					if(mysqli_num_rows($debitConn) > 0)

					{

						$debitFet = mysqli_fetch_object($debitConn);

						$debitQty = $debitFet->debitQty;

					}

					else

					{

						$debitQty = 0;

					}

					

					$creditQry	= "SELECT sum(credit_qty) as creditQty FROM `inv_qne_credit_note` qin JOIN `inv_qne_credit_detail` qid ON qin.credit_id = qid.credit_id  WHERE qin.status = 1 AND qid.`datetime` >= '" . $openingDate . "' AND `product_id` = '" . $fetch->product_id . "' AND `sku_id` = '" . $fetch->sku_id . "'";

					$creditConn	= $DB->query($creditQry);

					

					if(mysqli_num_rows($creditConn) > 0)

					{

						$creditFet = mysqli_fetch_object($creditConn);

						$creditQty = $creditFet->creditQty;

					}

					else

					{

						$creditQty = 0;

					}

					$orderPlaceDate = date('D d M Y h:i:s A', strtotime($openingDate));

					$normalQry 	= "SELECT sum(prd_qty) as Qty FROM z_orders_detail zod JOIN z_orders zo ON zod.ord_id = zo.order_id WHERE zo.deleted = 0 AND `size_id` = '" . $fetch->sku_id . "' AND `date_` >= '" . $opening_date . "' AND `ord_status` IN (1,2,3,4,5,6,9) AND `prod_type` = 'normal'";

					$normalConn	= mysql_query($normalQry);

					

					if(mysqli_num_rows($normalConn) > 0)

					{

						$normalFet	= mysqli_fetch_object($normalConn);

						$normalQty	= $normalFet->Qty;

					}

					else

					{

						$normalQty	= 0;

					}

							

					$bundleQry 	= "SELECT sum(offer_sku_qty * prd_qty) as Qty FROM product_offers_details pod JOIN z_orders_detail zod ON pod.offer_id = zod.prd_id JOIN z_orders zo ON zod.ord_id = zo.order_id WHERE zo.deleted = 0 AND pod.sku_id = '" . $fetch->sku_id . "' AND `date_` >= '" . $opening_date . "' AND `ord_status` IN (1,2,3,4,5,6,9) AND `prod_type` = 'bundle'";

					$bundleConn	= mysql_query($bundleQry);

					

					if(mysqli_num_rows($bundleConn) > 0)

					{

						$bundleFet	= mysqli_fetch_object($bundleConn);

						$bundleQty	= $bundleFet->Qty;

					}

					else

					{

						$bundleQty	= 0;

					}

					

					$mixQry = "SELECT sum(sku_qty) as Qty FROM z_orders_offer_details zod JOIN z_orders zo ON zod.orderId = zo.order_id WHERE zo.deleted = 0 AND `stock_sku_id` = '" . $fetch->sku_id . "' AND `date_` >= '" . $opening_date . "' AND `ord_status` IN (1,2,3,4,5,6,9)";

					$mixConn= mysql_query($mixQry);

					

					if(mysqli_num_rows($mixConn) > 0)

					{

						$mixFet	= mysqli_fetch_object($mixConn);

						$mixQty	= $mixFet->Qty;

					}

					else

					{

						$mixQty	=	0;

					}

							

					$totalSoldQty = $normalQty + $bundleQty + $mixQty;

					

					

					$allProducts[$c]->purchaseQty		=	$purchaseQty;

					$allProducts[$c]->creditQty			=	$creditQty;

					$allProducts[$c]->debitQty			=	$debitQty;

					$allProducts[$c]->soldQty			=	$totalSoldQty;

					/*$allProducts[$c]->product_type		=	$fetch->product_type;

					$allProducts[$c]->mrp_price			=	$fetch->mrp_price;

					$allProducts[$c]->sale_price		=	$fetch->sale_price;

					$allProducts[$c]->net_tp			=	$fetch->netTotal / $fetch->Quantity;

					$allProducts[$c]->added_date		=	$fetch->added_date;

					

					$allProducts[$c]->totalQty			=	$fetch->qty;

					$allProducts[$c]->sold				=	$fetch->sold;

					$allProducts[$c]->available			=	$fetch->available;

					$allProducts[$c]->hold				=	$fetch->hold;

					$allProducts[$c]->damage			=	$fetch->damage;

					$allProducts[$c]->returned			=	$fetch->returned;

					$allProducts[$c]->expired			=	$fetch->expired;*/

					$c++;

				}

				return $allProducts;

			}

		}	

	}

	public function salesPricingVirtual($product='', $start=0, $limit=0)
	{
		$DB		= new DB_connection();

        $where = '';

		if($product != '')
		{
			if($where == '')
			{
				$where .= " AND qp.productname LIKE '%" . $product . "%'";
			}
		}
		
        if($start == 0 && $limit == 0)
		{
			$select = "SELECT ps.id FROM `product` qp LEFT JOIN `product_attributes` ps ON qp.productid = ps.productid WHERE qp.is_virtual = 'y'" . $where . " ORDER BY productname ASC";
			$conn	= $DB->query($select);
			return mysqli_num_rows($conn);
		}
		else
		{
			$select = "SELECT qp.productid, qp.productname, ps.id, ps.attribute_title, ps.attribute_code, ps.price_without_discount as mrp_price, price_final as sale_price FROM `product` qp LEFT JOIN `product_attributes` ps ON qp.productid = ps.productid  WHERE qp.is_virtual = 'y'" . $where . " ORDER BY productname ASC LIMIT " . $start . ", " . $limit;
			$conn	= $DB->query($select);
			$rows	= mysqli_num_rows($conn);

            $c = 0;
			$allProducts = array();

            if($rows > 0)
			{
				while($fetch = mysqli_fetch_object($conn))
				{	
					$allProducts[$c]					=	new Purchase();
					$allProducts[$c]->product_id		=	$fetch->productid;
					$allProducts[$c]->product			=	$fetch->productname;
					$allProducts[$c]->sku_id			=	$fetch->id;
					$allProducts[$c]->sku_title			=	$fetch->attribute_title;
                    $allProducts[$c]->sku_code			=	$fetch->attribute_code;
					$allProducts[$c]->mrp_price			=	$fetch->mrp_price;
					$allProducts[$c]->sale_price		=	$fetch->sale_price;
					/*$allProducts[$c]->net_tp			=	$this->net_total / $this->qty;
					$allProducts[$c]->added_date		=	$this->added_date;
                    
                    $this->currentGRNItemDetails($fetch->sku_id, $fetch->grn_po_id);

                    if($this->net_tp != '-')
					{
						$lastTP	=	$this->net_tp;
						$tpDiff	=	$lastTP - $allProducts[$c]->net_tp;
					}
					else
					{
						$lastTP	=	"-";
						$netTP	=	$allProducts[$c]->net_tp;
						$tpDiff	=	"-";
					}

                    $allProducts[$c]->lastTP			=	$lastTP;
					$allProducts[$c]->tpDiff			=	$tpDiff;
					$allProducts[$c]->totalQty			=	$fetch->qty;
					$allProducts[$c]->sold				=	$fetch->sold;
					$allProducts[$c]->available			=	$fetch->available;
					$allProducts[$c]->hold				=	$fetch->hold;
					$allProducts[$c]->damage			=	$fetch->damage;
					$allProducts[$c]->returned			=	$fetch->returned;
					$allProducts[$c]->expired			=	$fetch->expired;*/
					$c++;
				}
				return $allProducts;
			}
		}	
	}
    
    public function salesPricingReport($product='', $start=0, $limit=0)
	{
		$DB		= new DB_connection();

        $where = '';

		if($product != '')
		{
			if($where == '')
			{
				$where .= " AND p.productname LIKE '%" . $product . "%'";
			}
		}
		//echo $where; exit();

		if($start == 0 && $limit == 0)
		{
			//$select = "SELECT ps.sku_id FROM `inv_qne_products` qp LEFT JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN (SELECT id, grn_po_id, sku_id, mrp_price, sale_price, qty, net_total, added_date FROM inv_qne_grn_po_details gpd  GROUP BY sku_id DESC ORDER BY id DESC) gd ON ps.sku_id = gd.sku_id " . $where . " GROUP BY gd.sku_id ORDER BY product ASC";
            //$select = "SELECT ps.sku_id FROM `inv_qne_products` qp LEFT JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id" . $where . " ORDER BY product ASC";
            $select = "SELECT pa.id FROM `product` p LEFT JOIN `product_attributes` pa ON p.productid = pa.productid WHERE `product_type` = 'normal'" . $where . " ORDER BY productname ASC";
			$conn	= $DB->query($select);
			return mysqli_num_rows($conn);
		}
		else
		{
			//$select = "SELECT qp.product_id, qp.product, ps.sku_id, ps.sku_title, ps.sku_code, gd.id, sum(qty) as Quantity, sum(net_total) as netTotal, gd.grn_po_id, gd.mrp_price, gd.sale_price, gd.added_date FROM `inv_qne_products` qp LEFT JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN (SELECT id, grn_po_id, sku_id, mrp_price, sale_price, qty, net_total, added_date FROM inv_qne_grn_po_details gpd GROUP BY sku_id ORDER BY id ASC) gd ON ps.sku_id = gd.sku_id " . $where . " GROUP BY gd.sku_id ORDER BY gd.id DESC LIMIT " . $start . ", " . $limit;
            //$select = "SELECT qp.product_id, qp.product, ps.sku_id, ps.sku_title, ps.sku_code FROM `inv_qne_products` qp LEFT JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id" . $where . " ORDER BY product ASC LIMIT " . $start . ", " . $limit;
            $select = "SELECT p.productid, p.productname, p.is_virtual, pa.id, pa.attribute_title, pa.attribute_code, pa.price_final, pa.price_without_discount FROM `product` p LEFT JOIN `product_attributes` pa ON p.productid = pa.productid WHERE `product_type` = 'normal'" . $where . " ORDER BY productname ASC LIMIT " . $start . ", " . $limit;
			$conn	= $DB->query($select);
			$rows	= mysqli_num_rows($conn);

            $c = 0;
			$allProducts = array();

            if($rows > 0)
			{
				while($fetch = mysqli_fetch_object($conn))
				{	
					$allProducts[$c]					=	new Purchase();
					$allProducts[$c]->product_id		=	$fetch->productid;
					$allProducts[$c]->product			=	$fetch->productname;
					$allProducts[$c]->sku_id			=	$fetch->id;
					$allProducts[$c]->sku_title			=	$fetch->attribute_title;
                    $allProducts[$c]->sku_code			=	$fetch->attribute_code;
                    $allProducts[$c]->skuPrice			=	$fetch->price_final;
                    $allProducts[$c]->skuMRP			=	$fetch->price_without_discount;
                    $allProducts[$c]->is_virtual    	=	$fetch->is_virtual;
					//$this->lastGRNItemDetails($fetch->sku_id, 0);
                    $this->currentGRNItemDetails($fetch->sku_id, 0);
                    $allProducts[$c]->id				=	$this->id;
					$allProducts[$c]->qty				=	$this->qty;
					$allProducts[$c]->net_total			=	$this->net_total;
					$allProducts[$c]->mrp_price			=	$this->mrp_price;
					$allProducts[$c]->sale_price		=	$this->sale_price;
					$allProducts[$c]->net_tp			=	$this->net_total / $this->qty;
					$allProducts[$c]->added_date		=	$this->added_date;
                    
                    //$this->currentGRNItemDetails($fetch->sku_id, $fetch->grn_po_id);

                    if($this->net_tp != '-')
					{
						$lastTP	=	$this->net_tp;
						$tpDiff	=	$lastTP - $allProducts[$c]->net_tp;
					}
					else
					{
						$lastTP	=	"-";
						$netTP	=	$allProducts[$c]->net_tp;
						$tpDiff	=	"-";
					}

                    $allProducts[$c]->lastTP			=	$lastTP;
					$allProducts[$c]->tpDiff			=	$tpDiff;
					$allProducts[$c]->totalQty			=	$fetch->qty;
					$allProducts[$c]->sold				=	$fetch->sold;
					$allProducts[$c]->available			=	$fetch->available;
					$allProducts[$c]->hold				=	$fetch->hold;
					$allProducts[$c]->damage			=	$fetch->damage;
					$allProducts[$c]->returned			=	$fetch->returned;
					$allProducts[$c]->expired			=	$fetch->expired;
					$c++;
				}
				return $allProducts;
			}
		}	
	}

    public function pricingReport($start=0, $limit=0, $distributor='', $company='', $product='')

	{

		$DB		= new DB_connection();

		

		$where = '';

		if($distributor != '')

		{

			$where = " WHERE qd.distributor_id = " . $distributor;

		}

		

		if($company != '')

		{

			if($where == '')

			{

				$where .= " WHERE qpc.company_id = " . $company;

			}

			else

			{

				$where .= " || qpc.company_id = " . $company;

			}

		}

		

		if($product != '')

		{

			if($where == '')

			{

				$where .= " WHERE qp.product LIKE '%" . $product . "%'";

			}

			else

			{

				$where .= " || qp.product LIKE '%" . $product . "%'";

			}

		}

		//echo $where; exit();

		if($start == 0 && $limit == 0)

		{

			//$select = "SELECT ps.sku_id FROM `inv_qne_products` qp JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id" . $where;

			$select = "SELECT ps.sku_id FROM `inv_qne_products` qp LEFT JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN `inv_qne_company` qpc ON qpc.company_id = qp.company LEFT JOIN `inv_qne_company_distributor` qcd ON qpc.company_id = qcd.distributor_id LEFT JOIN `inv_qne_distributor` qd ON qd.distributor_id = qcd.distributor_id LEFT JOIN (SELECT id, grn_po_id, sku_id, mrp_price, sale_price, qty, net_total, added_date FROM inv_qne_grn_po_details gpd ORDER BY id DESC) gd ON ps.sku_id = gd.sku_id " . $where . " GROUP BY gd.sku_id ORDER BY product ASC";

			$conn	= $DB->query($select);

			return mysqli_num_rows($conn);

		}

		else

		{

			//$select = "SELECT qp.product_id, qp.product, ps.sku_id, ps.sku_title, qpc.company, qd.distributor, gd.grn_po_id, gd.mrp_price, gd.sale_price, gd.added_date FROM `inv_qne_products` qp JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN `inv_qne_company` qpc ON qpc.company_id = qp.company LEFT JOIN `inv_qne_company_distributor` qcd ON qpc.company_id = qcd.distributor_id LEFT JOIN `inv_qne_distributor` qd ON qd.distributor_id = qcd.distributor_id LEFT JOIN `inv_qne_grn_po_details` gd ON ps.sku_id = gd.sku_id " . $where . " GROUP BY ps.sku_id ORDER BY product ASC LIMIT " . $start . ", " . $limit;

			$select = "SELECT qp.product_id, qp.product, ps.sku_id, ps.sku_title, qpc.company, qd.distributor, gd.id, sum(qty) as Quantity, sum(net_total) as netTotal, gd.grn_po_id, gd.mrp_price, gd.sale_price, gd.added_date FROM `inv_qne_products` qp LEFT JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN `inv_qne_company` qpc ON qpc.company_id = qp.company LEFT JOIN `inv_qne_company_distributor` qcd ON qpc.company_id = qcd.distributor_id LEFT JOIN `inv_qne_distributor` qd ON qd.distributor_id = qcd.distributor_id LEFT JOIN (SELECT id, grn_po_id, sku_id, mrp_price, sale_price, qty, net_total, added_date FROM inv_qne_grn_po_details gpd ORDER BY id DESC) gd ON ps.sku_id = gd.sku_id " . $where . " GROUP BY gd.sku_id ORDER BY product ASC LIMIT " . $start . ", " . $limit;

			$conn	= $DB->query($select);

			$rows	= mysqli_num_rows($conn);

			

			$c = 0;

			$allProducts = array();

			

			if($rows > 0)

			{

				while($fetch = mysqli_fetch_object($conn))

				{	

					$allProducts[$c]					=	new Purchase();

					/*if($start_date != '')

					{

						$selectStock = "SELECT stock FROM `inv_qne_product_closing_stock` WHERE `date` = '" . $start_date . "' AND `sku_id` = " . $fetch->sku_id;

						$connStock	= $DB->query($selectStock);

						$rowsStock	= mysqli_num_rows($connStock);

						if($rowsStock > 0)

						{

							$fetchStock 					= 	mysqli_fetch_object($connStock);

							$allProducts[$c]->openingStock	=	$fetchStock->stock;

						}

						else

						{

							$allProducts[$c]->openingStock	=	'-';

						}

					}

					else

					{

						$allProducts[$c]->openingStock	=	'-';

					}*/

					

					$allProducts[$c]->product_id		=	$fetch->product_id;

					$allProducts[$c]->product			=	$fetch->product;

					$allProducts[$c]->sku_id			=	$fetch->sku_id;

					$allProducts[$c]->sku_title			=	$fetch->sku_title;

					$allProducts[$c]->company			=	$fetch->company;

					$allProducts[$c]->distributor		=	$fetch->distributor;

					$allProducts[$c]->id				=	$fetch->id;

					$allProducts[$c]->qty				=	$fetch->Quantity;

					$allProducts[$c]->net_total			=	$fetch->netTotal;

					//$allProducts[$c]->batch				=	$fetch->batch;

					//$allProducts[$c]->product_type		=	$fetch->product_type;

					$allProducts[$c]->mrp_price			=	$fetch->mrp_price;

					$allProducts[$c]->sale_price		=	$fetch->sale_price;

					$allProducts[$c]->net_tp			=	$fetch->netTotal / $fetch->Quantity;

					$allProducts[$c]->added_date		=	$fetch->added_date;

					

					//$this->lastGRNItemDetails($fetch->sku_id, $fetch->grn_po_id);

                    $this->currentGRNItemDetails($fetch->sku_id, $fetch->grn_po_id);

													

					if($this->net_tp != '-')

					{

						$lastTP	=	$this->net_tp;

						//$netTP	=	$grn->net_total / $grn->qty;

						$tpDiff	=	$lastTP - $allProducts[$c]->net_tp;//$netTP;

					}

					else

					{

						$lastTP	=	"-";//$purchaseModel->net_tp;

						$netTP	=	$allProducts[$c]->net_tp;//$grn->net_total / $grn->qty;

						$tpDiff	=	"-";

					}

					$allProducts[$c]->lastTP			=	$lastTP;

					$allProducts[$c]->tpDiff			=	$tpDiff;

					

					$allProducts[$c]->totalQty			=	$fetch->qty;

					$allProducts[$c]->sold				=	$fetch->sold;

					$allProducts[$c]->available			=	$fetch->available;

					$allProducts[$c]->hold				=	$fetch->hold;

					$allProducts[$c]->damage			=	$fetch->damage;

					$allProducts[$c]->returned			=	$fetch->returned;

					$allProducts[$c]->expired			=	$fetch->expired;

					$c++;

				}

				return $allProducts;

			}

		}	

	}

	

	public function batchReport($start=0, $limit=0, $product='', $expiryDate='')

	{

		$DB		= new DB_connection();

		

		$where = " WHERE (gd.status != 'Close' || gd.status != 'close')";

		if($expiryDate != '')

		{

			$where .= " AND gd.expiry_date <= '" . $expiryDate . "'";

		}

		

		if($product != '')

		{

			$where .= " AND qp.product LIKE '%" . $product . "%'";

		}

		//echo $where; exit();

		if($start == 0 && $limit == 0)

		{

			//$select = "SELECT ps.sku_id FROM `inv_qne_products` qp JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id" . $where;

			$select = "SELECT qp.product_id FROM `inv_qne_products` qp LEFT JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN `inv_qne_company` qpc ON qpc.company_id = qp.company LEFT JOIN `inv_qne_company_distributor` qcd ON qpc.company_id = qcd.distributor_id LEFT JOIN `inv_qne_distributor` qd ON qd.distributor_id = qcd.distributor_id LEFT JOIN `inv_qne_grn_po_details` gd ON ps.sku_id = gd.sku_id " . $where;

			$conn	= $DB->query($select);

			return mysqli_num_rows($conn);

		}

		else

		{

			$select = "SELECT qp.product_id, qp.product, ps.sku_id, ps.sku_title, qpc.company, qd.distributor, gd.id, gd.qty, gd.sold_qty, gd.net_total, gd.grn_po_id, gd.batch, gd.expiry_date, gd.status, gd.added_date, po.grn_id FROM `inv_qne_products` qp LEFT JOIN `inv_qne_product_sku` ps ON qp.product_id = ps.product_id LEFT JOIN `inv_qne_company` qpc ON qpc.company_id = qp.company LEFT JOIN `inv_qne_company_distributor` qcd ON qpc.company_id = qcd.distributor_id LEFT JOIN `inv_qne_distributor` qd ON qd.distributor_id = qcd.distributor_id LEFT JOIN `inv_qne_grn_po_details` gd ON ps.sku_id = gd.sku_id  LEFT JOIN `inv_qne_grn_po` po ON gd.grn_po_id = po.grn_po_id " . $where . " ORDER BY product ASC, sku_title ASC LIMIT " . $start . ", " . $limit;

			$conn	= $DB->query($select);

			$rows	= mysqli_num_rows($conn);

			

			$c = 0;

			$allProducts = array();

			

			if($rows > 0)

			{

				while($fetch = mysqli_fetch_object($conn))

				{	

					$allProducts[$c]					=	new Purchase();

					$allProducts[$c]->product_id		=	$fetch->product_id;

					$allProducts[$c]->grn_po_id			=	$fetch->grn_id;

					$allProducts[$c]->product			=	$fetch->product;

					$allProducts[$c]->sku_id			=	$fetch->sku_id;

					$allProducts[$c]->sku_title			=	$fetch->sku_title;

					$allProducts[$c]->company			=	$fetch->company;

					$allProducts[$c]->distributor		=	$fetch->distributor;

					$allProducts[$c]->id				=	$fetch->id;

					$allProducts[$c]->qty				=	$fetch->qty;

					$allProducts[$c]->sold_qty			=	$fetch->sold_qty;

					$allProducts[$c]->net_total			=	$fetch->netTotal;

					$allProducts[$c]->batch				=	$fetch->batch;

					$allProducts[$c]->expiry_date		=	$fetch->expiry_date;

					$allProducts[$c]->mrp_price			=	$fetch->mrp_price;

					$allProducts[$c]->sale_price		=	$fetch->sale_price;

					$allProducts[$c]->net_tp			=	$fetch->netTotal / $fetch->Quantity;

					$allProducts[$c]->status			=	$fetch->status;

					$allProducts[$c]->added_date		=	$fetch->added_date;

					

					$c++;

				}

				return $allProducts;

			}

		}	

	}

	

	public function getDebitNoteDateRange($sku_id, $start_date='', $end_date='')

	{

		$DB		= new DB_connection();

		$where = '';

		if($start_date != '')

		{

			$where = " AND datetime >= '" . $start_date . "'";

		}

		

		if($end_date != '')

		{

			$where = " AND datetime <= '" . $end_date . "'";

		}

		$select_2 = "SELECT sum(sku_qty) as skuQty FROM `inv_qne_issuance_details` WHERE `sku_id` = " . $sku_id . "" . $where;

		$conn_2	= $DB->query($select_2) or die(mysql_error());

		$fet_2 	= mysqli_fetch_object($conn_2);

		mysql_close();

		

		if($fet_2->skuQty > 0)

		{

			return $fet_2->skuQty ;

		}

		else

		{

			return 0;

		}	

	}

    public function allGRNItemDetails($grn_po_id=0)
	{
		$DB		= new DB_connection();
		if(is_numeric($grn_po_id) && $grn_po_id > 0)
		{
			$where .= " WHERE pd.grn_po_id = " . $grn_po_id;
		}

        $select = "SELECT pd.*, qgp.grn_id, qp.product, ps.sku_title, ps.sku_code FROM `inv_qne_grn_po_details` pd LEFT JOIN `inv_qne_grn_po` qgp ON pd.grn_po_id = qgp.grn_po_id LEFT JOIN `inv_qne_products` qp ON pd.product_id = qp.product_id LEFT JOIN `inv_qne_product_sku` ps ON pd.sku_id = ps.sku_id" . $where;
		$conn	= $DB->query($select);
		$rows	= mysqli_num_rows($conn);

        if($rows > 0)
		{
			$c = 0;
			$productSKU = array();

            while($fetch = mysqli_fetch_object($conn))
			{
				$productSKU[$c]						= new Purchase();
				$productSKU[$c]->id					= $fetch->id;
                $productSKU[$c]->grn_id			    = $fetch->grn_id;
				$productSKU[$c]->grn_po_id			= $fetch->grn_po_id;
				$productSKU[$c]->product_id			= $fetch->product_id;
				$productSKU[$c]->sku_id				= $fetch->sku_id;
				$productSKU[$c]->qty				= $fetch->qty;
				$productSKU[$c]->price				= $fetch->price;
				$productSKU[$c]->sub_total			= $fetch->sub_total;
				$productSKU[$c]->tax				= $fetch->tax;
				$productSKU[$c]->discount			= $fetch->discount;
				$productSKU[$c]->discount_type		= $fetch->discount_type;
				$productSKU[$c]->net_total			= $fetch->net_total;
				$productSKU[$c]->batch				= $fetch->batch;
				$productSKU[$c]->manufacturing_date	= $fetch->manufacturing_date;
				$productSKU[$c]->expiry_date		= $fetch->expiry_date;
				$productSKU[$c]->product_type		= $fetch->product_type;
				$productSKU[$c]->mrp_price			= $fetch->mrp_price;
				$productSKU[$c]->sale_price			= $fetch->sale_price;
				$productSKU[$c]->added_date			= $fetch->added_date;
				$productSKU[$c]->product			= $fetch->product;
				$productSKU[$c]->sku_title			= $fetch->sku_title;
				$productSKU[$c]->sku_code			= $fetch->sku_code;
				$c++;
			}
			return $productSKU;
		}
	}

	public function updateSaleQnEPrice()
	{
		$DB		= new DB_connection();
        extract($_POST);
        if(is_numeric($grnItemID) && $grnItemID != 0)
		{
            $salePrice = $mrpPrice - $qne_discount;
            $grn_po_id = $grnPOID;
			$update = "UPDATE `inv_qne_grn_po_details` SET `sale_price` = '" . $salePrice . "' WHERE `sku_id` = '" . $skuID . "' AND `grn_po_id` = '" . $grn_po_id . "'";
			$conn	= $DB->query($update) or die(mysql_error());
		}

        $salePrice          = $mrpPrice - $qne_discount - $company_discount - $other_discount;
        $discount 			= $mrpPrice - $salePrice;
        $discount_percent 	= number_format(($discount / $mrpPrice) * 100, 2, '.', '');
        $update2 = "UPDATE `product_attributes` SET `price` = '" . $mrpPrice . "', `tax_value` = '" . $tax_amount . "', `discount_percent` = '" . $discount_percent . "', `price_final` = '" . $salePrice . "', `qne_discount` = '" . $qne_discount . "', `company_discount` = '" . $company_discount . "', `other_discount` = '" . $other_discount . "', `price_without_discount` = '" . $mrpPrice . "' WHERE `id` = '" . $skuID . "'";
		$conn2	= $DB->query($update2) or die(mysql_error());
        
        /*echo "1<br />".$update2 = "UPDATE `inv_qne_product_price` SET `cost_price_tax` = '" . $salePrice . "', `qne_discount` = '" . $qne_discount . "', `company_discount` = '" . $company_discount . "', `retail_price_tax` = '" . $mrpPrice . "' WHERE `sku_id` = '" . $skuID . "'";
        $DB->query($update2) or die(mysql_error());*/
        $update2 = "INSERT INTO `inv_qne_product_price` (`product_id`, `sku_id`, `cost_price`, `cost_price_tax`, `qne_discount`, `company_discount`, `retail_price`, `retail_price_tax`, `modify_date`, `date`) VALUES('" . $productID . "', '" . $skuID . "', '" . $salePrice . "', '" . $salePrice . "', '" . $qne_discount . "', '" . $company_discount . "', '" . $mrpPrice . "', '" . $mrpPrice . "', '" . date('Y-m-d') . "', '" . date('Y-m-d') . "')";
		$DB->query($update2) or die(mysql_error());
        
        $adminQry	= "INSERT INTO `log` (`id`, `task_performed`, `username`, `date`, `product_id`, value) VALUES('', 'PRICE CHANGE: ITEM SKU ID: " . $skuID . ", SP: " . $salePrice . "', '" . $_SESSION['sess_username'] . "', '" . date('Y-m-d H:i:s') . "', '" . $productID . "', '1')";
        $DB->query($adminQry);
        return true;
	}

	public function updateSalePrice($sku_id, $grnItemID, $salePrice)

	{

		$DB		= new DB_connection();

		

		if(is_numeric($grnItemID) && $grnItemID != 0)

		{

			$update = "UPDATE `inv_qne_grn_po_details` SET `sale_price` = '" . $salePrice . "' WHERE `id` = '" . $grnItemID . "'";

			$conn	= $DB->query($update) or die(mysql_error());

		}



		$select = "SELECT price, tax_type, tax_value, gst_percent, price_without_discount FROM `product_attributes` WHERE `id` = " . $sku_id;

		$conn	= $DB->query($select) or die(mysql_error());

		$rows	= mysqli_num_rows($conn);



		$discount_percent = 0;

		if($rows > 0)

		{

			$fetch 		= mysqli_fetch_object($conn);

			

			$mrp_price		= $fetch->price_without_discount;

			$tax_type		= $fetch->tax_type;

			$tax_value		= $fetch->tax_value;

			$tax_percent	= $fetch->gst_percent;

			$price			= $fetch->price;

				

			if($mrp_price > 0)

			{

				$discount 			= $mrp_price - $salePrice;

				$discount_percent 	= number_format(($discount / $mrp_price) * 100, 2, '.', '');

			}

			

			if($tax_type == "GST")

			{

				$tax_value = number_format($salePrice * $tax_percent / 100, 2, '.', '');

				$price     = $mrp_price - $tax_value;

			}

		}	

		

		$update2 = "UPDATE `product_attributes` SET `price` = '" . $price . "', `tax_value` = '" . $tax_value . "', `discount_percent` = '" . $discount_percent . "', `price_final` = '" . $salePrice . "' WHERE `id` = '" . $sku_id . "'";

		$conn2	= $DB->query($update2) or die(mysql_error());

		

		return true;

	}

    public function updateVirtualPrice($sku_id, $salePrice)
	{
		$DB		= new DB_connection();

        $select = "SELECT productid, price, tax_type, tax_value, gst_percent, price_without_discount FROM `product_attributes` WHERE `id` = " . $sku_id;
		$conn	= $DB->query($select) or die(mysql_error());
		$rows	= mysqli_num_rows($conn);

        $discount_percent = 0;
		if($rows > 0)
		{
			$fetch 		    = mysqli_fetch_object($conn);
            $productID		= $fetch->productid;
			$mrp_price		= $fetch->price_without_discount;
			$tax_type		= $fetch->tax_type;
			$tax_value		= $fetch->tax_value;
			$tax_percent	= $fetch->gst_percent;
			$price			= $fetch->price;

            if($mrp_price > 0)
			{
				$discount 			= $mrp_price - $salePrice;
				$discount_percent 	= number_format(($discount / $mrp_price) * 100, 2, '.', '');
			}

            if($tax_type == "GST")
			{
				$tax_value = number_format($salePrice * $tax_percent / 100, 2, '.', '');
				$price     = $mrp_price - $tax_value;
			}
		}	

        $update2 = "UPDATE `product_attributes` SET `price` = '" . $price . "', `tax_value` = '" . $tax_value . "', `discount_percent` = '" . $discount_percent . "', `price_final` = '" . $salePrice . "' WHERE `id` = '" . $sku_id . "'";
		$conn2	= $DB->query($update2) or die(mysql_error());
        
        $adminQry	= "INSERT INTO `log` (`id`, `task_performed`, `username`, `date`, `product_id`) VALUES('', 'Price Updated. Product ID: " . $productID . " Price: " . $price . "', '" . $_SESSION['sess_username'] . "', '" . date('Y-m-d H:i:s') . "', '" . $productID . "')";
        $DB->query($adminQry);
		return true;
	}
	

	public function updateMRPPrice($sku_id, $grnItemID, $mrpPrice)

	{

		$DB		= new DB_connection();

		if(is_numeric($grnItemID) && $grnItemID != 0)

		{

			$update = "UPDATE `inv_qne_grn_po_details` SET `mrp_price` = '" . $mrpPrice . "' WHERE `id` = '" . $grnItemID . "'";

			$conn	= $DB->query($update) or die(mysql_error());

		}

		

		$update2 = "UPDATE `product_attributes` SET `price_without_discount` = '" . $mrpPrice . "' WHERE `id` = '" . $sku_id . "'";

		$conn2	= $DB->query($update2) or die(mysql_error());

		

		return true;

	}

	

	public function purchaseOrderItems($po_number, $productID, $skuID)

	{

		$DB		= new DB_connection();

		

		$Where = "";

		if($productID != '' && $productID != 0)

		{

			$Where .= " AND `product_id` = " . $productID;

		}

		

		if($skuID != '' && $skuID != 0)

		{

			$Where .= " AND `product_sku_id` = " . $skuID;

		}

		

		$select = "SELECT * FROM `inv_qne_purchase_order_items` poi WHERE `po_id` = " . $po_number . $Where;

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);



		if($rows > 0)

		{

			$c = 0;

			$productSKU = array();



			while($fetch = mysqli_fetch_object($conn))

			{

				$productSKU[$c]						=	new Purchase();

				$productSKU[$c]->po_item_id			=	$fetch->po_item_id;

				$productSKU[$c]->po_id				=	$fetch->po_id;

				$productSKU[$c]->product_id			=	$fetch->product_id;

				$productSKU[$c]->product_sku_id		=	$fetch->product_sku_id;

				$productSKU[$c]->order_qty			=	$fetch->order_qty;

				$productSKU[$c]->received_qty		=	$fetch->received_qty;

				$productSKU[$c]->unit_price			=	$fetch->unit_price;

				$productSKU[$c]->unit_price_tax		=	$fetch->unit_price_tax;

				$productSKU[$c]->total_price		=	$fetch->total_price;

				$productSKU[$c]->date				=	$fetch->date;

				$c++;

			}

			return $productSKU;

		}

	}

	

	public function updatePOItems($po_item_id, $newQty, $price)

	{

		$DB		= new DB_connection();

		

		$update = "UPDATE `inv_qne_purchase_order_items` SET `order_qty` = '" . $newQty . "', `total_price` = '" . number_format($newQty * $price, 2, '.', '') . "' WHERE `po_item_id` = " . $po_item_id;

		if($DB->query($update))

		{

			return true;

		}

	}

	

	

	public function addPOItem()

	{

		$DB		= new DB_connection();

		

		extract($_POST);

		$totalPrice = $Qty * $price;

		//$update = "UPDATE `inv_qne_purchase_order_items` SET `order_qty` = '" . $newQty . "', `total_price` = '" . number_format($newQty * $price, 2, '.', '') . "' WHERE `po_item_id` = " . $po_item_id;

		echo $insert = "INSERT INTO `inv_qne_purchase_order_items` (`po_item_id`, `po_id`, `product_id`, `product_sku_id`, `order_qty`, `received_qty`, `unit_price`, `unit_price_tax`, `total_price`, `status`, `date`) VALUES ('', " . $po_id . ", " . $productID . ", " . $skuID . ",  " . $Qty . ", " . $price . ", " . $price . ", " . $totalPrice . ", 0, 'Pending', '" . date('Y-m-d H:i:s') . "')";

		if($DB->query($insert))

		{

			return mysql_insert_id();

		}

		else

		{

			return 0;

		}

	}

	

	

	public function deletePOItem($po_item_id)

	{

		$DB		= new DB_connection();

		

		$update = "DELETE FROM `inv_qne_purchase_order_items` WHERE `po_item_id` = " . $po_item_id;

		if($DB->query($update))

		{

			return true;

		}

	}

	

	public function getProductBySKU($sku_id)

	{

		$DB		= new DB_connection();

		

		$select = "SELECT * FROM `product_attributes` pa WHERE pa.id = " . $sku_id;

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);



		if($rows > 0)

		{

			$fetch = mysqli_fetch_object($conn);

		

			$this->id						=	$fetch->id;

			$this->productid				=	$fetch->productid;

			$this->whearhouse_id			=	$fetch->whearhouse_id;

			$this->attribute_title			=	$fetch->attribute_title;

			$this->attribute_code			=	$fetch->attribute_code;

			$this->price					=	$fetch->price;

			$this->stock_qty				=	$fetch->stock_qty;

			$this->minStock_qty				=	$fetch->minStock_qty;

			$this->max_order_qty			=	$fetch->max_order_qty;

			$this->discount_percent			=	$fetch->discount_percent;

			$this->tax_type					=	$fetch->tax_type;

			$this->gst_percent				=	$fetch->gst_percent;

			$this->tax_value				=	$fetch->tax_value;

			$this->price_final				=	$fetch->price_final;

			$this->price_without_discount	=	$fetch->price_without_discount;

			$this->org_img					=	$fetch->org_img;

			$this->thumbnail_img			=	$fetch->thumbnail_img;

			$this->created					=	$fetch->created;

		}

	}

	

	public function allGIN($ids='', $status='')

	{

		$DB		= new DB_connection();



		$Where	= "";

		if($status != '')

		{

			$Where	=	" AND `status` IN ('" . $status . "')";

		}

		

		if($ids != '')

		{

			$Where	=	" AND `grf_id` IN (" . $ids . ")";

		}



		//$select = "SELECT * FROM (SELECT * FROM `inv_qne_purchase_order` WHERE 1=1 " . $Where . " ORDER BY purchase_id DESC) `inv_qne_purchase_order` GROUP BY po_number";

		$select = "SELECT * FROM `inv_qne_goods_return` WHERE 1=1 " . $Where . " ORDER BY grf_id DESC";

		$conn	= $DB->query($select);

		$rows	= mysqli_num_rows($conn);

		

		$c = 0;

		$GIN = array();

		

		if($rows > 0)

		{

			while($fetch = mysqli_fetch_object($conn))

			{

				$GIN[$c]				=	new Purchase();

				$GIN[$c]->grf_id	=	$fetch->grf_id;

				$GIN[$c]->version		=	$fetch->version;

				$GIN[$c]->grf_date		=	$fetch->grf_date;

				$GIN[$c]->distributor	=	$fetch->distributor;

				$GIN[$c]->company		=	$fetch->company;

				$GIN[$c]->brand			=	$fetch->brand;

				$GIN[$c]->description	=	$fetch->description;

				$GIN[$c]->status		=	$fetch->status;

				$GIN[$c]->datetime		=	$fetch->datetime;

				$c++;

			}

			return $GIN;	

		}

	}

}

?>
