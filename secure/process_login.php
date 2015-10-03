<?php
require_once('../Connections/localhost.php');
include_once 'functions.php';
sec_session_start();  
if(isset($_POST['username'],$_POST['password'])) { 
   $username = $_POST['username'];
   $password = $_POST['password']; 
   //$password2=$_POST['password2'];
   //zlog($username);
   //zlog($password);
   //exit;
   if(login($username,$password) == true) {
      // Login success
     header("Location: ../index.php");
	 exit;
   } else {
      // Login failed
     header("Location: ../login.php?error=1");
	exit;
   }
} else { 
   // The correct POST variables were not sent to this page.
   echo 'Invalid Request';
}
?>