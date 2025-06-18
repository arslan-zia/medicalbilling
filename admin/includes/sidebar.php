<?php 
    $pageName	    = basename($_SERVER['PHP_SELF']); 
    $accessPrice    = "No";
    $sessionUserVar = $_SESSION['sess_user_id'];
    if($sessionUserVar == 7 || $sessionUserVar == 23)
    {
        $accessPrice = "Yes";
    }
	switch($pageName)
	{
		case 'index.php':
			$dashboardMenu 	= ' here show';
			$stockMenu 		= ''; 
			$stockSubMenu 	= '';
			$orderMenu 		= '';
			$orderSubMenu 	= '';
			$locationMenu 	= '';
			$locationSubMenu= '';
			$settingMenu 	= '';
			$settingSubMenu = '';
			$userMenu 		= '';
			$userSubMenu 	= '';
            $squareMenu     = '';
            $squareSubMenu  = '';
		break;
		
		case 'users.php':
			$dashboardMenu 	= '';
			$stockMenu 		= ''; 
			$stockSubMenu 	= '';
			$orderMenu 		= '';
			$orderSubMenu 	= '';
			$locationMenu 	= '';
			$locationSubMenu= '';
			$settingMenu 	= '';
			$settingSubMenu = '';
			$userMenu 		= ' here show';
			$userSubMenu 	= ' active';
            $squareMenu     = '';
            $squareSubMenu  = '';
		break;

        case 'square_config.php':
        case 'square_sync_settings.php':
        case 'square_location_mapping.php':
        case 'square_product_mapping.php':
            $dashboardMenu 	= '';
            $stockMenu 		= ''; 
            $stockSubMenu 	= '';
            $orderMenu 		= '';
            $orderSubMenu 	= '';
            $locationMenu 	= '';
            $locationSubMenu= '';
            $settingMenu 	= '';
            $settingSubMenu = '';
            $userMenu 		= '';
            $userSubMenu 	= '';
            $squareMenu     = ' here show';
            $squareSubMenu  = ' active';
        break;

		default:
			$dashboardMenu 	= ' here show';
			$stockMenu 		= ''; 
			$stockSubMenu 	= '';
			$orderMenu 		= '';
			$orderSubMenu 	= '';
			$locationMenu 	= '';
			$locationSubMenu= '';
			$settingMenu 	= '';
			$settingSubMenu = '';
			$userMenu 		= '';
			$userSubMenu 	= '';
            $squareMenu     = '';
            $squareSubMenu  = '';
		break;
	}
