<?php
	include('class/class.php'); 

	if( !isset($_SESSION['sess_email']) || empty($_SESSION['sess_email']) ) {
		header("Location: " . SERVER . "login.php");
		exit();
	}

	//echo "<pre>"; print_r($_SESSION); echo "</pre>"; exit();
	//$generalModel	= 	new General();
	//$loginModel	    = 	new Login();
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Dashboard - <?php echo SITE_NAME; ?></title>
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
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body >
		
		
	</body>
	<!--end::Body-->
</html>