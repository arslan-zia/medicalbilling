<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
include('includes/general-settings.php');
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>IP Settings - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="IP Settings - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="IP Settings - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="IP Settings - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | IP Settings" />
		<link rel="canonical" href="<?php echo SERVER; ?>" />
		<link rel="shortcut icon" href="<?php echo ASSETS; ?>media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="<?php echo ASSETS; ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo ASSETS; ?>plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="<?php echo ASSETS; ?>plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo ASSETS; ?>css/style.bundle.css" rel="stylesheet" type="text/css" />
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
					<?php include('includes/sidebar.php'); ?>
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">IP Settings</h1>
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
											<li class="breadcrumb-item text-muted">IP Settings</li>
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
<?php								if(isset($_GET['action']) && $_GET['action'] == 'success')
									{
?>										<div class="alert alert-success" id="successMessage" role="alert">
											<strong>Success!</strong> Added successfully.
										</div>
<?php								}
									else
									if(isset($_GET['action']) && $_GET['action'] == 'fail')	
									{
?>										<div class="alert alert-danger" id="successMessage" role="alert">
											<strong>Error!</strong> <?php echo addslashes($_GET['message']); ?>.
										</div>
<?php								}
?>										
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
													<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Allowed IPs" />
												</div>
												<!--end::Search-->
											</div>
											<!--begin::Card title-->
											<!--begin::Card toolbar-->
											<div class="card-toolbar">
												<!--begin::Toolbar-->
												<div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
													<!--begin::IP Restriction Toggle-->
													<?php
														$ipRestrictionsStatus = $generalModel->getIPRestrictionsStatus();
														
														if($ipRestrictionsStatus == 'Enabled')
														{
															//$ipStatus = "Enabled";
															$class = "btn-primary";
														}
														else
														{
															//$ipStatus = "Disabled";	
															$class = "btn-danger";
														}
													?>
													<button type="button" class="btn btn-sm <?php echo $class; ?> me-3" id="ip_restriction_toggle" data-ip-restrictions-status="<?php echo $ipRestrictionsStatus; ?>" onclick="toggleIPRestriction(this.dataset.ipRestrictionsStatus)">
														<i class="ki-duotone ki-shield-tick fs-2">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
														IP Restrictions: <span id="restriction_status"><b><?php echo $ipRestrictionsStatus; ?></b></span>
													</button>
													<!--end::IP Restriction Toggle-->
													<!--begin::Add customer-->
													<a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_ip">Add IP</a>
													<!--end::Add customer-->
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
														<th class="min-w-150px">IP Address</th>
														<th class="min-w-125px">Added By</th>
														<th class="min-w-125px">Status</th>
														<th class="text-end min-w-70px">Action</th>
													</tr>
												</thead>
												<tbody class="fw-semibold text-gray-600">