?>
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<!--begin::Logo-->
	<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
		<!--begin::Logo image-->
		<a href="index.php">
			<img alt="Logo" src="<?php echo ASSETS; ?>media/logos/billing-crm-logo.png" class="h-70px app-sidebar-logo-default" />
			<img alt="Logo" src="<?php echo ASSETS; ?>media/logos/default-small.png" class="h-20px app-sidebar-logo-minimize" />
		</a>
		<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
			<i class="ki-duotone ki-black-left-line fs-3 rotate-180">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Sidebar toggle-->
	</div>
	<!--end::Logo-->
	<!--begin::sidebar menu-->
	<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
		<!--begin::Menu wrapper-->
		<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
			<!--begin::Scroll wrapper-->
			<div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
				<!--begin::Menu-->
				<div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
					<!--begin:Menu item-->
					<div data-kt-menu-trigger="click" onclick="location.href='index.php'" class="menu-item <?php if($pageName == 'index.php'){ echo "here show";} ?> menu-accordion">
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-element-11 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
							</span>
							<span class="menu-title">Dashboard</span>
						</span>
					</div>
					<!--end:Menu item-->
					
					<!--begin:Menu item-->
					<div data-kt-menu-trigger="click" class="menu-item <?php if($pageName == 'add_user.php' || $pageName == 'users.php' || $pageName == 'edit_user.php'){ echo "here show";} ?> menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-profile-user fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
							<span class="menu-title">User Management</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'users.php'){ echo "active";} ?>" href="<?php echo ADMIN_URL; ?>users.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Show Users List</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'add_user.php'){ echo "active";} ?>" href="<?php echo ADMIN_URL; ?>add_user.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Add New User</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
																		
						</div>
						<!--end:Menu sub-->
					</div>
					<!--end:Menu item-->
					<!--begin:Menu item-->
					<div data-kt-menu-trigger="click" class="menu-item <?php if($pageName == 'leads.php' || $pageName == 'add_lead.php' || $pageName == 'edit_lead.php' || $pageName == 'view_lead.php'){ echo "here show";} ?> menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-chart-simple fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
									<span class="path5"></span>
								</i>
							</span>
							<span class="menu-title">Leads</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'leads.php'){ echo "active";} ?>" href="<?php echo ADMIN_URL; ?>leads.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">All Leads</span>
								</a>
								<!--end:Menu link-->
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'add_lead.php'){ echo "active";} ?>" href="<?php echo ADMIN_URL; ?>add_lead.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Add New Lead</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->												
						</div>
						<!--end:Menu sub-->
					</div>
					<!--end:Menu item-->

					<div data-kt-menu-trigger="click" class="menu-item <?php if($pageName == 'ip-settings.php' || $pageName == 'profile_settings.php' || $pageName == 'change_password.php'){ echo "here show";} ?> menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-map fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
							<span class="menu-title">Settings</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion" kt-hidden-height="84" style="">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'profile_settings.php'){ echo "active";} ?>" href="<?php echo ADMIN_URL; ?>profile_settings.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Profile Settings</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'ip-settings.php'){ echo "active";} ?>" href="<?php echo ADMIN_URL; ?>ip-settings.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">IP Settings</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'change_password.php'){ echo "active";} ?>" href="<?php echo ADMIN_URL; ?>change_password.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Change Password</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>

					<div data-kt-menu-trigger="click" class="menu-item <?php if($pageName == 'leads_sales_report.php'){ echo "here show";} ?> menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-calendar-search fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
							<span class="menu-title">Reports</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion" kt-hidden-height="84" style="">
							
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'leads_sales_report.php'){ echo "active";} ?>" href="<?php echo ADMIN_URL; ?>leads_sales_report.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Leads & Sales</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							
						</div>
						<!--end:Menu sub-->
					</div>


					<div data-kt-menu-trigger="click" onclick="location.href='view_chats.php'" class="menu-item <?php if($pageName == 'chats.php'){ echo "here show";} ?> menu-accordion">
						<span class="menu-link">
							<span class="menu-icon">
								<i class="bi bi-chat-dots fs-2" style="color: #ffc700;"></i>
							</span>
							<span class="menu-title">View Chats &nbsp<span class="newMessagesCount" style="color:red;"></span></span>
						</span>
					</div>

					<div data-kt-menu-trigger="click" onclick="location.href='chats.php'" class="menu-item <?php if($pageName == 'chats.php'){ echo "here show";} ?> menu-accordion">
						<span class="menu-link">
							<span class="menu-icon">
								<i class="bi bi-chat-dots fs-2" style="color: #ffc700;"></i>
							</span>
							<span class="menu-title">Chats &nbsp<span class="newMessagesCount" style="color:red;"></span></span>
						</span>
					</div>

					<?php /* ?>
					<div data-kt-menu-trigger="click" class="menu-item <?php if($pageName == 'add_location.php' || $pageName == 'locations.php'){ echo "here show";} ?> menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-bank fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Locations</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion" kt-hidden-height="84" style="">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'add_location.php'){ echo "active";} ?>" href="add_location.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Add Location</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'locations.php'){ echo "active";} ?>" href="locations.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">All Locations</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<?php */ ?>

					<!-- Begin: Square Sync Menu -->
					<?php /* ?>
					<div data-kt-menu-trigger="click" class="menu-item <?php if($pageName == 'square_config.php' || $pageName == 'square_sync_settings.php' || $pageName == 'square_location_mapping.php'){ echo "here show";} ?> menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-abstract-26 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title">Square Sync</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'square_config.php'){ echo "active";} ?>" href="square_config.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Configuration</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'square_location_mapping.php'){ echo "active";} ?>" href="square_location_mapping.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Location Mapping</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'square_product_mapping.php'){ echo "active";} ?>" href="square_product_mapping.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Product Mapping</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'square_sync_settings.php'){ echo "active";} ?>" href="square_sync_settings.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Sync Settings</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<?php */ ?>
					<!-- End: Square Sync Menu -->

					

					<?php /* ?>
					<div data-kt-menu-trigger="click" class="menu-item <?php if($pageName == 'users.php'){ echo "here show";} ?> menu-accordion">
						<!--begin:Menu link-->
						<span class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-menu fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
							</span>
							<span class="menu-title">User Management</span>
							<span class="menu-arrow"></span>
						</span>
						<!--end:Menu link-->
						<!--begin:Menu sub-->
						<div class="menu-sub menu-sub-accordion" kt-hidden-height="84" style="">
							<!--begin:Menu item-->
							<div class="menu-item">
								<!--begin:Menu link-->
								<a class="menu-link <?php if($pageName == 'users.php'){ echo "active";} ?>" href="users.php">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">All Users</span>
								</a>
								<!--end:Menu link-->
							</div>
							<!--end:Menu item-->
						</div>
						<!--end:Menu sub-->
					</div>
					<?php */ ?>

				</div>
				<!--end::Menu-->
			</div>
			<!--end::Scroll wrapper-->
		</div>
		<!--end::Menu wrapper-->
	</div>
	<!--end::sidebar menu-->
	<!--begin::Footer-->
	<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
		<a href="<?php echo ADMIN_URL; ?>logout.php" class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click">
			<span class="btn-label">Logout</span>
			<i class="ki-duotone ki-document btn-icon fs-2 m-0">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</a>
	</div>
	<!--end::Footer-->
</div>
