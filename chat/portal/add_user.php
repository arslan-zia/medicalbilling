<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
	header("Connection: Keep-alive");
	include('includes/general-settings.php');
	
	if(isset($_POST['action']) && $_POST['action'] == 'yes')
	{
		if(isset($_POST['add_user']) && $_POST['add_user'] == 'true')
		{
			$result = $loginModel->addUser($_POST);
			if( isset($result['success']) && $result['success'] == true )
			{
				header("Location: " . PORTAL_URL . "view_user.php?user_id=".$result['data']['user_id']);
			}			
		}	
	}
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Add User - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | Dashboard" />
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
		<style>
			/* Hide number input spinners */
			input[type=tel]::-webkit-outer-spin-button,
			input[type=tel]::-webkit-inner-spin-button {
				-webkit-appearance: none;
				margin: 0;
			}
			input[type=tel] {
				-moz-appearance: textfield;
			}
		</style>
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Add New User</h1>
										<!--end::Title-->
										<!--begin::Breadcrumb-->
										<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">
												<a href="<?php echo ADMIN_URL; ?>" class="text-muted text-hover-primary">Home</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">Add New User</li>
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
									<!--begin::Layout-->
									<div class="d-flex flex-column flex-lg-row">
										<!--begin::Content-->
										<div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-12">
