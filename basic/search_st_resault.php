<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php
$maxRows_Rs_St = 100;
$pageNum_Rs_St = 0;
$currentPage = $_SERVER["PHP_SELF"];
if (isset($_GET['pageNum_Rs_St'])) {
    $pageNum_Rs_St = $_GET['pageNum_Rs_St'];
}
$startRow_Rs_St = $pageNum_Rs_St * $maxRows_Rs_St;

$StName1_Rs_St = "";
if (isset($_POST['st_name1'])) {
    $StName1_Rs_St = ($_POST['st_name1'] != "") ? "and StName1 LIKE'%" . $_POST['st_name1'] . "%'" : ' ';
}
$StName2_Rs_St = "";
if (isset($_POST['st_name2'])) {
    $StName2_Rs_St = ($_POST['st_name2'] != "") ? "and StName2 LIKE'%" . $_POST['st_name2'] . "%'" : ' ';
}
$StName3_Rs_St = "";
if (isset($_POST['st_name3'])) {
    $StName3_Rs_St = ($_POST['st_name3'] != "") ? "and StName3 LIKE'%" . $_POST['st_name3'] . "%'" : ' ';
}
$StName4_Rs_St = "";
if (isset($_POST['st_name4'])) {
    $StName4_Rs_St = ($_POST['st_name4'] != "") ? "and StName4 LIKE'%" . $_POST['st_name4'] . "%'" : ' ';
}
mysqli_select_db($localhost, $database_localhost);
$sql_sex = sql_sex('e_sex');
$sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 1;
if ($_SESSION['user_group'] != 'edarh') {
    $query_Rs_St = sprintf("SELECT StID,Stfullname,arabic_name,HName,o_hide,`hide`,StEdarah,StHalaqah FROM view_0_students WHERE StName1<>'11' %s %s  %s  %s  %s ORDER BY `hide`,arabic_name,HName,Stfullname", $sql_sex, $StName1_Rs_St, $StName2_Rs_St, $StName3_Rs_St, $StName4_Rs_St);
    //echo $query_Rs_St;
} else {
    $query_Rs_St = sprintf("SELECT StID,Stfullname,arabic_name,HName,o_hide,`hide`,StEdarah,StHalaqah FROM view_0_students WHERE StEdarah = %s %s %s %s %s ORDER BY `hide`,arabic_name,HName,Stfullname", GetSQLValueString($_SESSION['user_id'], "int"), $StName1_Rs_St, $StName2_Rs_St, $StName3_Rs_St, $StName4_Rs_St);
}
$query_limit_Rs_St = sprintf("%s LIMIT %d,%d", $query_Rs_St, $startRow_Rs_St, $maxRows_Rs_St);

