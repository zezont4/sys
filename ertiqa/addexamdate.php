<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php $PageTitle = 'تحديد موعد الاختبار'; ?>
<?php if (login_check("admin,er,mktbr") == true) { ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

    $i = 0;
    foreach ($_POST['AutoNo'] as $AutoNo) {
        if ($_POST["FinalExamDate$i"] !== '') {
            if ($_POST["FinalExamStatus$i"] == '0') {
                $updateSQL = sprintf("UPDATE er_ertiqaexams SET FinalExamDate=%s,FinalExamStatus=1 WHERE AutoNo=%s",
                    GetSQLValueString(str_replace('/', '', $_POST["FinalExamDate$i"]), "int"),
                    GetSQLValueString($AutoNo, "int"));
                //echo $updateSQL;
                //exit;
            } else {
                $updateSQL = sprintf("UPDATE er_ertiqaexams SET FinalExamDate=%s WHERE AutoNo=%s",
                    GetSQLValueString(str_replace('/', '', $_POST["FinalExamDate$i"]), "int"),
                    GetSQLValueString($AutoNo, "int"));
            }
        } else {
            $updateSQL = sprintf("UPDATE er_ertiqaexams SET FinalExamDate=NULL,FinalExamStatus=0 WHERE AutoNo=%s",
                GetSQLValueString($AutoNo, "int"));
        }
        mysqli_select_db($localhost, $database_localhost);
        $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
        ++ $i;
    }
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        //header("Location: " . $editFormAction);
        //exit;
    }

}
?>
<?php

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ReExams = 40;
$pageNum_ReExams = 0;
if (isset($_GET['pageNum_ReExams'])) {
    $pageNum_ReExams = $_GET['pageNum_ReExams'];
}
$startRow_ReExams = $pageNum_ReExams * $maxRows_ReExams;
$sql_sex = sql_sex('edarah_sex');
mysqli_select_db($localhost, $database_localhost);
$query_ReExams = sprintf("SELECT StID,StName1,StName2,StName3,StName4,O_StudentName3,O_TeacherName3,O_FinalExamDate,O_Edarah,O_HName,O_MurtaqaName,ErtiqaID,AutoNo,FinalExamStatus
FROM view_er_ertiqaexams
where StID>0 %s
ORDER BY AutoNo DESC",
    $sql_sex);
$query_limit_ReExams = sprintf("%s LIMIT %d,%d", $query_ReExams, $startRow_ReExams, $maxRows_ReExams);
$ReExams = mysqli_query($localhost, $query_limit_ReExams) or die(mysqli_error($localhost));
$row_ReExams = mysqli_fetch_assoc($ReExams);

/*if (isset($_GET['totalRows_ReExams'])) {
$totalRows_ReExams = $_GET['totalRows_ReExams'];
} else {*/
$all_ReExams = mysqli_query($localhost, $query_ReExams);
$totalRows_ReExams = mysqli_num_rows($all_ReExams);
//}
$totalPages_ReExams = ceil($totalRows_ReExams / $maxRows_ReExams) - 1;

$queryString_ReExams = "";
if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
        if (stristr($param, "pageNum_ReExams") == false &&
            stristr($param, "totalRows_ReExams") == false
        ) {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString_ReExams = "&" . htmlentities(implode("&", $newParams));
    }
}
$queryString_ReExams = sprintf("&totalRows_ReExams=%d%s", $totalRows_ReExams, $queryString_ReExams);
?>
<?php include('../templates/header1.php'); ?>
<title><?php echo $PageTitle; ?></title>
<script>
    var fullDate = '';
    var spliteddate = new Array();
    var day_no = '';