<?php										if(isset($_GET['action']) && $_GET['action'] == 'success')
											{
?>												<div class="alert alert-success" id="successMessage" role="alert">
													<strong>Success!</strong> Product <?php echo addslashes($_GET['message']); ?>.
												</div>
<?php										}
											else
											if( (isset($_GET['action']) && $_GET['action'] == 'fail' ) || (isset($result['success']) && $result['success'] == false) )	
											{
												$message = isset($_GET['message']) ? $_GET['message'] : $result['message'];
?>												<div class="alert alert-danger" id="successMessage" role="alert">
													<strong>Error!</strong> <?php echo addslashes($message); ?>.
												</div>
<?php										}
?>											
											<!--begin::Card-->
											<div class="card">
												<!--begin::Card body-->
												
												<div class="card-body p-12">
													<!--begin::Form-->
													<form action="" method="post" id="kt_invoice_form" name="add_receive_products" class="form-horizontal" enctype="multipart/form-data">
														<input type="hidden" name="action" value="yes" />
														<input type="hidden" name="add_user" value="true" />
														<!--begin::Separator-->
                                                        <h2>User Detail</h2>
														<div class="separator separator-dashed my-10"></div>
														<!--end::Separator-->
														<!--begin::Wrapper-->
														<div class="mb-0">
															<!--begin::Row-->
															<div class="row gx-10 mb-5">
																<!--begin::Col-->
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">User's Full Name</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="full_name" required placeholder="Enter Full Name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Email ID</label>
																	<div class="mb-5">
																		<input type="email" class="form-control form-control-solid" name="email" required placeholder="Enter Email ID" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Date of Birth</label>
																	<div class="mb-5">
																		<input type="date" class="form-control form-control-solid" name="dob" required value="<?php echo isset($_POST['dob']) ? $_POST['dob'] : ''; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Father Name</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="father_name" required placeholder="Enter Father Name" value="<?php echo isset($_POST['father_name']) ? htmlspecialchars($_POST['father_name']) : ''; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">User Role</label>
																	<div class="mb-5">
																		<select id="user_role" class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Select Role" name="user_role">
																			<option value="">Select Role</option>
																			<option value="Sales" <?php echo (isset($_POST['user_role']) && $_POST['user_role'] == 'Sales') ? 'selected' : ''; ?>>Sales</option>
																			<option value="Billing" <?php echo (isset($_POST['user_role']) && $_POST['user_role'] == 'Billing') ? 'selected' : ''; ?>>Billing</option>
																			<option value="Support" <?php echo (isset($_POST['user_role']) && $_POST['user_role'] == 'Support') ? 'selected' : ''; ?>>Support</option>
																		</select>
																	</div>
																</div>
																<div class="col-lg-6" id="manager-select">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3"> User's Manager</label>
																	<div class="mb-5">
																		<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Select Manager" name="manager_id">
																			<option value="<?php echo $userInfo['user_id']; ?>"><?php echo $userInfo['full_name']; ?></option>
																		</select>
																	</div>
																</div>
																<div class="col-lg-12">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Residential Address</label>
																	<div class="mb-5">
																		<textarea class="form-control form-control-solid" name="address" required placeholder="Enter Address" rows="3"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Contact Number</label>
																	<div class="mb-5">
																		<div class="input-group">
																			<select class="form-select form-select-solid" style="width: 20%" name="country_code" required>
																				<?php
																					$country_codes = $generalModel->getCountryCodes();
																					foreach($country_codes as $key => $country_code)
																					{
																						echo '<option value="' . $key . '">' . $country_code . '</option>';
																					}
																				?>
																			</select>
																			<input type="tel" 
																				class="form-control form-control-solid" 
																				style="width: 80%" 
																				name="contact" 
																				required 
																				placeholder="Enter Contact Number (7-12 digits)" 
																				minlength="7"
																				maxlength="12"
																				onkeydown="validatePhoneNumber(event)"
																				value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>" />
																			<!--<input type="tel" class="form-control form-control-solid" style="width: 80%" name="contact" required placeholder="Enter Contact Number" value="<?php //echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>" oninput="validatePhoneNumber(this)" />-->
																		</div>
																		
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Emergency Contact Number</label>
																	<div class="mb-5">
																		<div class="input-group">
																			<select class="form-select form-select-solid" style="width: 20%" name="emergency_country_code" required>
																				<?php
																					foreach($country_codes as $key => $country_code)
																					{
																						echo '<option value="' . $key . '">' . $country_code . '</option>';
																					}
																				?>
																			</select>
																			
																			<input type="tel" 
																				class="form-control form-control-solid" 
																				style="width: 80%" 
																				name="emergency_contact" 
																				required 
																				placeholder="Enter Emergency Contact Number (7-12 digits)" 
																				minlength="7"
																				maxlength="12"
																				onkeydown="validatePhoneNumber(event)"
																				value="<?php echo isset($_POST['emergency_contact']) ? htmlspecialchars($_POST['emergency_contact']) : ''; ?>" />
																		</div>
																		<!--<input type="tel" class="form-control form-control-solid" name="emergency_contact" required placeholder="Enter Emergency Contact" value="<?php //echo isset($_POST['emergency_contact']) ? htmlspecialchars($_POST['emergency_contact']) : ''; ?>" />-->
																	</div>
																	<!--end::Input group-->
																</div>	
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">CNIC</label>
																	<div class="mb-5">
																		
																		<input type="text" class="form-control form-control-solid" name="cnic" required placeholder="Enter CNIC Number (xxxxx-xxxxxxx-x)" pattern="\d{5}-\d{7}-\d{1}" title="Please enter CNIC in format: xxxxx-xxxxxxx-x" maxlength="15" oninput="
																			this.value = this.value.replace(/[^0-9]/g, '');
																			if(this.value.length > 5 && this.value.charAt(5) !== '-') {
																				this.value = this.value.slice(0,5) + '-' + this.value.slice(5);
																			}
																			if(this.value.length > 13 && this.value.charAt(13) !== '-') {
																				this.value = this.value.slice(0,13) + '-' + this.value.slice(13);
																			}
																		" value="<?php echo isset($_POST['cnic']) ? htmlspecialchars($_POST['cnic']) : ''; ?>" />
																		
																		<!--<input type="text" class="form-control form-control-solid" name="cnic" required placeholder="Enter CNIC Number" value="<?php //echo isset($_POST['cnic']) ? htmlspecialchars($_POST['cnic']) : ''; ?>" />-->
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Date of Joining</label>
																	<div class="mb-5">
																		<input type="date" class="form-control form-control-solid" name="joining_date" required value="<?php echo isset($_POST['joining_date']) ? $_POST['joining_date'] : ''; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Basic Salary</label>
																	<div class="mb-5">
																		<input type="number" class="form-control form-control-solid" name="basic_salary" required placeholder="Enter Basic Salary" value="<?php echo isset($_POST['basic_salary']) ? htmlspecialchars($_POST['basic_salary']) : ''; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Commission (%)</label>
																	<div class="mb-5">
																		<input type="number" step="0.01" class="form-control form-control-solid" name="commission" required placeholder="Enter Commission Percentage" value="<?php echo isset($_POST['commission']) ? htmlspecialchars($_POST['commission']) : ''; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Work Location</label>
																	<div class="mb-5">
																		<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Select Work Location" name="work_location">
																			<option value="">Select Work Location</option>
																			<option value="Office" <?php echo (isset($_POST['work_location']) && $_POST['work_location'] == 'Office') ? 'selected' : ''; ?>>Office</option>
																			<option value="Remote" <?php echo (isset($_POST['work_location']) && $_POST['work_location'] == 'Remote') ? 'selected' : ''; ?>>Remote</option>
																			<option value="Hybrid" <?php echo (isset($_POST['work_location']) && $_POST['work_location'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid</option>
																		</select>
																	</div>
																	<!--end::Input group-->
																</div>
																<!--end::Col-->
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Temporary Password</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="temp_pass" required placeholder="Enter Temporary Password" value="<?php echo isset($_POST['temp_pass']) ? htmlspecialchars($_POST['temp_pass']) : ''; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																
																
																<div class="d-grid mb-10 col-lg-9">
																    &nbsp;
																</div>
																<div class="d-grid mb-10 col-lg-9">
																    &nbsp;
																</div>
																<div class="d-grid mb-10 col-lg-3 align-right">
																	<button type="submit" class="btn btn-primary">
																		<!--begin::Indicator label-->
																		<span class="indicator-label">Add User</span>																		
																		<!--end::Indicator progress-->
																	</button>
																</div>
															</div>
															<!--end::Row-->															
														</div>
														<!--end::Wrapper-->
													</form>
													<!--end::Form-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Card-->
										</div>
										<!--end::Content-->										
									</div>
									<!--end::Layout-->
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
		<script src="<?php echo ASSETS; ?>js/main.js"></script>
		<!--end::Custom Javascript-->

		<script>
			jQuery(document).ready(function() {       
			// initiate layout and plugins
			App.init();
			FormComponents.init();
			});
			
			
			$('#user_role').on('change', function() {
				var managerSelect = $('#manager-select');
				var roleValue = $(this).val();
				if($.inArray(roleValue, ['Sales', 'Billing', 'Support']) !== -1) {
					managerSelect.show();
					managerSelect.find('select').prop('required', true);
				} else {
					managerSelect.hide();
					managerSelect.find('select').prop('required', false);
				}
			});			
			
		</script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>