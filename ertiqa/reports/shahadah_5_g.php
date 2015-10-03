<?php
require_once('../../Connections/localhost.php');
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
	<link rel="stylesheet" type="text/css" href="sh_5_g.css">
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
	<div id="apDiv16"><?php echo $mushrefErtiqa; ?></div>
	<div class="dateDiv" id="apDiv29"><?php echo substr($row_Recordset1['FinalExamDate'], 6, 2); ?></div>
	<div class="dateDiv" id="apDiv30"><?php echo substr($row_Recordset1['FinalExamDate'], 4, 2); ?></div>
	<div class="dateDiv" id="apDiv31"><?php echo substr($row_Recordset1['FinalExamDate'], 2, 2); ?></div>
	<div id="apDiv33">
		<img src="../../_images/sign4.png" height="50">
	</div>


	<div id="apDiv34"><?php echo $row_Recordset1['MukhtaberTeacher']; ?></div>
	<div id="apDiv_34"><?php echo $row_Recordset1['MukhtaberTeacher2']; ?></div>
</div>
</body>
</html>