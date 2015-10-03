<?php
include 'Connections/localhost.php';
// Unset all session values
$_SESSION = array();
// get session parameters 
$params = session_get_cookie_params();
// Delete the actual cookie.
setcookie(session_name(),'',time() - 42000,$params["path"],$params["domain"],$params["secure"],$params["httponly"]);
// Destroy session
session_destroy();
header('Location: ./');
//echo $RootPath;
// *** Logout the current user.
$logoutGoTo = "/sys/index.php";
/*if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['username'] = NULL;
$_SESSION['user_group'] = NULL;
$_SESSION['arabic_name'] = NULL;


unset($_SESSION['username']);
unset($_SESSION['user_group']);
unset($_SESSION['arabic_name']);
*/
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>