<?php header("Connection: Keep-alive");
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
    header("Location: " . PORTAL_URL . "logout.php");
    exit;
}

// No user can access this portal except Admin or sub-admin
if( isset($_SESSION['sess_user_type']) && ($_SESSION['sess_user_type'] > 2) )
{
    header("Location: " . PORTAL_URL . "logout.php");
    exit();
}
?>