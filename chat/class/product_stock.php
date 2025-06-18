<?php
error_reporting(0);
//echo "<pre>"; print_r($_SESSION); echo "</pre>"; 
	include('class/class.php'); 
	//echo "<pre>"; print_r($_SESSION); echo "</pre>"; exit();
	$loginModel	    = 	new Login();
	$warehouseModel	= 	new Warehouse();

	if(isset($_GET['action']) && $_GET['action'] == 'delete')
	{
		$productID = $_GET['product_id'];
		$wooProductID = $_GET['wooProductID'];
		$warehouseModel->deleteProduct($productID, $wooProductID);

		echo "<script>location.href = 'product_stock.php?action=delete-successful'</script>";
	}

    $productTitle   = '';
	$skuCode        = '';
	$price          = '';
	$location       = '';
	$category       = '';
    
	if(isset($_GET['product_title']) && $_GET['product_title'] != '')
	{
		$productTitle = addslashes($_GET['product_title']);
        $searchParam  .= '&product_title=' . $productTitle;
	}
	if(isset($_GET['sku_code']) && $_GET['sku_code'] != '')
	{
		$skuCode = addslashes($_GET['sku_code']);
        $searchParam  .= '&sku_code=' . $skuCode;
	}
	if(isset($_GET['price']) && $_GET['price'] != '')
	{
		$price = addslashes($_GET['price']);
        $searchParam  .= '&price=' . $price;
	}
	if(isset($_GET['location']) && $_GET['location'] != '')
	{
		$location = addslashes($_GET['location']);
        $searchParam  .= '&location=' . $location;
	}
	if(isset($_GET['category']) && $_GET['category'] != '')
	{
		$category = addslashes($_GET['category']);
        $searchParam  .= '&category=' . $category;
	}

	$productCate 	= $warehouseModel->productCategory(0,1);
	$allLocations	= $warehouseModel->allLocation(0,0,0,0);
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Product Stock - Vintage Bazar</title>
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="" />
		<meta property="og:url" content="https://thevintagebazar.com" />
		<meta property="og:site_name" content="Dashboard | The Vintage Bazar" />
		<link rel="canonical" href="https://thevintagebazar.com" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<?php include('includes/header.php'); ?>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Sidebar-->
					<?php include('sidebar.php'); ?>
					<!--end::Sidebar-->
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Toolbar-->
							<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
								<!--begin::Toolbar container-->
								<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
									<!--begin::Page title-->
									<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
										<!--begin::Title-->
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Product Stock</h1>
										<!--end::Title-->
										<!--begin::Breadcrumb-->
										<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">
												<a href="index.php" class="text-muted text-hover-primary">Home</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">Product Stock</li>
											<!--end::Item-->
										</ul>
										<!--end::Breadcrumb-->
									</div>
									<!--end::Page title-->
								</div>
								<!--end::Toolbar container-->
							</div>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-xxl">
									<!--begin::Card-->
									<div class="card">
										<!--begin::Card header-->
										<div class="card-header border-0 pt-6">
											<!--begin::Card title-->
											<div class="card-title">
												<!--begin::Search-->
												<div class="d-flex align-items-center position-relative my-1">
													<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Product" />
												</div>
												<!--end::Search-->
											</div>
											<!--begin::Card title-->
<?php 										$allProductListing = $warehouseModel->allProductListing(0, 0);

											if(isset($_GET['page']))
											{
												$page  	= 	$_GET['page'];
												$limit 	= 	1;
											}	
											else
											{
												$page  	= 	1;
												$limit 	= 	1;
											}
							
											if($page>=1)
											{
												$page	=	($page - 1)*$limit;
											}				
								
											$tbl_name	= "";		
											$adjacents 	= 3;
											$qString	= "";

											if(isset($_REQUEST['action']))
											{
												$qString = "?action=" . $_GET['action'];
											}
						
											if($qString == '')
											{
												$qString = "?1=1";
											}
											
											
											$total_pages 	= 	$allProductListing;
											$targetpage 	= 	"product_stock.php" . $qString;
		
											$limit 	= 1000; 
											
											if(isset($_GET['page']))
											{
											$page 	= 	$_GET['page'];
											}
											if($page) 
												$start = ($page - 1) * $limit; 			
											else
												$start = 0;							
											
											include('pagination.php');
