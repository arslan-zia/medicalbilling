<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
include('includes/general-settings.php');
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>All Users - <?php echo SITE_NAME; ?></title>
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Users</h1>
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
											<li class="breadcrumb-item text-muted">Users</li>
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
													<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Users" />
												</div>
												<!--end::Search-->
											</div>
											<!--begin::Card title-->
											<!--begin::Card toolbar-->
											<div class="card-toolbar">
												<!--begin::Toolbar-->
												<div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
													<!--begin::Add customer-->
													<a href="<?php echo ADMIN_URL; ?>add_user.php" class="btn btn-sm btn-primary">Add User</a>
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
														<th class="min-w-125px">Full Name</th>
														<th class="min-w-125px">Username</th>
														<th class="min-w-125px">Email</th>
														<th class="min-w-125px">User Role</th>
														<th class="min-w-125px">Status</th>
														<th class="text-end min-w-70px">Action</th>
													</tr>
												</thead>
												<tbody class="fw-semibold text-gray-600">
<?php												$allUsers	=	$loginModel->allUsers();
                                                    if(sizeof($allUsers) > 0)
                                                    {
                                                        $c = 1;
                                                        foreach($allUsers as $user)
                                                        {
                                                            //echo "<pre>"; print_r($user); echo "</pre>";
?>															<tr>
																<td>
																	<div class="form-check form-check-sm form-check-custom form-check-solid">
																		<?php echo $c; ?>
																	</div>
																</td>
																<td>
																	<!--begin::Badges-->
																	<?php echo $user['full_name']; ?>
																	<!--end::Badges-->
																</td>
																<td>
																	<a href="" class="text-gray-800 text-hover-primary mb-1"><?php echo $user['username']; ?></a>
																</td>
                                                                <td><?php echo $user['email']; ?></td>
                                                                <td><?php echo $user['user_role']; ?></td>
																
																<td><?php if($user['status'] == '1') { echo "Active"; } else { echo "In-Active"; } ?></td>
																
																<td class="text-end">
																	
																	<a href="<?php echo ADMIN_URL; ?>view_user.php?user_id=<?php echo $user['user_id']; ?>" class="fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" title="View User">
																		<i class="bi bi-eye fs-6 text-muted me-1"></i>
																	</a>
																	&nbsp;
																	<a href="<?php echo ADMIN_URL; ?>edit_user.php?user_id=<?php echo $user['user_id']; ?>" class="fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" title="Edit User">
																		<i class="bi bi-pencil fs-6 text-muted me-1"></i>
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
									<div class="modal fade" id="kt_modal_new_address" tabindex="-1" aria-hidden="true">
										<!--begin::Modal dialog-->
										<div class="modal-dialog modal-dialog-centered mw-650px">
											<!--begin::Modal content-->
											<div class="modal-content">
												<!--begin::Form-->
												<form class="form" method="post" action="users.php" id="kt_modal_new_address_form" data-kt-redirect="">
                                                    <input type="hidden" name="action" value="yes" />
													<!--begin::Modal header-->
													<div class="modal-header" id="kt_modal_new_address_header">
														<!--begin::Modal title-->
														<h2 class="fw-bold">Add User</h2>
														<!--end::Modal title-->
														<!--begin::Close-->
														<div  data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
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
														<div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
															<!--begin::Input group-->
                                                            <div class="row g-9 mb-7">
                                                                <div class="col-md-6 fv-row">
                                                                    <label class="required fs-6 fw-semibold mb-2">Username</label>
                                                                    <input class="form-control form-control-solid" name="username" required value="" placeholder="Username" />
                                                                </div>
                                                                <div class="col-md-6 fv-row">
                                                                    <label class="required fs-6 fw-semibold mb-2">User Type</label>
                                                                    <select required name="user_type" class="form-select form-select-solid fw-bold">
                                                                        <option value="">-- Select User Type --</option>
<?php											                        $userTypes	=	$loginModel->allUserType();	
                                                                        if(sizeof($userTypes) > 0)
                                                                        {
                                                                            foreach($userTypes as $type)
                                                                            {
?>														                        <option value="<?php echo $type->type_id; ?>"><?php echo $type->type; ?></option>
<?php                       												}
                                                                        }																		
?>                                                                  </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row g-9 mb-7">
                                                                <!--begin::Col-->
                                                                <div class="col-md-6 fv-row">
                                                                    <label class="required fs-6 fw-semibold mb-2">First Name</label>
                                                                    <input class="form-control form-control-solid" name="f_name" required value="" placeholder="First Name" />
                                                                </div>
                                                                
                                                                <div class="col-md-6 fv-row">
                                                                    <label class="required fs-6 fw-semibold mb-2">Last Name</label>
                                                                    <input class="form-control form-control-solid" name="l_name" required value="" placeholder="Last Name"/>
                                                                </div>
                                                                <!--end::Col-->
                                                            </div>
                                                            
                                                            <div class="row g-9 mb-7">
                                                                <div class="d-flex flex-column mb-7 fv-row">
																	<label class="required fs-6 fw-semibold mb-2">Email Address</label>
																	<input class="form-control form-control-solid" type="email" name="email" required value="" placeholder="info@example.com" />
																</div>
                                                            </div>

                                                            <div class="row g-9 mb-7">
                                                                <!--begin::Col-->
                                                                <div class="col-md-6 fv-row">
                                                                    <label class="required fs-6 fw-semibold mb-2">Password</label>
                                                                    <input class="form-control form-control-solid" type="password" name="password" required id="password" value="" placeholder="" />
                                                                </div>
                                                                
                                                                <div class="col-md-6 fv-row">
                                                                    <label class="required fs-6 fw-semibold mb-2">Re-Type Password</label>
                                                                    <input class="form-control form-control-solid" type="password" name="re_password" id="re_password" value="" placeholder="" />
                                                                </div>
                                                                <!--end::Col-->
                                                            </div>

                                                            <div class="row g-9 mb-7">
                                                                <div class="col-md-6 fv-row">
                                                                    <label class="required fs-6 fw-semibold mb-2">Status</label>
                                                                    <select required name="status" class="form-select form-select-solid fw-bold">
                                                                        <option value="1">Active</option>
                                                                        <option value="0">In-Active</option>
                                                                    </select>
                                                                </div>
                                                            </div>
															<!--end::Billing form-->
														</div>
														<!--end::Scroll-->
													</div>
													<!--end::Modal body-->
													<!--begin::Modal footer-->
													<div class="modal-footer flex-center">
														<!--begin::Button-->
														<button type="reset" id="kt_modal_new_address_cancel" class="btn btn-light me-3">Discard</button>
														<!--end::Button-->
														<!--begin::Button-->
														<button type="submit" id="kt_modal_new_address_submit" class="btn btn-primary">
															<span class="indicator-label">Submit</span>
															<span class="indicator-progress">Please wait...
															<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
														</button>
														<!--end::Button-->
													</div>
													<!--end::Modal footer-->
												</form>
												<!--end::Form-->
											</div>
										</div>
									</div>
									<!--end::Modal - Customers - Add-->
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
		<!--end::Custom Javascript-->
	</body>
	<!--end::Body-->
</html>