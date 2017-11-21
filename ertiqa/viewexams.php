<?php
require_once('../functions.php');
$EdarahIDS = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
//$EdarahIDS = $_SESSION['user_id'];

$maxRows_ReExams = 20;
$pageNum_ReExams = 0;
$currentPage = $_SERVER["PHP_SELF"];
if (isset($_GET['pageNum_ReExams'])) {
    $pageNum_ReExams = $_GET['pageNum_ReExams'];
}
$startRow_ReExams = $pageNum_ReExams * $maxRows_ReExams;
$user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : '';
$sql_sex = sql_sex('edarah_sex');
if ($user_group != 'edarh') {
    $query_ReExams = sprintf("SELECT O_StudentName3,O_TeacherName3,O_FinalExamDate,O_Edarah,O_HName,O_MurtaqaName,AutoNo,FinalExamStatus,MarkName_Long
FROM view_er_ertiqaexams
where AutoNo>0 %s
ORDER BY AutoNo DESC",
        $sql_sex);
} else {
    $query_ReExams = sprintf("SELECT O_StudentName3,O_TeacherName3,O_FinalExamDate,O_Edarah,O_HName,O_MurtaqaName,AutoNo,FinalExamStatus,MarkName_Long
FROM view_er_ertiqaexams
WHERE EdarahID = %s
ORDER BY AutoNo DESC",
        GetSQLValueString($_SESSION['user_id'], "int"));
}
//echo $query_ReExams;
//exit;
$query_limit_ReExams = sprintf("%s LIMIT %d,%d", $query_ReExams, $startRow_ReExams, $maxRows_ReExams);
$ReExams = mysqli_query($localhost, $query_limit_ReExams) or die(mysqli_error($localhost));
$row_ReExams = mysqli_fetch_assoc($ReExams);

if (isset($_GET['totalRows_ReExams'])) {
    $totalRows_ReExams = $_GET['totalRows_ReExams'];
} else {
    $all_ReExams = mysqli_query($localhost, $query_ReExams);
    $totalRows_ReExams = mysqli_num_rows($all_ReExams);
}
$totalPages_ReExams = ceil($totalRows_ReExams / $maxRows_ReExams) - 1;
?>

