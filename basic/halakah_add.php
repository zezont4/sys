<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$insertSQL = sprintf("INSERT INTO 0_halakat (HName,EdarahID) VALUES (%s,%s)",
                       GetSQLValueString($_POST['HName'],"text"),
                       GetSQLValueString($_POST['EdarahID'],"int"));

  mysqli_select_db($localhost,$database_localhost);
  $Result1 = mysqli_query($localhost,$insertSQL)or die(mysqli_error($localhost));
	if ($Result1){ 
		$_SESSION['u1']="u1";
		header("Location: " . $_SERVER['PHP_SELF']);
		exit;
	}
}

$maxRows_RSHalaqat = 20;
$pageNum_RSHalaqat = 0;
if (isset($_GET['pageNum_RSHalaqat'])) {
  $pageNum_RSHalaqat = $_GET['pageNum_RSHalaqat'];
}
$startRow_RSHalaqat = $pageNum_RSHalaqat * $maxRows_RSHalaqat;

mysqli_select_db($localhost,$database_localhost);
if ($_SESSION['user_group']!='edarh'){
	$query_RSHalaqat = sprintf("SELECT AutoNo,HName,arabic_name,o_hide,`hide` FROM view_0_halakat WHERE EdarahID > 0 %s order by `hide`,arabic_name,HName",$sql_sex);
}else{
	$query_RSHalaqat = sprintf("SELECT AutoNo,HName,arabic_name,o_hide,`hide` FROM view_0_halakat WHERE EdarahID = %s %s order by `hide`,arabic_name,HName",$_SESSION['user_id'],$sql_sex);
}
$query_limit_RSHalaqat = sprintf("%s LIMIT %d,%d",$query_RSHalaqat,$startRow_RSHalaqat,$maxRows_RSHalaqat);

$RSHalaqat = mysqli_query($localhost,$query_limit_RSHalaqat)or die('query_RSHalaqat '.mysqli_error($localhost));
$row_RSHalaqat = mysqli_fetch_assoc($RSHalaqat);
if (isset($_GET['totalRows_RSHalaqat'])) {
  $totalRows_RSHalaqat = $_GET['totalRows_RSHalaqat'];
} else {
  $all_RSHalaqat = mysqli_query($localhost,$query_RSHalaqat);
  $totalRows_RSHalaqat = mysqli_num_rows($all_RSHalaqat);
}
$totalPages_RSHalaqat = ceil($totalRows_RSHalaqat/$maxRows_RSHalaqat)-1;

/*mysqli_select_db($localhost,$database_localhost);
$query_RSEdarat = sprintf("SELECT id,arabic_name FROM `0_users` WHERE `hidden`=0 %s and user_group='edarh' ORDER BY Sex,arabic_name ASC",$sql_sex);
//echo $query_RSEdarat;
$RSEdarat = mysqli_query($localhost,$query_RSEdarat)or die(mysqli_error($localhost));
$row_RSEdarat = mysqli_fetch_assoc($RSEdarat);
$totalRows_RSEdarat = mysqli_num_rows($RSEdarat);*/

