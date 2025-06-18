<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
	header("Connection: Keep-alive");
	include('includes/general-settings.php');
	
	if(isset($_POST['action']) && $_POST['action'] == 'yes')
	{
		if(isset($_POST['edit_user']) && $_POST['edit_user'] == 'true')
		{
            //echo "<pre>"; print_r($_REQUEST); echo "</pre>"; 
            //echo "<pre>"; print_r($_FILES['product_images']); echo "</pre>"; die;
            
            $itemuserID = $_POST['user_id'];
			$result = $loginModel->editProfile();

			if($result === true)
			{
				header("Location: " . PORTAL_URL . "edit_user.php?action=success&message=User Updated Successfully");
				exit;
			}
			else
			{
				header("Location: " . PORTAL_URL . "edit_user.php?action=fail&message=" . urlencode($result));
				exit;
			}
		}	
	}
	
	
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Edit Profile - <?php echo SITE_NAME; ?></title>
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit Profile</h1>
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
											<li class="breadcrumb-item text-muted">Edit Profile</li>
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
													<strong>Success!</strong> <?php echo htmlspecialchars($_GET['message']); ?>
												</div>
<?php										}
											else
											if(isset($_GET['action']) && $_GET['action'] == 'fail')	
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
														<input type="hidden" name="edit_user" value="true" />
														<!--begin::Separator-->
                                                        <h2><?php echo $_SESSION['sess_full_name']; ?> - Profile Details</h2>
														<div class="separator separator-dashed my-10"></div>
														<!--end::Separator-->
														<!--begin::Wrapper-->
														<div class="mb-0">
															<!--begin::Row-->
															<div class="row gx-10 mb-5">
																<!--begin::Col-->
																<div class="col-lg-12 mb-8">
																	<!--begin::Profile picture section-->
																	<div class="d-flex flex-column align-items-start">
																		<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Profile Picture</label>
																		<div class="d-flex align-items-center gap-5">
																			<div class="symbol symbol-150px" style="border-radius: 8px; overflow: hidden;">
																				<img id="profile_preview" 
																					<?php if (!empty($userInfo['profile_picture'])): ?>
																						src="<?php echo SERVER; ?>/uploads/profile_pictures/<?php echo htmlspecialchars($userInfo['profile_picture']); ?>"
																					<?php else: ?>
																						src="<?php echo ASSETS; ?>media/avatars/blank.png"
																					<?php endif; ?>
																					alt="Profile Picture" 
																					style="width: 150px; height: 150px; object-fit: cover;">
																			</div>
																			<div class="d-flex flex-column">
																				<input type="file" class="form-control form-control-solid w-250px mb-2" 
																					name="profile_picture" 
																					id="profile_picture"
																					accept="image/*"
																					onchange="previewImage(this);"/>
																				<div class="text-muted fs-7">Allowed file types: png, jpg, jpeg. Max size: 2MB</div>
																			</div>
																		</div>
																	</div>
																	<!--end::Profile picture section-->
																</div>

																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Full Name</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="full_name" required placeholder="Enter Full Name" value="<?php echo htmlspecialchars($userInfo['full_name']); ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Email ID</label>
																	<div class="mb-5">
																		<input type="email" class="form-control form-control-solid" name="email" placeholder="Enter Email ID" disabled value="<?php echo htmlspecialchars($userInfo['email']); ?>" />
																	</div>
																	<!--end::Input group-->
																</div>

																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">User Role</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="user_role" placeholder="Enter User Role" disabled value="<?php echo htmlspecialchars($userInfo['user_role']); ?>" />
																	</div>
																</div>
																<div class="col-lg-6">
																	<!--end::Input group-->
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Father Name</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="father_name" required placeholder="Enter Father Name" value="<?php echo htmlspecialchars($userInfo['father_name']); ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Date of Birth</label>
																	<div class="mb-5">
																		<input type="date" class="form-control form-control-solid" name="dob" required value="<?php echo $userInfo['dob']; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Residential Address</label>
																	<div class="mb-5">
																		<textarea class="form-control form-control-solid" name="address" required placeholder="Enter Address" rows="3" disabled><?php echo htmlspecialchars($userInfo['address']); ?></textarea>
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Contact Number</label>
																	<div class="mb-5">
																		<input type="tel" class="form-control form-control-solid" name="contact" required placeholder="Enter Contact Number" value="<?php echo htmlspecialchars($userInfo['contact_no']); ?>" disabled />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Emergency Contact Number</label>
																	<div class="mb-5">
																		<input type="tel" class="form-control form-control-solid" name="emergency_contact" required placeholder="Enter Emergency Contact" value="<?php echo htmlspecialchars($userInfo['emergency_contact_no']); ?>" disabled />
																	</div>
																	<!--end::Input group-->
																</div>	
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">CNIC</label>
																	<div class="mb-5">
																		<input type="text" class="form-control form-control-solid" name="cnic" required placeholder="Enter CNIC Number" value="<?php echo htmlspecialchars($userInfo['cnic']); ?>" disabled />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Date of Joining</label>
																	<div class="mb-5">
																		<input type="date" disabled class="form-control form-control-solid" name="joining_date" required value="<?php echo $userInfo['joining_date']; ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Basic Salary</label>
																	<div class="mb-5">
																		<input type="number" disabled class="form-control form-control-solid" name="basic_salary" required placeholder="Enter Basic Salary" value="<?php echo htmlspecialchars($userInfo['basic_salary']); ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Commission (%)</label>
																	<div class="mb-5">
																		<input type="number" step="0.01" disabled class="form-control form-control-solid" name="commission" required placeholder="Enter Commission Percentage" value="<?php echo htmlspecialchars($userInfo['commission']); ?>" />
																	</div>
																	<!--end::Input group-->
																</div>
																<div class="col-lg-6">
																	<!--begin::Input group-->
																	<label class="form-label fs-6 fw-bold text-gray-700 mb-3">Work Location</label>
																	<div class="mb-5">
																		<input type="text" disabled class="form-control form-control-solid" name="work_location" required placeholder="Enter Work Location" value="<?php echo htmlspecialchars($userInfo['work_location']); ?>" />
																	</div>
																	<!--end::Input group-->
																</div>																
																<div class="d-grid mb-10 col-lg-9">
																    &nbsp;
																</div>
																<div class="d-grid mb-10 col-lg-3 align-right">
																	<button type="submit" class="btn btn-primary">
																		<!--begin::Indicator label-->
																		<span class="indicator-label">Update Profile</span>																		
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

			function previewImage(input) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();
					
					reader.onload = function(e) {
						document.getElementById('profile_preview').src = e.target.result;
					}
					
					reader.readAsDataURL(input.files[0]);
				}
			}
		</script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>