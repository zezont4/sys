<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php $PageTitle = 'تفاصيل الاختبار'; ?>
<?php if(login_check("admin,er") == true) { ?>
<?php
	$EdarahIDS=$_SESSION['user_id'];
?>
<?php
$Result1 ="";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE er_ertiqaexams set ErtiqaID=%s,H_SolokGrade=%s,H_MwadabahGrade=%s,TestExamDate=%s,TestExamDegree=%s  WHERE AutoNo=%s",
                       GetSQLValueString($_POST['MurtaqaID'],"int"),
                       GetSQLValueString($_POST['H_SolokGrade'],"int"),
                       GetSQLValueString($_POST['H_MwadabahGrade'],"int"),
                       GetSQLValueString(str_replace('/','',$_POST['TestExamDate']),"int"),
                       GetSQLValueString($_POST['TestExamDegree'],"int"),
                       GetSQLValueString($_GET['AutoNo'],"int"));

	//echo $updateSQL;
	//exit;
  mysqli_select_db($localhost,$database_localhost);
  $Result1 = mysqli_query($localhost,$updateSQL)or die('$updateSQL '.mysqli_error($localhost));
}

	if ($Result1){ 
	$insertGoTo = "addexamdate.php";
	if (isset($_SERVER['QUERY_STRING'])) {
	$insertGoTo .= (strpos($insertGoTo,'?')) ? "&" : "?";
	$insertGoTo .= $_SERVER['QUERY_STRING'];
  }
		$_SESSION['u1']="u1";
		header(sprintf("Location: %s",$insertGoTo));
		exit;
}

    
	
?>
<?php
if (isset($_SESSION['user_id'])) {
  $colname_RSEdarat = $_SESSION['user_id'];
}
$colname_RsExams = "-1";
if (isset($_GET['AutoNo'])) {
  $colname_RsExams = $_GET['AutoNo'];
}
mysqli_select_db($localhost,$database_localhost);
$query_RsExams = sprintf("SELECT O_StudentName,O_TeacherName,O_TestExamDate,O_FinalExamDate,O_Edarah,O_HName,O_MurtaqaName,AutoNo,ErtiqaID,HalakahID,StID,TeacherID,EdarahID,TestExamDegree,H_SolokGrade,H_MwadabahGrade,FinalExamDegree FROM view_er_ertiqaexams WHERE AutoNo = %s",GetSQLValueString($colname_RsExams,"int"));
$RsExams = mysqli_query($localhost,$query_RsExams)or die('$query_RsExams '.mysqli_error($localhost));
$row_RsExams = mysqli_fetch_assoc($RsExams);
$totalRows_RsExams = mysqli_num_rows($RsExams);
?>
<?php include('../templates/header1.php'); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include ('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->
	<div class="content lp CSSTableGenerator">
			<table>
				<caption>بيانات <?php echo get_gender_label('st','ال'); ?> الأساسية  (وقت التسجيل بالمرتقى)</caption>
					<tr>
					<td>اسم <?php echo get_gender_label('st','ال'); ?></td>
					<td>المجمع</td>
					<td>الحلقة</td>
					<td>المعلم</td>
				</tr>
					<tr>
					<td><?php echo $row_RsExams['O_StudentName'];?></td>
					<td><?php echo $row_RsExams['O_Edarah'];?></td>
					<td><?php echo $row_RsExams['O_HName'];?></td>
					<td><?php echo $row_RsExams['O_TeacherName'];?></td>
				</tr>
			</table>
	</div>
	<div class="content">

    <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1" data-validate="parsley">
    
    <div class="FieldsTitle">الاختبار التجريبي</div>
    
    <div class="four columns alpha">
      <div class="LabelContainer"><label for="MurtaqaID">المرتقى</label></div>
	  <?php echo create_combo("MurtaqaID",$murtaqaName,0,$row_RsExams['ErtiqaID'],'data-required="true"'); ?>
    </div>

    <div class="four columns">
      <div class="LabelContainer"><label for="H_SolokGrade">درجة السلوك</label></div>
      <input class="EnableDisable"  name="H_SolokGrade" id="H_SolokGrade" type="tel" value="<?php echo $row_RsExams['H_SolokGrade']; ?>"  data-required="true" data-type="digits" data-max="20" autocomplete="off">
       </div>
    <div class="four columns">
      <div class="LabelContainer"><label for="H_MwadabahGrade">درجة المواظبة</label></div>
      <input class="EnableDisable"  name="H_MwadabahGrade" id="H_MwadabahGrade" type="tel" value="<?php echo $row_RsExams['H_MwadabahGrade']; ?>"  data-required="true"  data-type="digits" data-max="20" autocomplete="off">
      </div>
    <div class="four columns omega">
      <div class="LabelContainer"><label for="TestExamDegree">درجة الاختبار التجريبي</label></div>
      <input class="EnableDisable" name="TestExamDegree" id="TestExamDegree" type="tel" value="<?php echo $row_RsExams['TestExamDegree']; ?>"  data-required="true"  data-type="digits" data-range="[85,100]" onChange="validateForm('form1',this.name,'TestExamResault')" autocomplete="off">
    </div>
	<br class="clear">
    <div class="four columns alpha">
      <div class="LabelContainer"><label for="TestExamResault">التقدير</label></div>
      <input class="EnableDisable" name="TestExamResault" id="TestExamResault" type="text" value="" readonly  tabindex="-1"/>
    </div>
    <div class="four columns">
      <div class="LabelContainer"><label for="TestExamDate">تاريخ الاختبار التجريبي</label></div>
      <input name="TestExamDate" type="text" class="EnableDisable" id="TestExamDate" value="<?php echo $row_RsExams['O_TestExamDate']; ?>" zezo_date="true">
</div>

    <div class="four columns omega left">
      <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
	</div>
	<br class="clear">
	<div class="four columns omega left">
     <a href="delexam.php?AutoNo=<?php echo $row_RsExams['AutoNo']; ?>" onclick="return confirm('هل تريد حذف بيانات الاختبار الحالي؟')">حذف الموعد الحالي</a>
	</div>
    <input type="hidden" name="MM_update" value="form1">
 
    </form>


<?php
if (isset($_SESSION['u1'])){
		?>
    <script>
    	$(document).ready(function() {
   		alertify.success("تم تحديث البيانات بنجاح");
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

<script  type="text/javascript">

	//تغيير لون المحدد

	$(document).ready(function() {

		//الحالة
		$('#FinalExamDegree').change(function(){
			changeStatus();
		});
		$('#FinalExamDate').change(function(){
			changeStatus();
		});
		
		$("input[checked=checked]").parent().addClass('RadioSelected');
	});
</script>

<script>
$(document).ready(function() {
	$('#TestExamResault').val(GetMarkName($('#TestExamDegree').val(),'long'));
	
	$('#TestExamDegree').change(function(){
		$('#TestExamResault').val(GetMarkName($('#TestExamDegree').val(),'long'));
	});
	
});
</script>	
    
</div><!--content-->
<?php include('../templates/footer.php'); ?>
<?php }else{include('../templates/restrict_msg.php');}?>