<?php require_once('../../Connections/localhost.php');
require("../../functions.php");
require_once '../../secure/functions.php';
sec_session_start();
$sex = $_SESSION['sex'];

$colname_Recordset1 = "-1";
if (isset($_GET['ExamNo'])) {
	$colname_Recordset1 = $_GET['ExamNo'];
}
mysqli_select_db($localhost, $database_localhost);
$query_Recordset1 = sprintf("SELECT ex.AutoNo,ex.O_StudentName,ex.O_TeacherName,ex.O_Edarah,ex.O_HName,ex.O_MurtaqaName,ex.FinalExamDate,ex.H_SolokGrade,ex.H_MwadabahGrade,ex.
Sora1Name,ex.Sora1Discount,ex.Sora2Name,ex.Sora2Discount,ex.Sora3Name,ex.Sora3Discount,ex.Sora4Name,ex.Sora4Discount,ex.Sora5Name,ex.Sora5Discount,ex.Ek_mwathbah,ex.Ek_slok,ex.MarkName_Short,ex.
MarkName_Long,ex.Money,ex.FinalExamDegree,
u1.arabic_name  MukhtaberTeacher,u2.arabic_name MukhtaberTeacher2
FROM view_er_ertiqaexams AS ex
LEFT JOIN 0_users u1 ON u1.id=ex.MukhtaberTeacher
LEFT JOIN 0_users u2 ON u2.id=ex.MukhtaberTeacher2
WHERE AutoNo = %s", GetSQLValueString($colname_Recordset1, "int"));

$Recordset1 = mysqli_query($localhost, $query_Recordset1) or die(mysqli_error($localhost));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<style>
		* {
			margin: 0px;
			padding: 0px;
		}

		@media print {
			#HPageWrapper {
				background: none !important;
			}

			#printButton {
				display: none !important;
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
			text-shadow: 0px 1px 0 rgba(0, 0, 0, 0.25);
			border: 1px solid #46b3d3;
			border-radius: 5px;
			cursor: pointer;
			box-shadow: inset 0 0 2px rgba(256, 256, 256, 0.75);
			-moz-box-shadow: inset 0 0 2px rgba(256, 256, 256, 0.75);
			-webkit-box-shadow: inset 0 0 2px rgba(256, 256, 256, 0.75);
			font-size: 18px;
		}

		.button-primary:hover {
			background: #2B7791;
			border: 1px solid rgba(256, 256, 256, 0.75);
			box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
			-moz-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
			-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
		}

		@font-face {
			font-family: 'al_jass_zq';
			src: url('../../_fonts/jasscoozq.eot');  /* IE9 Compat Modes */
			src: url('../../_fonts/jasscoozq.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */ url('../../_fonts/jasscoozq.woff') format('woff'), /* Modern Browsers */ url('../../_fonts/jasscoozq.ttf') format('truetype'), /* Safari,Android,iOS */ url('../../_fonts/jasscoozq.svg#al_jass_zqregular') format('svg'); /* Legacy iOS */
			font-weight: normal;
			font-style: normal;
		}

		body {
			direction: rtl;
			text-align: center;
			font-family: 'al_jass_zq';
			font-size: 20px;
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
			width: 29.5cm;
			height: 20.5cm;
			border: none;
			margin: 0px auto;
			padding: 0px;
			background: url(../../_images/Shahadah.jpg) no-repeat;
			background-size: 100%;
		}

		#containerDiv1 {
			width: 18.785cm;
			height: 3.44cm;
			position: absolute;
			left: 57px;
			top: 74px; /*border: 1px solid gray;*/
		}

		.FieldDiv {
			/*text-align: right;*/
			vertical-align: middle;
		}

		#containerDiv2 .FieldDiv {
			font-size: 120%;
			padding-top: 2px;
		}

		#apDiv1 {
			position: absolute;
			left: 205px;
			top: 19px;
			width: 381px;
			height: 29px;
			z-index: 1;
		}

		#apDiv2 {
			position: absolute;
			left: 375px;
			top: 51px;
			width: 262px;
			height: 30px;
			z-index: 1;
		}

		#apDiv3 {
			position: absolute;
			left: 22px;
			top: 50px;
			width: 263px;
			height: 28px;
			z-index: 1;
		}

		#apDiv4 {
			position: absolute;
			left: 376px;
			top: 86px;
			width: 260px;
			height: 28px;
			z-index: 1;
		}

		#apDiv5 {
			position: absolute;
			left: 20px;
			top: 87px;
			width: 264px;
			height: 28px;
			z-index: 1;
		}

		#apDiv6 {
			position: absolute;
			left: 14px;
			top: 17px;
			width: 126px;
			height: 265px;
			z-index: 1;
		}

		#apDiv7 {
			position: absolute;
			left: 429px;
			top: 19px;
			width: 133px;
			height: 180px;
			z-index: 1;
		}

		#apDiv8 {
			position: absolute;
			left: 293px;
			top: 244px;
			width: 127px;
			height: 34px;
			z-index: 1;
		}

		#apDiv9 {
			position: absolute;
			left: 421px;
			top: 4px;
			width: 93px;
			height: 28px;
			z-index: 1;
		}

		#apDiv10 {
			position: absolute;
			left: 422px;
			top: 43px;
			width: 93px;
			height: 28px;
			z-index: 1;
		}

		#apDiv11 {
			position: absolute;
			left: 254px;
			top: 6px;
			width: 52px;
			height: 28px;
			z-index: 1;
		}

		#apDiv12 {
			position: absolute;
			left: 157px;
			top: 16px;
			width: 131px;
			height: 223px;
			z-index: 1;
		}

		#containerDiv2 {
			width: 14.975cm;
			height: 7.699cm;
			position: absolute;
			left: 56px;
			top: 247px; /*border: 1px solid gray;*/
		}

		#apDiv13 {
			position: absolute;
			left: 256px;
			top: 45px;
			width: 51px;
			height: 31px;
			z-index: 1;
		}

		#apDiv14 {
			position: absolute;
			left: 111px;
			top: 6px;
			width: 58px;
			height: 31px;
			z-index: 1;
		}

		#apDiv15 {
			position: absolute;
			left: 114px;
			top: 44px;
			width: 54px;
			height: 31px;
			z-index: 1;
		}

		#apDiv16 {
			position: absolute;
			left: 6px;
			top: 675px;
			width: 222px;
			height: 25px;
			z-index: 1;
			text-align: right;
			font-size: 90%;
		}

		#apDiv17 {
			position: absolute;
			left: 6px;
			top: 41px;
			width: 102px;
			height: 32px;
			z-index: 1;
		}

		#apDiv18 {
			position: absolute;
			left: 156px;
			top: 16px;
			width: 132px;
			height: 226px;
			z-index: 1;
		}

		#apDiv19 {
			position: absolute;
			left: 356px;
			top: 94px;
			width: 276px;
			height: 28px;
			z-index: 1;
		}

		#apDiv20 {
			position: absolute;
			left: 64px;
			top: 536px;
			width: 615px;
			height: 78px;
			z-index: 1;
		}

		#apDiv21 {
			position: absolute;
			left: 299px;
			top: 251px;
			width: 126px;
			height: 34px;
			z-index: 1;
		}

		#apDiv22 {
			position: absolute;
			left: 421px;
			top: 4px;
			width: 93px;
			height: 28px;
			z-index: 1;
		}

		#apDiv23 {
			position: absolute;
			left: 422px;
			top: 43px;
			width: 93px;
			height: 28px;
			z-index: 1;
		}

		#apDiv24 {
			position: absolute;
			left: 422px;
			top: 43px;
			width: 93px;
			height: 28px;
			z-index: 1;
		}

		#apDiv25 {
			position: absolute;
			left: 256px;
			top: 45px;
			width: 51px;
			height: 31px;
			z-index: 1;
		}

		#apDiv26 {
			position: absolute;
			left: 256px;
			top: 45px;
			width: 51px;
			height: 31px;
			z-index: 1;
		}

		#apDiv27 {
			position: absolute;
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
			left: 647px;
			top: 617px;
			width: 20px;
			height: 18px;
			z-index: 1;
		}

		#apDiv30 {
			position: absolute;
			left: 616px;
			top: 617px;
			width: 18px;
			height: 22px;
			z-index: 1;
		}

		#apDiv31 {
			position: absolute;
			left: 589px;
			top: 617px;
			width: 21px;
			height: 18px;
			z-index: 2;
		}

		#apDiv32 {
			position: absolute;
			left: 114px;
			top: 44px;
			width: 54px;
			height: 31px;
			z-index: 1;
		}

		#apDiv33 {
			position: absolute;
			left: 69px;
			top: 691px;
			width: 172px;
			height: 78px;
			z-index: 1;
		}

		#apDiv34 {
			position: absolute;
			left: 471px;
			top: 677px;
			width: 228px;
			height: 26px;
			z-index: 1;
			font-size: 90%;
			text-align: right;
		}

		#apDiv36 {
			position: absolute;
			left: 355px;
			top: 648px;
			width: 126px;
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
	<input class="button-primary" type="button" value="طباعة" onclick="window.print()">
