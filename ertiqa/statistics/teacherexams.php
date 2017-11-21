<?php require_once('../../Connections/localhost.php'); ?>
<?php include_once("../../functions.php"); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$maxRows_RSStudentExams = 100;
$pageNum_RSStudentExams = 0;
if (isset($_GET['pageNum_RSStudentExams'])) {
    $pageNum_RSStudentExams = $_GET['pageNum_RSStudentExams'];
}
$startRow_RSStudentExams = $pageNum_RSStudentExams * $maxRows_RSStudentExams;

$colname_RSStudentExams = "-1";
if (isset($_GET['TeacherID'])) {
    $colname_RSStudentExams = $_GET['TeacherID'];
}
mysqli_select_db($localhost, $database_localhost);
$query_RSStudentExams = sprintf("SELECT O_StudentName,O_TeacherName,O_FinalExamDate,FinalExamStatus,O_Edarah,O_HName,O_MurtaqaName,AutoNo,StID,TeacherID,FinalExamDegree,MarkName_Long,Money FROM view_er_ertiqaexams WHERE TeacherID = %s", GetSQLValueString($colname_RSStudentExams, "double"));
$query_limit_RSStudentExams = sprintf("%s LIMIT %d,%d", $query_RSStudentExams, $startRow_RSStudentExams, $maxRows_RSStudentExams);
$RSStudentExams = mysqli_query($localhost, $query_limit_RSStudentExams) or die('query_RSStudentExams : ' . mysqli_error($localhost));
$row_RSStudentExams = mysqli_fetch_assoc($RSStudentExams);

if (isset($_GET['totalRows_RSStudentExams'])) {
    $totalRows_RSStudentExams = $_GET['totalRows_RSStudentExams'];
} else {
    $all_RSStudentExams = mysqli_query($localhost, $query_RSStudentExams);
    $totalRows_RSStudentExams = mysqli_num_rows($all_RSStudentExams);
}
$totalPages_RSStudentExams = ceil($totalRows_RSStudentExams / $maxRows_RSStudentExams) - 1;

$colname_RSTeacherData = "-1";
if (isset($_GET['TeacherID'])) {
    $colname_RSTeacherData = $_GET['TeacherID'];
}
mysqli_select_db($localhost, $database_localhost);
$query_RSTeacherData = sprintf("SELECT TID,Tfullname,arabic_name,HName,TMobileNo FROM view_0_teachers WHERE TID = %s", GetSQLValueString($colname_RSTeacherData, "double"));
$RSTeacherData = mysqli_query($localhost, $query_RSTeacherData) or die('query_RSTeacherData : ' . mysqli_error($localhost));
$row_RSTeacherData = mysqli_fetch_assoc($RSTeacherData);
$totalRows_RSTeacherData = mysqli_num_rows($RSTeacherData);

mysqli_select_db($localhost, $database_localhost);
$query_RsBra3m = sprintf("SELECT AutoNo,SchoolLevelID,DarajahID,Money,O_StFullName,O_arabic_name,O_HName,O_DDate FROM view_er_bra3m WHERE TeacherID = %s ORDER BY DDate", GetSQLValueString($colname_RSTeacherData, "int"));
$RsBra3m = mysqli_query($localhost, $query_RsBra3m) or die('query_RsBra3m : ' . mysqli_error($localhost));
$row_RsBra3m = mysqli_fetch_assoc($RsBra3m);
$totalRows_RsBra3m = mysqli_num_rows($RsBra3m);

