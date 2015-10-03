<?php error_reporting(E_ERROR | E_WARNING );?>
<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_localhost = "mysql501.opentransfer.com";
$database_localhost = "C277890_qprogram";
$username_localhost = "C277890_qprogram";
$password_localhost = "Kli124816";
$localhost= mysqli_connect ( $hostname_localhost,$username_localhost,$password_localhost ) or trigger_error ( mysqli_error($localhost),E_USER_ERROR );
mysqli_set_charset ($localhost,'utf8' );

if (! function_exists ( "GetSQLValueString" )) {
	function GetSQLValueString($theValue,$theType,$theDefinedValue = "",$theNotDefinedValue = "") {
	global$localhost;
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc () ? stripslashes ( $theValue ) : $theValue;
		}
		$theValue = function_exists ( "mysqli_real_escape_string" ) 
		? mysqli_real_escape_string ($localhost,$theValue ) 
		: mysqli_escape_string ($localhost,$theValue );
		
		switch ($theType) {
			case "text" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "long" :
			case "int" :
				$theValue = ($theValue != "") ? intval ( $theValue ) : "NULL";
				break;
			case "double" :
				$theValue = ($theValue != "") ? doubleval ( $theValue ) : "NULL";
				break;
			case "date" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined" :
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}
}
//Secure Session Start Function:
if (! function_exists ( "sec_session_start" )) {
	function sec_session_start() {
		$session_name = 'sec_session_id'; // Set a custom session name
		$secure = false; // Set to true if using https.
		$httponly = true; // This stops javascript being able to access the session id. 
	
		ini_set('session.use_only_cookies',1); // Forces sessions to only use cookies. 
		$cookieParams = session_get_cookie_params(); // Gets current cookies params.
		session_set_cookie_params($cookieParams["lifetime"],$cookieParams["path"],$cookieParams["domain"],$secure,$httponly); 
		session_name($session_name); // Sets the session name to the one set above.
		//$status = session_status();
		//if($status == PHP_SESSION_NONE){
		if (!isset($_SESSION)){
			//There is no active session
			//session_start();
			session_start(); // Start the php session
			session_regenerate_id(); // regenerated the session,delete the old one.  
		}
	}
}
//if($status == PHP_SESSION_NONE){
sec_session_start();
//}

$smsUser = '966500511556';
$smsPass = '765765Klm124816';
$smsSender = 'QuranZulfi';
?>