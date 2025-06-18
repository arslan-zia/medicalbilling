<?php
	session_start();
	//error_reporting(0); 
	header("Connection: Keep-alive");
	require_once(dirname(dirname(__FILE__)) . '/includes/constant.php');
	require_once(dirname(dirname(__FILE__)) . '/includes/connection.php');

	include(dirname(dirname(__FILE__)) . '/class/inv-login-model.php');
	include(dirname(dirname(__FILE__)) . '/class/inv-general-model.php');


	$generalModel	= 	new General();
	$loginModel		= 	new Login();

	$loginModel->logoutUser();

	if(isset($_GET['message']))
	{
		$message = "?message=" . $_GET['message'];
	} else {
		$message = "";
	}

	header("Location: " . SERVER . "login.php" . $message);
    
	exit("Forbidden ...");
?>