</div>

<div id="HPageWrapper">
	<div id="containerDiv1">
		<div class="FieldDiv" id="apDiv1"><?php echo $row_Recordset1['O_StudentName']; ?></div>
		<div class="FieldDiv" id="apDiv2"><?php echo $row_Recordset1['O_TeacherName']; ?></div>
		<div class="FieldDiv" id="apDiv3"><?php echo $row_Recordset1['O_Edarah']; ?></div>
		<div class="FieldDiv" id="apDiv4"><?php echo $row_Recordset1['O_MurtaqaName']; ?></div>
		<div class="FieldDiv" id="apDiv5"><?php echo $row_Recordset1['O_HName']; ?></div>
	</div>
	<div id="containerDiv2">
		<div class="FieldDiv" id="apDiv6">

			<?PHP
			$TotalDiscount = $row_Recordset1['Sora1Discount'] + $row_Recordset1['Sora2Discount'] + $row_Recordset1['Sora3Discount'] + $row_Recordset1['Sora4Discount'] + $row_Recordset1['Sora5Discount'];

			$TotalRremaining = (20 - $row_Recordset1['Sora1Discount']) + (20 - $row_Recordset1['Sora2Discount']) + (20 - $row_Recordset1['Sora3Discount']) + (20 - $row_Recordset1['Sora4Discount']) + (20 - $row_Recordset1['Sora5Discount']);
			?>

			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><?PHP echo 20 - $row_Recordset1['Sora1Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 20 - $row_Recordset1['Sora2Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 20 - $row_Recordset1['Sora3Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 20 - $row_Recordset1['Sora4Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo 20 - $row_Recordset1['Sora5Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo ceil($TotalRremaining); ?></td>
				</tr>
				<tr>
					<td><?php echo $row_Recordset1['MarkName_Short']; ?></td>
				</tr>
			</table>
		</div>

		<div class="FieldDiv" id="apDiv12">
			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><?php echo $row_Recordset1['Sora1Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_Recordset1['Sora2Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_Recordset1['Sora3Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_Recordset1['Sora4Discount']; ?></td>
				</tr>
				<tr>
					<td><?php echo $row_Recordset1['Sora5Discount']; ?></td>
				</tr>
				<tr>
					<td><?PHP echo $TotalDiscount; ?></td>
				</tr>
			</table>
		</div>
		<div id="apDiv7">
			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><?php echo $soraName[$row_Recordset1['Sora1Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_Recordset1['Sora2Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_Recordset1['Sora3Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_Recordset1['Sora4Name']]; ?></td>
				</tr>
				<tr>
					<td><?php echo $soraName[$row_Recordset1['Sora5Name']]; ?></td>
				</tr>
			</table>
		</div>
		<div id="apDiv8"><?php echo $row_Recordset1['MarkName_Long']; ?></div>

	</div>
	<div id="apDiv20">
		<div class="FieldDiv" id="apDiv9"><?php echo $row_Recordset1['H_SolokGrade']; ?></div>
		<div class="FieldDiv" id="apDiv10"><?php echo $row_Recordset1['H_MwadabahGrade']; ?></div>
		<div class="FieldDiv" id="apDiv11"><?php echo $row_Recordset1['Ek_slok']; ?></div>
		<div class="FieldDiv" id="apDiv13"><?php echo $row_Recordset1['Ek_mwathbah']; ?></div>
		<div class="FieldDiv"
			 id="apDiv14"><?php echo $row_Recordset1['Ek_slok'] + $row_Recordset1['H_SolokGrade']; ?></div>
		<div class="FieldDiv"
			 id="apDiv15"><?php echo $row_Recordset1['Ek_mwathbah'] + $row_Recordset1['H_MwadabahGrade']; ?></div>
		<div class="FieldDiv" id="apDiv17"><?php echo $row_Recordset1['Money']; ?></div>
	</div>
	<div id="apDiv36"><img src="../../_images/stamp.png" height="102"></div>
	<div id="apDiv16"><?php if ($sex == 0) {
			echo $mushrefErtiqa;
		} else {
			echo $mudeerTanfethee;
		} ?></div>
	<div class="dateDiv" id="apDiv29"><?php echo substr($row_Recordset1['FinalExamDate'], 6, 2); ?></div>
	<div class="dateDiv" id="apDiv30"><?php echo substr($row_Recordset1['FinalExamDate'], 4, 2); ?></div>
	<div class="dateDiv" id="apDiv31"><?php echo substr($row_Recordset1['FinalExamDate'], 2, 2); ?></div>
	<div id="apDiv33">
		<?php if ($sex == 0) { ?><img src="../../_images/sign4.png" height="50"><?php } else { ?><img
			src="../../_images/sign1.png" height="80"><?php } ?>

	</div>


	<div id="apDiv34"><?php echo $row_Recordset1['MukhtaberTeacher']; ?></div>
	<div id="apDiv_34"><?php echo $row_R['MukhtaberTeacher2']; ?></div>
</div>
</body>
</html>

