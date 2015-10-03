<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_localhost = "mysql501.opentransfer.com";
$database_localhost = "C277890_qprogram";
$username_localhost = "C277890_qprogram";
$password_localhost = "Kli124816";
$localhost= mysqli_pconnect($hostname_localhost,$username_localhost,$password_localhost) or trigger_error(mysqli_error(),E_USER_ERROR); 
mysqli_set_charset('utf8',$localhost); 

//$AjaxFilePath="http://quranzulfi.com/sys/";

?>