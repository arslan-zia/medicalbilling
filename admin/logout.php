<?php
	session_start();
	//error_reporting(0); 
	header("Connection: Keep-alive");
	require_once(dirname(dirname(__FILE__)) . '/includes/constant.php');
	require_once(dirname(dirname(__FILE__)) . '/includes/connection.php');

	include(dirname(dirname(__FILE__)) . '/class/inv-login-model.php');
	include(dirname(dirname(__FILE__)) . '/class/inv-general-model.php');

	$page_name 	= 	$_SERVER['PHP_SELF'];	
	
	$generalModel	= 	new General();
	$loginModel		= 	new Login();

	$loginModel->logoutUser();
	header("Location: " . SERVER . "login.php");
    
	exit("Forbidden ...");
?>