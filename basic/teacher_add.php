<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php
	$EdarahIDS=$_SESSION['user_id'];
?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	$colname_RsIfExistT = "-1";
	if (isset($_POST['TID'])) {
	  $colname_RsIfExistT = $_POST['TID'];
	}
	mysqli_select_db($localhost,$database_localhost);
	$query_RsIfExistT = sprintf("SELECT TID,Tfullname FROM view_0_teachers WHERE TID = %s",GetSQLValueString($colname_RsIfExistT,"double"));
	$RsIfExistT = mysqli_query($localhost,$query_RsIfExistT)or die(mysqli_error($localhost));
	$row_RsIfExistT = mysqli_fetch_assoc($RsIfExistT);
	$totalRows_RsIfExistT = mysqli_num_rows($RsIfExistT);
	if ($totalRows_RsIfExistT>0){
		?>
		<?php include('../templates/header1.php'); ?>
        <br><br><br>
		<div style="direction:rtl;text-align:center;font-size:22px;">
<br />
<br />
<br />
<h1 dir="rtl" align="center">السجل المدني(<?php echo $row_RsIfExistT['TID'];?>) موجود سابقا,وهو تابع للمعلم :<?php echo $row_RsIfExistT['Tfullname'];?></h1>
<h2 dir="rtl" align="center">قد يكون تابع لمجمع آخر ,لذا يرجى الاتصال بقسم الارتقاء ليقوم بنقل المعلم إلى مجمعكم.</h2>
</div>
</html>
<?php
        exit;
	}


  $insertSQL = sprintf("INSERT INTO 0_teachers (TID,TName1,TName2,TName3,TName4,TMobileNo,TEdarah,THalaqah)
  						 VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($_POST['TID'],"double"),
                       GetSQLValueString($_POST['TName1'],"text"),
                       GetSQLValueString($_POST['TName2'],"text"),
                       GetSQLValueString($_POST['TName3'],"text"),
                       GetSQLValueString($_POST['TName4'],"text"),
                       GetSQLValueString($_POST['TMobileNo'],"text"),
                       GetSQLValueString($_POST['EdarahID'],"int"),
                       GetSQLValueString($_POST['HalaqatID'],"int"));


  mysqli_select_db($localhost,$database_localhost);
  $Result1 = mysqli_query($localhost,$insertSQL)or die(mysqli_error($localhost));
  if ($Result1){ 
		$_SESSION['u1']="u1";
    }
	mysqli_free_result($RsIfExistT);

}
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'إضافة '. get_gender_label('t',''); ?>
<title><?php echo $PageTitle; ?></title>
<style type="text/css">
form table {
	border: 1px solid #333;
}
form table tr th {
	/*background-color: #999;*/
	text-align: center;
	vertical-align: middle;
	border: 1px solid rgb(189,189,189);
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
<?php include ('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
  <div class="content">
	<?php if(login_check("admin,edarh,t3lem") == true) { ?>
	<form method="post" id="form1" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">
		<div class="four columns alpha">
			<div class="LabelContainer">
				<label for="EdarahID"><?php echo get_gender_label('e','ال'); ?></label>
			</div>
			<?php create_edarah_combo();?>
		</div>
		<div class="four columns">
			<div class="LabelContainer">
				<label for="HalaqatID">الحلقة</label>
			</div>
			<select name="HalaqatID" class="FullWidthCombo" id="HalaqatID" data-required="true">
				<option VALUE>--</option>
			</select>
		</div>
		<div class="four columns omega">
			<div class="LabelContainer">
				<label for="TID">السجل المدني</label>
			</div>
			<input name="TID" type="text" id="TID" data-required="true" data-maxlength="10" data-minlength="10" data-type="digits">
		</div>
		<br class="clear" />
		<div class="four columns alpha">
			<div class="LabelContainer">
				<label for="TName1">الاسم الأول</label>
			</div>
			<input type="text" name="TName1" id="TName1" data-required="true">
		</div>
		<div class="four columns">
			<div class="LabelContainer">
				<label for="TName2">اسم الأب</label>
			</div>
			<input type="text" name="TName2" id="TName2" data-required="true">
		</div>
		<div class="four columns">
			<div class="LabelContainer">
				<label for="TName3">اسم الجد</label>
			</div>
			<input type="text" name="TName3" id="TName3" data-required="true">
		</div>
		<div class="four columns omega">
			<div class="LabelContainer">
				<label for="TName4">اسم العائلة</label>
			</div>
			<input type="text" name="TName4" id="TName4" data-required="true">
		</div>
		<br class="clear" />
		<div class="four columns alpha">
			<div class="LabelContainer">
				<label for="TMobileNo">رقم جوال <?php echo get_gender_label('t','ال'); ?></label>
			</div>
			<input type="tel" name="TMobileNo" id="TMobileNo" data-required="true" data-type="digits" data-maxlength="10" data-minlength="10">
		</div>
		<br class="clear" />
		<div class="four columns omega left">
			<input name="submit" type="submit" class="button-primary" id="submit" value="إضافة">
		</div>
		<input type="hidden" name="MM_insert" value="form1">
	</form>
	<div class="CSSTableGenerator"> 
	<script type="text/javascript">
	$(document).ready(function() {
		$('#EdarahID').change(function() {
			ClearList('HalaqatID');
			FillList(this.value,'HalaqatID','AutoNo','HName','SELECT * FROM 0_halakat WHERE `hide`=0 and EdarahID = %s ORDER BY HName','لا يوجد حلقات','اختر الحلقة');
		});
		$('#EdarahID').trigger("change");
	});
	</script>
<?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>
</div>
		<!--content-->
		<?php include('../templates/footer.php'); ?>
<?php
if (isset($_SESSION['u1'])){
		?>
<script>
	$(document).ready(function() {
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