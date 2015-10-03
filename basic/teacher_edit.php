<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE `0_teachers` SET TName1=%s,TName2=%s,TName3=%s,TName4=%s,TMobileNo=%s,TEdarah=%s,THalaqah=%s WHERE TID=%s",
                       GetSQLValueString($_POST['TName1'],"text"),
                       GetSQLValueString($_POST['TName2'],"text"),
                       GetSQLValueString($_POST['TName3'],"text"),
                       GetSQLValueString($_POST['TName4'],"text"),
                       GetSQLValueString($_POST['TMobileNo'],"text"),
					   GetSQLValueString($_POST['EdarahID'],"int"),
                       GetSQLValueString($_POST['THalaqah'],"int"),
                       GetSQLValueString($_POST['TID'],"double"));

  mysqli_select_db($localhost,$database_localhost);
  $Result1 = mysqli_query($localhost,$updateSQL)or die(mysqli_error($localhost));
  if ($Result1){ 
		$_SESSION['u1']="u1";
		header("Location: ".$editFormAction);
		exit;
     }
/*  $updateGoTo = "teacher_add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo,'?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s",$updateGoTo));*/
}

mysqli_select_db($localhost,$database_localhost);

$colname_RsTeachers = "-1";
if (isset($_GET['TID'])) {
  $colname_RsTeachers = $_GET['TID'];
}
mysqli_select_db($localhost,$database_localhost);
$query_RsTeachers = sprintf("SELECT TID,TName1,TName2,TName3,TName4,TMobileNo, THalaqah,TEdarah FROM 0_teachers WHERE TID = %s",GetSQLValueString($colname_RsTeachers,"double"));
$RsTeachers = mysqli_query($localhost,$query_RsTeachers)or die(mysqli_error($localhost));
$row_RsTeachers = mysqli_fetch_assoc($RsTeachers);
$totalRows_RsTeachers = mysqli_num_rows($RsTeachers);
$EdarahNo_RsHalakat = "-1";
if (isset($_GET['EdarahNo'])) {
  $EdarahNo_RsHalakat = $_GET['EdarahNo'];
}
mysqli_select_db($localhost,$database_localhost);
$query_RsHalakat = sprintf("SELECT AutoNo,HName FROM `0_halakat` WHERE `hide`=0 and EdarahID = %s",GetSQLValueString($EdarahNo_RsHalakat,"int"));
$RsHalakat = mysqli_query($localhost,$query_RsHalakat)or die(mysqli_error($localhost));
$row_RsHalakat = mysqli_fetch_assoc($RsHalakat);
$totalRows_RsHalakat = mysqli_num_rows($RsHalakat);

?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'تعديل معلم'; ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
		<?php include ('../templates/nav_menu.php'); ?>
		<div id="PageTitle"><?php echo $PageTitle; ?></div>
		<!--PageTitle-->
  <div class="content lp">
	<?php if(login_check("admin,edarh,t3lem") == true) { ?>
			<form method="post" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">
				<div class="four columns alpha">
					<div class="LabelContainer">
						<label for="TName1">الاسم</label>
					</div>
					<input type="text" name="TName1" id="TName1" value="<?php echo htmlentities($row_RsTeachers['TName1'],ENT_COMPAT,'UTF-8'); ?>" data-required="true">
				</div>
				<div class="four columns">
					<div class="LabelContainer">
						<label for="TName2">الأب</label>
					</div>
					<input type="text" name="TName2" id="TName2" value="<?php echo htmlentities($row_RsTeachers['TName2'],ENT_COMPAT,'UTF-8'); ?>" data-required="true">
				</div>
				<div class="four columns">
					<div class="LabelContainer">
						<label for="TName3">الجد</label>
					</div>
					<input type="text" name="TName3" id="TName3" value="<?php echo htmlentities($row_RsTeachers['TName3'],ENT_COMPAT,'UTF-8'); ?>" data-required="true">
				</div>
				<div class="four columns omega">
					<div class="LabelContainer">
						<label for="TName4">العائلة</label>
					</div>
					<input type="text" name="TName4" id="TName4" value="<?php echo htmlentities($row_RsTeachers['TName4'],ENT_COMPAT,'UTF-8'); ?>" data-required="true">
				</div>
				<br class="clear" />
				<div class="four columns alpha">
					<div class="LabelContainer">
						<label for="TEdarah">المجمع</label>
					</div>
					<?php create_edarah_combo($row_RsTeachers['TEdarah']);?>
				</div>
				<div class="four columns omega">
					<div class="LabelContainer">
						<label for="THalaqah">الحلقة</label>
					</div>
					<select name="THalaqah" id="THalaqah" class="FullWidthCombo" data-required="true">
						<?php 
do {  
?>
						<option value="<?php echo $row_RsHalakat['AutoNo']?>" <?php if (!(strcmp($row_RsHalakat['AutoNo'],htmlentities($row_RsTeachers['THalaqah'],ENT_COMPAT,'UTF-8')))) {echo "SELECTED";} ?>><?php echo $row_RsHalakat['HName']?></option>
						<?php
} while ($row_RsHalakat = mysqli_fetch_assoc($RsHalakat));
?>
					</select>
				</div>
				<br class="clear" />
				<div class="four columns alpha">
					<div class="LabelContainer">
						<label for="TMobileNo">رقم جوال المعلم</label>
					</div>
					<input type="tel" name="TMobileNo" id="TMobileNo" value="<?php echo $row_RsTeachers['TMobileNo']; ?>" data-type="digits" data-maxlength="10" data-minlength="10" data-required="true">
				</div>
				<div class="four columns omega">
					<div class="LabelContainer">
						<label for="TID">السجل المدني</label>
					</div>
					<input type="text" name="TID" id="TID" value="<?php echo htmlentities($row_RsTeachers['TID'],ENT_COMPAT,'UTF-8'); ?>" size="32" disabled="disabled">
				</div>
				<br class="clear" />
				<div class="four columns omega left">
					<input type="submit" class="button-primary" value="حفظ التعديلات">
				</div>
				<input type="hidden" name="MM_update" value="form1">
				<input type="hidden" name="TID" value="<?php echo $row_RsTeachers['TID']; ?>">
			</form>
			<?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>

		</div>
		<!--content-->
		<?php include('../templates/footer.php'); ?>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#EdarahID').change(function() {
			ClearList('THalaqah');
			FillList(this.value,'THalaqah','AutoNo','HName','SELECT * FROM 0_halakat WHERE `hide`=0 and EdarahID = %s ORDER BY HName','لا يوجد حلقات','اختر الحلقة');
		});
		//$('#EdarahID').trigger("change");
	});
	</script>
<?php
if (isset($_SESSION['u1'])){
		?>
<script>
    $(document).ready(function() {
		alertify.success("تمت التعديل بنجاح");
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
mysqli_free_result($RsTeachers);
mysqli_free_result($RsHalakat);
?>
