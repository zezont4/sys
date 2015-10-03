<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php if(login_check("admin,er") == true) { ?>
<?php

if ((isset($_GET['AutoNo'])) && ($_GET['AutoNo'] != "")) {
  $deleteSQL = sprintf("DELETE FROM er_ertiqaexams WHERE AutoNo=%s",
                       GetSQLValueString($_GET['AutoNo'],"int"));

  mysqli_select_db($localhost,$database_localhost);
  $Result1 = mysqli_query($localhost,$deleteSQL)or die(mysqli_error($localhost));

  $deleteGoTo = "addexamdate.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo,'?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s",$deleteGoTo));
}
?>
<?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>