$Rs_St = mysqli_query($localhost, $query_limit_Rs_St) or die(mysqli_error($localhost));
$row_Rs_St = mysqli_fetch_assoc($Rs_St);
if (isset($_GET['totalRows_Rs_St'])) {
    $totalRows_Rs_St = $_GET['totalRows_Rs_St'];
} else {
    $all_Rs_St = mysqli_query($localhost, $query_Rs_St);
    $totalRows_Rs_St = mysqli_num_rows($all_Rs_St);
}
$totalPages_Rs_St = ceil($totalRows_Rs_St / $maxRows_Rs_St) - 1;
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'نتيجة البحث عن ' . get_gender_label('st', ''); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<div class="content lp CSSTableGenerator">
    <?php if (login_check($all_groups) == true) { ?>
        <?php if ($totalRows_Rs_St > 0) { ?>
            <table border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td>م</td>
                    <td>م</td>
                    <td class="NoMobile">السجل المدني</td>
                    <td>اسم <?php echo get_gender_label('st', 'ال'); ?><br>
                        <span class="SmallNote">اضغط على <?php echo get_gender_label('st', 'ال'); ?>
                            لتعديل البيانات</span>
                    </td>
                    <td class="NoMobile"><?php echo get_gender_label('e', 'ال'); ?></td>
                    <td>الحلقة</td>
                    <td>خدمات</td>
                    <td>الاختبارات<br>
                        السابقة
                    </td>
                </tr>

                <?php
                //$_SESSION['u1']="u1";
                if ($pageNum_Rs_St == 0) {
                    $_SESSION['EdarahIndex'] = 0;
                    $_SESSION['HalakahIndex'] = 0;
                    //echo '###'.$_GET['pageNum_Rs_St'].' s '.$_SESSION['HalakahIndex'];
                }
                $_SESSION['LastEdarahName'] = $row_Rs_St['arabic_name'];
                $_SESSION['LastHalakahName'] = $row_Rs_St['HName'];
                do {
                    if ($_SESSION['LastEdarahName'] != $row_Rs_St['arabic_name']) {
                        $_SESSION['LastEdarahName'] = $row_Rs_St['arabic_name'];
                        $_SESSION['EdarahIndex'] = 0;
                        echo '<tr class="EdarahSeperator"><td colspan="9">&nbsp;</td></tr>';
                    }
                    if ($_SESSION['LastHalakahName'] != $row_Rs_St['HName']) {
                        $_SESSION['LastHalakahName'] = $row_Rs_St['HName'];
                        $_SESSION['HalakahIndex'] = 0;
                        echo '<tr class="HalakahSeperator"><td colspan="9">&nbsp;</td></tr>';
                    }
                    $_SESSION['EdarahIndex']++;
                    $_SESSION['HalakahIndex']++;
                    ?>
                    <tr <?php if ($row_Rs_St["hide"] == 1) { ?> class='hidenRow' <?php ;
                    } ?>>
                        <td><?php echo $_SESSION['EdarahIndex']; ?></td>
                        <td><?php echo $_SESSION['HalakahIndex']; ?></td>
                        <td class="NoMobile"><?php echo $row_Rs_St['StID']; ?></td>
                        <td>
                            <a href="student_edit.php?StID=<?php echo $row_Rs_St['StID']; ?>"
                               target="_blank"><?php echo $row_Rs_St['Stfullname']; ?></a></td>
                        <td class="NoMobile"><?php echo $row_Rs_St['arabic_name']; ?></td>
                        <td><?php echo $row_Rs_St['HName']; ?></td>
                        <td>
                            <a href="../ertiqa/newexam.php?StID=<?php echo $row_Rs_St['StID']; ?>" target="_blank">حجز بالمرتقيات</a>
                            <hr>
                            <a href="../ertiqa/bra3m_add.php?StID=<?php echo $row_Rs_St['StID']; ?>" target="_blank">تسجيل بالبراعم</a>
                            <hr>
                            <a href="../fahd/Register_add.php?StID=<?php echo $row_Rs_St['StID']; ?>&&EdarahNo=<?php echo $row_Rs_St['StEdarah']; ?>"
                               target="_blank">تسجيل بالفهد</a>
                            <hr>
                            <a href="../salman/Register_add.php?StID=<?php echo $row_Rs_St['StID']; ?>&&EdarahNo=<?php echo $row_Rs_St['StEdarah']; ?>"
                               target="_blank">تسجيل بالملك سلمان</a>
                            <hr>
                            <a href="../etqan/Register_add.php?StID=<?php echo $row_Rs_St['StID']; ?>" target="_blank">التسجيل بأمير الرياض</a>
                            <?php if ($sex == 1 || $sex == 2) { ?>
                                <hr>
                                <a href="../shabab/Register_add.php?StID=<?php echo $row_Rs_St['StID']; ?>" target="_blank">التسجيل بالهيئة العامة للرياضة</a>
                            <?php } ?>
                        </td>
                        <td><a href="../ertiqa/statistics/studentexams.php?StudentID=<?php echo $row_Rs_St['StID']; ?>" target="_blank">معلومات<br>عامة</a></td>
                    </tr>


                <?php } while ($row_Rs_St = mysqli_fetch_assoc($Rs_St)); ?>
            </table>
            <br/>
            <center>
                <div class="button-group">
                    <?php if ($pageNum_Rs_St > 0) { // Show if not first page ?>
                        <a title="الصفحة الأولى" class="button-primary"
                           href="<?php printf("%s?pageNum_Rs_St=%d", $currentPage, 0); ?>"
                           tabindex="-1"> << </a>
                    <?php } else { // Show if not first page ?>
                        <a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
                    <?php } ?>
                    <?php if ($pageNum_Rs_St > 0) { // Show if not first page ?>
                        <a title="السابق" class="button-primary"
                           href="<?php printf("%s?pageNum_Rs_St=%d", $currentPage, max(0, $pageNum_Rs_St - 1)); ?>"
                           tabindex="-1"> < </a>
                    <?php } else { // Show if not first page ?>
                        <a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
                    <?php } // Show if not first page ?>
                    <?php if ($pageNum_Rs_St < $totalPages_Rs_St) { // Show if not last page ?>
                        <a title="التالي" class="button-primary"
                           href="<?php printf("%s?pageNum_Rs_St=%d", $currentPage, min($totalPages_Rs_St, $pageNum_Rs_St + 1)); ?>"
                           tabindex="-1"> > </a>
                    <?php } else { // Show if not first page ?>
                        <a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
                    <?php } // Show if not last page ?>
                    <?php if ($pageNum_Rs_St < $totalPages_Rs_St) { // Show if not last page ?>
                        <a title="الصفحة الأخيرة" class="button-primary"
                           href="<?php printf("%s?pageNum_Rs_St=%d", $currentPage, $totalPages_Rs_St); ?>"
                           tabindex="-1"> >> </a>
                    <?php } else { // Show if not first page ?>
                        <a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
                    <?php } // Show if not last page ?>
                </div>
                <br>
                السجلات <?php echo($startRow_Rs_St + 1) ?>
                إلى <?php echo min($startRow_Rs_St + $maxRows_Rs_St, $totalRows_Rs_St) ?>
                من <?php echo $totalRows_Rs_St ?>
            </center>
        <?php } else { ?>
            <p class="WrapperMSG">لاتوجد نتائج</p>
        <?php } ?>
    <?php } else {
        echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
    } ?>
</div>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
mysqli_free_result($Rs_St);
?>