<?php												$ipSettings	=	$generalModel->getIPSettings();
                                                    if(sizeof($ipSettings) > 0)
                                                    {
                                                        $c = 1;
                                                        foreach($ipSettings as $ipSetting)
                                                        {
?>															<tr>
																
																<td>
																	<div class="form-check form-check-sm form-check-custom form-check-solid">
																		<?php echo $c; ?>
																	</div>
																</td>
																<td>
																	<!--begin::Badges-->
																	<?php echo $ipSetting['ip_address']; ?>
																	<!--end::Badges-->
																</td>
																<td>
																	<a href="" class="text-gray-800 text-hover-primary mb-1"><?php echo $ipSetting['added_by_name']; ?></a>
																</td>
																
																<td><?php if($ipSetting['status'] == '1') { echo "Active"; } else { echo "In-Active"; } ?></td>
																
																<td class="text-end">
																	
																	<a href="javascript:void(0);" class="btn btn-sm btn-flex btn-secondary fw-bold deleteIPSetting" data-ip-setting-id="<?php echo $ipSetting['id']; ?>">
																		<i class="ki-duotone ki-filter fs-6 text-muted me-1">
																			<span class="path1"></span>
																			<span class="path2"></span>
																		</i>Delete
																	</a>
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
									<!--begin::Modal - Customers - Add-->
									<div class="modal fade" id="kt_modal_add_ip" tabindex="-1" aria-hidden="true">
										<!--begin::Modal dialog-->
										<div class="modal-dialog modal-dialog-centered mw-650px">
											<!--begin::Modal content-->
											<div class="modal-content">
												<!--begin::Form-->
												<form class="form" method="post" action="" id="kt_modal_add_ip_form" onsubmit="return validateIPAddress()">
													<input type="hidden" name="action" value="addIPSetting" />
													<!--begin::Modal header-->
													<div class="modal-header">
														<!--begin::Modal title-->
														<h2 class="fw-bold">Add IP Address</h2>
														<!--end::Modal title-->
														<!--begin::Close-->
														<div data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
															<i class="ki-duotone ki-cross fs-1">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</div>
														<!--end::Close-->
													</div>
													<!--end::Modal header-->
													<!--begin::Modal body-->
													<div class="modal-body py-10 px-lg-17">
														<!--begin::Scroll-->
														<div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_ip_header" data-kt-scroll-wrappers="#kt_modal_add_ip_scroll" data-kt-scroll-offset="300px">
															<!--begin::Input group-->
															<div class="row g-9 mb-7">
																<div class="col-md-12 fv-row">
																	<label class="required fs-6 fw-semibold mb-2">IP Address</label>
																	<div class="small text-muted mb-2">Use * for wildcard access</div>
																	<input type="text" class="form-control form-control-solid" id="ip_address" name="ip_address" required placeholder="Enter IP Address" pattern="^(?:\*|(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|\*)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|\*))$" title="Please enter a valid IP address (e.g. 192.168.1.1) or use * as wildcard" oninput="this.value = this.value.replace(/[^0-9.*]/g, '');" maxlength="15"/>
																	
																</div>
															</div>
															<div class="row g-9 mb-7">
																<div class="col-md-12 fv-row">
																	<label class="required fs-6 fw-semibold mb-2">Status</label>
																	<select name="status" id="status" class="form-select form-select-solid" required>
																		<option value="">Select Status</option>
																		<option value="1">Active</option>
																		<option value="0">Inactive</option>
																	</select>
																</div>
															</div>
														</div>
														<!--end::Scroll-->
													</div>
													<!--end::Modal body-->
													<!--begin::Modal footer-->
													<div class="modal-footer flex-center">
														<!--begin::Button-->
														<button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Cancel</button>
														<!--end::Button-->
														<!--begin::Button-->
														<button type="submit" id="kt_modal_add_ip_submit" class="btn btn-primary">
															<span class="indicator-label">Submit</span>
														</button>
														<!--end::Button-->
													</div>
													<!--end::Modal footer-->
												</form>
												<!--end::Form-->
											</div>
										</div>
									</div>
									
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<?php include('includes/footer-bottom.php'); ?>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="<?php echo ASSETS; ?>plugins/global/plugins.bundle.js"></script>
		<script src="<?php echo ASSETS; ?>js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="<?php echo ASSETS; ?>plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="<?php echo ASSETS; ?>js/custom/apps/ecommerce/customers/listing/listing.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/apps/ecommerce/customers/listing/add.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/apps/ecommerce/customers/listing/export.js"></script>
		<script src="<?php echo ASSETS; ?>js/widgets.bundle.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/widgets.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/apps/chat/chat.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/create-app.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/users-search.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/new-address.js"></script>
		<script src="<?php echo ASSETS; ?>js/main.js"></script>
		<!--end::Custom Javascript-->

		<script type="text/javascript">

			$(document).ready(function()
			{
				$('.deleteIPSetting').on('click', function()
				{
					if (confirm("Are you sure you want to delete this IP setting?")) {
						var ipSettingID = $(this).data('ip-setting-id');
						deleteIPSetting(ipSettingID);
					}
					return false;
				});
			});

			function deleteIPSetting(ipSettingID)
			{
				var result = $.ajax({
					type: "POST",
					url: "ajax.php",
					data: "work=deleteIPSetting&ipSettingID=" + ipSettingID,
					async: false
				}).responseText.split('=====');	
				console.log(result);
				if(result[0] == 'success')
				{
					//$('#deleteIPSetting-' + ipSettingID).css('display', 'none');
					location.reload();
				}
				else
				{
					alert("Error: IP Setting Deletion Failed");
				}
			}
			function validateIPAddress()
			{
				var ipAddress = document.getElementById('ip_address').value;
				var status = document.getElementById('status').value;
				//alert(ipAddress);
				if(ipAddress == '')
				{
					alert('Please enter an IP address');
					return false;
				}

				var result = $.ajax({
					type: "POST",
					url: "ajax.php",
					data: "work=addIPAddress&ipAddress=" + ipAddress + "&status=" + status,
					async: false
				}).responseText.split('=====');	
				console.log(result);
				if(result[0] == 'success')
				{
					alert('IP address added successfully');
					return true;
				}
				else
				{
					alert('IP address already exists');
					return false;
				}
			}

			function toggleIPRestriction(ipRestrictionsStatus) {
				if (ipRestrictionsStatus == 'Enabled') {
					// Confirm before disabling
					if (confirm("Are you sure you want to disable IP restrictions? This will allow access from all IP addresses.")) {
						
						document.getElementById("restriction_status").innerHTML = "Disabled";
						document.getElementById("ip_restriction_toggle").classList.remove("btn-primary");
						document.getElementById("ip_restriction_toggle").classList.add("btn-danger");
						$("#ip_restriction_toggle").attr("data-ip-restrictions-status", 'Disabled');

						ipStatus = 'Disabled';

					}
				} else {
					// Confirm before enabling
					if (confirm("Are you sure you want to enable IP restrictions? This will only allow access from whitelisted IPs.")) {
						
						document.getElementById("restriction_status").innerHTML = "Enabled";
						document.getElementById("ip_restriction_toggle").classList.remove("btn-danger");
						document.getElementById("ip_restriction_toggle").classList.add("btn-primary");
						$("#ip_restriction_toggle").attr("data-ip-restrictions-status", 'Enabled');

						ipStatus = 'Enabled';
					}
				}
				//console.log(ipStatus);

				var result = $.ajax({
					type: "POST",
					url: "ajax.php",
					data: "work=toggleIPRestriction&ipRestrictionsStatus=" + ipStatus,
					async: false
				}).responseText.split('=====');	
				console.log(result);

			}
		</script>
		
	</body>
	<!--end::Body-->
</html>