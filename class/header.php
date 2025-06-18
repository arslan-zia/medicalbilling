<?php include("fbconnect.php"); ?>

<!-- <script> location.href = 'home.php';</script> -->


<script>
	function SubmitSearch()
	{
		var Textsrch = document.getElementById('srchText').value;
		if(document.sch_frm.srchText.value=='' || document.sch_frm.srchText.value=='search products here...')
		{
			alert('Please type text for search!');
			document.sch_frm.srchText.focus();
			return false;
		}
		
		document.sch_frm.action='<?php echo $siteaddress;?>search_results.php?search='+Textsrch;
		document.sch_frm.method='post';
		document.sch_frm.submit();
	}
</script>
    <header id="header" class="type_6">
        <!--<hr>-->
        


<div class="bottom_part hide-mob topStickyHeader">
          

		<script type="text/javascript">
			function searchProduct()
			{
				var srchText = $('#srchText').val();
				if(srchText == '' || srchText == ' ')
				{
					$("#closeBtn").css("display","none");
					$("#searchProducts").css("display","none"); 
				}
				else
				{
				//	$('#searchProducts').html("<br/><img src='<?php echo IMAGES; ?>loader_1.gif'/>");
					var data_lnk = "work=searchProduct&srchText=" + srchText;    
					 $("#loader").show();
					$.ajax({
						type: "POST",
						url: "<?php echo $siteaddress;?>ajax_search_product.php",
						data: data_lnk,
						success: function(mshg){
						 $("#loader").hide();
							$("#closeBtn").css("display","block");
							$("#searchProducts").css("display","block"); 
							$("#searchProducts").html(mshg);
						}
					});
				}	
			}
			
			function closeSerchBox()
			{
				$("#searchProducts").css("display","none");
				$("#closeBtn").css("display","none");
			}
		</script>

		<style>
			.srchProds { position:absolute; z-index:9998; max-height: 450px; padding:5px 0px 0px 10px !important; background-color:#ffffff; box-shadow: 2px 2px 5px 1px #ccc; -webkit-box-shadow: 2px 2px 5px 1px #ccc; -moz-box-shadow: 10px 2px 2px 5px 1px #ccc; box-shadow: 10px 2px 2px 5px 1px #ccc; display:none; top:50px; }
			.productSearchListing { border-bottom:1px solid #ccc; padding:10px 0px 10px 0px;}
			.srchProds .productSearchListing img{ max-width:60px; max-height:60px; }
			.srchProds .productSearchListing .leftImg{ float:left; width:20%; text-align:center; }
			.srchProds .productSearchListing .rightDetail{ float:right; width:78%; text-align:left; }
			.srchProds .productSearchListing a { font:500 14px/21px "Roboto",sans-serif; color:#000; }
			.srchProds .productSearchListing a:hover { color:#61C250; }
			.srchProds a.brandSearchTitle { font-weight:normal; color:#888; }
			#closeBtn { float:right; position:relative; z-index:9999; display:none; cursor:pointer; top:10px; }
			.mytmslot{ min-width:300px !important; }
			.times-area > a::after, .times-area > a::after{ content:inherit !important; }
			.scrol{ padding:1px 0 0px !important; }
			.scrollbar{ background: none repeat scroll 0 0 #fff; height: 250px; overflow-y: auto; }
			.custHover:hover{ background:#61C250 !important; }
			.whatsapp{color:#64d448; font-size:12px !important;}
			.call{color:#EEAF00; font-size:12px !important;}
			.loginReg{color:#53D1C8; font-size:12px !important;}
			.Reglogin{color:#428BCA; font-size:12px !important;}
			.logout{color:#E83A00; font-size:12px !important;}
			.mobile_menu_log { float: left; height: 56px; padding-top: 15px; text-align: center; width: 10%; }
			
			.main_navigation:not(.full_width_nav)>ul>li>a {
    border: none;
    background-color: #61c250;
    padding: 12px 18px;
    color: #fff;
}
			
			#open_shopping_cart {
    position: relative;
    padding: 5px 10px 9px 65px;
    border: none;
    margin-left: 15px;
    line-height: 18px;
    text-align: left;
    border-radius: 3px;
}
		</style>
        
        <div id="main_navigation_wrap" style="padding-top:1px;padding-bottom:1px;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12"> 
                        <div class="sticky_inner type_2"> 
							<style>
                                .mytmslot{ min-width:300px !important; }
                                .times-area > a::after, .times-area > a::after { content:inherit !important; }
                                .scrol { padding:1px 0 0px !important; }
                                .scrollbar { background: none repeat scroll 0 0 #fff; min-height:110px; max-height: 250px; overflow-y: auto; }
                                .login-pup { border-color: #eeaf00; border-style: solid; border-width: 1px 0 0; display: block; left:0; opacity: 0; position: absolute; right:0; top:47px; transition: opacity 0.7s ease 0s; float:left; width:100%; background:#EEAF00; }
                                .custHover:hover { background:#61C250 !important; }
								.flRight { text-align:right;} 
                            </style>
<?php
							function current_page_actve()
							{
								$url_pg = explode('/',$_SERVER['PHP_SELF']);
								$current_url_pg = $url_pg[count($url_pg)-1];
								$current_url_pg = str_replace(".php","",$current_url_pg);
								return $current_url_pg;
							}

							$pg_name = current_page_actve();

							switch($pg_name)
							{
								case '':
								case 'index':
									$current_pag1 = "class='current'";
								break;	
								
								case 'contact-us':
									$current_pag2 = "class='current'";
								break;
								
								case 'my-account':
									$current_pag3 = "class='current'";
								break;
								
								case 'referral-friends':
									$current_pag4 = "class='current'";
								break;
								
								case 'login':
									$current_pag5 = "class='current'";
								break;
								
								case 'login-mybooking':
									$current_pag6 = "class='current'";
								break;
								
								default:
									$current_pag1 = "class='current'";
								break;
							}
?>
                            <div class="nav_item moveLeft">
								<nav>
									<div class="mm-toggle-wrap">
										<div class="mm-toggle"><span class="toggle-menu toggle_menu" style="border:0px solid #ccc; margin-top:6px; font-size:30px"></span> </div>
									</div>
								</nav>
                                <nav class="main_navigation hide-mob">
                                    <ul>
                                       
                                        <li onclick="location.href='https://inventory.thevintagebazar.com';" class="has_submenu hide-mob">
                                            <img style="display:none;" id="lg" src="assets/media/logos/vintage.png">
                                        </li>
                  						<li class="has_submenu hide-mob">
                                        	<a href="javascript:;">Category</a>
                                            <ul class="theme_menu submenu offer-list">
												<?php include("top_menu_category.php");?>
                                            </ul>    
                                        </li>
<?php 								  if($_SESSION['myuserid'])
										{
?>										   <li <?php echo $current_pag3;?>><a href="<?php echo $siteaddress;?>my-account.php">My Account</a></li>
                                           
											<li><a href="<?php echo $siteaddress;?>logout.php">Logout</a></li>
<?php 								  }
										else
										{
?>										  
                                            <li <?php echo $current_pag6;?>><a href="<?php echo $siteaddress;?>my-order.html">My Order</a></li>
																		
<?php 								  }
?>								  
																		<li <?php echo $current_pag6;?>>
																			
																			       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="searchBox" style="    width: 200% !important;
    position: relative;
    top: 1px;"> 
                            <form class="clearfix search" name="sch_frm"  method="post" onfocusout="setTimeout(function(){ closeSerchBox(); }, 300);" onsubmit="return SubmitSearch();" style="margin:0px;">
                                <?php /*<input type="text" placeholder="search products here..." name="srchText" id="srchText" class="alignleft">*/ ?>
								<input type="text" class="alignleft form-control" name="srchText" id="srchText" onkeyup="searchProduct();" placeholder="Search Products" autocomplete="off" />
                                <button onclick="SubmitSearch();" class="topSrchBtn button_blue fa fa-search alignleft" style="    background: #EEAF00;
    border-color: #faa71d!important;"></button>
                           <img  style="width:20px;height:20px !important;display:none;border:none;position: relative;
    bottom: 30px;
    left: 578px;" id="loader" src="images/loading.gif"> 
													</form>
							<div id="closeBtn" style="display:none;"><img onclick="closeSerchBox();" src="<?php echo IMAGES . "close-button.png";?>" alt="Close Search" /></div>
							<ul id="searchProducts" style="display:none;" class="timeslots srchProds col-lg-3 col-md-3 col-sm-3 col-xs-12"></ul>
                        </div>
						
																			
																			</li>
																	</ul>
                                </nav>
                            </div>
							
							<!-- Start Mobile View Here -->
            				<div class="mobile-logo mobile-show" id="mobileLogo"><img src="<?php echo IMAGES; ?>mobile-logo.png" /></div>
							<div class="search-mobile mobile-show" id="searchMobile"><a href="<?php echo LOGIN; ?>" class="loginReg"><img src="<?php echo IMAGES; ?>ic-person.png" style="width:24px; " /></a> <a href="javascript:void()" onclick="toggleMobileSearch(1);"><img src="<?php echo IMAGES; ?>search-mobile.png" /></a></div>
							<div class="mobile-search mobile-show" id="mobileSearchBox" style="display:none"> 
								<input type="text" class=" form-control" name="mobSrchText" id="mobSrchText" onfocusout="document.getElementById('clc').click();" onkeyup="mobSearchProduct();" placeholder="Search Products" autocomplete="off" /><img onclick="toggleMobileSearch(0);" id="clc" class="closeSearch" src="<?php echo IMAGES; ?>mobile-search-close.png" alt="Close Search" />
								<ul id="mobSearchProducts" class="mobSrchProds srchProds col-lg-3 col-md-3 col-sm-3 col-xs-12"></ul>
							</div>
							
							<script type="text/javascript">
								function toggleMobileSearch(toggle)
								{
									if(toggle == 1)
									{
										$('#mobileLogo').css('display', 'none');
										$('#searchMobile').css('display', 'none');
										$('#mobSearchProducts').html('');
										$('#mobSrchText').val('');
										$('#mobSearchProducts').css('display', 'none');
										$('#mobileSearchBox').css('display', 'block');
									}
									else
									{
										$('#mobileLogo').css('display', 'block');
										$('#searchMobile').css('display', 'block');
										$('#mobileSearchBox').css('display', 'none');
									}
								}
							</script>
							
							<script type="text/javascript">
								function mobSearchProduct()
								{
									var srchText = $('#mobSrchText').val();

									$('#mobSearchProducts').html("<br/><img src='<?php echo IMAGES; ?>loader_1.gif'/>");
									var data_lnk = "work=mobSearchProduct&srchText=" + srchText;    
									$.ajax({
										type: "POST",
										url: "<?php echo $siteaddress;?>ajax_search_product.php",
										data: data_lnk,
										success: function(mshg){
											//$("#mobCloseBtn").css("display","block");
											$("#mobSearchProducts").css("display","block"); 
											$("#mobSearchProducts").html(mshg);
										}
									});
								}
							</script>
							<!-- End Mobile View -->
                            <div class="nav_item moveRight size_3" id="loadcartajaxData">
<?php							$cart_basket = mysql_query("select * from z_cart where cart_session='".$session_id."'");
														//	echo "select * from z_cart where cart_session='".$session_id."'";
								$basket_rec  = mysql_num_rows($cart_basket);
														//echo $basket_rec;
?>							  <button id="open_shopping_cart" class="open_button" data-amount="<?php echo $basket_rec;?>"> <b class="title hide-mob">My Cart</b></button>
                                <div class="shopping_cart dropdown animated_item">
                                	<div class="animated_item scrol">
                                		<div class="scrollbar">
<?php										$basketsumPrice 		     	= 0;
											$DiscountOnProducts_bskt 	   = 0;
											$DiscountPercentOfCoupen_bskt  = 0;
											
											if($basket_rec > 0)
											{
												while($rs_basket = mysql_fetch_assoc($cart_basket))
												{
													$Prid 			= $rs_basket['prod_id'];
													$basketsub_total = $rs_basket['total_price'];
													$basketsumPrice  = $basketsumPrice + $basketsub_total;
													$select_prod 	 = mysql_fetch_assoc(mysql_query("select productid, thumbnail1, productname from product where productid=".$Prid));
													$bskProd_attr 	= mysql_fetch_assoc(mysql_query("SELECT attribute_title FROM `product_attributes` WHERE id=".$rs_basket['size_id']));
	
													// for Discount % in product
													if($rs_basket['prod_actual_price'] > 0)
													{
														$prodUntdiscontPrc_bskt  = $rs_basket['prod_actual_price'] - $rs_basket['prod_price'];
														$DiscountOnProducts_bskt = $DiscountOnProducts_bskt + ($prodUntdiscontPrc_bskt * $rs_basket['prod_qty']);
													}
													
													// for Discount % in coupen code 
													if($rs_basket["promocode"] != '' && $rs_basket["promocode_percent"] != '')
													{
														$clearDiscAmount_bskt 		 = str_replace(",","",$basketsub_total);
														$DiscontPerc_bskt	 		 = ($clearDiscAmount_bskt * $rs_basket["promocode_percent"])/100;
														$DiscountPercentOfCoupen_bskt = $DiscountPercentOfCoupen_bskt + $DiscontPerc_bskt;
													}
													
													$UrlheadcartPrdDetail1 = preg_replace('/[^a-zA-Z0-9]/', ' ', strtolower($select_prod['productname']));
													$UrlheadcartPrdDetail  = preg_replace('/[ ]+/', '-', trim($UrlheadcartPrdDetail1));
													
													$bsk_cImg	= '';
													$bas_linkHrf = 'javascript:;';
														
													if($rs_basket['pro_type'] == 'mixNmatch')
													{
														$bas_MixOfferimg = mysql_query("SELECT * FROM `z_cart_offers_detail`  WHERE `zcart_id`=".$rs_basket['cart_id']." AND `sessionId`='".$session_id."' ORDER BY `id` DESC");
														while($bas_MixOfferimg_rs = mysql_fetch_assoc($bas_MixOfferimg))
														{
															$bask_ProdAttimags = mysql_fetch_assoc(mysql_query('SELECT pImgs.thumbnail1, AttImgs.thumbnail_img, AttImgs.attribute_code FROM `product_attributes` AttImgs  INNER JOIN product pImgs ON AttImgs.productid=pImgs.productid WHERE `id`="'.$bas_MixOfferimg_rs['stock_sku_id'].'" '));
															if(is_file($bask_ProdAttimags['thumbnail_img']))
															{
																$bsk_cImg.="<img src='".$siteaddress.$bask_ProdAttimags['thumbnail_img']."' style='width:40px;' />";
															}
															else
															if(is_file($bask_ProdAttimags['thumbnail1']))
															{
																$bsk_cImg.="<img src='".$siteaddress.$bask_ProdAttimags['thumbnail1']."' style='width:40px;' />";
															}
															else
															{
																$bsk_cImg.="<img src='".$siteaddress."images/no-img-83-83.gif' style='width:40px;' />";
															} 
															$bas_linkHrf=$siteaddress."mix-and-match-details.php?offer_id=".$bas_MixOfferimg_rs['offer_id'];	  
														}
													}
													else
													{
														$bsk_cImg    = "<img src='".$siteaddress.$select_prod['thumbnail1']."' />";
														$bas_linkHrf = $siteaddress.$UrlheadcartPrdDetail."-".$Prid.'.html';
													}
					  
?>											  	  	<div class="animated_item" id="bsktrowNum<?php echo $rs_basket['cart_id'];?>">
                                                        <?php /*<p class="title">Recently added item(s)</p>*/ ?>
                                                        <div class="clearfix sc_product"> 
                                                            <a href="<?php echo $bas_linkHrf;?>" class="product_thumb"><?php echo $bsk_cImg;?> </a> 
                                                            <a href="<?php echo $bas_linkHrf;?>" class="product_name"><?php echo $select_prod['productname']; if($bskProd_attr['attribute_title']){ echo "<br/>(".$bskProd_attr['attribute_title'].")";}?></a>
                                                            <p class="mini_cart_price">
                                                                <?php echo $rs_basket['prod_qty'];?> x <?php if($rs_basket['prod_actual_price']>0){ echo $currency . " " . number_format($rs_basket['prod_price'],2) . " <s>".number_format($rs_basket['prod_actual_price'],2)."</s> ";} else { echo $currency . " " . number_format($rs_basket['prod_price'],2);}?>
                                                            </p>
                                                            <a class="fa fa-remove" style="cursor:pointer; position:absolute; right:0; top:2px;" onclick="DeleteajaxCartItems(<?php echo $rs_basket['cart_id'];?>);"></a>
                                                        </div>
                                                    </div>
<?php											}
				
												if($DiscountPercentOfCoupen_bskt>0)
												{
													if($_SESSION['SelectAreadeliverCharges'])
													{ 
														$delchrs = $_SESSION['SelectAreadeliverCharges'];
													} 
													else
													{ 
														$delchrs = 0;
													}
													$TotalDiscount_bskt = $DiscountPercentOfCoupen_bskt+$DiscountOnProducts_bskt;
													$gTotal_bskt		= $basketsumPrice + $delchrs - $DiscountPercentOfCoupen_bskt;
												} 
												else 
												{
													$TotalDiscount_bskt = $DiscountOnProducts_bskt;
													$gTotal_bskt= $basketsumPrice + $delchrs; 
												}
											}
											else
											{ 
?>											  <div style="font-weight: bold; text-align: center; color: rgb(98, 98, 98); font-size: 20px; padding: 30px 0px 0px;"><i class="fa  fa-shopping-cart"></i> <br/>No item in cart! </div>
<?php										}
?>
                                        </div>
                                    </div>

                                    <div class="animated_item cartSavingTotal"> 
										<table cellpadding="5" cellspacing="5" border="0" class="table_type_1 shopping_cart_table mini_shopping_cart">
											<tbody>
												<?php if($TotalDiscount_bskt > 0){?>
													<tr>
                                            			<td class="left"><b><span class="youSave">Sub Total:</span></b></td>
														<td class="right"><b><?php echo $currency.number_format($TotalDiscount_bskt+$gTotal_bskt,2);?></b></td>
                                            		</tr>
                                            		<tr>
                                            			<td class="left"><b><span class="youSave">You Save:</span></b></td>
														<td class="right"><b><?php echo $currency.number_format($TotalDiscount_bskt,2);?></b></td>
                                            		</tr>
                                            	<?php }?>
												<tr>
													<td class="left"><b><span class="yourTotal">Total:</span></b></td>
													<td class="right"><b><?php echo $currency.number_format($gTotal_bskt,2);?></b></td>
												</tr>
											</tbody>
										</table>
                                    </div>
                                    
									<?php if($basket_rec > 0) { ?>
                                        <div class="animated_item flRight"> 
                                            <a href="<?php echo $siteaddress;?>shopping-cart.php" class="button_grey active">VIEW CART</a> 
                                            <a href="<?php echo $siteaddress;?>shopping-cart.php" class="button_blue">CHECKOUT</a> 
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	  <div class="container" style="width:100% !important;">
                <div class="row"  >
                    <div class="main_header_row">
                        <div class="col-sm-12" style="width:15% !important; text-align:center"> 
                                        <div style="float:left;">
                                            <a href="<?php echo $siteaddress;?>" class="logo">
                                                <img src="assets/media/logos/vintage.png" alt="The Vintage Bazar" />
                                            </a>
                                        </div>
                            	<div style="float:right;">
														  
													 
                            <div class="call_us">
								<span class="call"><i class="call-fa fa fa-phone"></i> <?php echo $siteContactNumber;?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="whatsapp"><i class="call-fa fa fa-whatsapp"></i> <?php echo WHATSAPP;?></span>&nbsp;&nbsp;&nbsp;&nbsp; <br>
								<?php if(!$_SESSION['myuserid']) { ?>
                                    <a href="<?php echo LOGIN; ?>" class="btn btn-primary">Login</a>
                                    <a href="<?php echo REGISTER; ?>" class="btn btn-success">Register</a>
								<?php } else { ?>
								<span class="loginReg">Hi <?php echo substr($_SESSION['firstname'],0,10); ?>,</span> &nbsp; <a href="<?php echo $siteaddress;?>logout.php" title="Logout"><span class="logout"><i class="call-fa fa fa-sign-out"></i></span></a>
								<?php } ?>
							</div>
											
											
											</div>

                 
						<?php /*<div class="col-sm-3" style="width:35% !important;float: right;"> 
                            <div class="call_us">
								<span class="call"><i class="call-fa fa fa-phone"></i> <?php echo $siteContactNumber;?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="whatsapp"><i class="call-fa fa fa-whatsapp"></i> <?php echo WHATSAPP;?></span>&nbsp;&nbsp;&nbsp;&nbsp; 
								<?php if(!$_SESSION['myuserid']) { ?><a href="<?php echo LOGIN; ?>" class="loginReg"><i class="call-fa fa fa-user"></i><span> Login</span></a> &nbsp; <a href="<?php echo REGISTER; ?>"><span class="Reglogin"><i class="call-fa fa fa-key"></i> Register</span></a>
								<?php } else { ?>
								<span class="loginReg">Hi <?php echo substr($_SESSION['firstname'],0,10); ?>,</span> &nbsp; <a href="<?php echo $siteaddress;?>logout.php" title="Logout"><span class="logout"><i class="call-fa fa fa-sign-out"></i></span></a>
								<?php } ?>
							</div>
							<span style="font-size:13px; color:#73B943;margin-bottom:5px">FREE Delivery For Order Rs 500 and Above</span><br />
							<span style="font-size:13px; color:#10561A;margin-bottom:5px; "><i class="call-fa fa fa-money"></i> Pay on Delivery. Cash or Card</span>
							
                        </div>*/ ?>
                    </div>
                </div>
            </div>
        </div>
<script>
			
			$(window).scroll(function(){
  if ($(window).scrollTop() >= 100) {
    $('#lg').show();
   }
   else {
    $('#lg').hide();
   }
});
			</script>
    </header>
