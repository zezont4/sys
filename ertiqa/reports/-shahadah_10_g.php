<?php require_once('../../Connections/localhost.php'); 
require("../../functions.php");
require_once '../../secure/functions.php';
sec_session_start();  
$sex=$_SESSION['sex'];
$colname_R = "-1";
if (isset($_GET['ExamNo'])) {
  $colname_R = $_GET['ExamNo'];
}
mysqli_select_db($localhost,$database_localhost);
$query_R = sprintf("SELECT AutoNo,O_StudentName,O_TeacherName,O_Edarah,O_HName,O_MurtaqaName,FinalExamDate,H_SolokGrade,H_MwadabahGrade,Sora1Name,Sora1Discount,Sora2Name,Sora2Discount,Sora3Name,Sora3Discount,Sora4Name,Sora4Discount,Sora5Name,Sora5Discount,Sora6Name,Sora6Discount,Sora7Name,Sora7Discount,Sora8Name,Sora8Discount,Sora9Name,Sora9Discount,Sora10Name,Sora10Discount,Ek_mwathbah,Ek_slok,MarkName_Short,MarkName_Long,Money,FinalExamDegree FROM view_er_ertiqaexams WHERE AutoNo = %s",GetSQLValueString($colname_R,"int"));
$R = mysqli_query($localhost,$query_R)or die(mysqli_error($localhost));
$row_R = mysqli_fetch_assoc($R);
$totalRows_R = mysqli_num_rows($R);

$ExamNo1_RsMktberTeacher = "-1";
if (isset($_GET['ExamNo'])) {
  $ExamNo1_RsMktberTeacher = $_GET['ExamNo'];
}
mysqli_select_db($localhost,$database_localhost);
$query_RsMktberTeacher = sprintf("SELECT 0_users.arabic_name FROM  0_users,er_shahadah WHERE er_shahadah.MukhtaberTeacher=0_users.id and er_shahadah.ExamNo=%s",GetSQLValueString($ExamNo1_RsMktberTeacher,"int"));
$RsMktberTeacher = mysqli_query($localhost,$query_RsMktberTeacher)or die(mysqli_error($localhost));
$row_RsMktberTeacher = mysqli_fetch_assoc($RsMktberTeacher);
$totalRows_RsMktberTeacher = mysqli_num_rows($RsMktberTeacher);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<style>
*{
	margin:0px;
	padding:0px;	
}
@media print {
#HPageWrapper{
	background: none!important;
}
#printButton{
	display: none!important;
}

}
#printButton {
	text-align: center;
	margin: 15px 0px;
	border-bottom: 2px dashed #2B9FBB;
	padding-bottom: 5px;
}
.button-primary {
	background-color: #2C9FBB;
	padding: 5px 20px;
	color: #fff;
	text-shadow: 0px 1px 0 rgba(0,0,0,0.25);
	border: 1px solid #46b3d3;
	border-radius: 5px;
	cursor: pointer;
	box-shadow: inset 0 0 2px rgba(256,256,256,0.75);
	-moz-box-shadow: inset 0 0 2px rgba(256,256,256,0.75);
	-webkit-box-shadow: inset 0 0 2px rgba(256,256,256,0.75);
	font-size: 18px;
}
.button-primary:hover {
	background: #2B7791;
	border: 1px solid rgba(256,256,256,0.75);
	box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);
	-moz-box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);
	-webkit-box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);
}