?>												
											<!--begin::Card toolbar-->
											<div class="card-toolbar">
												<!--begin::Toolbar-->
												<div class="d-flex align-items-center gap-2 gap-lg-3" data-kt-customer-table-toolbar="base">
													<div><span class="text-gray-400 pt-1 fw-semibold fs-6"><?php echo $total_pages; ?> Record(s) Found</span></div>
													<!--begin::Filter Widget-->
													<form name="filter" method="get">
														<input type="hidden" name="search" value="filter">
														<div class="m-0">
															<!--begin::Menu toggle-->
															<a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
															<i class="ki-duotone ki-filter fs-6 text-muted me-1">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>Search Product(s)</a>
															<!--end::Menu toggle-->
															<!--begin::Menu 1-->
															<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_64b776137cfc4" style="">
																<!--begin::Header-->
																<div class="px-7 py-5">
																	<div class="fs-5 text-dark fw-bold">Filter Options</div>
																</div>
																<!--end::Header-->
																<!--begin::Menu separator-->
																<div class="separator border-gray-200"></div>
																<!--end::Menu separator-->
																<!--begin::Form-->
																<div class="px-7 py-5">
																	<!--begin::Input group-->
																	<div class="mb-2">
																		<!--begin::Label-->
																		<label class="form-label fw-semibold">Product</label>
																		<!--end::Label-->
																		<!--begin::Input-->
																		<div>
																			<input type="text" class="form-control form-control-lg form-control-solid" name="product_title" placeholder="Product Title" value="<?php echo $productTitle; ?>" />
																		</div>
																		<!--end::Input-->
																	</div>
																	<!--end::Input group-->
																	<!--begin::Input group-->
																	<div class="mb-2">
																		<!--begin::Label-->
																		<label class="form-label fw-semibold">SKU Code:</label>
																		<!--end::Label-->
																		<!--begin::Input-->
																		<div>
																			<input type="text" class="form-control form-control-lg form-control-solid" name="sku_code" placeholder="SKU Code" value="<?php echo $skuCode; ?>" />
																		</div>
																		<!--end::Input-->
																	</div>
																	<!--end::Input group-->
																	<!--begin::Input group-->
																	<div class="mb-2">
																		<!--begin::Label-->
																		<label class="form-label fw-semibold">Price:</label>
																		<!--end::Label-->
																		<!--begin::Input-->
																		<div>
																			<input type="text" class="form-control form-control-lg form-control-solid" name="price" placeholder="20.00" value="<?php echo $price; ?>" />
																		</div>
																		<!--end::Input-->
																	</div>
																	<!--end::Input group-->
																	<!--begin::Input group-->
																	<div class="mb-2">
																		<!--begin::Label-->
																		<label class="form-label fw-semibold">Location:</label>
																		<!--end::Label-->
																		<!--begin::Input-->
																		<div>
																			<select class="form-select form-select-solid" name="location" data-kt-select2="true" data-close-on-select="true" data-placeholder="Select option" data-dropdown-parent="#kt_menu_64b776137cfc4" data-allow-clear="true">
																				<option></option>
<?php																			if(sizeof($allLocations) > 0){
																				foreach($allLocations as $locations)
																				{
?>																				<option value="<?php echo $locations->location_id; ?>" <?php if($locations->location_id == $location){ ?> selected <?php } ?>><?php echo $locations->location_name; ?></option>																				
<?php																			}}
?>																				
																			</select>
																		</div>
																		<!--end::Input-->
																	</div>
																	<!--end::Input group-->

																	<!--begin::Input group-->
																	<div class="mb-2">
																		<!--begin::Label-->
																		<label class="form-label fw-semibold">Category:</label>
																		<!--end::Label-->
																		<!--begin::Input-->
																		<div>
																			<select class="form-select form-select-solid" name="category" data-kt-select2="true" data-close-on-select="true" data-placeholder="Select option" data-dropdown-parent="#kt_menu_64b776137cfc4" data-allow-clear="true">
																				<option></option>
<?php																			if(sizeof($productCate) > 0){
																				foreach($productCate as $productCategory)
																				{
?>																				<option value="<?php echo $productCategory->category_id; ?>" <?php if($productCategory->category_id == $category){?> selected <?php } ?>><?php echo $productCategory->category_title; ?></option>																				
<?php																			}}
?>																			</select>
																		</div>
																		<!--end::Input-->
																	</div>
																	<!--end::Input group-->

																	<!--begin::Actions-->
																	<div class="d-flex justify-content-end">
																		<button type="button" id="clearSearch" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Clear</button>
																		<input type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true" value="Search">
																	</div>
																	<!--end::Actions-->
																</div>
																<!--end::Form-->
															</div>
															<!--end::Menu 1-->
														</div>
													</form>
													<!--End::Filter Widget-->
													<!--begin::Add customer-->
													<a href="export-products.php?action=export&<?php echo $searchParam; ?>" class="btn btn-light-primary btn-sm fw-bold">
													<i class="ki-duotone ki-exit-up fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>Export</a>
													<a href="add_product.php" class="btn btn-sm fw-bold btn-primary">Add Product</a>
												</div>
												<!--end::Toolbar-->
												<!--begin::Group actions-->
												<div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
													<div class="fw-bold me-5">
													<span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div>
													<button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Delete Selected</button>
												</div>
												<!--end::Group actions-->
											</div>
											<!--end::Card toolbar-->
										</div>
										<!--end::Card header-->
																			
										<!--begin::Card body-->
										<div class="card-body pt-0">
											<!--begin::Table-->
											<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
												<thead>
													<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
														<th class="w-10px pe-2">
															<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
																#
															</div>
														</th>
														<th class="min-w-125px">Product Title</th>
														<th class="min-w-125px">SKU Title</th>
														<th class="min-w-125px">Stock Qty</th>
														<th class="min-w-125px">MRP Price</th>
														<th class="min-w-125px">Wholesale Price</th>
														<th class="text-end min-w-70px">Price</th>
														<th class="text-end min-w-200px">Action</th>
													</tr>
												</thead>
												<tbody class="fw-semibold text-gray-600">
