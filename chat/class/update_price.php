<?php
	ini_set('max_execution_time', 99000);
	error_reporting(0); 
	header("Connection: Keep-alive");
	include('class/class.php'); 
	
	$generalModel	= 	new General();
	$productModel	= 	new Product();
	$purchaseModel	= 	new Purchase();

    extract($_REQUEST);
	
	if(isset($_POST['action']) && $_POST['action'] == 'updatePrice')
	{
       // echo "<pre>"; print_r($_POST); echo "</pre>";
		$purchaseModel->updateSaleTVBPrice();//exit(); // Function name in Purchase class needs to be updated
        echo "<script>window.close(); window.opener.location.reload();</script>";
	}
	else
	{
        $skuID      = $_GET['sku_id'];
        $productID  = $_GET['product_id'];
        //$productModel->productSkuById($skuID);
        $productModel->productAttrById($skuID);
        $purchaseModel->lastGRNItemDetails($skuID);
        $productModel->productByID($productID);
        $generalModel->taxById($productModel->tax_type);
        
        $mrpPrice        = $purchaseModel->mrp_price;
        $tvbDiscount     = $productModel->qne_discount; // Using existing DB field name
        $companyDiscount = $productModel->company_discount;
        $taxAmount       = 0;
        $taxType         = $generalModel->tax_type;
        $taxValue        = $generalModel->tax_value;
        $grnPOID         = $purchaseModel->grn_po_id;
        
        if($taxType == "Fixed")
        {
            $retail_price= ($mrpPrice) / (1 + $taxValue / 100);
            $taxAmount	 = number_format($mrpPrice - $retail_price,2,'.','');
            $gstValue    = $mrpPrice - $tvbDiscount - $taxAmount;
        }
        else
        if($taxType == "Normal")
        {
            $retail_price= ($mrpPrice - $tvbDiscount) / (1 + $taxValue / 100);
            $taxAmount	 = number_format($mrpPrice - $tvbDiscount - $retail_price,2,'.','');
            $gstValue    = $mrpPrice - $tvbDiscount - $taxAmount;
        }
        else
        {
            $retail_price= ($mrpPrice - $tvbDiscount) / (1 + $taxValue / 100);
            $taxAmount	 = number_format($mrpPrice - $tvbDiscount - $retail_price,2,'.','');
            $gstValue    = $mrpPrice - $tvbDiscount - $taxAmount;
        }
        
        $netSP           = $mrpPrice - $tvbDiscount - $companyDiscount;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<style>
			input[type="number"] { width:150px; height:25px; border:1px solid #ccc; padding:2px; }
			input[type="number"]:hover { }
			.productTitle{ font-family:Segoe UI; font-weight:bold; text-align:left; }
			.title{ color:#76BF44; font-size:16px; }
			.skutitle{ color:#FAA81D; font-size:13px; }
			.bBlue { background: rgba(0, 0, 0, 0) -moz-linear-gradient(center top , #5ba5cb 0%, #3a70ab 100%) repeat scroll 0 0;color:#fff; font-family:Segoe UI; font-weight:bold; cursor:pointer; font-size:13px; border: 1px solid #3e76af; box-shadow: 0 1px 2px 0 #66b2d2 inset; }
			.buttonM { margin:3px; padding:7px 15px; }
		</style>
		<script language="javascript" type="text/javascript">
			$(document).ready(function(){
				$(".numeric").numeric();
			});
			
			$('.numbersOnly').keyup(function () {
			if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
			   this.value = this.value.replace(/[^0-9\.]/g, '');
			}
		});
		</script>
		<script type="text/javascript" src="js/1.4.jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.numeric.js"></script>
	</head>
	
	<body>
		<div id="content">
			<form name="editCartQty" action="" method="post" onsubmit="return confirmPost();">
				<input type="hidden" name="action" value="updatePrice" />
                <input type="hidden" name="grnPOID" id="grnPOID" value="<?php echo $grnPOID; ?>" />
                <input type="hidden" name="grnItemID" id="grnItemID" value="<?php echo $purchaseModel->grnItemID; ?>" />
                <input type="hidden" name="productID" id="productID" value="<?php echo $productID; ?>" />
                <input type="hidden" name="skuID" id="skuID" value="<?php echo $skuID; ?>" />
                <input type="hidden" name="taxType" id="taxType" value="<?php echo $taxType; ?>" />
                <input type="hidden" name="taxValue" id="taxValue" value="<?php echo $taxValue; ?>" />
                <input type="hidden" name="mrpPrice" id="mrpPrice" value="<?php echo $mrpPrice; ?>" />
                <?php /*<input type="hidden" name="amountExcGST" id="amountExcGST" value="<?php echo $purchaseModel->gst_value; ?>" />*/ ?>

                <table width="70%" border="0" cellspacing="5" style="font-family:Segoe UI;" cellpadding="5" align="center" class="tLight">
                    <tr>
                        <td align="center" colspan="3"><span style="font-size:16px; font-weight:bold;" class="title">SELLING PRCE</span></td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <div class="productTitle">
                                <span class="skutitle"><?php echo $productModel->product . " (" . $productModel->attribute_title . ")"; ?></span>
                            </div>
                        </td>
                        <td align="right"><img src="<?php echo SITE_ADDRESS . "/" . $productModel->thumbnail1; ?>" width="90" border="0" /></td>
                    </tr>
                </table>	
                
                <style>
                    .sellingData {font-family:Calibri;font-size:14px; }
                    .sellingData tr td{border:0px solid #cccccc;padding:5px 5px; }
                    .bBlue {background-color: cadetblue;color:#ffffff;padding:5px 5px; width:120px;height:30px; }
                    .sellingData tr td input{padding:0px 0px 0px 5px;}  
                    input:-moz-read-only { background-color:#cccccc; }
                    input:read-only { background-color:#cccccc; }
                </style>

                <table width="70%" cellspacing="0" class="sellingData" cellpadding="0" align="center" class="tLight">
                    <tbody>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        
                        <tr>
                            <td colspan="2">VALUE EXCULING GST : 
                                <select name="amountExcGST" id="amountExcGST" style="width:100px">
<?php                               $gstValues = $purchaseModel->last3GRNDetails($skuID);
                                    if(sizeof($gstValues) > 0)
                                    {
                                        foreach($gstValues as $gstVal)
                                        {
?>                                          <option value="<?php echo $gstVal->gst_value; ?>"><?php echo number_format($gstVal->gst_value,4); ?></option>
<?php                                   }
                                    }
?>                              </select>
                            </td>
                        </tr>
                       
                        <?php /*<tr>
                            <td colspan="2">VALUE EXCULING GST : <?php echo $purchaseModel->gst_value; ?></td>
                        </tr>*/ ?>

                        <tr>
                            <td width="50%">SELLING PRICE</td>
                            <td width="50%"><input type="number" readonly name="sp" id="sp" value="<?php echo $mrpPrice; ?>" class="positive-integer" placeholder="Selling Price" /></td>
                        </tr>

                        <tr>
                            <td width="50%">TVB DISCOUNT</td>
                            <td width="50%"><input type="number" name="qne_discount" id="qne_discount" alt="<?php echo $qneDiscount; ?>" value="<?php echo $qneDiscount; ?>" class="positive-integer" placeholder="TVB Discount" /></td>
                        </tr>

                        <tr>
                            <td width="50%">VALUE (W/O GST)<br/></td>
                            <td width="50%"><input type="number" readonly name="gst_value" id="gst_value" value="<?php echo $gstValue; ?>" class="positive-integer" placeholder="Tax Value" /></td>
                        </tr>
                        
                        <tr>
                            <td width="50%">TAX AMOUNT<br/></td>
                            <td width="50%"><input type="number" readonly name="tax_amount" id="tax_amount" value="<?php echo $taxAmount; ?>" class="positive-integer" placeholder="Tax Amount" /><br /><?php echo $generalModel->tax; ?></td>
                        </tr>
                        
                        <tr>
                            <td width="50%">COMPANY DISCOUNT</td>
                            <td width="50%"><input type="number" name="company_discount" id="company_discount" alt="<?php echo $companyDiscount; ?>" value="<?php echo $companyDiscount; ?>" class="positive-integer" placeholder="Company Discount" /></td>
                        </tr>
                        
                        <tr>
                            <td width="50%">NET SELLING PRICE</td>
                            <td width="50%"><input type="number" readonly name="net_sp" id="net_sp" value="<?php echo $netSP; ?>" class="positive-integer" placeholder="Net SP" /><br />Net SP > <?php echo $purchaseModel->gst_value; ?></td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center"><input type="submit" class="bBlue buttonM" value="UPDATE" /></td>
                        </tr>
                    </tbody>
                </table>
			</form>
		</div>
		
        <script type="text/javascript" src="js/changeProductPrice.js"></script>
		<script type="text/javascript">
			/*$(".numeric").numeric();
			$(".integer").numeric(false, function() { alert("Integers only"); this.value = ""; this.focus(); });
			$(".positive").numeric({ negative: false }, function() { alert("No negative values"); this.value = ""; this.focus(); });
			$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
			$(".decimal-2-places").numeric({ decimalPlaces: 2 });
			$("#remove").click(function(e){
				e.preventDefault();
				$(".numeric,.integer,.positive,.positive-integer,.decimal-2-places").removeNumeric();
			});
			
			
			$(".positive-integer").keyup(function(e){
				e.preventDefault();
				$(".numeric,.integer,.positive,.positive-integer,.decimal-2-places").removeNumeric();
			});
			
			$(".positive-integer").keyup(function(){
				var productID	= $('#productID').val();
				var skuID	    = $('#skuID').val();
				var taxType	    = $('#taxType').val();
				var taxValue	= $('#taxValue').val();
                var mrpPrice	= $('#mrpPrice').val();
                var sp	        = $('#sp').val();
				var tvb_discount= $('#qne_discount').val(); // Using existing form field name
                var company_disc= $('#company_discount').val();
                var amountExcGST= $('#amountExcGST').val();
                var altTvbDisc  = $("#qne_discount").attr("alt");
                var altCompDisc = $("#company_discount").attr("alt");
                
                if(taxType == "Fixed")
                {
                    retail_price = mrpPrice / (1 + taxValue / 100);
                    taxAmount	 = mrpPrice - retail_price;
                    netSP        = sp - tvb_discount;
                    gstValue     = netSP - taxAmount;
                }
                else
                if(taxType == "Normal")
                {
                    retail_price= (sp - tvb_discount) / (1 + taxValue / 100);
                    taxAmount	= sp - tvb_discount - retail_price;
                    netSP        = sp - tvb_discount;
                    gstValue     = netSP - taxAmount;
                }   
                else
                {
                    retail_price= (sp - tvb_discount) / (1 + taxValue / 100);
                    taxAmount	= sp - tvb_discount - retail_price;
                    netSP        = sp - tvb_discount;
                    gstValue     = netSP - taxAmount;
                }
                netSP = netSP - company_disc;
                if(gstValue < amountExcGST || netSP < 0)
                {
                    alert('VALUE (W/O GST) cannot be less than VALUE EXCULING GST');   
                    $('#qne_discount').val(altTvbDisc);
                    $('#company_discount').val(altCompDisc);
                    
                }
                else
                {
                    $('#gst_value').val(gstValue.toFixed(2));
                    $('#tax_amount').val(taxAmount.toFixed(2));
                    $('#net_sp').val(netSP.toFixed(2));   
                    //$("#gst_value").attr("alt",gstValue.toFixed(2));
                    $("#qne_discount").attr("alt", tvb_discount);
                    $("#company_discount").attr("alt", company_disc);
                }
                
                

			});*/
		</script>
	</body>
</html>
