<?php
require("../../functions.php");
$exam_no = "-1";
if (isset($_GET['ExamNo'])) {
    $exam_no = $_GET['ExamNo'];
}
$query_R = sprintf("SELECT * FROM view_er_ertiqaexams WHERE AutoNo = %s", GetSQLValueString($exam_no, "int"));
$R = mysqli_query($localhost, $query_R) or die(mysqli_error($localhost));
$row_R = mysqli_fetch_assoc($R);
$totalRows_R = mysqli_num_rows($R);
$sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 1;

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="sh_20_b.css">
    <title>وثيقة تخرج</title>
</head>

<body>

<div id="printButton">
    <input class="button-primary" type="button" value="طباعة" onclick="window.print()">
</div>
<?php
$female = '';
if ($sex == 0) {
    $female = 'ا';
}

$shahadah_type = '';
if ($row_R['ErtiqaID'] == 9) {
    $shahadah_type = ' وتجويده';
}
?>
<div class="container">
    <table>
        <tr>
            <td colspan="2">
                <p class="p1">وافقـــت جمعيـــة تحفيـــظ القـــرآن الكـــريـــم بمحـــافظـــة الـــزلفـــي على</p>
                <p class="p1"> منح <?php echo get_gender_label('st', 'ال'); ?>
                    <span class="p2">
			<?php echo $row_R['O_StudentName']; ?>
			</span>
                    <?php echo get_array_1($nationality, $row_R['nationality']); ?>
                    الجنسية
                    <?php if ($row_R['nationality'] == 1) { ?>
                        رقم سجله<?php echo $female; ?> المدني
                    <?php } else { ?>
                        رقم جوازه<?php echo $female; ?>
                    <?php } ?>
                    <span class="p2">
			<?php echo $row_R['StID']; ?>
		</span>
                </p>
                <p class="p1">
                    شهادة اجتياز حفظ القرآن الكريم
                    <?php echo $shahadah_type; ?>
                    للعام الدراسي
                    <span class="p2">
<?php echo getStudyingYearInfo($row_R['FinalExamDate'])->name; ?>
                        هـ
		</span>
                    بتقدير (
                    <span class="p2">
			<?php echo get_array_1($MarkName_Long, $row_R['MarkName_Short']); ?>
		</span>
                    ) وبنسبة
                    <span class="p2">
			<?php echo $row_R['FinalExamDegree']; ?> %
		</span>
                </p>
                <p class="p1">
                    والجمعية إذ تمنحه<?php echo $female; ?>
                    هذه الوثيقة توصيه<?php echo $female; ?>
                    بتقوى الله والعمل بكتابه والمداومة على تعلمه وتعليمه.
                </p>
                <p class="p2">
                    والله الموفق
                </p>
            </td>
        </tr>
        <tr class="footer">
            <td class="date">
                حررت هذه الوثيقة بتاريخ
                <?php echo getHijriDate()->formatted_date; ?>
                هـ
                <spn class="stamp">&nbsp; الختم</spn>
            </td>

            <td class="sign" style="padding-top: 60px;">
                رئيس الجمعية
                <br/>
                <br/>
                <?php echo $raeesJam3iah; ?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
