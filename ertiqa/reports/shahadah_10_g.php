<?php
require_once('../../Connections/localhost.php');
$colname_R = "-1";
if (isset($_GET['ExamNo'])) {
    $colname_R = $_GET['ExamNo'];
}
mysqli_select_db($localhost, $database_localhost);
$query_R = sprintf("SELECT ex.AutoNo,ex.O_StudentName,ex.O_TeacherName,ex.O_Edarah,ex.O_HName,ex.O_MurtaqaName,ex.FinalExamDate,ex.H_SolokGrade,ex.H_MwadabahGrade,ex.
Sora1Name,ex.Sora1Discount,ex.Sora2Name,ex.Sora2Discount,ex.Sora3Name,ex.Sora3Discount,ex.Sora4Name,ex.Sora4Discount,ex.Sora5Name,ex.Sora5Discount,ex.Sora6Name,ex.
Sora6Discount,ex.Sora7Name,ex.Sora7Discount,ex.Sora8Name,ex.Sora8Discount,ex.Sora9Name,ex.Sora9Discount,ex.Sora10Name,ex.Sora10Discount,ex.Ek_mwathbah,ex.Ek_slok,ex.MarkName_Short,ex.
MarkName_Long,ex.Money,ex.FinalExamDegree,
u1.arabic_name  MukhtaberTeacher,u2.arabic_name MukhtaberTeacher2
FROM view_er_ertiqaexams as ex
LEFT JOIN 0_users u1 on u1.id=ex.MukhtaberTeacher
LEFT JOIN 0_users u2 on u2.id=ex.MukhtaberTeacher2
WHERE AutoNo = %s", GetSQLValueString($colname_R, "int"));
$R = mysqli_query($localhost, $query_R) or die(mysqli_error($localhost));
$row_R = mysqli_fetch_assoc($R);
$totalRows_R = mysqli_num_rows($R);

$ExamNo1_RsMktberTeacher = "-1";
if (isset($_GET['ExamNo'])) {
    $ExamNo1_RsMktberTeacher = $_GET['ExamNo'];
}
?>
<?php require("../../functions.php"); ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="sh_10_g.css">
    <title>شهادة نجاح</title>
</head>

<body>

<div id="printButton">
    <input class="button-primary" type="button" value="طباعة" onclick="window.print()">
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
                    <td class="TotalRremaining"><?PHP echo ceil($TotalRremaining); ?></td>
                </tr>
                <tr>
                    <td class="MarkName_Short"><?php echo $row_R['MarkName_Short']; ?></td>
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
                    <td class="TotalDiscount"><?PHP echo $TotalDiscount; ?></td>
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
    <div id="apDiv36"><img src="../../_images/stamp.png" height="102"></div>
    <div id="apDiv16"><?php echo $mudeerEshraf; ?></div>
    <div class="dateDiv" id="apDiv29"><?php echo substr($row_R['FinalExamDate'],6,2); ?></div>
    <div class="dateDiv" id="apDiv30"><?php echo substr($row_R['FinalExamDate'],4,2); ?></div>
    <div class="dateDiv" id="apDiv31"><?php echo substr($row_R['FinalExamDate'],2,2); ?></div>
    <div id="apDiv33">
        <img src="../../_images/sign3.png" width="130" height="62">
    </div>

    <div id="apDiv34"><?php echo $row_R['MukhtaberTeacher']; ?></div>
    <div id="apDiv_34"><?php echo $row_R['MukhtaberTeacher2']; ?></div>
</div>
</body>
</html>
