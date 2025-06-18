<?php
	error_reporting(0); 
	if(isset($_REQUEST['po_number']) && is_numeric($_REQUEST['po_number']))
	{
		$po_number	=	$_REQUEST['po_number'];
	}

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=new-allGRN-export.xls");
	include('class/class.php'); 
	
	$generalModel	= 	new General();
	$productModel	= 	new Product();
	$purchaseModel	= 	new Purchase();

    $_SESSION['po_number']	=	$_REQUEST['po_number'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>Untitled Document</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>
    <body>
        <table border="1">
            <tr>
                <th>No.</th>
                <th>Barcode</th>
                <th class="numeric">Product ID</th>
                <th class="numeric">SKU ID</th>
                <th class="numeric">GRN Number</th>				
                <th class="numeric">Title</th>
                <th class="numeric">SKU Title</th>
                <th class="numeric">Qty</th>
                <th class="numeric">Cost Price</th>
                <th class="numeric">Disc.Amount</th>
                <th class="numeric">Disc. Percent</th>
                <th class="numeric">GST Amount</th>
                <th class="numeric">GST Percent</th>
                <th class="numeric">Tax Type</th>
                <th class="numeric">Net Amount</th>
                <th class="numeric">Net TP</th>
                <th class="numeric">S. Price</th>
                <th class="numeric">MRP</th>
                <th class="numeric">GP Amount</th>
                <th class="numeric">GP Percent</th>
                <th class="numeric">Expiry Date</th>
                <th class="numeric">Date</th>
            </tr>
<?php       $grn_number = 0;
            if(isset($_REQUEST['grn_number']) && is_numeric($_REQUEST['grn_number']))	
            {
                $grn_number = $_REQUEST['grn_number'];
            }

            $grnDetails	=	$purchaseModel->allGRNItemDetails($grn_number);
            if(sizeof($grnDetails) > 0)
            {
                $totalQty				= 0;
                $totalSalePrice			= 0;
                $totalGrossAmount		= 0;
                $totalDiscountAmount    = 0;
                $totalGSTAmount			= 0;
                $totalNetAmount			= 0;
                $totalGrossProfit		= 0;
                $cntRows				= 1;

                foreach($grnDetails as $grn)
                {
                    $productDetail = $productModel->allProducts($grn->product_id);
                    if($grn->discount_type == "percent")
                    {
                        $discount_amt = number_format($grn->qty * $grn->price * $grn->discount / 100, 2);
                        $discount_per =	$grn->discount;
                    }
                    else
                    {
                        $discount_amt = number_format($grn->discount, 2);
                        $discount_per = number_format($grn->discount * 100 / $grn->sub_total,2);
                    }

                    if($grn->tax != 0)
                    {
                        $taxAmount	= $grn->tax;
                        $taxPercent	= number_format($grn->tax * 100 / $grn->sub_total,2);
                    }
                    else
                    {
                        $taxAmount	= 0;
                        $taxPercent	= 0;
                    }

                    $purchaseModel->lastGRNItemDetails($grn->sku_id, $grn_number);

                    if($purchaseModel->net_tp != '-')
                    {
                        $lastTP	= $purchaseModel->net_tp;
                        $netTP	= $grn->net_total / $grn->qty;
                        $tpDiff	= $lastTP - $netTP;
                    }
                    else
                    {
                        $lastTP	= $purchaseModel->net_tp;
                        $netTP	= $grn->net_total / $grn->qty;
                        $tpDiff	= "-";
                    }	

                    $sale_price	= $grn->sale_price;
                    $mrp_price	= $grn->mrp_price;

                    $gp_amount	= $sale_price - $netTP;
                    $gp_percent	= $gp_amount * 100 / $sale_price;

                    $totalQty			+= $grn->qty;
                    $totalSalePrice		+= $sale_price * $grn->qty;
                    $totalGrossAmount	+= $grn->sub_total;
                    $totalDiscountAmount+= $discount_amt;
                    $totalGSTAmount		+= $taxAmount;
                    $totalNetAmount		+= $grn->net_total;
                    $totalGrossProfit	+= $gp_amount;
?>                  <tr <?php if($grn->product_type == "Bonus"){?> style="color:#FAA71D;"<?php } ?>>
                        <td align="center" data-title="No."><?php echo $cntRows; ?></td>
                        <td data-title="Code"><?php echo $grn->sku_code; ?></td>
                        <td data-title="Code"><?php echo $grn->product_id; ?></td>
                        <td data-title="Code"><?php echo $grn->sku_id; ?></td>
                        <td data-title="Code"><?php echo $grn->grn_id; ?></td>	
                        <td data-title="Title"><?php echo $grn->product; ?></td>
                        <td data-title="Title"><?php echo $grn->sku_title; ?></td>
                        <td data-title="Qty" align="center"><?php echo $grn->qty; ?></td>
                        <td data-title="G. Amount" align="center" class="numeric"><?php echo number_format($grn->sub_total, 2, '.', ''); ?></td>
                        <td data-title="Disc.(%)" align="center" class="numeric"><?php echo $discount_amt; ?></td>
                        <td data-title="Disc.(%)" align="center" class="numeric"><?php echo $discount_per; ?></td>
                        <td data-title="GST(%)" align="center" class="numeric"><?php echo $taxAmount; ?></td>
                        <td data-title="GST(%)" align="center" class="numeric"><?php echo $taxPercent; ?></td>
                        <td data-title="Tax Type" align="center" class="numeric"><?php echo $productDetail[0]->tax; ?></td>
                        <td data-title="Net Amount" align="center" class="numeric"><?php echo number_format($grn->net_total, 2, '.', ''); ?></td>
                        <td data-title="Net TP" align="center" class="numeric"><?php echo number_format($netTP, 2, '.', ''); ?></td>
                        <td data-title="Sale Price" align="center" class="numeric"><?php echo number_format($sale_price, 2, '.', ''); ?></td>
                        <td data-title="MRP" align="center" class="numeric"><?php echo number_format($mrp_price, 2, '.', ''); ?></td>
                        <td data-title="GP Amount(%)" align="center" class="numeric"><span id="net_tp_<?php echo $cntRows; ?>"><?php echo number_format($gp_amount,2, '.', ''); ?></span></td>
                        <td data-title="GP Amount(%)" align="center" class="numeric"><span id="net_tp_<?php echo $cntRows; ?>"><?php echo number_format($gp_percent,2, '.', ''); ?></span></td>
                        <td data-title="Expiry Date" align="center" class="numeric"><?php echo date('d/m/Y', strtotime($grn->expiry_date));//echo date('m/d/Y', strtotime($grn->expiry_date)); ?></td>
                        <td data-title="GRN Date" align="center" class="numeric"><?php echo date('d/m/Y', strtotime($grn->added_date)); ?></td>
                    </tr>
<?php			    $cntRows++;
                }
            }
?>      </table>
    </body>
</html>