<?php
	define('SERVER', 'http://' . $_SERVER['HTTP_HOST'] . "/");
	//define('SERVER', 'https://med-crm.com/');

	define("HOSTDB"	, "localhost");
	define("DATABASE"	, "u852193329_medBilling");
	define("USERNAME"	, "u852193329_medBilling");
	define("PASSWORD"	, "medBilling!@#123");

	// server should keep session data for AT LEAST 1 hour
	//ini_set('session.gc_maxlifetime', 360000);

	// each client should remember their session id for EXACTLY 1 hour
	//session_set_cookie_params(360000);

	define('ADMIN_URL', SERVER . 'admin/');
	define('PORTAL_URL', SERVER . 'portal/');

	define('CATEGORY', SERVER . 'category/');
	define('COMPANY', SERVER . 'company/');
	define('OUT', SERVER . 'out/');
	define('BLOG', SERVER . 'blog/');
	define('ABOUT_US', SERVER . 'about-us/');
	
	define('ASSETS', SERVER . 'assets/');
	define('CSS', ASSETS . 'css/');
	define('JS', ASSETS . 'js/');
	define('PLUGINS', ASSETS . 'plugins/');
	define('BOOTSTRAP', PLUGINS . 'bootstrap/');
	define('SCRIPTS', ASSETS . 'scripts/');
	define('POPUP', SERVER . 'popup_assets/');
	define('IMAGES', ASSETS . 'img/');
	define('PRODUCT_IMAGES', IMAGES . 'product_img/');
	define('STORE_LOGO', ASSETS . 'images/company/logo/');
	define('SKU_IMAGES', PRODUCT_IMAGES . 'sku_img/');
	define('NO_IMAGE' , ASSETS . 'images/no_image.jpg');
	define('STORE_IMAGES', SERVER . 'images/stores/');
	define('STORE_SNAPSHOT', SERVER . 'images/stores/stores_snapshot/');

    define('SITENAME', 'Billing Meister');
	define('SITE_NAME', 'Billing Meister CRM');
	define('SLOGAN', 'Medical Billing CRM');
	define('SITE_DOMAIN', 'https://crm.billingmeister.com');
	define("DEFAULT_CURRENCY", "USD");

	define('FEATURED_DATA_COUNT', '50');
?>
