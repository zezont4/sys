<?php
include 'functions.php';
if (isset($_COOKIE['rememberme'])) {
    unset($_COOKIE['rememberme']);
    setcookie('rememberme', '', time() - 3600, '/');
}
if (isset($_COOKIE['arabic_name'])) {
    unset($_COOKIE['arabic_name']);
    setcookie('arabic_name', '', time() - 3600, '/');
}
// Unset all session values
$_SESSION = array();
// get session parameters
$params = session_get_cookie_params();
// Delete the actual cookie.
setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
// Destroy session
session_destroy();
header('Location: /sys/index.php');