<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'عرض الاختبارات والحجوزات'; ?>
    <title><?php echo $PageTitle; ?></title>
    <style>
        .hday {
            font-size: 12px
        }
    </style>
    </head>
    <body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->
    <div class="content CSSTableGenerator">
        <?php if (login_check("admin,edarh") == true) { ?>
            <table>
                <caption>مواعيد ونتائج <?php echo get_gender_label('sts', 'ال'); ?> في اختبارات المرتقيات</caption>
                <tr>
                    <td>اسم <?php echo get_gender_label('st', 'ال'); ?></td>
                    <td class="NoMobile">اسم <?php echo get_gender_label('t', 'ال'); ?></td>
                    <td class="NoMobile">الحلقة</td>
                    <td class="NoMobile"><?php echo get_gender_label('e', 'ال'); ?></td>
                    <td>المرتقى</td>
                    <td>التاريخ</td>
                    <td class="NoMobile">الحالة</td>
                    <td>التقدير</td>
                </tr>
                <?php
                $ii = 0;
                do { ?>
                    <tr class="Status_<?php echo $row_ReExams['FinalExamStatus']; ?>">
                        <td><?php echo $row_ReExams['O_StudentName3']; ?></td>
                        <td class="NoMobile"><?php echo $row_ReExams['O_TeacherName3']; ?></td>
                        <td class="NoMobile"><?php echo $row_ReExams['O_HName']; ?></td>
                        <td class="NoMobile"><?php echo str_replace("مجمع ", "", $row_ReExams['O_Edarah']); ?></td>
                        <td><?php echo $row_ReExams['O_MurtaqaName']; ?></td>
                        <td><span class="hday" id="hday<?php echo $ii; ?>"><?php if ($row_ReExams['O_FinalExamDate'] != null) {
                                    echo '<img height="12px" src="/sys/_images/loader.gif" alt="انتظر...">';
                                }; ?></span> <span id="hdate<?php echo $ii; ?>"><?php echo $row_ReExams['O_FinalExamDate']; ?></span></td>
                        <?php /*echo $row_ReExams['O_FinalExamDate'];*/ ?>
                        <td class="NoMobile"><a
                                    href="reports/esh3ar.php?AutoNo=<?php echo $row_ReExams['AutoNo']; ?>"><?php echo get_array_1($statusName, $row_ReExams['FinalExamStatus']); ?></a>
                        </td>
                        <td><?php echo $row_ReExams['MarkName_Long']; ?></td>
                    </tr>
                    <?php
                    $ii++;
                } while ($row_ReExams = mysqli_fetch_assoc($ReExams)); ?>
            </table>
            <script>
                $(function () {
                    var fullDate = '';
                    var spliteddate = new Array();
                    var day_no = '';
                    for (var y = 0; y < 20; y++) {
                        fullDate = $('#hdate' + y).html();
                        spliteddate = fullDate.split('/');
                        //var d1 = calendar.newDate(parseInt(spliteddate[0],10),parseInt(spliteddate[1],10),parseInt(spliteddate[2],10));
                        yyyy = parseInt(spliteddate[0], 10);
                        mm = ((parseInt(spliteddate[1], 10) < 10) ? '0' + parseInt(spliteddate[1], 10) : parseInt(spliteddate[1], 10));
                        dd = ((parseInt(spliteddate[2], 10) < 10) ? '0' + parseInt(spliteddate[2], 10) : parseInt(spliteddate[2], 10));
                        yyyymmdd = String(yyyy) + String(mm) + String(dd);
                        day_no = (yyyymmdd > 0) ? weekeay_name[get_g_date(yyyymmdd, 'yes')] : '';
                        //var hdate = calendar.formatDate ('yyyy/mm/dd',d1);
                        //$('#hdate'+yy).text(hdate);
                        $('#hday' + y).html(day_no);
                    }
                });
            </script>
            <br>

            <center>
                <div class="button-group">

                    <?php if ($pageNum_ReExams > 0) { // Show if not first page ?>
                        <a title="الصفحة الأولى" class="button-primary" href="<?php printf("%s?pageNum_ReExams=%d", $currentPage, 0); ?>" tabindex="-1"> << </a>
                    <?php } else { // Show if not first page ?>
                        <a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
                    <?php } ?>

                    <?php if ($pageNum_ReExams > 0) { // Show if not first page ?>
                        <a title="السابق" class="button-primary" href="<?php printf("%s?pageNum_ReExams=%d", $currentPage, max(0, $pageNum_ReExams - 1)); ?>" tabindex="-1"> < </a>
                    <?php } else { // Show if not first page ?>
                        <a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
                    <?php } // Show if not first page ?>

                    <?php if ($pageNum_ReExams < $totalPages_ReExams) { // Show if not last page ?>
                        <a title="التالي" class="button-primary" href="<?php printf("%s?pageNum_ReExams=%d", $currentPage, min($totalPages_ReExams, $pageNum_ReExams + 1)); ?>"
                           tabindex="-1"> > </a>
                    <?php } else { // Show if not first page ?>
                        <a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
                    <?php } // Show if not last page ?>

                    <?php if ($pageNum_ReExams < $totalPages_ReExams) { // Show if not last page ?>
                        <a title="الصفحة الأخيرة" class="button-primary" href="<?php printf("%s?pageNum_ReExams=%d", $currentPage, $totalPages_ReExams); ?>" tabindex="-1"> >> </a>
                    <?php } else { // Show if not first page ?>
                        <a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
                    <?php } // Show if not last page ?>
                </div>
                <br>
                السجلات <?php echo($startRow_ReExams + 1) ?> إلى <?php echo min($startRow_ReExams + $maxRows_ReExams, $totalRows_ReExams) ?> من <?php echo $totalRows_ReExams ?>
            </center>
            <br/>

        <?php } else {
            echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
        } ?>
    </div><!--content-->
<?php include('../templates/footer.php'); ?>
<?php
mysqli_free_result($ReExams);
?>