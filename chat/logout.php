<?php
	session_start();
	//error_reporting(0); 
	header("Connection: Keep-alive");
	require_once('includes/constant.php');
	require_once('includes/connection.php');

	include('class/inv-login-model.php');
	include('class/inv-general-model.php');

	$page_name 	= 	$_SERVER['PHP_SELF'];	
	
	$generalModel	= 	new General();
	$loginModel		= 	new Login();

	$SessionTokenVar= "";
	$PostTokenVar 	= "";
	if(isset($_SESSION['_tokenVar']))
	{
		$SessionTokenVar = $_SESSION['_tokenVar'];
	}

	if(isset($_POST['_tokenVar']))
	{
		$PostTokenVar = $_POST['_tokenVar'];
	}

    if($SessionTokenVar == $PostTokenVar && $SessionTokenVar != '')
    {
        if(isset($_REQUEST['action']))
        {
            switch($_REQUEST['action'])
            {
                case "loginUser":
                    $result	=	$loginModel->loginUser();
					// echo "<pre>";
					// print_r($result);
					// echo "</pre>";
					// die();
                    if( isset($result) && !empty($result) && sizeof($result) > 0 )
                    {
						if($result['user_type'] == 1 || $result['user_type'] == 2)
						{
							header("Location: " . ADMIN_URL . "index.php");
						} else {
							header("Location: " . PORTAL_URL . "index.php");
						}
                    }
                    else
                    {
                        header("Location: " . SERVER . "login.php?message=login_fail");
                    }
                break;
            }
        }
    }

	if(isset($_REQUEST['action']))
	{
		switch($_REQUEST['action'])
		{
			case "loginUser":
				$result	=	$loginModel->loginUser();
				
				if( isset($result) && !empty($result) && sizeof($result) > 0 )
				{
					if($result['user_type'] == 1 || $result['user_type'] == 2)
					{
						header("Location: " . ADMIN_URL . "index.php");
					} else {
						header("Location: " . PORTAL_URL . "index.php");
					}
				}
				else
				{
					header("Location: " . SERVER . "login.php?message=login_fail");
				}
			break;
		
			case "registerUser":
				$result = $loginModel->registerUser();
				
				if($result == 0 || $result == 2)
				{
					echo "<script>location.href = 'login.php?message=register_fail&error=" . $result . "';</script>";
				}
				else
				if($result == 1)
				{
					echo "<script>location.href = 'login.php?message=register_success';</script>";
				}
			break;
			
			case "forgetPassword":
				$email	=	$_REQUEST['email'];
				$result	=	$loginModel->resetPassword($email);
				
				if($result == 0)
				{
					echo "<script>location.href = 'login.php?message=email_fail';</script>";
				}
				else
				if($result == 1)
				{
					echo "<script>location.href = 'login.php?message=email_success';</script>";
				}
			break;
			
			case "logoutUser":
				$loginModel->logoutUser();
				header("Location: " . SERVER . "login.php");
			break;
			
			default:
				
			break;
		}
	}
    
    $loginModel->generateSessionToken();
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Login - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="Login - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="Login - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Login - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SITE_DOMAIN; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | Dashboard" />
		<link rel="canonical" href="<?php echo SERVER; ?>" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank">
		<!--begin::Theme mode setup on page load-->
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Body-->
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
					<!--begin::Form-->
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
						<!--begin::Wrapper-->
						<div class="w-lg-500px p-10">
							<!--begin::Form-->
							<div style="margin:0px auto;width:100%;text-align:center"><img src="assets/img/logo_billing_meister.webp" width="250"style="margin:0px auto;" /></div>
							<form class="form w-100" novalidate="novalidate" action="login.php" method="post">
                                <input type="hidden" name="action" value="loginUser" />
                                <input type="hidden" name="_tokenVar" value="<?php echo $SessionTokenVar; ?>" />
								<!--begin::Heading-->
								<div class="text-center mb-11">
									<!--begin::Title-->
									<div class="text-gray-500 fw-semibold fs-6">&nbsp;</div>
									<h1 class="text-dark fw-bolder mb-3">Login</h1>
									<!--end::Title-->
									<!--begin::Subtitle-->
									
									<!--end::Subtitle=-->
								</div>
								<!--begin::Heading-->
								<?php /*<!--begin::Login options-->
								<div class="row g-3 mb-9">
									<!--begin::Col-->
									<div class="col-md-6">
										<!--begin::Google link=-->
										<a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
										<img alt="Logo" src="assets/media/svg/brand-logos/google-icon.svg" class="h-15px me-3" />Sign in with Google</a>
										<!--end::Google link=-->
									</div>
									<!--end::Col-->
									<!--begin::Col-->
									<div class="col-md-6">
										<!--begin::Google link=-->
										<a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
										<img alt="Logo" src="assets/media/svg/brand-logos/apple-black.svg" class="theme-light-show h-15px me-3" />
										<img alt="Logo" src="assets/media/svg/brand-logos/apple-black-dark.svg" class="theme-dark-show h-15px me-3" />Sign in with Apple</a>
										<!--end::Google link=-->
									</div>
									<!--end::Col-->
								</div>
								<!--end::Login options-->
								<!--begin::Separator-->
								<div class="separator separator-content my-14">
									<span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
								</div>
								<!--end::Separator-->*/ ?>
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Email-->
									<input type="text"  placeholder="Username or Email" name="username" autocomplete="off" class="form-control bg-transparent" />
									<!--end::Email-->
								</div>
								<!--end::Input group=-->
								<div class="fv-row mb-3">
									<!--begin::Password-->
									<input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" />
									<!--end::Password-->
								</div>
								<!--end::Input group=-->
								<?php /*<!--begin::Wrapper-->
								<div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
									<div></div>
									<!--begin::Link-->
									<a href="../../demo1/dist/authentication/layouts/corporate/reset-password.html" class="link-primary">Forgot Password ?</a>
									<!--end::Link-->
								</div>
								<!--end::Wrapper-->*/ ?>
								<!--begin::Submit button-->
								<div class="d-grid mb-10">
									<button type="submit" class="btn btn-primary">
										<!--begin::Indicator label-->
										<span class="indicator-label">Sign In</span>
										<!--end::Indicator label-->
										<!--begin::Indicator progress-->
										<span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
										<!--end::Indicator progress-->
									</button>
								</div>
								<!--end::Submit button-->
								<?php /*<!--begin::Sign up-->
								<div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet?
								<a href="../../demo1/dist/authentication/layouts/corporate/sign-up.html" class="link-primary">Sign up</a></div>
								<!--end::Sign up--> */ ?>
							</form>
							<!--end::Form-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Form-->
					<!--begin::Footer-->
					<?php /*
					<div class="w-lg-500px d-flex flex-stack px-10 mx-auto">
						<!--begin::Languages-->
						<div class="me-10">
							<!--begin::Toggle-->
							<button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
								<img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="assets/media/flags/united-states.svg" alt="" />
								<span data-kt-element="current-lang-name" class="me-1">English</span>
								<span class="d-flex flex-center rotate-180">
									<i class="ki-duotone ki-down fs-5 text-muted m-0"></i>
								</span>
							</button>
							<!--end::Toggle-->
							<!--begin::Menu-->
							<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7" data-kt-menu="true" id="kt_auth_lang_menu">
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link d-flex px-5" data-kt-lang="English">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="assets/media/flags/united-states.svg" alt="" />
										</span>
										<span data-kt-element="lang-name">English</span>
									</a>
								</div>
								<!--end::Menu item-->								
							</div>
							<!--end::Menu-->
						</div>
						<!--end::Languages-->
						<!--begin::Links-->
						<div class="d-flex fw-semibold text-primary fs-base gap-5">
							<a href="#" target="_blank">Terms</a>
							<a href="#" target="_blank">Privacy Policy</a>
							<a href="#" target="_blank">Contact Us</a>
						</div>
						<!--end::Links-->
					</div>
					<?php */ ?>
					<!--end::Footer-->
				</div>
				<!--end::Body-->
				<!--begin::Aside-->
				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style=" background-color:#024985;"><!-- background-image: url(assets/media/misc/auth-bg.png); -->
					<!--begin::Content-->
					<div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
						<!--begin::Logo-->
						<a href="#" class="mb-0 mb-lg-12">
							<h1 class="text-white fs-2qx fw-bolder text-center mb-7"><?php echo SITENAME; ?></h1>
							<h3 class="text-white text-center"><?php echo SLOGAN; ?></h3>
						</a>
						<!--end::Logo-->
						<!--begin::Image-->
						<img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="assets/media/misc/auth-screens.png" alt="" />
						<!--end::Image-->
						<!--begin::Title-->
						<!-- <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Medical Billing CRM</h1> -->
						<!--end::Title-->
						<?php /*<!--begin::Text-->
						<div class="d-none d-lg-block text-white fs-base text-center">In this kind of post,
						<a href="#" class="opacity-75-hover text-warning fw-bold me-1">the blogger</a>introduces a person they’ve interviewed
						<br />and provides some background information about
						<a href="#" class="opacity-75-hover text-warning fw-bold me-1">the interviewee</a>and their
						<br />work following this is a transcript of the interview.</div>
						<!--end::Text-->*/ ?>
					</div>
					<!--end::Content-->
				</div>
				<!--end::Aside-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/custom/authentication/sign-in/general.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>