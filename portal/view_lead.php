<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
	header("Connection: Keep-alive");
	include('includes/general-settings.php');
    
    // Check if lead_id is provided and valid
    if(isset($_GET['lead_id']) && is_numeric($_GET['lead_id']))
    {
        $leadID = $_GET['lead_id'];
        $leadInfo = $leadModel->getLeadByID($leadID);
        
        // If no lead found with this ID, redirect to leads list
        if(empty($leadInfo)) {
            header("Location: " . PORTAL_URL . "leads.php");
            exit();
        }
    }
    else
    {
        // Redirect to leads list if no valid ID provided
        header("Location: " . PORTAL_URL . "leads.php");
        exit();
    }
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_dispute') {
		$leadId = intval($_POST['lead_id']);

		$result = $leadModel->updateDispute($leadId, $_POST);

		if ($result) {
			header("Location: view_lead.php?lead_id=$leadId&action=success&message=Dispute updated");
			
			exit();
		} else {
			header("Location: view_lead.php?lead_id=$leadId&action=fail&message=Could not update dispute");
			
			exit();
		}
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_refund') {
		$leadId = intval($_POST['lead_id']);

		$result = $leadModel->updateRefund($leadId, $_POST);

		if ($result) {
			header("Location: view_lead.php?lead_id=$leadId&action=success&message=Refund updated");
			
			exit();
		} else {
			header("Location: view_lead.php?lead_id=$leadId&action=fail&message=Could not update refund");
			
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>View Lead - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="View Lead - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="View Lead - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | View Lead" />
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">View Lead</h1>
										<!--end::Title-->
										<!--begin::Breadcrumb-->
										<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">
												<a href="<?php echo PORTAL_URL; ?>" class="text-muted text-hover-primary">Home</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">
												<a href="<?php echo PORTAL_URL; ?>leads.php" class="text-muted text-hover-primary">Leads</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">View Lead</li>
											<!--end::Item-->
										</ul>
										<!--end::Breadcrumb-->
									</div>
									<!--end::Page title-->
									<!--begin::Actions-->
									<div class="d-flex align-items-center gap-2 gap-lg-3">
										<?php if($_SESSION['sess_user_type'] == 3) // only manager can edit the lead
										{
										?>
										<!--begin::Primary button-->
										<a href="<?php echo PORTAL_URL; ?>edit_lead.php?lead_id=<?php echo $leadID; ?>" class="btn btn-sm fw-bold btn-primary">Edit Lead</a>
										<?php
										}
										?>
										<a href="javascript:void(0);" class="btn btn-sm fw-bold btn-primary mark-as-dispute">Mark As Dispute</a>
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
													<!-- Lead Information -->
													<div class="d-flex flex-column flex-xl-row">
														<!-- Left Column -->
														<div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10 me-xl-10">
															<div class="card card-flush border-0 bg-light-primary mb-5 mb-xl-8">
																<div class="card-header ribbon ribbon-top ribbon-vertical">
																	<div class="ribbon-label bg-primary">
																		<i class="ki-duotone ki-user fs-2 text-white"></i>
																	</div>
																	<h3 class="card-title align-items-start flex-column">
																		<span class="card-label fw-bold fs-3 mb-1">Lead Information</span>
																		<span class="text-muted mt-1 fw-semibold fs-7">Customer details</span>
																	</h3>
																</div>
																<div class="card-body pt-5">
																	<!-- Lead ID -->
																	<div class="d-flex flex-stack fs-4 py-3">
																		<div class="fw-bold rotate collapsible">Lead ID</div>
																		<div class="badge badge-light-primary fs-5">#<?php echo $leadInfo['lead_id']; ?></div>
																	</div>
																	
																	<!-- Customer Name -->
																	<div class="d-flex flex-stack fs-4 py-3">
																		<div class="fw-bold">Customer Name</div>
																		<div class="text-gray-700 fs-5"><?php echo htmlspecialchars($leadInfo['customer_name']); ?></div>
																	</div>
																	
																	<!-- Email -->
																	<div class="d-flex flex-stack py-3">
																		<div class="fw-bold">Email</div>
																		<div class="text-gray-700">
																			<a href="#" class="text-hover-primary">
																				<?php
																				$email = $leadInfo['email'];
																				$atPos = strpos($email, '@');
																				$firstChar = substr($email, 0, 1);
																				$domain = substr($email, $atPos);
																				echo $maskedEmail = $firstChar . str_repeat('*', $atPos-1) . $domain;
																				
																				// $email = $leadInfo['email'];
																				// $atPos = strpos($email, '@');
																				// $firstChar = substr($email, 0, 1);
																				// $lastChar = substr($email, -1);
																				// $maskedEmail = $firstChar . str_repeat('*', $atPos-1) . '@' . str_repeat('*', strlen($email)-$atPos-1) . $lastChar;
																				// echo htmlspecialchars($maskedEmail);
																				?>
																			</a>
																		</div>
																	</div>
																	
																	<!-- Phone -->
																	<div class="d-flex flex-stack py-3">
																		<div class="fw-bold">Phone</div>
																		<div class="text-gray-700">
																			<a href="#" class="text-hover-primary">
																				<?php 
																				$phone = $leadInfo['contact_no'];
																				$masked_phone = substr($phone, 0, 3) . str_repeat('*', strlen($phone)-5) . substr($phone, -2);
																				echo $masked_phone;
																				?>
																				<?php //echo htmlspecialchars($leadInfo['contact_no']); ?>
																			</a>
																		</div>
																	</div>
																	
																	<!-- Company Name -->
																	<div class="d-flex flex-stack py-3">
																		<div class="fw-bold">Company Name</div>
																		<div class="text-gray-700"><?php echo htmlspecialchars($leadInfo['company_name']); ?></div>
																	</div>
																	
																	<!-- DOB -->
																	<div class="d-flex flex-stack py-3">
																		<div class="fw-bold">Date of Birth</div>
																		<div class="text-gray-700"><?php echo date('M d, Y', strtotime($leadInfo['dob'])); ?></div>
																	</div>
																	
																	<!-- SSN -->
																	<div class="d-flex flex-stack py-3">
																		<div class="fw-bold">SSN</div>
																		<div class="text-gray-700">
																			<?php 
																				$masked_ssn = preg_replace('/^(\d{3})-(\d{2})-(\d{4})$/', '$1-$2-****', $leadInfo['ssn']);
																				echo htmlspecialchars($masked_ssn); 
																			?>
																		</div>
																	</div>
																	
																	<!-- Residential Address -->
																	<div class="d-flex flex-stack py-3">
																		<div class="fw-bold">Residential Address</div>
																		<div class="text-gray-700"><?php echo htmlspecialchars($leadInfo['res_address']); ?></div>
																	</div>
																	
																	<!-- Status -->
																	<div class="d-flex flex-stack py-3">
																		<div class="fw-bold">Status</div>
																		<div>
																			<?php if($leadInfo['status'] == 1): ?>
																				<span class="badge badge-light-success">Active</span>
																			<?php else: ?>
																				<span class="badge badge-light-danger">Inactive</span>
																			<?php endif; ?>
																		</div>
																	</div>
																</div>
															</div>
															
															<!-- Sales Team Information -->
															<div class="card card-flush border-0 bg-light-info mb-5 mb-xl-8">
																<div class="card-header">
																	<h3 class="card-title align-items-start flex-column">
																		<span class="card-label fw-bold fs-3 mb-1">Sales Team</span>
																		<span class="text-muted mt-1 fw-semibold fs-7">Sales Person & Manager</span>
																	</h3>
																</div>
																<div class="card-body pt-5">
																	<!-- Sales Person -->
																	<div class="d-flex align-items-center mb-7">
																		<div class="symbol symbol-50px me-5">
																		<?php 
																		if (!empty($leadInfo['sales_person_picture'])): ?>
																			<img src="<?php echo SERVER; ?>/uploads/profile_pictures/<?php echo $leadInfo['sales_person_picture']; ?>" class="" alt="Profile Picture">
																		<?php else: ?>
																			<img src="<?php echo ASSETS; ?>media/avatars/blank.png" class="" alt="Profile Picture">
																		<?php endif; ?>
																		</div>
																		<div class="flex-grow-1">
																			<a href="#" class="text-dark fw-bold text-hover-primary fs-6"><?php echo $leadInfo['sales_person_name']; ?></a>
																			<span class="text-muted d-block fw-semibold">ID: <?php echo $leadInfo['added_by']; ?></span>
																		</div>
																	</div>
																	
																	<?php
																	$managerInfo = $loginModel->userbyID($leadInfo['manager_id']);
																	if( isset($managerInfo) && !empty($managerInfo) )
																	{
																	?>
																	<!-- Manager -->
																	<div class="d-flex align-items-center">
																		<div class="symbol symbol-50px me-5">
																			<?php
																			if (!empty($managerInfo['profile_picture'])): ?>
																				<img src="<?php echo SERVER; ?>/uploads/profile_pictures/<?php echo $managerInfo['profile_picture']; ?>" class="" alt="Profile Picture">
																			<?php else: ?>
																				<img src="<?php echo ASSETS; ?>media/avatars/blank.png" class="" alt="Profile Picture">
																			<?php endif; ?>
																		</div>
																		<div class="flex-grow-1">
																			<a href="#" class="text-dark fw-bold text-hover-primary fs-6"><?php echo $managerInfo['full_name']; ?></a>
																			<span class="text-muted d-block fw-semibold"><?php echo $managerInfo['user_role']; ?></span>
																		</div>
																	</div>
																	<?php
																	}
																	?>
																</div>
															</div>

															<!--begin::Sales Disputes Information-->
															<div class="card card-flush border-0 bg-light-info mb-5 mb-xl-8">
																<div class="card-header">
																	<h3 class="card-title align-items-start flex-column">
																		<span class="card-label fw-bold fs-3 mb-1">Dispute Info</span>
																		<span class="text-muted mt-1 fw-semibold fs-7">Lead Dispute Information</span>
																	</h3>
																</div>
																<div class="card-body pt-5">
																	<!-- Sales Person -->
																	<div class="d-flex align-items-center mb-7">
																		<div class="flex-grow-1">
																			<a href="#" class="text-dark fw-bold text-hover-primary fs-6">Date: <?php echo $leadInfo['dispute_date']; ?></a>
																			<span class="text-muted d-block fw-semibold">Amount: <?php echo $leadInfo['dispute_amount']; ?></span>
																		</div>
																	</div>
																</div>
															</div>
															<!--end::Sales Disputes Information-->

															<!--begin::Sales Refund Information-->
															<div class="card card-flush border-0 bg-light-info mb-5 mb-xl-8">
																<div class="card-header">
																	<h3 class="card-title align-items-start flex-column">
																		<span class="card-label fw-bold fs-3 mb-1">Refund Info</span>
																		<span class="text-muted mt-1 fw-semibold fs-7">Lead Refund Information</span>
																	</h3>
																</div>
																<div class="card-body pt-5">
																	<!-- Sales Person -->
																	<div class="d-flex align-items-center mb-7">
																		<div class="flex-grow-1">
																			<a href="#" class="text-dark fw-bold text-hover-primary fs-6">Date: <?php echo $leadInfo['refund_date']; ?></a>
																			<span class="text-muted d-block fw-semibold">Amount: <?php echo $leadInfo['refund_amount']; ?></span>
																		</div>
																	</div>
																</div>
															</div>
															<!--end::Sales Refund Information-->
														</div>
														
														<!-- Right Column -->
														<div class="flex-lg-row-fluid">
															<!-- Payment Information -->
															<div class="card card-flush border-0 bg-light-warning mb-5 mb-xl-8">
																<div class="card-header">
																	<div class="card-title">
																		<h3 class="fw-bold fs-3 mb-1">Payment Information</h3>
																	</div>
																	<div class="card-toolbar">
																		<span class="badge badge-light-primary fs-7 fw-bold">
																			<?php echo htmlspecialchars($leadInfo['payment_method']); ?>
																		</span>
																	</div>
																</div>
																<div class="card-body pt-5">
																	<?php if($leadInfo['payment_method'] == 'Credit Card'): ?>
																	<!-- Credit Card Details -->
																	<div class="mb-5">
																		<div class="d-flex flex-stack mb-3">
																			<div class="badge badge-light fs-7 fw-bold">CREDIT CARD DETAILS</div>
																		</div>
																		<div class="px-7 py-5 bg-light-primary rounded mb-5">
																			<!-- Card Number -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Card Number</div>
																				<div class="fw-bold">
																					<?php 
																						$masked_card = substr($leadInfo['card_number'], -4);
																						echo htmlspecialchars('**** **** **** ' . $masked_card); 
																					?>
																				</div>
																			</div>
																			<!-- Expiry -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Expiry</div>
																				<div class="fw-bold">
																					<?php echo sprintf('%02d', $leadInfo['exp_month']) . '/' . $leadInfo['exp_year']; ?>
																				</div>
																			</div>
																			<!-- CVV -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">CVV</div>
																				<div class="fw-bold">
																					<?php 
																						$masked_cvv = preg_replace('/\d/', '*', $leadInfo['cvv']);
																						echo htmlspecialchars($masked_cvv); 
																					?>
																				</div>
																			</div>
																		</div>
																	</div>
																	
																	<?php elseif($leadInfo['payment_method'] == 'Checking'): ?>
																	<!-- Checking Details -->
																	<div class="mb-5">
																		<div class="d-flex flex-stack mb-3">
																			<div class="badge badge-light fs-7 fw-bold">CHECKING DETAILS</div>
																		</div>
																		<div class="px-7 py-5 bg-light-primary rounded mb-5">
																			<!-- Account Number -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Account Number</div>
																				<div class="fw-bold">
																					<?php 
																						echo htmlspecialchars($leadInfo['account_no']); 
																					?>
																				</div>
																			</div>
																			<!-- Routing Number -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Routing Number</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($leadInfo['routing_no']); ?></div>
																			</div>
																			<!-- Checking Number -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Checking Number</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($leadInfo['checking_no']); ?></div>
																			</div>
																		</div>
																	</div>
																	
																	<?php elseif($leadInfo['payment_method'] == 'Payment Link'): ?>
																	<!-- Payment Links -->
																	<div class="mb-5">
																		<div class="d-flex flex-stack mb-3">
																			<div class="badge badge-light fs-7 fw-bold">PAYMENT LINKS</div>
																		</div>
																		<div class="px-7 py-5 bg-light-primary rounded mb-5">
																			<!-- PayPal -->
																			<?php if(!empty($leadInfo['paypal_link'])): ?>
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">PayPal</div>
																				<div class="fw-bold">
																					<a href="<?php echo htmlspecialchars($leadInfo['paypal_link']); ?>" target="_blank" class="text-primary">
																						View Link <i class="ki-duotone ki-external-link fs-7 ms-2"></i>
																					</a>
																				</div>
																			</div>
																			<?php endif; ?>
																			<!-- Stripe -->
																			<?php if(!empty($leadInfo['stripe_link'])): ?>
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Stripe</div>
																				<div class="fw-bold">
																					<a href="<?php echo htmlspecialchars($leadInfo['stripe_link']); ?>" target="_blank" class="text-primary">
																						View Link <i class="ki-duotone ki-external-link fs-7 ms-2"></i>
																					</a>
																				</div>
																			</div>
																			<?php endif; ?>
																			<!-- Square -->
																			<?php if(!empty($leadInfo['square_link'])): ?>
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Square</div>
																				<div class="fw-bold">
																					<a href="<?php echo htmlspecialchars($leadInfo['square_link']); ?>" target="_blank" class="text-primary">
																						View Link <i class="ki-duotone ki-external-link fs-7 ms-2"></i>
																					</a>
																				</div>
																			</div>
																			<?php endif; ?>
																			<!-- Custom Link -->
																			<?php if(!empty($leadInfo['custom_payment_link'])): ?>
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Custom Link</div>
																				<div class="fw-bold">
																					<a href="<?php echo htmlspecialchars($leadInfo['custom_payment_link']); ?>" target="_blank" class="text-primary">
																						View Link <i class="ki-duotone ki-external-link fs-7 ms-2"></i>
																					</a>
																				</div>
																			</div>
																			<?php endif; ?>
																		</div>
																	</div>
																	<?php endif; ?>
																	
																	<!-- Billing Information -->
																	<div class="mb-0">
																		<div class="d-flex flex-stack mb-3">
																			<div class="badge badge-light fs-7 fw-bold">BILLING DETAILS</div>
																		</div>
																		<div class="px-7 py-5 bg-light-success rounded">
																			<!-- Billing Address -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Billing Address</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($leadInfo['billing_address']); ?></div>
																			</div>
																			<!-- Sale Amount -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Sale Amount</div>
																				<div class="fw-bold text-success">$<?php echo number_format($leadInfo['sale_amount'], 2); ?></div>
																			</div>
																			<!-- Discount -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Discount</div>
																				<div class="fw-bold text-danger">-$<?php echo number_format($leadInfo['discount'], 2); ?></div>
																			</div>
																			<!-- Total Amount -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-bold fs-6">Total Amount</div>
																				<div class="fw-bolder fs-6 text-primary">
																					$<?php echo number_format($leadInfo['sale_amount'] - $leadInfo['discount'], 2); ?>
																				</div>
																			</div>
																			<div class="separator my-5"></div>
																			<!-- Sale Date -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Sale Date</div>
																				<div class="fw-bold"><?php echo date('M d, Y', strtotime($leadInfo['date'])); ?></div>
																			</div>
																			<!-- Merchant -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Merchant</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($leadInfo['merchant']); ?></div>
																			</div>
																			<!-- Added Date -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Added On</div>
																				<div class="fw-bold"><?php echo date('M d, Y h:i A', strtotime($leadInfo['added_date'])); ?></div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
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

		<!--begin::Dispute Modal-->
		<div class="modal fade" id="leadModal" tabindex="-1" aria-labelledby="leadModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form method="post" action="" id="leadForm">
					<input type="hidden" name="action" value="">
    			<input type="hidden" name="lead_id" value="<?php echo $leadID; ?>">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="leadModalLabel"></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body" id="modalBody"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary" id="leadModalSubmitBtn"></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!--end::Dispute Modal-->
		
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
		<script>
			document.querySelector('.mark-as-dispute').addEventListener('click', function() {
				const leadModal = new bootstrap.Modal(document.getElementById('leadModal'));

				document.querySelector('input[name="action"]').value = 'update_dispute';

				document.getElementById('leadModalLabel').textContent = 'Mark As Dispute';

				document.getElementById('leadModalSubmitBtn').textContent = 'Save Dispute';

				document.getElementById('modalBody').innerHTML = `
					<div class="mb-3">
						<label for="dispute_date" class="form-label">Dispute Date</label>
						<input type="date" class="form-control" id="dispute_date" name="dispute_date" value="<?php echo htmlspecialchars($leadInfo['dispute_date'] ?? date('Y-m-d')); ?>" required>
					</div>
					<div class="mb-3">
						<label for="dispute_amount" class="form-label">Dispute Amount</label>
						<input type="number" class="form-control" id="dispute_amount" name="dispute_amount" min="0" value="<?php echo $leadInfo['dispute_amount'] ? str_replace('$', '', $leadInfo['dispute_amount']) : 0; ?>" required>
					</div>
				`;

				leadModal.show();
			});

			document.querySelector('.mark-as-refund').addEventListener('click', function() {
				const leadModal = new bootstrap.Modal(document.getElementById('leadModal'));

				document.querySelector('input[name="action"]').value = 'update_refund';

				document.getElementById('leadModalLabel').textContent = 'Mark As Refund';

				document.getElementById('leadModalSubmitBtn').textContent = 'Save Refund';

				document.getElementById('modalBody').innerHTML = `
					<div class="mb-3">
						<label for="refund_date" class="form-label">Refund Date</label>
						<input type="date" class="form-control" id="refund_date" name="refund_date" value="<?php echo htmlspecialchars($leadInfo['refund_date'] ?? date('Y-m-d')); ?>">
					</div>
				`;

				leadModal.show();
			});

			document.getElementById('leadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const action = form.querySelector('input[name="action"]').value;
        
        let confirmationMessage = '';
        
        if (action === 'update_dispute') {
					confirmationMessage = 'Are you sure you want to mark this lead as disputed?';
        } else {
					confirmationMessage = 'Are you sure you want to mark this lead as refunded?';
        }
        
        if (confirm(confirmationMessage)) {
					form.submit();
        }
    	});
		</script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>