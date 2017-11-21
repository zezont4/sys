<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php
$EdarahIDS = $_SESSION['user_id'];
?>
<?php
$max_rows = 100;
$page_no = 0;
if (isset($_GET['page_no'])) {
	$page_no = $_GET['page_no'];
}
$start_row = $page_no * $max_rows;

$name1 = "";
if (isset($_POST['e_name1'])) {
	$name1 = ($_POST['e_name1'] != "") ? "and name1 LIKE'%" . $_POST['e_name1'] . "%'" : ' ';
}
$name2 = "";
if (isset($_POST['e_name2'])) {
	$name2 = ($_POST['e_name2'] != "") ? "and name2 LIKE'%" . $_POST['e_name2'] . "%'" : ' ';
}
$name3 = "";
if (isset($_POST['e_name3'])) {
	$name3 = ($_POST['e_name3'] != "") ? "and name3 LIKE'%" . $_POST['e_name3'] . "%'" : ' ';
}
$name4 = "";
if (isset($_POST['e_name4'])) {
	$name4 = ($_POST['e_name4'] != "") ? "and name4 LIKE'%" . $_POST['e_name4'] . "%'" : ' ';
}
mysqli_select_db($localhost, $database_localhost);
$sql_sex = sql_sex('e_sex');
if ($_SESSION['user_group'] != 'edarh') {
	$query = sprintf("SELECT * FROM view_0_employees WHERE name1<>'11' %s %s  %s  %s  %s ORDER BY `is_hidden`,arabic_name,full_name",
		$sql_sex,
		$name1,
		$name2,
		$name3,
		$name4);
} else {
	$query = sprintf("SELECT * FROM view_0_employees WHERE edarah_id = %s %s %s %s %s ORDER BY `is_hidden`,arabic_name,full_name",
		GetSQLValueString($_SESSION['user_id'], "int"),
		$name1,
		$name2,
		$name3,
		$name4
	);
}

$query_limit = sprintf("%s LIMIT %d,%d", $query, $start_row, $max_rows);

$rs = mysqli_query($localhost, $query_limit) or die(mysqli_error($localhost));
$row = mysqli_fetch_assoc($rs);
if (isset($_GET['total_rows'])) {
	$total_rows = $_GET['total_rows'];
} else {
	$all_Rs_T = mysqli_query($localhost, $query);
	$total_rows = mysqli_num_rows($all_Rs_T);
}
$totalPages_Rs_T = ceil($total_rows / $max_rows - 1);
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'نتيجة البحث عن ' . get_gender_label('emp', ''); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<div class="content lp CSSTableGenerator">
	<?php if (login_check($all_groups) == true) { ?>
		<table border="1" cellpadding="0" cellspacing="0">
			<tr>
				<td>السجل المدني</td>
				<td>الاسم</td>
				<td><?php echo get_gender_label('e', 'ال'); ?></td>
				<td>مسمى الوظيفة</td>
				<td>الجوال</td>
				<td>تاريخ المباشرة</td>
				<td>المؤهل العلمي</td>
				<td>الحالة</td>
				<td>تعديل</td>
			</tr>
			<?php do { ?>
				<tr <?php if ($row["is_hidden"] == 1) { ?> class='hidenRow' <?php ;
				} ?>>
					<td><?php echo $row['national_id']; ?></td>
					<td><?php echo $row['full_name']; ?></td>
					<td><?php echo $row['arabic_name']; ?></td>
					<td><?php echo get_array_1($job, $row['job_title']); ?></td>
					<td><?php echo $row['mobile_no']; ?></td>
					<td><?php echo $row['o_start_date']; ?></td>
					<td><?php echo get_array_1($qualification, $row['qualification']); ?></td>
					<?php if ($row["is_hidden"] == 1) { ?>
						<td><a href="emp_show.php?id=<?php echo $row['id']; ?>"><?php echo $row['o_hide']; ?></a></td>
					<?php } else { ?>
						<td><a href="emp_hide.php?id=<?php echo $row['id']; ?>"><?php echo $row['o_hide']; ?></a></td>
					<?php } ?>
					<td><a href="emp_edit.php?id=<?php echo $row['id']; ?>">تعديل</a></td>
				</tr>
			<?php } while ($row = mysqli_fetch_assoc($rs)); ?>
		</table>

		<br/>
		<center>
			<div class="button-group">
				<?php if ($page_no > 0) { // Show if not first page ?>
					<a title="الصفحة الأولى" class="button-primary" href="<?php printf("%s?page_no=%d%s", $currentPage, 0, $queryString_Rs_T); ?>" tabindex="-1"> << </a>
				<?php } else { // Show if not first page ?>
					<a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
				<?php } ?>
				<?php if ($page_no > 0) { // Show if not first page ?>
					<a title="السابق" class="button-primary" href="<?php printf("%s?page_no=%d%s", $currentPage, max(0, $page_no - 1), $queryString_Rs_T); ?>" tabindex="-1"> < </a>
				<?php } else { // Show if not first page ?>
					<a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
				<?php } // Show if not first page ?>
				<?php if ($page_no < $totalPages_Rs_T) { // Show if not last page ?>
					<a title="التالي" class="button-primary" href="<?php printf("%s?page_no=%d%s", $currentPage, min($totalPages_Rs_T, $page_no + 1), $queryString_Rs_T); ?>" tabindex="-1"> > </a>
				<?php } else { // Show if not first page ?>
					<a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
				<?php } // Show if not last page ?>
				<?php if ($page_no < $totalPages_Rs_T) { // Show if not last page ?>
					<a title="الصفحة الأخيرة" class="button-primary" href="<?php printf("%s?page_no=%d%s", $currentPage, $totalPages_Rs_T, $queryString_Rs_T); ?>" tabindex="-1"> >> </a>
				<?php } else { // Show if not first page ?>
					<a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
				<?php } // Show if not last page ?>
			</div>
			<br>
			السجلات <?php echo($start_row + 1) ?> إلى <?php echo min($start_row + $max_rows, $total_rows) ?> من <?php echo $total_rows ?>
		</center>
	<?php } else {
		echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
	} ?>
</div>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
mysqli_free_result($rs);
?>
