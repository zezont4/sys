<?php
if (!isset($_SESSION)) {
	if (!headers_sent()) {
		session_start();
	}
}
global $type;
if (isset($_SESSION['arabic_name'])){ 
	if($type=='short'){  
		echo 'مرحبا ('.$_SESSION['arabic_name'].')'; /*<a class="logout" href="/sys/logout.php" tabindex="-1">تسجيل خروج</a>*/
	}if($type=='long'){
		echo '<a title="مرحبا :'.$_SESSION['arabic_name'].'<br>اضغط هنا لتسجيل الخروج" class="logoutIcon" href="/sys/logout.php"></a>';
	}
}else{
	echo '<a title="تسجيل الدخول" class="loginIcon" href="/sys/login.php" tabindex="-1"></a>';
} 
 ?> 