mysqli_select_db($localhost, $database_localhost);
$query_rs_featured_teacher = sprintf("SELECT auto_no,teacher_id,t_edarah,f_t_date,full_degree,approved,max_degree FROM ms_fahd_featured_teacher WHERE teacher_id = %s ORDER BY f_t_date", GetSQLValueString($colname_RSTeacherData, "int"));
$rs_featured_teacher = mysqli_query($localhost, $query_rs_featured_teacher) or die('query_rs_featured_teacher : ' . mysqli_error($localhost));
$row_rs_featured_teacher = mysqli_fetch_assoc($rs_featured_teacher);
$totalRows_rs_featured_teacher = mysqli_num_rows($rs_featured_teacher);
?>
<?php include('../../templates/header1.php'); ?>
<?php $PageTitle = 'استعلام عن ' . get_gender_label('t', ''); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../../templates/header2.php'); ?>
<?php include('../../templates/nav_menu.php'); ?>
<div id="PageTitle">
    <?php echo $PageTitle; ?>
</div>
<!--PageTitle-->
<div class="content lp">
    <p class="FieldsTitle">بحث</p>

    <form name="form1" id="form1" method="GET" action="<?php echo $editFormAction; ?>" data-validate="parsley">
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="TeacherID">رقم السجل المدني <?php echo get_gender_label('t', 'ال'); ?></label>
            </div>
            <input name="TeacherID" type="text" value="<?php echo $_GET['TeacherID']; ?>" id="TeacherID"
                   data-required="true" data-type="digits" data-maxlength="10">
        </div>
        <div class="four columns omega">
            <input type="submit" class="button-primary" id="submit" value="بحث"/>
        </div>
    </form>