</script>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<div class="CSSTableGenerator content lp">
    <form name="form1" id="form1" method="post" data-validate="parsley" action="<?php echo $editFormAction; ?>">
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td><?php echo get_gender_label('st', 'ال'); ?></td>
                <td class="NoMobile"><?php echo get_gender_label('t', 'ال'); ?></td>
                <td class="NoMobile"><?php echo get_gender_label('e', 'ال'); ?></td>
                <td>المرتقى</td>
                <td class="NoMobile">اليوم</td>
                <td>التاريخ</td>
                <td class="NoMobile">الحالة</td>
                <td>تفاصيل<br>
                    الاختبار
                </td>
                <td class="NoMobile">اختبارات<br>
                    سابقة
                </td>
                <td class="NoMobile">تعديل<br>
                    الحجز
                </td>
            </tr>
            <?php
            $array1 = 0;
            do { ?>
            <input name="AutoNo[<?php echo $array1; ?>]" type="hidden" value="<?php echo $row_ReExams['AutoNo']; ?>">
            <tr class="Status_<?php echo $row_ReExams['FinalExamStatus']; ?>">
                <td><?php echo $row_ReExams['O_StudentName3']; ?></td>
                <td class="NoMobile"><?php echo $row_ReExams['O_TeacherName3']; ?></td>
                <td class="NoMobile"><?php echo str_replace("مجمع ", "", $row_ReExams['O_Edarah']); ?></td>
                <td><?php echo $row_ReExams['O_MurtaqaName']; ?>
                    <input name="FinalExamStatus<?php echo $array1; ?>" type="hidden" id="FinalExamStatus<?php echo $array1; ?>" value="<?php echo $row_ReExams['FinalExamStatus']; ?>" size="3"></td>
                <td class="NoMobile"><span id="hday<?php echo $array1; ?>">
                        <?php if ($row_ReExams['O_FinalExamDate'] != null) {
                            echo '<img height="12px" src="/sys/_images/loader.gif" alt="انتظر...">';
                        }; ?>
                        </span></td>
                <td><input name="FinalExamDate<?php echo $array1; ?>" type="text" id="FinalExamDate<?php echo $array1; ?>" value="<?php echo $row_ReExams['O_FinalExamDate']; ?>" size="11" zezo_date="true"></td>
                <td class="NoMobile"><?php echo get_array_1($statusName, $row_ReExams['FinalExamStatus']); ?></td>
                <td><a href="<?php if ($row_ReExams['ErtiqaID'] <= 4) { ?>shahadah_5.php<?php } else { ?>shahadah_10.php<?php } ?>?ExamNo=<?php echo $row_ReExams['AutoNo']; ?>" tabindex="-1">النتيجة</a></td>
                <td class="NoMobile"><a href="search_duplicate.php?StID=<?php echo $row_ReExams['StID']; ?>&Name1=<?php echo $row_ReExams['StName1']; ?>&Name2=<?php echo $row_ReExams['StName2']; ?>&Name3=<?php echo $row_ReExams['StName3']; ?>&Name4=<?php echo $row_ReExams['StName4']; ?>&O_MurtaqaName=<?php echo $row_ReExams['O_MurtaqaName']; ?>" target="new"><img src="../_images/find.png" width="20" height="20" alt="بحث"></a></td>
                <td class="NoMobile"><a href="examfulldetail.php?AutoNo=<?php echo $row_ReExams['AutoNo']; ?>" tabindex="-1"><img src="../_images/view_detail.png" width="16" height="16" alt="تفاصيل الحجز"></a></td>
                <?php

                ++ $array1;
                }
                while ($row_ReExams = mysqli_fetch_assoc($ReExams)); ?>
            </tr>

        </table>
        <script>
            $(function () {
                var fullDate = '';
                var spliteddate = new Array();
                var day_no = '';
                for (var y = 0; y < 40; y++) {
                    fullDate = $('#FinalExamDate' + y).val();
                    if (fullDate != '') {
                        spliteddate = fullDate.split('/');
                        yyyy = parseInt(spliteddate[0], 10);
                        mm = ((parseInt(spliteddate[1], 10) < 10) ? '0' + parseInt(spliteddate[1], 10) : parseInt(spliteddate[1], 10));
                        dd = ((parseInt(spliteddate[2], 10) < 10) ? '0' + parseInt(spliteddate[2], 10) : parseInt(spliteddate[2], 10));
                        yyyymmdd = String(yyyy) + String(mm) + String(dd);
                        day_no = (yyyymmdd > 0) ? weekeay_name[get_g_date(yyyymmdd, 'yes')] : '';
                        $('#hday' + y).html(day_no);
                    }
                }
            });
        </script>
        <br>
        <center>
            <div class="button-group">
                <?php if ($pageNum_ReExams > 0) { // Show if not first page ?>
                    <a title="الصفحة الأولى" class="button-primary" href="<?php printf("%s?pageNum_ReExams=%d%s", $currentPage, 0, $queryString_ReExams); ?>" tabindex="-1"> << </a>
                <?php } else { // Show if not first page ?>
                    <a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
                <?php } ?>
                <?php if ($pageNum_ReExams > 0) { // Show if not first page ?>
                    <a title="السابق" class="button-primary" href="<?php printf("%s?pageNum_ReExams=%d%s", $currentPage, max(0, $pageNum_ReExams - 1), $queryString_ReExams); ?>" tabindex="-1"> < </a>
                <?php } else { // Show if not first page ?>
                    <a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
                <?php } // Show if not first page ?>
                <?php if ($pageNum_ReExams < $totalPages_ReExams) { // Show if not last page ?>
                    <a title="التالي" class="button-primary" href="<?php printf("%s?pageNum_ReExams=%d%s", $currentPage, min($totalPages_ReExams, $pageNum_ReExams + 1), $queryString_ReExams); ?>" tabindex="-1"> > </a>
                <?php } else { // Show if not first page ?>
                    <a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
                <?php } // Show if not last page ?>
                <?php if ($pageNum_ReExams < $totalPages_ReExams) { // Show if not last page ?>
                    <a title="الصفحة الأخيرة" class="button-primary" href="<?php printf("%s?pageNum_ReExams=%d%s", $currentPage, $totalPages_ReExams, $queryString_ReExams); ?>" tabindex="-1"> >> </a>
                <?php } else { // Show if not first page ?>
                    <a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
                <?php } // Show if not last page ?>
            </div>
            <br>
            السجلات <?php echo($startRow_ReExams + 1) ?> إلى <?php echo min($startRow_ReExams + $maxRows_ReExams, $totalRows_ReExams) ?> من <?php echo $totalRows_ReExams ?>
        </center>
        <br/>
        <?php if (login_check("admin,er") == true) { ?>
            <div class="FieldsButton">
                <input name="MM_insert" type="hidden" value="form1">
                <input name="submit" type="submit" class="button-primary" id="submit" value="حــفــظ">
            </div>
        <?php } ?>
    </form>
    <?php if (isset($_SESSION['msg'])) { ?>
        <script>$(document).ready(function () {
                alertify.success("تم تحديث البيانات بنجاح");
            });</script>
        <?php $_SESSION['u1'] = null;
        unset($_SESSION['u1']);
    } ?>
    <script type="text/javascript">showError();</script>
</div>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
mysqli_free_result($ReExams);
?>
<?php } else {
    include('../templates/restrict_msg.php');
} ?>