@font-face {
	font-family: 'al_jass_zq';
	src: url('../../_fonts/jasscoozq.eot');  /* IE9 Compat Modes */
	src: url('../../_fonts/jasscoozq.eot?#iefix') format('embedded-opentype'),/* IE6-IE8 */  
	url('../../_fonts/jasscoozq.woff') format('woff'),/* Modern Browsers */  
	url('../../_fonts/jasscoozq.ttf') format('truetype'),/* Safari,Android,iOS */  
	url('../../_fonts/jasscoozq.svg#al_jass_zqregular') format('svg'); /* Legacy iOS */
	font-weight: normal;
	font-style: normal;
}
body {
	direction: rtl;
	text-align: center;
	font-family: 'al_jass_zq';
	font-size: 18px;
	white-space: nowrap;
}
.dateDiv {
	font-size: 16px;
	text-align: center;
	vertical-align: top;
}
#HPageWrapper {
	z-index: 0;
	position: relative;
	height: 29cm;
	width: 20.5cm;
	border: none;
	margin: 0px auto;
	padding: 0px;
	background: url(../../_images/Shahadah_10.jpg) no-repeat;
	background-size: 21cm 29.5cm;
}
#containerDiv1 {
	width: 18.785cm;
	height: 3.44cm;
	position: absolute;
	left: -3px;
	top: 280px;	/*border: 1px solid gray;*/
	text-align: right;
	font-size: 95%;
}

