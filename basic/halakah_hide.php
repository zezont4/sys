<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php if(login_check("admin,edarh,t3lem") == true) { ?>
<?php

if ((isset($_GET['AutoNo'])) && ($_GET['AutoNo'] != "")) {
  $deleteSQL = sprintf("update `0_halakat` set `hide` =1 WHERE AutoNo=%s",
                       GetSQLValueString($_GET['AutoNo'],"int"));

  mysqli_select_db($localhost,$database_localhost);
  $Result1 = mysqli_query($localhost,$deleteSQL)or die(mysqli_error($localhost));

  $deleteGoTo = "halakah_add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo,'?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s",$deleteGoTo));
}
?>
<?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>