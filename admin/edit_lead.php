<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
	include('includes/general-settings.php');
	
	$users = $loginModel->allUsers();

    // Check if lead_id is provided and valid
    if(isset($_GET['lead_id']) && is_numeric($_GET['lead_id']))
    {
        $leadID = $_GET['lead_id'];
        $leadInfo = $leadModel->getLeadByID($leadID);
        
        // If no lead found with this ID, redirect to leads list
        if(empty($leadInfo)) {
            header("Location: " . ADMIN_URL . "leads.php");
            exit();
        }
    }
    else
    {
        // Redirect to leads list if no valid ID provided
        header("Location: " . ADMIN_URL . "leads.php");
        exit();
    }
	
	if(isset($_POST['action']) && $_POST['action'] == 'yes')
	{
		if(isset($_POST['edit_lead']) && $_POST['edit_lead'] == 'true')
		{
			$result = $leadModel->editLead($_GET['lead_id'],$_POST);
			if($result === true)
			{
				header("Location: " . ADMIN_URL . "view_lead.php?lead_id=". $leadID . "&action=success&message=Updated Successfully");
				exit();	
			}
			else
			{
				header("Location: " . ADMIN_URL . "edit_lead.php?lead_id=". $leadID . "&action=fail&message=" . urlencode($result));
				exit();
			}
		}	
	}
	
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Edit Lead - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="Edit Lead - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="Edit Lead - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | Edit Lead" />
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit Lead</h1>
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
											<li class="breadcrumb-item text-muted">
												<a href="<?php echo ADMIN_URL; ?>leads.php" class="text-muted text-hover-primary">Leads</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">Edit Lead</li>
											<!--end::Item-->
										</ul>
										<!--end::Breadcrumb-->
									</div>
									<!--end::Page title-->
									<!--begin::Actions-->
									<div class="d-flex align-items-center gap-2 gap-lg-3">
										<!--begin::Primary button-->
										<a href="<?php echo ADMIN_URL; ?>view_lead.php?lead_id=<?php echo $leadID; ?>" class="btn btn-sm fw-bold btn-secondary">View Lead</a>
										<!--end::Primary button-->
									</div>
									<!--end::Actions-->
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
											<?php
											if(isset($_GET['action']) && $_GET['action'] == 'success')
											{
												?>
												<div class="alert alert-success" id="successMessage" role="alert">
													<strong>Success!</strong> Lead <?php echo addslashes($_GET['message']); ?>.
												</div>
												<?php
											} else if(isset($_GET['action']) && $_GET['action'] == 'fail')	
											{
												?>
												<div class="alert alert-danger" id="successMessage" role="alert">
													<strong>Error!</strong> <?php echo addslashes($_GET['message']); ?>.
												</div>
												<?php
											}
											?>											
											<!--begin::Card-->
											<div class="card">
												<!--begin::Card body-->
												
												<div class="card-body p-12">
													<!--begin::Form-->
													<form action="" method="post" id="kt_invoice_form" name="edit_lead_form" class="form-horizontal" enctype="multipart/form-data">
														<input type="hidden" name="action" value="yes" />
														<input type="hidden" name="edit_lead" value="true" />
														<input type="hidden" name="lead_id" value="<?php echo $leadID; ?>" />
														
														<!-- General Information -->
														<h2>General Information</h2>
														<div class="separator separator-dashed my-10"></div>
														<div class="mb-0">
															<div class="row gx-10 mb-5">
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Customer Name</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="customer_name" required placeholder="Enter Customer Name" value="<?php echo htmlspecialchars($leadInfo['customer_name']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Email</label>
																	<div class="mb-5">
																		<input type="email" class="form-control form-control-solid" name="email" required placeholder="Enter Email" value="<?php echo htmlspecialchars($leadInfo['email']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Phone/Contact No</label>
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
																				name="phone" 
																				required 
																				placeholder="Enter Phone/Contact No (7-12 digits)" 
																				minlength="7"
																				maxlength="12"
																				onkeydown="validatePhoneNumber(event)"
																				value="<?php echo htmlspecialchars($leadInfo['contact_no']); ?>" />
																		</div>
																	</div>
																	<!--end::Input group-->
																</div>
																<!-- <div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Phone/Contact No</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="contact_no" required placeholder="Enter Phone/Contact No" value="<?php //echo htmlspecialchars($leadInfo['contact_no']); ?>" />
																	</div>
																</div> -->
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Company Name</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="company_name" required placeholder="Enter Company Name" value="<?php echo htmlspecialchars($leadInfo['company_name']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">DOB</label>
																	<div class="mb-5">
																		<input type="date" class="form-control form-control-solid" name="dob" required value="<?php echo htmlspecialchars($leadInfo['dob']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">SSN</label>
																	<div class="mb-5">

																		<input type="text" class="form-control form-control-solid" name="ssn" required placeholder="Enter SSN" value="<?php echo htmlspecialchars($leadInfo['ssn']); ?>" onkeydown="formatSSN(event)" />
																	</div>
																</div>
																<div class="col-lg-12">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Residential Address</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="res_address" required placeholder="Enter Residential Address" value="<?php echo htmlspecialchars($leadInfo['res_address']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Status</label>
																	<div class="mb-5">
																		<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" name="status">
																			<option value="1" <?php echo ($leadInfo['status'] == 1) ? 'selected' : ''; ?>>Active</option>
																			<option value="0" <?php echo ($leadInfo['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
																		</select>
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Sales Person</label>
																	<div class="mb-5">
																		<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" name="sales_person">
																			<?php foreach($users as $user) { ?>
																				<option value="<?php echo $user['user_id']; ?>" <?php echo ($leadInfo['sales_person'] == $user['user_id']) ? 'selected' : ''; ?>><?php echo $user['full_name']; ?></option>
																			<?php } ?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
														
														<!-- Payment Information -->
														<h2>Payment Information</h2>
														<div class="separator separator-dashed my-10"></div>
														<div class="mb-0">
															<div class="row gx-10 mb-5">
																<div class="col-lg-12">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Payment Method</label>
																	<div class="mb-5">
																		<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Select Payment Method" name="payment_method" id="payment_method">
																			<option value="Credit Card" <?php echo ($leadInfo['payment_method'] == 'Credit Card') ? 'selected' : ''; ?>>Credit Card</option>
																			<option value="Checking" <?php echo ($leadInfo['payment_method'] == 'Checking') ? 'selected' : ''; ?>>Checking</option>
																			<option value="Payment Link" <?php echo ($leadInfo['payment_method'] == 'Payment Link') ? 'selected' : ''; ?>>Payment Link</option>
																		</select>
																	</div>
																</div>
															</div>
														</div>

														<!-- Payment Information :: Credit Card -->
														<div class="mb-0 payment-method payment-credit-card" style="display: none;">
															<div class="row gx-10 mb-5">
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Card Number</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="card_number" placeholder="Enter Card Number" maxlength="19" pattern="\d{4}\s\d{4}\s\d{4}\s\d{1,4}" value="<?php echo htmlspecialchars($leadInfo['card_number']); ?>" oninput="this.value = this.value.replace(/\D/g, '').replace(/(\d{4})(?=\d)/g, '$1 ');" />
																	</div>
																</div>
																<div class="col-lg-3">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Expiry Month</label>
																	<div class="mb-5">
																		<input type="number" class="form-control form-control-solid" name="expiry_month" placeholder="MM" value="<?php echo htmlspecialchars($leadInfo['exp_month']); ?>" min="01" max="12" title="Please enter a valid month (01-12)" maxlength="2" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);" />
																	</div>
																</div>
																<div class="col-lg-3">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Expiry Year</label>
																	<div class="mb-5">
																		<input type="number" class="form-control form-control-solid" name="expiry_year" placeholder="YYYY" value="<?php echo htmlspecialchars($leadInfo['exp_year']); ?>" min="2025" max="2050" title="Please enter a valid year (4 digits)" maxlength="4" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">CVV</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="cvv" placeholder="Enter CVV" value="<?php echo htmlspecialchars($leadInfo['cvv']); ?>" maxlength="4" pattern="\d{3,4}" title="Please enter a valid CVV (3 or 4 digits)" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" />
																	</div>
																</div>
															</div>
														</div>

														<!-- Payment Information :: Checking -->
														<div class="mb-0 payment-method payment-checking" style="display: none;">
															<div class="row gx-10 mb-5">
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Account Number</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="account_no" placeholder="Enter Account Number" value="<?php echo htmlspecialchars($leadInfo['account_no']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Routing Number</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="routing_no" placeholder="Enter Routing Number" value="<?php echo htmlspecialchars($leadInfo['routing_no']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Checking Number</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="checking_no" placeholder="Enter Checking Number" value="<?php echo htmlspecialchars($leadInfo['checking_no']); ?>" />
																	</div>
																</div>
															</div>
														</div>

														<!-- Payment Information :: Payment Link -->
														<div class="mb-0 payment-method payment-link" style="display: none;">
															<div class="row gx-10 mb-5">
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Paypal</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="paypal_link" placeholder="Enter Paypal Link" value="<?php echo htmlspecialchars($leadInfo['paypal_link']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Stripe</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="stripe_link" placeholder="Enter Stripe Link" value="<?php echo htmlspecialchars($leadInfo['stripe_link']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Square</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="square_link" placeholder="Enter Square Link" value="<?php echo htmlspecialchars($leadInfo['square_link']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Custom Link</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="custom_payment_link" placeholder="Enter Custom Link" value="<?php echo htmlspecialchars($leadInfo['custom_payment_link']); ?>" />
																	</div>
																</div>
															</div>
														</div>
														
														<!-- Billing Information -->
														<h2>Billing Information</h2>
														<div class="separator separator-dashed my-10"></div>
														<div class="mb-0">
															<div class="row gx-10 mb-5">
																<div class="col-lg-12">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Billing Address</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="billing_address" required placeholder="Enter Billing Address" value="<?php echo htmlspecialchars($leadInfo['billing_address']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Sale Amount</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="sale_amount" required placeholder="Enter Sale Amount" value="<?php echo htmlspecialchars($leadInfo['sale_amount']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Discount</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="discount" required placeholder="Enter Discount" value="<?php echo htmlspecialchars($leadInfo['discount']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Sale Date</label>
																	<div class="mb-5">
																		<input type="date" class="form-control form-control-solid" name="date" required value="<?php echo htmlspecialchars($leadInfo['date']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Merchant</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="merchant" required placeholder="Enter Merchant" value="<?php echo htmlspecialchars($leadInfo['merchant']); ?>" />
																	</div>
																</div>
															</div>
														</div>
														
														<div class="mb-0">
															<div class="row gx-10 mb-5">
																<div class="col-lg-6">
																	&nbsp;
																</div>
																<div class="col-lg-6">
																	<div class="d-grid mb-10 col-lg-8 align-right" style="float: right;">
																		<button type="submit" class="btn btn-primary">
																			<span class="indicator-label">Update Lead</span>
																		</button>
																	</div>
																</div>
															</div>
														</div>
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
									<!--begin::Chat-->
									<?php include('includes/chat.php'); ?>
									<!--end::Chat-->
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
		<script src="<?php echo ASSETS; ?>js/widgets.bundle.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/widgets.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/apps/chat/chat.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/create-app.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/users-search.js"></script>
		<script src="<?php echo ASSETS; ?>js/main.js"></script>
		<!--end::Custom Javascript-->
        <script type="text/javascript">
            // Function to show/hide payment method fields
			function togglePaymentFields(selectedMethod) {
				// First hide all payment method sections
				$('.payment-method').hide();
				
				// Then show the relevant section based on selection
				if (selectedMethod === 'Credit Card') {
					$('.payment-credit-card').show();
				} else if (selectedMethod === 'Checking') {
					$('.payment-checking').show();
				} else if (selectedMethod === 'Payment Link') {
					$('.payment-link').show();
				}
			}			
			// Handle initial state
			togglePaymentFields($('#payment_method').val());
        </script>
		<script>
			$(document).ready(function() { 
				// Handle change event
				$(document).on('change', '#payment_method', function() { 
					togglePaymentFields($(this).val());
				});
				
				// Get the default value of the payment method on page load
				var defaultPaymentMethod = $('#payment_method').val();
				if(defaultPaymentMethod == '')
				{
					togglePaymentFields('Credit Card');
				}

				// initiate layout and plugins
				App.init();
				FormComponents.init();
			});
		</script>
		<script type="text/javascript">
			// Format existing SSN value on page load
			window.addEventListener('load', function() {
				let ssnInput = document.querySelector('input[name="ssn"]');
				if (ssnInput && ssnInput.value) {
					// Clean the value of any non-digits
					let cleanValue = ssnInput.value.replace(/\D/g, '');
					
					// Add dashes at correct positions
					if (cleanValue.length > 3) {
						cleanValue = cleanValue.slice(0,3) + '-' + cleanValue.slice(3);
					}
					if (cleanValue.length > 6) {
						cleanValue = cleanValue.slice(0,6) + '-' + cleanValue.slice(6);
					}
					
					// Update input value
					ssnInput.value = cleanValue;
				}
			});
		</script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>