.FieldDiv {
	/*text-align: right;*/
	vertical-align: middle;
}
#containerDiv2 .FieldDiv{
	font-size:120%;	
	padding-top:2px;
}
#apDiv1 {
	position: absolute;
	left: 197px;
	top: 33px;
	width: 257px;
	height: 29px;
	z-index: 1;
}
#apDiv2 {
	position: absolute;
	left: 202px;
	top: 65px;
	width: 251px;
	height: 30px;
	z-index: 1;
}
#apDiv3 {
	position: absolute;
	left: 9px;
	top: 64px;
	width: 124px;
	height: 28px;
	z-index: 1;
}
#apDiv4 {
	position: absolute;
	left: 8px;
	top: 33px;
	width: 124px;
	height: 28px;
	z-index: 1;
}
#apDiv5 {
	position: absolute;
	left: 7px;
	top: 97px;
	width: 127px;
	height: 28px;
	z-index: 1;
}
#apDiv6 {
	position: absolute;
	left: 5px;
	top: 56px;
	width: 177px;
	height: 411px;
	z-index: 1;
	bottom: -1px;
}
#apDiv7 {
	position: absolute;
	left: 343px;
	top: 60px;
	width: 100px;
	height: 345px;
	z-index: 1;
	bottom: -1px;
}
#apDiv8 {
	position: absolute;
	left: 240px;
	top: 441px;
	width: 102px;
	height: 34px;
	z-index: 1;
	font-size: 16px;
	bottom: -1px;
}
#apDiv9 {
	position: absolute;
	left: 328px;
	top: -1px;
	width: 74px;
	height: 28px;
	z-index: 1;
}
#apDiv10 {
	position: absolute;
	left: 328px;
	top: 37px;
	width: 74px;
	height: 28px;
	z-index: 1;
}
#apDiv11 {
	position: absolute;
	left: 188px;
	top: -2px;
	width: 74px;
	height: 28px;
	z-index: 1;
}
#apDiv12 {
	position: absolute;
	left: 114px;
	top: 57px;
	width: 165px;
	height: 381px;
	z-index: 1;
	bottom: -1px;
}
#containerDiv2 {
	width: 14.896cm;
	height: 15.584cm;
	position: absolute;
	left: -3px;
	top: 392px;	/*border: 1px solid gray;*/
}
#apDiv13 {
	position: absolute;
	left: 188px;
	top: 36px;
	width: 74px;
	height: 31px;
	z-index: 1;
}
#apDiv14 {
	position: absolute;
	left: 86px;
	top: -2px;
	width: 74px;
	height: 31px;
	z-index: 1;
}
#apDiv15 {
	position: absolute;
	left: 86px;
	top: 36px;
	width: 74px;
	height: 31px;
	z-index: 1;
}
#apDiv16 {
	position: absolute;
	left: 1px;
	top: 987px;
	width: 193px;
	height: 25px;
	z-index: 1;
	text-align: right;
	font-size: 90%;
}
#apDiv17 {
	position: absolute;
	left: 27px;
	top: 35px;
	width: 87px;
	height: 32px;
	z-index: 1;
}
#apDiv18 {	position: absolute;
	left: 156px;
	top: 16px;
	width: 132px;
	height: 226px;
	z-index: 1;
}
#apDiv19 {	position: absolute;
	left: 356px;
	top: 94px;
	width: 276px;
	height: 28px;
	z-index: 1;
}
#apDiv20 {
	position: absolute;
	left: 13px;
	top: 870px;
	width: 580px;
	height: 78px;
	z-index: 1;
}
#apDiv21 {	position: absolute;
	left: 299px;
	top: 251px;
	width: 126px;
	height: 34px;
	z-index: 1;
}
#apDiv22 {	position: absolute;
	left: 421px;
	top: 4px;
	width: 93px;
	height: 28px;
	z-index: 1;
}
#apDiv23 {	position: absolute;
	left: 422px;
	top: 43px;
	width: 93px;
	height: 28px;
	z-index: 1;
}
#apDiv24 {	position: absolute;
	left: 422px;
	top: 43px;
	width: 93px;
	height: 28px;
	z-index: 1;
}
#apDiv25 {	position: absolute;
	left: 256px;
	top: 45px;
	width: 51px;
	height: 31px;
	z-index: 1;
}
#apDiv26 {	position: absolute;
	left: 256px;
	top: 45px;
	width: 51px;
	height: 31px;
	z-index: 1;
}
#apDiv27 {	position: absolute;
	left: 114px;
	top: 44px;
	width: 54px;
	height: 31px;
	z-index: 1;
}
#apDiv28 {
	position: absolute;
	left: 864px;
	top: 624px;
	width: 18px;
	height: 18px;
	z-index: 1;
}
#apDiv29 {
	position: absolute;
	left: 58px;
	top: 1044px;
	width: 20px;
	height: 18px;
	z-index: 1;
}
#apDiv30 {
	position: absolute;
	left: 33px;
	top: 1044px;
	width: 18px;
	height: 18px;
	z-index: 1;
}
#apDiv31 {
	position: absolute;
	left: 11px;
	top: 1044px;
	width: 21px;
	height: 18px;
	z-index: 2;
}
#apDiv32 {	position: absolute;
	left: 114px;
	top: 44px;
	width: 54px;
	height: 31px;
	z-index: 1;
}
#apDiv33 {
	position: absolute;
	left: 60px;
	top: 1004px;
	width: 73px;
	height: 49px;
	z-index: 1;
}
#apDiv34 {
	position: absolute;
	left: 257px;
	top: 987px;
	width: 236px;
	height: 26px;
	z-index: 1;
	font-size: 90%;
	text-align: right;
}
#apDiv35 {
	position: absolute;
	left: 248px;
	top: 409px;
	width: 91px;
	height: 31px;
	z-index: 1;
	bottom: -1px;
}
#apDiv36 {
	position: absolute;
	left: 214px;
	top: 970px;
	width: 127px;
	height: 103px;
	z-index: 1;
}
#apDiv36 {
}
</style>
<title>شهادة نجاح</title>
</head>

<body>

<div id="printButton">
<input class="button-primary"  type="button" value="طباعة" onclick="window.print()">
</div>

