<?php
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; die;
$generalModel	= 	new General();
$loginModel	    = 	new Login();
$leadModel  	= 	new Lead();

if(isset($_SESSION['sess_user_id']) && is_numeric($_SESSION['sess_user_id']))
{
    $userID = $_SESSION['sess_user_id'];
    $userInfo = $loginModel->userbyID($userID);
}
else
{
    header("Location: " . SERVER . "login.php");
    exit;
}

// Admin can't access this portal
if(isset($_SESSION['sess_user_type']) && $_SESSION['sess_user_type'] == 1)
{
    header("Location: " . PORTAL_URL . "logout.php");
    exit;
}

// **************************************
// Check if IP restrictions are enabled
// **************************************
$ipRestrictionsStatus = $generalModel->getIPRestrictionsStatus();

if($ipRestrictionsStatus == 'Enabled')
{
    // Get user's IP address
    $user_ip = '';

    // Check for Cloudflare
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $user_ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    // Check for proxy/load balancer
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
    }
    // Check for shared internet
    else if (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $user_ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    // Direct connection
    else if (isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
        $user_ip = $_SERVER['REMOTE_ADDR'];
    }

    // for localhost testing
    $ip = file_get_contents('https://api.ipify.org');
    $user_ip = $ip;

    // Check if IP is allowed
    $ip_status = $generalModel->isIPAllowed($user_ip);

    if (!$ip_status) {
        header("Location: " . PORTAL_URL . "logout.php?message=IP Restriction Imposed. Please contact your manager or admin for access.");
        exit;
    }

}

// **************************************
// Check if logged in user is a Manager
// **************************************
$manager_id = 0;
$team_members = array();
$team_member_ids = array($_SESSION['sess_user_id']);
if(isset($_SESSION['sess_user_type']) && $_SESSION['sess_user_type'] == 3)
{
    $manager_id = $userID;
    $team_members = $loginModel->allUsers(0, $userID);
    if(sizeof($team_members) > 0)
    {
        foreach($team_members as $team_member)
        {
            $team_member_ids[] = $team_member['user_id'];
            //array_push($team_member_ids, $team_member['user_id']);
        }
    }
}
//echo "<pre>"; print_r($team_member_ids); echo "</pre>"; die;
?>