?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'الحلقات'; ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
		<?php include ('../templates/nav_menu.php'); ?>
		<div id="PageTitle"><?php echo $PageTitle; ?></div>
		<!--PageTitle-->
		<div class="content lp">
	<?php if(login_check("admin,edarh,t3lem") == true) { ?>
			<div class="FieldsTitle">بيانات الحلقة</div>
			<form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">
				<div class="four columns alpha">
					<div class="LabelContainer">
						<label for="EdarahID"><?php echo get_gender_label('e','ال'); ?></label>
					</div>
					<?php create_edarah_combo();?>
					
				</div>
				<div class="four columns">
					<div class="LabelContainer">
						<label for="HName">اسم الحلقة</label>
					</div>
					<input type="text" name="HName" id="HName"  data-required="true">
				</div>
				<div class="four columns omega left">
					<input name="submit" type="submit" class="button-primary" id="submit" value="إضافة">
				</div>
				<input type="hidden" name="MM_insert" value="form1">
			</form>
			</div>
			<div class="content CSSTableGenerator">
				<table>
				<caption>الحلقات المسجلة</caption>
					<tr>
						<td>اسم الحلقة</td>
						<td><?php echo get_gender_label('e','ال'); ?></td>
						<td>تعديل</td>
						<td>الحالة</td>
					</tr>
					<?php do { ?>
						<tr <?php if ($row_RSHalaqat["hide"]==1){ ?> class='hidenRow' <?php ;} ?>>
							<td><?php echo $row_RSHalaqat['HName']; ?></td>
							<td><?php echo $row_RSHalaqat['arabic_name']; ?></td>
							<td><a href="halakah_edit.php?AutoNo=<?php echo $row_RSHalaqat['AutoNo']; ?>">تعديل</a></td>
							<?php if ($row_RSHalaqat["hide"]==1){ ?>
							<td><a href="halakah_show.php?AutoNo=<?php echo $row_RSHalaqat['AutoNo']; ?>"><?php echo $row_RSHalaqat['o_hide']; ?></a></td>
							<?php } else { ?>
							<td><a href="halakah_hide.php?AutoNo=<?php echo $row_RSHalaqat['AutoNo']; ?>"><?php echo $row_RSHalaqat['o_hide']; ?></a></td>
							<?php } ?>
						</tr>
						<?php } while ($row_RSHalaqat = mysqli_fetch_assoc($RSHalaqat)); ?>
				</table>
			
			<br />
			<center>
				<div class="button-group">
					<?php if ($pageNum_RSHalaqat > 0) { // Show if not first page ?>
					<a  title="الصفحة الأولى" class="button-primary" href="<?php printf("%s?pageNum_RSHalaqat=%d%s",$currentPage,0,$queryString_RSHalaqat); ?>" tabindex="-1"> << </a>
					<?php }else{ // Show if not first page ?>
					<a  title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
					<?php } ?>
					<?php if ($pageNum_RSHalaqat > 0) { // Show if not first page ?>
						<a title="السابق" class="button-primary" href="<?php printf("%s?pageNum_RSHalaqat=%d%s",$currentPage,max(0,$pageNum_RSHalaqat - 1),$queryString_RSHalaqat); ?>" tabindex="-1"> < </a>
						<?php }else{ // Show if not first page ?>
						<a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
						<?php } // Show if not first page ?>
					<?php if ($pageNum_RSHalaqat < $totalPages_RSHalaqat) { // Show if not last page ?>
						<a title="التالي" class="button-primary" href="<?php printf("%s?pageNum_RSHalaqat=%d%s",$currentPage,min($totalPages_RSHalaqat,$pageNum_RSHalaqat + 1),$queryString_RSHalaqat); ?>" tabindex="-1"> > </a>
						<?php }else{ // Show if not first page ?>
						<a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
						<?php } // Show if not last page ?>
					<?php if ($pageNum_RSHalaqat < $totalPages_RSHalaqat) { // Show if not last page ?>
						<a title="الصفحة الأخيرة" class="button-primary" href="<?php printf("%s?pageNum_RSHalaqat=%d%s",$currentPage,$totalPages_RSHalaqat,$queryString_RSHalaqat); ?>" tabindex="-1"> >> </a>
						<?php }else{ // Show if not first page ?>
						<a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
						<?php } // Show if not last page ?>
				</div>
				<br>
				السجلات <?php echo ($startRow_RSHalaqat + 1) ?> إلى <?php echo min($startRow_RSHalaqat + $maxRows_RSHalaqat,$totalRows_RSHalaqat) ?> من <?php echo $totalRows_RSHalaqat ?>
			</center>
			<br />
			<?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>

	</div>
	<!--content-->
	<?php include('../templates/footer.php'); ?>
		<?php
if (isset($_SESSION['u1'])){
		?>
			<script>
    $(document).ready(function() {
		$('#toggleCSS').href = "../CSS/alertify/alertify.default.css";
			alertify.set({
				delay : 5000,
			});
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
<?php
mysqli_free_result($RSHalaqat);
?>