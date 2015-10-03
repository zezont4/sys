<?php
	if (!isset($_SESSION)) {
		session_start();
	}
?>
<?php include('templates/header1.php'); ?>
<?php $PageTitle = 'تن44h55hبيه'; ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('templates/header2.php'); ?>
<?php include ('templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->
<div class="content">
<div class="WrapperMSG">
<?php 
	if ($_GET['ID']=='WrongPass'){
		echo ' عفوا... اسم المستخدم أو كلمة المرور غير صحيحة';
	}else if ($_GET['ID']=='AccessDenide'){
		echo 'عفوا... لاتملك صلاحيات لدخول هذه الصفحة';
	}
?>
</div>
</div><!--content-->
<?php include('templates/footer.php'); ?>
<?php ?>