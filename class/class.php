<?php  
//error_reporting(0);

$currentUrl = $_SERVER['REQUEST_URI'];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(dirname(dirname(__FILE__)) . '/includes/constant.php');
require_once(dirname(dirname(__FILE__)) . '/includes/connection.php');

if( !isset($_SESSION['sess_email']) || empty($_SESSION['sess_email']) ) {
    header("Location: " . SERVER . "login.php");
    exit();
}

if (isset($_SESSION['sess_email']) && ($_SESSION['sess_user_rights'] == 'admin' || $_SESSION['sess_user_rights'] == 'sub_admin') && strpos($currentUrl, '/admin') === false) {
    header('Location: ' . ADMIN_URL . 'index.php');
    exit();
}

if (isset($_SESSION['sess_email']) && $_SESSION['sess_user_rights'] != 'admin' && $_SESSION['sess_user_rights'] != 'sub_admin' && strpos($currentUrl, '/portal') === false) {
    header('Location: ' . PORTAL_URL . 'index.php');
    
    exit();
}

include(dirname(dirname(__FILE__)) . '/class/inv-login-model.php');
include(dirname(dirname(__FILE__)) . '/class/inv-general-model.php');
include(dirname(dirname(__FILE__)) . '/class/inv-lead-model.php');

$page_name = $_SERVER['PHP_SELF'];    

date_default_timezone_set("Asia/Karachi");
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";die;
?>