<div id="HPageWrapper">
	<div id="containerDiv1">
		<div class="FieldDiv" id="apDiv1"><?php echo $row_R['O_StudentName']; ?></div>
		<div class="FieldDiv" id="apDiv2"><?php echo $row_R['O_TeacherName']; ?></div>
		<div class="FieldDiv" id="apDiv3"><?php echo $row_R['O_Edarah']; ?></div>
		<div class="FieldDiv" id="apDiv4"><?php echo $row_R['O_MurtaqaName']; ?></div>
		<div class="FieldDiv" id="apDiv5"><?php echo $row_R['O_HName']; ?></div>
	</div>
	<div id="containerDiv2">
		<div class="FieldDiv" id="apDiv6">

		<?PHP
		 	$TotalDiscount = $row_R['Sora1Discount'] + $row_R['Sora2Discount'] + $row_R['Sora3Discount'] + $row_R['Sora4Discount'] + $row_R['Sora5Discount']
						   + $row_R['Sora6Discount'] + $row_R['Sora7Discount'] + $row_R['Sora8Discount'] + $row_R['Sora9Discount'] + $row_R['Sora10Discount']; 
		 	
			$TotalRremaining = (10 - $row_R['Sora1Discount']) + (10 - $row_R['Sora2Discount']) + (10 - $row_R['Sora3Discount']) + (10 - $row_R['Sora4Discount']) + (10 - $row_R['Sora5Discount'])
							 + (10 - $row_R['Sora6Discount']) + (10 - $row_R['Sora7Discount']) + (10 - $row_R['Sora8Discount']) + (10 - $row_R['Sora9Discount']) + (10 - $row_R['Sora10Discount']); 
		 ?>
			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><?PHP echo 10 - $row_R['Sora1Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora2Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora3Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora4Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora5Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora6Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora7Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora8Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora9Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 10 - $row_R['Sora10Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP  echo ceil($TotalRremaining); ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['MarkName_Short']; ?></td>
				</tr>
			</table>
		</div>

		<div class="FieldDiv" id="apDiv12">
			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><?php echo $row_R['Sora1Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora2Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora3Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora4Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora5Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora6Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora7Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora8Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora9Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_R['Sora10Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo $TotalDiscount; ?></td>
				</tr>
			</table>
		</div>
		<div id="apDiv7">
			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><?php echo $soraName[$row_R['Sora1Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora2Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora3Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora4Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora5Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora6Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora7Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora8Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora9Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_R['Sora10Name']]; ?></td>
				</tr>
			</table>
		</div>
		<div id="apDiv35">100</div>
		<div id="apDiv8"><?php echo $row_R['MarkName_Long']; ?></div>

	</div>
<div id="apDiv20">
	<div class="FieldDiv" id="apDiv9"><?php echo $row_R['H_SolokGrade']; ?></div>
	<div class="FieldDiv" id="apDiv10"><?php echo $row_R['H_MwadabahGrade']; ?></div>
	<div class="FieldDiv" id="apDiv11"><?php echo $row_R['Ek_slok']; ?></div>
	<div class="FieldDiv" id="apDiv13"><?php echo $row_R['Ek_mwathbah']; ?></div>
	<div class="FieldDiv" id="apDiv14"><?php echo $row_R['Ek_slok'] + $row_R['H_SolokGrade']; ?></div>
	<div class="FieldDiv" id="apDiv15"><?php echo $row_R['Ek_mwathbah'] + $row_R['H_MwadabahGrade']; ?></div>
	<div class="FieldDiv" id="apDiv17"><?php echo $row_R['Money']; ?></div>
</div>
<div id="apDiv36"><img src="../../_images/stamp.png" width="112" height="93"></div>
<div id="apDiv16"><?php if ($sex==0){echo $mudeerEshraf;}else{echo $mudeerTanfethee;} ?></div>
<div class="dateDiv" id="apDiv29"><?php //echo substr($row_R['FinalExamDate'],6,2); ?></div>
<div class="dateDiv" id="apDiv30"><?php //echo substr($row_R['FinalExamDate'],4,2); ?></div>
<div class="dateDiv" id="apDiv31"><?php //echo substr($row_R['FinalExamDate'],2,2); ?></div>
<div id="apDiv33">
<?php if ($sex==0){?><img src="../../_images/sign3.png" width="93" height="52"><?php }else{?><img src="../../_images/sign1.png" width="93" height="52"><?php } ?>

</div>



<div id="apDiv34"><?php echo $row_RsMktberTeacher['arabic_name']; ?></div>
</div>
</body>
</html>
<?php
mysqli_free_result($R);

mysqli_free_result($RsMktberTeacher);
?>
