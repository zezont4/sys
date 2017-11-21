<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

mysqli_select_db($localhost, $database_localhost);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	$national_id = "-1";
	if (isset($_POST['national_id'])) {
		$national_id = $_POST['national_id'];
	}
//	mysqli_select_db($localhost, $database_localhost);
	$query_exist = sprintf("SELECT national_id,concat_ws(' ',name1,name2,name3,name4) as full_name FROM 0_employees WHERE national_id = %s", GetSQLValueString($national_id, "double"));
	$rs_if_exist = mysqli_query($localhost, $query_exist) or die('$rs_if_exist: ' . mysqli_error($localhost));
	$row_if_exist = mysqli_fetch_assoc($rs_if_exist);
	$total_rows_if_exist = mysqli_num_rows($rs_if_exist);
	if ($total_rows_if_exist > 0) {
		?>
		<?php include('../templates/header1.php'); ?>
		<br><br><br>
		<div style="direction:rtl;text-align:center;font-size:22px;">
			<br/>
			<br/>
			<br/>
			<h1 dir="rtl" align="center">السجل المدني(<?php echo $row_if_exist['national_id']; ?>) موجود سابقا,وهو تابع <?php echo get_gender_label('emp', 'لل'); ?> :<?php echo $row_if_exist['full_name']; ?></h1>
			<h2 dir="rtl" align="center">قد يكون تابع <?php echo get_gender_label('e', 'ل'); ?> آخر ,لذا يرجى الاتصال بقسم الارتقاء ليقوم بنقل <?php get_gender_label('emp', 'ال'); ?></h2>
		</div>
		<?php
		exit;
	}


	$insertSQL = sprintf("INSERT INTO 0_employees (national_id,name1,name2,name3,name4,mobile_no,edarah_id,start_date, qualification,job_title)
  						 VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
		GetSQLValueString($_POST['national_id'], "double"),
		GetSQLValueString($_POST['name1'], "text"),
		GetSQLValueString($_POST['name2'], "text"),
		GetSQLValueString($_POST['name3'], "text"),
		GetSQLValueString($_POST['name4'], "text"),
		GetSQLValueString($_POST['mobile_no'], "text"),
		GetSQLValueString($_POST['EdarahID'], "int"),
		GetSQLValueString(str_replace('/', '', $_POST['start_date']), "text"),
		GetSQLValueString($_POST['qualification'], "int"),
		GetSQLValueString($_POST['job_title'], "int")
	);


	mysqli_select_db($localhost, $database_localhost);
	$Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));
	if ($Result1) {
		$_SESSION['u1'] = "u1";
	}

}
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'إضافة ' . get_gender_label('emp', ''); ?>
<title><?php echo $PageTitle; ?></title>
<style type="text/css">
	form table {
		border: 1px solid #333;
	}

	form table tr th {
		/*background-color: #999;*/
		text-align: center;
		vertical-align: middle;
		border: 1px solid rgb(189, 189, 189);
		padding: 5px 2px;
	}

	form table tr td {
		text-align: center;
		vertical-align: top;
		border: 1px solid #666;
		padding: 5px 5px;
	}
</style>
</head>
<body>

<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<div class="content">
	<?php if (login_check("admin,edarh,t3lem") == true) { ?>
		<form method="post" id="form1" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">

			<div class="four columns alpha">
				<div class="LabelContainer">
					<label for="name1">الاسم</label>
				</div>
				<input type="text" name="name1" id="name1" data-required="true">
			</div>
			<div class="four columns">
				<div class="LabelContainer">
					<label for="name2"> الأب</label>
				</div>
				<input type="text" name="name2" id="name2" data-required="true">
			</div>
			<div class="four columns">
				<div class="LabelContainer">
					<label for="name3">الجد</label>
				</div>
				<input type="text" name="name3" id="name3" data-required="true">
			</div>
			<div class="four columns omega">
				<div class="LabelContainer">
					<label for="name4">العائلة</label>
				</div>
				<input type="text" name="name4" id="name4" data-required="true">
			</div>

			<br class="clear"/>

			<div class="four columns alpha">
				<div class="LabelContainer">
					<label for="national_id">السجل المدني</label>
				</div>
				<input name="national_id" type="text" id="national_id" data-required="true" data-maxlength="10" data-minlength="10" data-type="digits">
			</div>

			<div class="four columns">
				<div class="LabelContainer">
					<label for="mobile_no">رقم الجوال</label>
				</div>
				<input type="tel" name="mobile_no" id="mobile_no" data-required="true" data-type="digits" data-maxlength="10" data-minlength="10">
			</div>

			<div class="four columns ">
				<div class="LabelContainer">المؤهل العلمي</div>
				<?php echo create_combo("qualification", $qualification, 0, '', 'data-required="true"'); ?>
			</div>

			<div class="four columns omega">
				<div class="LabelContainer">
					<label for="start_date">تاريخ المباشرة</label>
				</div>
				<input type="text" name="start_date" id="start_date" zezo_date="true" data-required="true">
			</div>

			<br class="clear"/>
			<br class="clear"/>

			<div class="four columns alpha">
				<div class="LabelContainer">
					<label for="EdarahID"><?php echo get_gender_label('e', 'ال'); ?></label>
				</div>
				<?php create_edarah_combo(); ?>
			</div>

			<div class="four columns ">
				<div class="LabelContainer">العمل في <?php echo get_gender_label('e', 'ال'); ?></div>
				<?php echo create_combo("job_title", $job, 0, '', 'data-required="true"'); ?>
			</div>

			<br class="clear"/>
			<br class="clear"/>

			<div class="four columns omega left">
				<input name="submit" type="submit" class="button-primary" id="submit" value="إضافة">
			</div>
			<input type="hidden" name="MM_insert" value="form1">
		</form>
	<?php } else {
		echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
	} ?>
</div>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
if (isset($_SESSION['u1'])) {
	?>
	<script>
		$(document).ready(function () {
			alertify.success("تمت الإضافة بنجاح");
		});
		//alertify.success("تمت الإضافة بنجاح");
	</script>
	<?php
	$_SESSION['u1'] = NULL;
	unset($_SESSION['u1']);
}
?>
<script type="text/javascript">
	showError();
</script>