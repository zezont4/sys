<?php
require_once('../../Connections/localhost.php');
include_once("../../functions.php");
include_once '../../secure/functions.php';
include_once('../../fahd/fahd_functions.php');
sec_session_start();

$logedInUser = false;
if (isset($_SESSION['username'])) {
    $logedInUser = true;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$student_id = "";
if (isset($_GET['StudentID'])) {
    $student_id = $_GET['StudentID'];
}

mysqli_select_db($localhost, $database_localhost);
$query_RSStudentExams = sprintf("SELECT O_TeacherName,O_FinalExamDate,O_Edarah,O_HName,O_MurtaqaName,AutoNo,StID,FinalExamDegree,FinalExamDate,MarkName_Long,Money FROM view_er_ertiqaexams WHERE StID = %s", GetSQLValueString($student_id, "double"));
$RSStudentExams = mysqli_query($localhost, $query_RSStudentExams) or die('query_RSStudentExams : ' . mysqli_error($localhost));
$row_RSStudentExams = mysqli_fetch_assoc($RSStudentExams);
$totalRows_RSStudentExams = mysqli_num_rows($RSStudentExams);

mysqli_select_db($localhost, $database_localhost);
$query_RSStudentData = sprintf("SELECT Stfullname,O_BurthDate,arabic_name,HName,StMobileNo,StID FROM view_0_students WHERE StID = %s", GetSQLValueString($student_id, "double"));
$RSStudentData = mysqli_query($localhost, $query_RSStudentData) or die(mysqli_error($localhost));
$row_RSStudentData = mysqli_fetch_assoc($RSStudentData);
$totalRows_RSStudentData = mysqli_num_rows($RSStudentData);

mysqli_select_db($localhost, $database_localhost);
$query_RSFahdExam = sprintf("SELECT * FROM ms_fahd_rgstr WHERE StID = %s", GetSQLValueString($student_id, "double"));
$RSFahdExam = mysqli_query($localhost, $query_RSFahdExam) or die(mysqli_error($localhost));
$row_RSFahdExam = mysqli_fetch_assoc($RSFahdExam);
$totalRows_RSFahdExam = mysqli_num_rows($RSFahdExam);

mysqli_select_db($localhost, $database_localhost);
$query_rs_etqan_exam = sprintf("SELECT * FROM ms_etqan_rgstr WHERE StID = %s", GetSQLValueString($student_id, "double"));
$rs_etqan_exam = mysqli_query($localhost, $query_rs_etqan_exam) or die(mysqli_error($localhost));
$row_rs_etqan_exam = mysqli_fetch_assoc($rs_etqan_exam);
$totalRows_rs_etqan_exam = mysqli_num_rows($rs_etqan_exam);

mysqli_select_db($localhost, $database_localhost);
$query_rs_shabab_exam = sprintf("SELECT * FROM ms_shabab_rgstr WHERE StID = %s", GetSQLValueString($student_id, "double"));
$rs_shabab_exam = mysqli_query($localhost, $query_rs_shabab_exam) or die(mysqli_error($localhost));
$row_rs_shabab_exam = mysqli_fetch_assoc($rs_shabab_exam);
$totalRows_rs_shabab_exam = mysqli_num_rows($rs_shabab_exam);


mysqli_select_db($localhost, $database_localhost);
$query_RsBra3m = sprintf("SELECT AutoNo,SchoolLevelID,DarajahID,Money,O_StFullName,O_TFullName,O_arabic_name,O_HName,O_DDate FROM view_er_bra3m WHERE StID = %s ORDER BY DDate ASC", GetSQLValueString($student_id, "double"));
$RsBra3m = mysqli_query($localhost, $query_RsBra3m) or die(mysqli_error($localhost));
$row_RsBra3m = mysqli_fetch_assoc($RsBra3m);
$totalRows_RsBra3m = mysqli_num_rows($RsBra3m);
?>
<?php include('../../templates/header1.php'); ?>
<?php $PageTitle = 'استعلام عن ' . get_gender_label('st', ''); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../../templates/header2.php'); ?>
<?php include('../../templates/nav_menu.php'); ?>
<div id="PageTitle"> <?php echo $PageTitle; ?> </div>
<!--PageTitle-->
<div class="content lp">
    <form name="form1" id="form1" method="GET" action="<?php echo $editFormAction; ?>" data-validate="parsley">
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="StudentID">رقم السجل المدني <?php echo get_gender_label('st', 'لل'); ?></label>
            </div>
            <input name="StudentID" type="text" value="<?php echo $student_id; ?>" id="StudentID" data-required="true" data-type="digits" data-maxlength="10">
        </div>
        <div class="four columns">
            <input type="submit" class="button-primary" id="submit" value="بحث"/>
        </div>
    </form>
</div>
<?php if ($totalRows_RSStudentData > 0) { // Show if recordset not empty ?>
    <div class="content CSSTableGenerator">
        <?php echo get_st_info($_GET['StudentID']); ?>
    </div>
    <div class="content CSSTableGenerator">
        <table>
            <caption>
                <h1>اختبارات المرتقيات السابقة</h1>
            </caption>
            <tr>
                <td>المرتقى</td>
                <td>درجة الاختبار النهائي</td>
                <td>التقدير</td>
                <td>المكافأة</td>
                <td>تاريخ الاختبار النهائي</td>
                <td class="NoMobile">المجمع</td>
                <td>الحلقة</td>
                <td class="NoMobile">المعلم</td>
            </tr>
            <?php if ($totalRows_RSStudentExams > 0) { // Show if recordset not empty ?>
                <?php do { ?>
                    <tr>
                        <td><?php echo $row_RSStudentExams['O_MurtaqaName']; ?></td>
                        <td><?php echo $row_RSStudentExams['FinalExamDegree']; ?></td>
                        <td><?php echo $row_RSStudentExams['MarkName_Long']; ?></td>
                        <td><?php echo $row_RSStudentExams['Money']; ?></td>
                        <td><?php echo $row_RSStudentExams['O_FinalExamDate']; ?></td>
                        <td class="NoMobile"><?php echo $row_RSStudentExams['O_Edarah']; ?></td>
                        <td><?php echo $row_RSStudentExams['O_HName']; ?></td>
                        <td class="NoMobile"><?php echo $row_RSStudentExams['O_TeacherName']; ?></td>
                    </tr>
                <?php } while ($row_RSStudentExams = mysqli_fetch_assoc($RSStudentExams)); ?>
            <?php } else { ?>
                <td colspan="8">لا توجد اختبارات في المرتقيات <?php echo get_gender_label('st', 'ال'); ?></td>
            <?php } ?>
        </table>
    </div>
    <div class="content CSSTableGenerator">
        <table>
            <caption>
                <h1>سلم البراعم</h1>
            </caption>
            <tr>
                <td>الدرجة</td>
                <td>المكافأة</td>
                <td>الصف الدراسي</td>
                <td>تاريخ الدرجة</td>
                <td class="NoMobile">المجمع</td>
                <td>الحلقة</td>
                <td class="NoMobile">المعلم</td>
                <?php if ($logedInUser == true) { ?>
                    <td class="NoMobile">تعديل</td><?php } ?>
            </tr>
            <?php if ($totalRows_RsBra3m > 0) { ?>
                <?php do { ?>
                    <tr>
                        <td><?php echo $bra3mName[$row_RsBra3m['DarajahID']]; ?></td>
                        <td><?php echo $row_RsBra3m['Money']; ?></td>
                        <td><?php echo $SchoolLevelName[$row_RsBra3m['SchoolLevelID']]; ?></td>
                        <td><?php echo $row_RsBra3m['O_DDate']; ?></td>
                        <td class="NoMobile"><?php echo $row_RsBra3m['O_arabic_name']; ?></td>
                        <td><?php echo $row_RsBra3m['O_HName']; ?></td>
                        <td class="NoMobile"><?php echo $row_RsBra3m['O_TFullName']; ?></td>
                        <?php if ($logedInUser == true) { ?>
                            <td class="NoMobile"><a
                                    href="../../ertiqa/bra3m_edit.php?AutoNo=<?php echo $row_RsBra3m['AutoNo']; ?>">تعديل</a>
                            </td> <?php } ?>
                    </tr>
                <?php } while ($row_RsBra3m = mysqli_fetch_assoc($RsBra3m)); ?>
            <?php } else { ?>
                <td colspan="8">لايوجد درجات <?php echo get_gender_label('st', 'لل'); ?> في سلم البراعم</td>
            <?php } ?>
        </table>

    </div>
    <div class="content CSSTableGenerator">
        <table>
            <caption>
                <h1>مشاركات <?php echo get_gender_label('st', 'ال'); ?> في مسابقة الفهد</h1>
            </caption>
            <tr>
                <td>العام الدراسي</td>
                <td>المسابقة</td>
                <td>التاريخ</td>
                <td>آخر مرتقى</td>
                <td>السنة الدراسية</td>
                <td>المجمع</td>
                <td>الحلقة</td>
                <td>المعلم</td>
                <?php if ($logedInUser == true) { ?>
                    <td>تعديل</td><?php } ?>
            </tr>
            <?php if ($totalRows_RSFahdExam > 0) { ?>
                <?php do { ?>
                    <?php $study_fahd_name = get_fahd_year_name($row_RSFahdExam['RDate']); ?>
                    <tr>
                        <td><?php echo $study_fahd_name; ?></td>
                        <td><?php echo get_array_1($MsbkhType, $row_RSFahdExam['MsbkhID']); ?></td>
                        <td><?php echo StringToDate($row_RSFahdExam['RDate']); ?></td>
                        <td><?php echo get_array_1($murtaqaName, $row_RSFahdExam['ErtiqaID']); ?></td>
                        <td><?php echo get_array_1($SchoolLevelNameAll, $row_RSFahdExam['SchoolLevelID']); ?></td>
                        <td><?php echo get_edarah_name($row_RSFahdExam['EdarahID']); ?></td>
                        <td><?php echo get_halakah_name($row_RSFahdExam['HalakahID']); ?></td>
                        <td><?php echo get_teacher_name($row_RSFahdExam['TeacherID']); ?></td>
                        <?php if ($logedInUser == true) { ?>
                            <td><a href="../../fahd/Register_edit.php?AutoNo=<?php echo $row_RSFahdExam['AutoNo']; ?>">تعديل</a>
                            </td><?php } ?>
                    </tr>
                <?php } while ($row_RSFahdExam = mysqli_fetch_assoc($RSFahdExam)); ?>
            <?php } else { ?>
                <td colspan="9">لاتوجد مشاركات سابقة <?php echo get_gender_label('st', 'لل'); ?> في مسابقة الفهد</td>
            <?php } ?>
        </table>
    </div>
    <div class="content CSSTableGenerator">
        <table>
            <caption>
                <h1>مشاركات <?php echo get_gender_label('st', 'ال'); ?> في مسابقة أمير الرياض</h1>
            </caption>
            <tr>
                <td>العام الدراسي</td>
                <td>المسابقة</td>
                <td>التاريخ</td>
                <td>آخر مرتقى</td>
                <td>السنة الدراسية</td>
                <td>المجمع</td>
                <td>الحلقة</td>
                <td>المعلم</td>
                <?php if ($logedInUser == true) { ?>
                    <td>تعديل</td><?php } ?>
            </tr>
            <?php if ($totalRows_rs_etqan_exam > 0) { ?>
                <?php do { ?>
                    <?php $study_fahd_name = get_fahd_year_name($row_rs_etqan_exam['RDate']); ?>
                    <tr>
                        <td><?php echo $study_fahd_name; ?></td>
                        <td><?php echo get_array_1($etqan_msbkh_type, $row_rs_etqan_exam['MsbkhID']); ?></td>
                        <td><?php echo StringToDate($row_rs_etqan_exam['RDate']); ?></td>
                        <td><?php echo get_array_1($murtaqaName, $row_rs_etqan_exam['ErtiqaID']); ?></td>
                        <td><?php echo get_array_1($SchoolLevelNameAll, $row_rs_etqan_exam['SchoolLevelID']); ?></td>
                        <td><?php echo get_edarah_name($row_rs_etqan_exam['EdarahID']); ?></td>
                        <td><?php echo get_halakah_name($row_rs_etqan_exam['HalakahID']); ?></td>
                        <td><?php echo get_teacher_name($row_rs_etqan_exam['TeacherID']); ?></td>
                        <?php if ($logedInUser == true) { ?>
                            <td><a
                                href="../../etqan/Register_edit.php?AutoNo=<?php echo $row_rs_etqan_exam['AutoNo']; ?>">تعديل</a>
                            </td><?php } ?>
                    </tr>
                <?php } while ($row_rs_etqan_exam = mysqli_fetch_assoc($rs_etqan_exam)); ?>
            <?php } else { ?>
                <td colspan="9">لاتوجد مشاركات سابقة <?php echo get_gender_label('st', 'لل'); ?> في مسابقة الإتقان</td>
            <?php } ?>
        </table>
    </div>

    <div class="content CSSTableGenerator">
        <table>
            <caption>
                <h1>مشاركات <?php echo get_gender_label('st', 'ال'); ?> في مسابقة رعاية الشباب</h1>
            </caption>
            <tr>
                <td>العام الدراسي</td>
                <td>المسابقة</td>
                <td>التاريخ</td>
                <td>آخر مرتقى</td>
                <td>السنة الدراسية</td>
                <td>المجمع</td>
                <td>الحلقة</td>
                <td>المعلم</td>
                <?php if ($logedInUser == true) { ?>
                    <td>تعديل</td><?php } ?>
            </tr>
            <?php if ($totalRows_rs_shabab_exam > 0) { ?>
                <?php do { ?>
                    <?php $study_fahd_name = get_fahd_year_name($row_RSFahdExam['RDate']); ?>
                    <tr>
                        <td><?php echo $study_fahd_name; ?></td>
                        <td><?php echo get_array_1($shabab_msbkh_type, $row_rs_shabab_exam['MsbkhID']); ?></td>
                        <td><?php echo StringToDate($row_rs_shabab_exam['RDate']); ?></td>
                        <td><?php echo get_array_1($murtaqaName, $row_rs_shabab_exam['ErtiqaID']); ?></td>
                        <td><?php echo get_array_1($SchoolLevelNameAll, $row_rs_shabab_exam['SchoolLevelID']); ?></td>
                        <td><?php echo get_edarah_name($row_rs_shabab_exam['EdarahID']); ?></td>
                        <td><?php echo get_halakah_name($row_rs_shabab_exam['HalakahID']); ?></td>
                        <td><?php echo get_teacher_name($row_rs_shabab_exam['TeacherID']); ?></td>
                        <?php if ($logedInUser == true) { ?>
                            <td><a
                                href="../../shabab/Register_edit.php?AutoNo=<?php echo $row_rs_shabab_exam['AutoNo']; ?>">تعديل</a>
                            </td><?php } ?>
                    </tr>
                <?php } while ($row_rs_shabab_exam = mysqli_fetch_assoc($rs_shabab_exam)); ?>
            <?php } else { ?>
                <td colspan="9">لاتوجد مشاركات سابقة <?php echo get_gender_label('st', 'لل'); ?> في مسابقة رعاية
                    الشباب
                </td>
            <?php } ?>
        </table>
    </div>

<?php } else { // if there is no student with that id no ?>
    <?php
    if (isset($_GET['StudentID'])) { ?>
        <div class="FieldsButton"><p style="font-size:22px;color:red;">لايوجد <?php echo get_gender_label('st', ''); ?>
                بهذا السجل المدني : <?php echo $student_id; ?></p></div>
    <?php } ?>
<?php } ?>

<script>
    $(document).ready(function () {
        <?php if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'shabab') {
        ?>
        alertify.success("تم التسجيل بمسابقة رعاية الشباب بنجاح");
    <?php }
    if ($_GET['msg'] == 'etqan') {
        ?>
        alertify.success("تم التسجيل بمسابقة الاتقان بنجاح");
    <?php } ?>
    <?php if ($_GET['msg'] == 'ertiqa') { ?>
        alertify.success("تم حجز موعد بنجاح");
    <?php } ?>
    <?php if ($_GET['msg'] == 'fahd') { ?>
        alertify.success("تم التسجيل بمسابقة الفهد بنجاح");
    <?php } ?>
    <?php if ($_GET['msg'] == 'bra3m') { ?>
        alertify.success("تم التسجيل بالبراعم بنجاح");
    <?php } ?>
<?php } ?>
    });
</script>
<script type="text/javascript">
    showError();
</script>

<?php include('../../templates/footer.php');
