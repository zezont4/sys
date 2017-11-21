<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php $PageTitle = 'الإدارات والمستخدمون'; ?>
<?php if (login_check("admin,t3lem") == true) { ?>
	<?php
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . $_SERVER['QUERY_STRING'];
	}

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
		$insertSQL = sprintf("INSERT INTO 0_users (username,password,user_group,arabic_name,mobile_no,sex) VALUES (%s,%s,%s,%s,%s,%s)",
			GetSQLValueString($_POST['username'], "text"),
			GetSQLValueString($_POST['password'], "text"),
			GetSQLValueString($_POST['user_group'], "text"),
			GetSQLValueString($_POST['arabic_name'], "text"),
			GetSQLValueString($_POST['mobile_no'], "text"),
			GetSQLValueString($_POST['sex'], "int"));

		mysqli_select_db($localhost, $database_localhost);
		$Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));
		if ($Result1) {
			$_SESSION['u1'] = "";
			//header("Location: " . $editFormAction);
			//exit;
		}

	}
	$maxRows_RSEdarat = 200;
	$pageNum_RSEdarat = 0;
	if (isset($_GET['pageNum_RSEdarat'])) {
		$pageNum_RSEdarat = $_GET['pageNum_RSEdarat'];
	}
	$startRow_RSEdarat = $pageNum_RSEdarat * $maxRows_RSEdarat;
	if ($_SESSION['user_group'] != 'admin') {
		$group_sql = 'and user_group="edarh"';
	}
	mysqli_select_db($localhost, $database_localhost);
	$query_RSEdarat = sprintf("SELECT id,username,password,arabic_name,o_hide,hidden,o_sex,`user_group` FROM view_0_users where id>0 %s %s ORDER BY hidden,user_group,sex,arabic_name ASC", $group_sql, $sql_sex);