<?php												$allProductListing = $warehouseModel->allProductListing($start, $limit);
													if(sizeof($allProductListing) > 0)
													{
														$c = 1;	
														foreach($allProductListing as $product)
														{ 
?>															<tr>
																<td>
																	<div class="form-check form-check-sm form-check-custom form-check-solid">
																		<?php echo $c; ?>
																	</div>
																</td>
																<td>
																	<a href="view_product.php?product_id=<?php echo $product->product_id; ?>" class="text-gray-800 text-hover-primary mb-1"><?php echo $product->product; ?></a>
																</td>
																<td>
																	<a href="#" class="text-gray-600 text-hover-primary mb-1"><?php echo $product->product_sku; ?></a>
																	
																</td>
																<td>
																	<!--begin::Badges-->
																	<div class="text-center"><?php echo $product->product_qty; ?></div>
																	<!--end::Badges-->
																</td>
																<td><div class="text-center"><?php echo $product->product_msrp; ?></div></td>
																<td><div class="text-center"><?php echo $product->product_wholesale; ?></div></td>
																<td class="text-end"><div class="text-center"><?php echo $product->product_price; ?></div>
																	<!--end::Menu-->
																</td>
																<td class="text-end">
																	<?php /*<a href="edit_product.php?product_id=<?php echo $product->product_id; ?>" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
																		<i class="ki-duotone ki-filter fs-6 text-muted me-1">
																			<span class="path1"></span>
																			<span class="path2"></span>
																		</i>Edit
																	</a><br /><br />
																	<a href="product_stock.php?ation=delete&product_id=<?php echo $product->product_id; ?>" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
																		<i class="ki-duotone ki-filter fs-6 text-muted me-1">
																			<span class="path1"></span>
																			<span class="path2"></span>
																		</i>Delete
																	</a>*/ ?>
																	<?php if($product->woo_product_id == 0 && $product->product_location == 6){ ?><a href="javascript:syncWooProduct(<?php echo $product->product_id; ?>);" class="badge badge-light-success me-auto" id="synProductWoo-<?php echo $product->product_id; ?>">Sync</a> <?php } ?>
																	<a href="edit_product.php?product_id=<?php echo $product->product_id; ?>" class="fw-bold">Edit</a> | 
																	<a href="javascript:void()" onclick="deleteProduct(<?php echo $product->product_id; ?>, <?php echo $product->woo_product_id; ?>)" class="fw-bold deleteProduct">Delete</a>
																	<!--end::Menu-->
																</td>
															</tr>
<?php 														$c++;
														} 
													} 
?>												</tbody>
												<!--end::Table body-->
											</table>
											<!--end::Table-->
										</div>
										<!--end::Card body-->
									</div>
									<!--end::Card-->
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<?php include('includes/footer.php'); ?>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Drawers-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
		
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/custom/apps/ecommerce/customers/listing/listing.js"></script>
		<script src="assets/js/custom/apps/ecommerce/customers/listing/add.js"></script>
		<script src="assets/js/custom/apps/ecommerce/customers/listing/export.js"></script>
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->

		<script>
			function deleteProduct(productID, wooProductID)
			{ 
				if(confirm("Confirm Delete this Product?"))
				{
					location.href = 'product_stock.php?action=delete&product_id=' + productID + '&wooProductID=' + wooProductID;
				}
			}

            $("#clearSearch").click(function(){
                location.href = 'product_stock.php';
            });

			function syncWooProduct(productID)
			{
				var result = $.ajax({
					type: "POST",
					url: "ajax.php",
					data: "work=syncWooProduct&productID=" + productID,
					async: false
				}).responseText.split('=====');	
				$('#synProductWoo-' + productID).css('display', 'none');
				//alert(result);
			}
		</script>
	</body>
	<!--end::Body-->
</html>