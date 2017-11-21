<?php require_once('../../functions.php');

$colname_R = "-1";
if (isset($_GET['ExamNo'])) {
    $colname_R = $_GET['ExamNo'];
}
$query_R = sprintf("SELECT AutoNo,O_StudentName,O_TeacherName,O_Edarah,O_HName,O_MurtaqaName,FinalExamDate,H_SolokGrade,H_MwadabahGrade,Sora1Name,Sora1Discount,Sora2Name,Sora2Discount,Sora3Name,Sora3Discount,Sora4Name,Sora4Discount,Sora5Name,Sora5Discount,Sora6Name,Sora6Discount,Sora7Name,Sora7Discount,Sora8Name,Sora8Discount,Sora9Name,Sora9Discount,Sora10Name,Sora10Discount,Ek_mwathbah,Ek_slok,MarkName_Short,MarkName_Long,Money,FinalExamDegree FROM view_er_ertiqaexams WHERE AutoNo = %s", GetSQLValueString($colname_R, "int"));
$R = mysqli_query($localhost, $query_R) or die(mysqli_error($localhost));
$row_R = mysqli_fetch_assoc($R);
$totalRows_R = mysqli_num_rows($R);

$ExamNo1_RsMktberTeacher = "-1";
if (isset($_GET['ExamNo'])) {
    $ExamNo1_RsMktberTeacher = $_GET['ExamNo'];
}
$query_RsMktberTeacher = sprintf("SELECT 0_users.arabic_name,0_users.id FROM  0_users,er_shahadah WHERE er_shahadah.MukhtaberTeacher=0_users.id AND er_shahadah.ExamNo=%s", GetSQLValueString($ExamNo1_RsMktberTeacher, "int"));
$RsMktberTeacher = mysqli_query($localhost, $query_RsMktberTeacher) or die(mysqli_error($localhost));
$row_RsMktberTeacher = mysqli_fetch_assoc($RsMktberTeacher);
$totalRows_RsMktberTeacher = mysqli_num_rows($RsMktberTeacher);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="sh_10_b.css">
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
        <div class="FieldDiv" id="apDiv5"><?php echo $row_R['O_HName']; ?></div>
        <div class="FieldDiv" id="apDiv3"><?php echo $row_R['O_Edarah']; ?></div>
        <div class="FieldDiv" id="apDiv4"><?php echo $row_R['O_MurtaqaName']; ?></div>
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
            </table>
        </div>
        <div id="apDiv7">
            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><?php echo $row_R['Sora1Name'] ? $soraName[$row_R['Sora1Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora2Name'] ? $soraName[$row_R['Sora2Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora3Name'] ? $soraName[$row_R['Sora3Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora4Name'] ? $soraName[$row_R['Sora4Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora5Name'] ? $soraName[$row_R['Sora5Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora6Name'] ? $soraName[$row_R['Sora6Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora7Name'] ? $soraName[$row_R['Sora7Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora8Name'] ? $soraName[$row_R['Sora8Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora9Name'] ? $soraName[$row_R['Sora9Name']] : ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $row_R['Sora10Name'] ? $soraName[$row_R['Sora10Name']] : ''; ?></td>
                </tr>
            </table>
        </div>

    </div>
    <div id="apDiv20">
        <div id="apDiv8b"><?php echo $TotalDiscount; ?></div>
        <div id="apDiv8c"><?php echo ceil($TotalRremaining); ?></div>
        <div id="apDiv8"><?php echo $row_R['MarkName_Long']; ?></div>
        <div id="apDiv8a"><?php echo $row_R['MarkName_Short']; ?></div>
        <div class="FieldDiv" id="apDiv9"><?php echo $row_R['H_SolokGrade']; ?></div>
        <div class="FieldDiv" id="apDiv10"><?php echo $row_R['H_MwadabahGrade']; ?></div>
        <div class="FieldDiv" id="apDiv11"><?php echo $row_R['Ek_slok']; ?></div>
        <div class="FieldDiv" id="apDiv13"><?php echo $row_R['Ek_mwathbah']; ?></div>
        <div class="FieldDiv" id="apDiv14"><?php echo $row_R['Ek_slok'] + $row_R['H_SolokGrade']; ?></div>
        <div class="FieldDiv" id="apDiv15"><?php echo $row_R['Ek_mwathbah'] + $row_R['H_MwadabahGrade']; ?></div>
        <div class="FieldDiv" id="apDiv17"><?php echo $row_R['Money']; ?></div>
    </div>
    <div id="apDiv34"><?php echo $row_RsMktberTeacher['arabic_name']; ?></div>
    <!--	توقيع المعلم المختبر-->
    <?php
    $image_file = "../../_images/mktbr_sign_{$row_RsMktberTeacher['id']}.png";
    $mktbr_id = $row_RsMktberTeacher['id'];
    if (file_exists($image_file)) { ?>
        <div id="apDiv34_sign">
            <img src="../../_images/mktbr_sign_<?php echo $mktbr_id; ?>.png" height="40">
        </div>
    <?php } ?>
    <div id="apDiv16"><?php echo $mudeerTanfethee; ?></div>
    <div id="apDiv33"><img src="../../_images/sign1.png" width="130" height="62"></div>

    <div id="apDiv36"><img src="../../_images/stamp.png" height="100"></div>

    <div class="dateDiv" id="apDiv29"><?php echo substr($row_R['FinalExamDate'], 6, 2); ?></div>
    <div class="dateDiv" id="apDiv30"><?php echo substr($row_R['FinalExamDate'], 4, 2); ?></div>
    <div class="dateDiv" id="apDiv31"><?php echo substr($row_R['FinalExamDate'], 2, 2); ?></div>


</div>
</body>
</html>

