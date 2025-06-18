<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
	include('includes/general-settings.php');
    
    // Check if user_id is provided and valid
    if(isset($_GET['user_id']) && is_numeric($_GET['user_id']))
    {
        $userID = $_GET['user_id'];
        $userInfo = $loginModel->userbyID($userID);
        
        // If no user found with this ID, redirect to users list
        if(empty($userInfo)) {
            header("Location: " . ADMIN_URL . "users.php");
            exit();
        }
    }
    else
    {
        // Redirect to users list if no valid ID provided
        header("Location: " . ADMIN_URL . "users.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>View User - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="View User - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="View User - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | View User" />
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">View User</h1>
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
												<a href="<?php echo PORTAL_URL; ?>users.php" class="text-muted text-hover-primary">Users</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">View User</li>
											<!--end::Item-->
										</ul>
										<!--end::Breadcrumb-->
									</div>
									<!--end::Page title-->
									<!--begin::Actions-->
									<div class="d-flex align-items-center gap-2 gap-lg-3">
										<!--begin::Primary button-->
										<a href="<?php echo ADMIN_URL; ?>edit_user.php?user_id=<?php echo $userID; ?>" class="btn btn-sm fw-bold btn-primary">Edit User</a>
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
													<strong>Success!</strong> User <?php echo addslashes($_GET['message']); ?>.
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
													<!-- User Information -->
													<div class="row">
														
														<!-- Right Column -->
														<div class="col-lg-9">
															<!-- User Information -->
															<div class="card card-flush border-0 bg-light-warning mb-5 mb-xl-8">
																<div class="card-header">
																	<div class="card-title">
																		<h3 class="fw-bold fs-3 mb-1">Employee Information</h3>
																	</div>
																	
																</div>
																<div class="card-body pt-5">
																	
																	<div class="px-7 py-5 bg-light-primary rounded mb-5">
																		<!-- Full Name -->
																		<div class="d-flex flex-stack fs-4 py-3">
																			<div class="fw-bold">Full Name</div>
																			<div class="text-gray-700 fs-5"><?php echo htmlspecialchars($userInfo['full_name']); ?></div>
																		</div>
																		
																		<!-- Email -->
																		<div class="d-flex flex-stack fs-4 py-3">
																			<div class="fw-bold">Email</div>
																			<div class="text-gray-700">
																				<a href="mailto:<?php echo htmlspecialchars($userInfo['email']); ?>" class="text-hover-primary">
																					<?php echo htmlspecialchars($userInfo['email']); ?>
																				</a>
																			</div>
																		</div>
																		<!-- Role -->
																		<div class="d-flex flex-stack fs-4 py-3">
																			<div class="fw-bold">Employee Role</div>
																			<div class="text-gray-700 fs-5"><?php echo htmlspecialchars($userInfo['user_role']); ?></div>
																		</div>
																		<!-- Status -->
																		<div class="d-flex flex-stack fs-4 py-3">
																			<div class="fw-bold">Employee Status</div>
																			<div class="text-gray-700 fs-5"><?php echo ($userInfo['status'] == 1) ? 'Active' : 'Inactive'; ?></div>
																		</div>

																	</div>

																	<!-- Basic Employment Details -->
																	<div class="mb-5">
																		<div class="d-flex flex-stack mb-3">
																			<div class="badge badge-light fs-7 fw-bold">
																				<h4>EMPLOYMENT DETAILS</h4>
																			</div>
																		</div>
																		<div class="px-7 py-5 bg-light-primary rounded mb-5">
																			<!-- Employee ID -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Employee ID</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($userInfo['user_id']); ?></div>
																			</div>
																			<!-- Joining Date -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Joining Date</div>
																				<div class="fw-bold"><?php echo date('M d, Y', strtotime($userInfo['joining_date'])); ?></div>
																			</div>
																			<!-- Basic Salary -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Basic Salary</div>
																				<div class="fw-bold">$<?php echo number_format($userInfo['basic_salary'], 2); ?></div>
																			</div>
																			<!-- Commission -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Commission</div>
																				<div class="fw-bold"><?php echo $userInfo['commission']; ?>%</div>
																			</div>
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Work Location</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($userInfo['work_location']); ?></div>
																			</div>
																		</div>
																	</div>
																	
																	<!-- Personal Details -->
																	<div class="mb-0">
																		<div class="d-flex flex-stack mb-3">
																			<h4>PERSONAL DETAILS</h4>
																		</div>
																		<div class="px-7 py-5 bg-light-success rounded">
																			<!-- CNIC -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">CNIC</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($userInfo['cnic']); ?></div>
																			</div>
																			<!-- Date of Birth -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Date of Birth</div>
																				<div class="fw-bold"><?php echo date('M d, Y', strtotime($userInfo['dob'])); ?></div>
																			</div>
																			<!-- Father Name -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Father's Name</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($userInfo['father_name']); ?></div>
																			</div>
																			<!-- Work Location -->
																			
																			<!-- Contact Numbers -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Contact Number</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($userInfo['contact_no']); ?></div>
																			</div>
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Emergency Contact</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($userInfo['emergency_contact_no']); ?></div>
																			</div>
																			<!-- Address -->
																			<div class="d-flex flex-stack py-2">
																				<div class="fw-semibold text-gray-600">Residential Address</div>
																				<div class="fw-bold"><?php echo htmlspecialchars($userInfo['address']); ?></div>
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
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>