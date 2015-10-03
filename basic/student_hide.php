<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php if(login_check("admin,edarh,t3lem") == true) { ?>
<?php

if ((isset($_GET['StID'])) && ($_GET['StID'] != "")) {
  $deleteSQL = sprintf("update `0_students` set `hide` =1 WHERE StID=%s",
                       GetSQLValueString($_GET['StID'],"double"));

  mysqli_select_db($localhost,$database_localhost);
  $Result1 = mysqli_query($localhost,$deleteSQL)or die(mysqli_error($localhost));

  $deleteGoTo = "search_st_resault.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo,'?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s",$deleteGoTo));
}
?>
<?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>