//echo $query_RSEdarat ;
	$query_limit_RSEdarat = sprintf("%s LIMIT %d,%d", $query_RSEdarat, $startRow_RSEdarat, $maxRows_RSEdarat);

	$RSEdarat = mysqli_query($localhost, $query_limit_RSEdarat) or die('$query_RSEdarat 1' . mysqli_error($localhost));
	$row_RSEdarat = mysqli_fetch_assoc($RSEdarat);
	if (isset($_GET['totalRows_RSEdarat'])) {
		$totalRows_RSEdarat = $_GET['totalRows_RSEdarat'];
	} else {
		$all_RSEdarat = mysqli_query($localhost, $query_RSEdarat) or die('$query_RSEdarat 2' . mysqli_error($localhost));
		$totalRows_RSEdarat = mysqli_num_rows($all_RSEdarat);
	}
	$totalPages_RSEdarat = ceil($totalRows_RSEdarat / $maxRows_RSEdarat) - 1;

	?>
	<?php include('../templates/header1.php'); ?>
	<title><?php echo $PageTitle; ?></title>
	</head>
	<body>
	<?php include('../templates/header2.php'); ?>
	<?php include('../templates/nav_menu.php'); ?>
	<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->

	<div class="content lp">
		<div class="FieldsTitle">بيانات المستخدم</div>
		<form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">
			<div class="four columns alpha">
				<div class="LabelContainer">
					<label for="username">اسم المستخدم</label>
				</div>
				<input type="text" name="username" value="" data-required="true">
			</div>
			<div class="four columns">
				<div class="LabelContainer">
					<label for="password">كلمة المرور</label>
				</div>
				<input type="text" name="password" value="" data-required="true">
			</div>
			<div class="four columns omega">
				<div class="LabelContainer">
					<label for="user_group">المجموعة</label>
				</div>
				<?php $ug = count($user_groups); ?>
				<select name="user_group" data-required="true" class="full-width">
					<?php for ($i1 = 0; $i1 < $ug; $i1++) { ?>
						<option value="<?php echo $user_groups[$i1][0]; ?>"><?php echo $user_groups[$i1][1]; ?></option>
					<?php } ?>
				</select>
			</div>
			<br class="clear">
			<div class="four columns alpha">
				<div class="LabelContainer">
					<label for="arabic_name">الاسم الحقيقي للمستخدم</label>
				</div>
				<input type="text" name="arabic_name" value="" data-required="true">
			</div>
			<div class="four columns">
				<div class="LabelContainer">
					<label for="mobile_no">رقم الجوال</label>
				</div>
				<input type="tel" name="mobile_no" id="mobile_no" value="" data-type="digits" data-maxlength="10" data-minlength="10">
			</div>
			<div class="four columns">
				<div class="LabelContainer">
					<label for="sex">الجنس</label>
				</div>
				<div>
					<label for="Sex_0"><input type="radio" name="sex" id="Sex_0" value="1">
						بنين</label>
					&nbsp; &nbsp; | &nbsp; &nbsp;
					<label for="Sex_1"><input type="radio" name="sex" id="Sex_1" value="0" data-required="true">
						بنات</label>
				</div>
			</div>
			<br class="clear">
			<div class="four columns omega left">
				<input type="submit" class="button-primary" value="حفظ">
			</div>
			<input type="hidden" name="MM_insert" value="form1">
		</form>

	</div>
	<div class="content CSSTableGenerator">
		<table>
			<caption>المستخدمون والإدارات والدور السابقة</caption>
			<tr>
				<td>الاسم</td>
				<td>الجنس</td>
				<td>اسم المستخدم</td>
				<td>كلمة المرور</td>
				<td class="NoMobile">الصلاحيات</td>
				<td>تعديل</td>
			</tr>
			<?php do { ?>
				<tr <?php if ($row_RSEdarat["hidden"] == 1) { ?> class='hidenRow' <?php ;
				} ?>>
					<td><?php echo $row_RSEdarat['arabic_name']; ?></td>
					<td><?php echo $row_RSEdarat['o_sex']; ?></td>
					<td><?php echo $row_RSEdarat['username']; ?></td>
					<td><?php echo $row_RSEdarat['password']; ?></td>
					<td class="NoMobile"><?php echo get_array_1($user_groups, $row_RSEdarat['user_group']); ?></td>
					<td><a href="edarah_edit.php?id=<?php echo $row_RSEdarat['id']; ?>">تعديل</a></td>
				</tr>
			<?php } while ($row_RSEdarat = mysqli_fetch_assoc($RSEdarat)); ?>
		</table>
		<br/>
		<div class="button-group">

			<?php if ($pageNum_RSEdarat > 0) { // Show if not first page ?>
				<a title="الصفحة الأولى" class="button-primary" href="<?php printf("%s?pageNum_RSEdarat=%d%s", $currentPage, 0, $queryString_RSEdarat); ?>" tabindex="-1"> << </a>
			<?php } else { // Show if not first page ?>
				<a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
			<?php } ?>

			<?php if ($pageNum_RSEdarat > 0) { // Show if not first page ?>
				<a title="السابق" class="button-primary" href="<?php printf("%s?pageNum_RSEdarat=%d%s", $currentPage, max(0, $pageNum_RSEdarat - 1), $queryString_RSEdarat); ?>" tabindex="-1"> < </a>
			<?php } else { // Show if not first page ?>
				<a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
			<?php } // Show if not first page ?>

			<?php if ($pageNum_RSEdarat < $totalPages_RSEdarat) { // Show if not last page ?>
				<a title="التالي" class="button-primary" href="<?php printf("%s?pageNum_RSEdarat=%d%s", $currentPage, min($totalPages_RSEdarat, $pageNum_RSEdarat + 1), $queryString_RSEdarat); ?>" tabindex="-1"> > </a>
			<?php } else { // Show if not first page ?>
				<a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
			<?php } // Show if not last page ?>

			<?php if ($pageNum_RSEdarat < $totalPages_RSEdarat) { // Show if not last page ?>
				<a title="الصفحة الأخيرة" class="button-primary" href="<?php printf("%s?pageNum_RSEdarat=%d%s", $currentPage, $totalPages_RSEdarat, $queryString_RSEdarat); ?>" tabindex="-1"> >> </a>
			<?php } else { // Show if not first page ?>
				<a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
			<?php } // Show if not last page ?>
		</div>
		<br>
		السجلات <?php echo($startRow_RSEdarat + 1) ?> إلى <?php echo min($startRow_RSEdarat + $maxRows_RSEdarat, $totalRows_RSEdarat) ?> من <?php echo $totalRows_RSEdarat ?>
		<br/>

	</div><!--content-->
	<?php include('../templates/footer.php'); ?>
	<?php
	if (isset($_SESSION['u1'])) {
		?>
		<script>
			$(document).ready(function () {
				alertify.success("تمت الإضافة بنجاح");
			});
		</script>
		<?php
		$_SESSION['u1'] = NULL;
		unset($_SESSION['u1']);
	}
	?>
	<script type="text/javascript">
		showError();
	</script>

<?php } else {
	include('../templates/restrict_msg.php');
} ?>