</div>
<?php if ($totalRows_RSTeacherData > 0) { // Show if recordset not empty ?>
    <div class="content CSSTableGenerator">
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
            <caption>
                بيانات <?php echo get_gender_label('t', 'ال'); ?> الأساسية
            </caption>
            <tr>
                <td>اسم <?php echo get_gender_label('t', 'ال'); ?></td>
                <td>رقم الجوال</td>
                <td><?php echo get_gender_label('e', 'ال'); ?></td>
                <td>الحلقة</td>
            </tr>
            <tr>
                <td><?php echo $row_RSTeacherData['Tfullname']; ?></td>
                <td><?php echo $row_RSTeacherData['TMobileNo']; ?></td>
                <td><?php echo $row_RSTeacherData['arabic_name']; ?></td>
                <td><?php echo $row_RSTeacherData['HName']; ?></td>
            </tr>
        </table>
    </div>
    <div class="content CSSTableGenerator">
        <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <caption>
                الاختبارات السابقة لطلابه في المرتقيات
            </caption>
            <tr>
                <td>اسم <?php echo get_gender_label('st', 'ال'); ?></td>
                <td><?php echo get_gender_label('e', 'ال'); ?></td>
                <td>الحلقة</td>
                <td>المرتقى</td>
                <td>النتيجة</td>
                <td>تاريخ الاختبار</td>
                <td>درجة الاختبار</td>
                <td>التقدير</td>
                <td>المكافأة</td>
            </tr>
            <?php if ($totalRows_RSStudentExams > 0) { ?>
                <?php do { ?>
                    <tr>
                        <td><?php echo $row_RSStudentExams['O_StudentName']; ?></td>
                        <td><?php echo $row_RSStudentExams['O_Edarah']; ?></td>
                        <td><?php echo $row_RSStudentExams['O_HName']; ?></td>
                        <td><?php echo $row_RSStudentExams['O_MurtaqaName']; ?></td>
                        <td><?php echo get_array_1($statusName, $row_RSStudentExams['FinalExamStatus']); ?></td>
                        <td><?php echo $row_RSStudentExams['O_FinalExamDate']; ?></td>
                        <td><?php echo $row_RSStudentExams['FinalExamDegree']; ?></td>
                        <td><?php echo $row_RSStudentExams['MarkName_Long']; ?></td>
                        <td><?php echo $row_RSStudentExams['Money']; ?></td>
                    </tr>
                <?php } while ($row_RSStudentExams = mysqli_fetch_assoc($RSStudentExams)); ?>
            <?php } else { ?>
                <td colspan="9">لم يختبر أحد من طلابه في المرتقيات</td>
            <?php } ?>
        </table>
    </div>
    <div class="content CSSTableGenerator">
        <table border="1" cellpadding="0" cellspacing="0">
            <caption>
                <h1>سلم البراعم</h1>
            </caption>
            <tr>
                <td><?php echo get_gender_label('st', 'ال'); ?></td>
                <td>الدرجة</td>
                <td>المكافأة</td>
                <td>الصف الدراسي</td>
                <td>تاريخ الدرجة</td>
                <td class="NoMobile"><?php echo get_gender_label('e', 'ال'); ?></td>
                <td class="NoMobile">الحلقة</td>
            </tr>
            <?php if ($totalRows_RsBra3m > 0) { ?>
                <?php do { ?>
                    <tr>
                        <td class="NoMobile"><?php echo $row_RsBra3m['O_StFullName']; ?></td>
                        <td><?php echo $bra3mName[$row_RsBra3m['DarajahID']]; ?></td>
                        <td><?php echo $row_RsBra3m['Money']; ?></td>
                        <td><?php echo $SchoolLevelName[$row_RsBra3m['SchoolLevelID']]; ?></td>
                        <td><?php echo $row_RsBra3m['O_DDate']; ?></td>
                        <td class="NoMobile"><?php echo $row_RsBra3m['O_arabic_name']; ?></td>
                        <td><?php echo $row_RsBra3m['O_HName']; ?></td>
                    </tr>
                <?php } while ($row_RsBra3m = mysqli_fetch_assoc($RsBra3m)); ?>
            <?php } else { ?>
                <td colspan="8">لا يوجد طلاب في سلم البراعم</td>
            <?php } ?>
        </table>

    </div>

    <div class="content CSSTableGenerator">
        <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <caption>
                مسابقة الفهد للمعلم المتميز
            </caption>
            <tr>
                <td><?php echo get_gender_label('e', 'ال'); ?></td>
                <td>التاريخ</td>
                <td>الدرجة</td>
                <td>الدرجة العظمى</td>
                <td>تعديل</td>
            </tr>
            <?php if ($totalRows_rs_featured_teacher > 0) { ?>
                <?php do { ?>
                    <tr>
                        <td><?php echo get_edarah_name($row_rs_featured_teacher['t_edarah']); ?></td>
                        <td><?php echo StringToDate($row_rs_featured_teacher['f_t_date']); ?></td>
                        <td><?php echo $row_rs_featured_teacher['full_degree']; ?></td>
                        <td><?php echo $row_rs_featured_teacher['max_degree']; ?></td>
                        <td>
                            <?php if ($row_rs_featured_teacher['approved'] == 0) { ?>
                                <a href="/sys/fahd/featured_teacher_edit.php?auto_no=<?php echo $row_rs_featured_teacher['auto_no']; ?>">تعديل الدرجات</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } while ($row_rs_featured_teacher = mysqli_fetch_assoc($rs_featured_teacher)); ?>
            <?php } else { ?>
                <td colspan="8">لم يسجل سابقا في المسابقة</td>
            <?php } ?>
        </table>
        <p style="padding:5px 0;font-size:14px;">
            <a href="/sys/fahd/featured_teacher_add.php?TID=<?php echo $colname_RSTeacherData; ?>">للتسجيل في المسابقة
                اضغط هنا</a>
        </p>
    </div>

<?php } else { // if there is no student with that id no ?>
    <?php
    if (isset($_GET['TeacherID'])) { ?>
        <div class="FieldsButton" style="height:40px">
            <p style="font-size:22px;color:red;">
                لايوجد معلم أو معلمة في النظام بهذا السجل المدني : <?php echo $colname_RSStudentExams; ?>
            </p>
        </div>
    <?php } ?>
<?php } // Show if recordset not empty ?>
<script type="text/javascript">
    showError();
</script>

</div><!--content-->
<?php include('../../templates/footer.php'); ?>
<?php
mysqli_free_result($RSStudentExams);

mysqli_free_result($RSTeacherData);
?>
