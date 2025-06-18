<?php include(dirname(dirname(__FILE__)) . '/class/class.php');
	header("Connection: Keep-alive");
	include('includes/general-settings.php');
	
	if(isset($_POST['action']) && $_POST['action'] == 'yes')
	{
		//echo "<pre>"; print_r($_POST); echo "</pre>"; die;
		if(isset($_POST['change_password']) && $_POST['change_password'] == 'true')
		{            
			$result = $loginModel->changeProfilePassword($userID);
			
			$result_parts = explode(":", $result);
			$result_type = trim($result_parts[0]);
			$result_message = isset($result_parts[1]) ? trim($result_parts[1]) : '';
			
			if($result_type === "Success")
			{
				header("Location: " . PORTAL_URL . "change_password.php?action=" . $result_type . "&message=" . urlencode($result_message));
				exit;
			}
			else
			{
				header("Location: " . PORTAL_URL . "change_password.php?action=" . $result_type . "&message=" . urlencode($result_message));
				exit;
			}
		}	
	}
	
	
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Change Password - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Change Password - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | Change Password" />
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Change Password</h1>
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
												<a href="#" class="text-muted text-hover-primary">Settings</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">Change Password</li>
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
<?php										if(isset($_GET['action']) && $_GET['action'] == 'Success')
											{
?>												<div class="alert alert-success" id="successMessage" role="alert">
													<strong>Success!</strong> <?php echo htmlspecialchars($_GET['message']); ?>
												</div>
<?php										}
											else
											if(isset($_GET['action']) && $_GET['action'] == 'Error')
											{
?>												<div class="alert alert-danger" id="successMessage" role="alert">
													<strong>Error!</strong> <?php echo htmlspecialchars($_GET['message']); ?>
												</div>
<?php										}
?>											
											<!--begin::Card-->
											<div class="card">
												<!--begin::Card body-->
												<div class="card-body p-12">
													<!--begin::Form-->
													<form action="" method="post" id="kt_invoice_form" name="edit_user_form" class="form-horizontal" enctype="multipart/form-data">
														<input type="hidden" name="action" value="yes" />
                                                        <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
														<input type="hidden" name="change_password" value="true" />
														<!--begin::Separator-->
                                                        <h2><?php echo $_SESSION['sess_full_name']; ?> - Change Password</h2>
														<div class="separator separator-dashed my-10"></div>
														<!--end::Separator-->
														<!--begin::Wrapper-->
														<div class="mb-0">
															<!--begin::Row-->
															<div class="row gx-10 mb-5">
																<!--begin::Col-->
																<div class="col-lg-9">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Old Password</label>
																	<div class="mb-5">
																		<input type="password" class="form-control form-control-solid" name="old_password" required placeholder="Enter Old Password" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-9">
																	<hr />
																</div>
																<div class="col-lg-9">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">New Password</label>
																	<div class="mb-5">
																		<input type="password" class="form-control form-control-solid" name="new_password" required placeholder="Enter New Password" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-9">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Confirm Password</label>
																	<div class="mb-5">
																		<input type="password" class="form-control form-control-solid" name="confirm_password" required placeholder="Enter Confirm Password" />
																	</div>
																</div>
																
																<div class="col-lg-9">
																	&nbsp;
																</div>
																<div class="d-grid mb-10 col-lg-3">
																    &nbsp;
																</div>
																<div class="d-grid mb-10 col-lg-3 align-left">
																	<button type="submit" class="btn btn-primary">
																		<!--begin::Indicator label-->
																		<span class="indicator-label">Update Password</span>																		
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
		<!--end::Custom Javascript-->
        <script type="text/javascript">
            function generareSKUCode()
            {
                const d = new Date();
                let ms = d.valueOf();
                $("#product_sku").val(ms);
            }
        </script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>