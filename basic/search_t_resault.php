<?php require_once('../functions.php');
$user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$maxRows_Rs_T = 100;
$currentPage = 0;
$queryString_Rs_T = 0;
$pageNum_Rs_T = Input::get('pageNum_Rs_T') ? Input::get('pageNum_Rs_T') : 0;
$startRow_Rs_T = $pageNum_Rs_T * $maxRows_Rs_T;

$T_Name1_Rs_T = Input::get('t_name1') ? "and TName1 LIKE'%" . Input::get('t_name1') . "%'" : ' ';
$T_Name2_Rs_T = Input::get('t_name2') ? "and TName2 LIKE'%" . Input::get('t_name2') . "%'" : ' ';
$T_Name3_Rs_T = Input::get('t_name3') ? "and TName3 LIKE'%" . Input::get('t_name3') . "%'" : ' ';
$T_Name4_Rs_T = Input::get('t_name4') ? "and TName4 LIKE'%" . Input::get('t_name4') . "%'" : ' ';

$sql_sex = sql_sex('e_sex');
if ($user_group != 'edarh') {
    $query_Rs_T = sprintf("SELECT TID,Tfullname,arabic_name,HName,o_hide,`hide`,h_hide,TEdarah FROM view_0_teachers WHERE TName1<>'11' %s %s  %s  %s  %s ORDER BY `hide`,arabic_name,HName,Tfullname",
        $sql_sex,
        $T_Name1_Rs_T,
        $T_Name2_Rs_T,
        $T_Name3_Rs_T,
        $T_Name4_Rs_T);
} else {
    $query_Rs_T = sprintf("SELECT TID,Tfullname,arabic_name,HName,o_hide,`hide`,h_hide,TEdarah FROM view_0_teachers WHERE TEdarah = %s %s %s %s %s ORDER BY `hide`,arabic_name,HName,Tfullname",
        GetSQLValueString($user_id, "int"),
        $T_Name1_Rs_T,
        $T_Name2_Rs_T,
        $T_Name3_Rs_T,
        $T_Name4_Rs_T
    );
}

$query_limit_Rs_T = sprintf("%s LIMIT %d,%d", $query_Rs_T, $startRow_Rs_T, $maxRows_Rs_T);

$Rs_T = mysqli_query($localhost, $query_limit_Rs_T) or die(mysqli_error($localhost));
$row_Rs_T = mysqli_fetch_assoc($Rs_T);
if (isset($_GET['totalRows_Rs_T'])) {
    $totalRows_Rs_T = $_GET['totalRows_Rs_T'];
} else {
    $all_Rs_T = mysqli_query($localhost, $query_Rs_T);
    $totalRows_Rs_T = mysqli_num_rows($all_Rs_T);
}
$totalPages_Rs_T = ceil($totalRows_Rs_T / $maxRows_Rs_T) - 1;
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'نتيجة البحث عن ' . get_gender_label('t', ''); ?>
    <title><?php echo $PageTitle; ?></title>
    </head>
    <body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
    <!--PageTitle-->
    <div class="content lp CSSTableGenerator">
        <?php if (login_check($all_groups) == true) { ?>
            <table border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="NoMobile">السجل المدني</td>
                    <td><?php echo get_gender_label('t', 'ال'); ?></td>
                    <td><?php echo get_gender_label('e', 'ال'); ?></td>
                    <td>الحلقة</td>
                    <td>تعديل</td>
                    <td>الحالة</td>
                    <td>معلومات عامة</td>
                </tr>
                <?php do { ?>
                    <tr <?php if ($row_Rs_T["hide"] == 1) { ?> class='hidenRow' <?php ;
                    } ?>>
                        <td class="NoMobile"><?php echo $row_Rs_T['TID']; ?></td>
                        <td><?php echo $row_Rs_T['Tfullname']; ?></td>
                        <td><?php echo $row_Rs_T['arabic_name']; ?></td>
                        <td><?php echo $row_Rs_T['HName'];
                            if ($row_Rs_T['h_hide'] == 1) {
                                echo '<br>(الحلقة محذوفة)';
                            } ?></td>
                        <td><a href="teacher_edit.php?TID=<?php echo $row_Rs_T['TID']; ?>&EdarahNo=<?php echo $row_Rs_T['TEdarah']; ?>">تعديل</a></td>
                        <?php if ($row_Rs_T["hide"] == 1) { ?>
                            <td><a href="teacher_show.php?TID=<?php echo $row_Rs_T['TID']; ?>"><?php echo $row_Rs_T['o_hide']; ?></a></td>
                        <?php } else { ?>
                            <td><a href="teacher_hide.php?TID=<?php echo $row_Rs_T['TID']; ?>"><?php echo $row_Rs_T['o_hide']; ?></a></td>
                        <?php } ?>
                        <td><a href="../ertiqa/statistics/teacherexams.php?TeacherID=<?php echo $row_Rs_T['TID']; ?>" target="_blank">معلومات<br>عامة</a></td>
                    </tr>
                <?php } while ($row_Rs_T = mysqli_fetch_assoc($Rs_T)); ?>
            </table>

            <br/>
            <center>
                <div class="button-group">
                    <?php if ($pageNum_Rs_T > 0) { // Show if not first page ?>
                        <a title="الصفحة الأولى" class="button-primary" href="<?php printf("%s?pageNum_Rs_T=%d%s", $currentPage, 0, $queryString_Rs_T); ?>" tabindex="-1"> << </a>
                    <?php } else { // Show if not first page ?>
                        <a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
                    <?php } ?>
                    <?php if ($pageNum_Rs_T > 0) { // Show if not first page ?>
                        <a title="السابق" class="button-primary" href="<?php printf("%s?pageNum_Rs_T=%d%s", $currentPage, max(0, $pageNum_Rs_T - 1), $queryString_Rs_T); ?>"
                           tabindex="-1"> < </a>
                    <?php } else { // Show if not first page ?>
                        <a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
                    <?php } // Show if not first page ?>
                    <?php if ($pageNum_Rs_T < $totalPages_Rs_T) { // Show if not last page ?>
                        <a title="التالي" class="button-primary"
                           href="<?php printf("%s?pageNum_Rs_T=%d%s", $currentPage, min($totalPages_Rs_T, $pageNum_Rs_T + 1), $queryString_Rs_T); ?>" tabindex="-1"> > </a>
                    <?php } else { // Show if not first page ?>
                        <a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
                    <?php } // Show if not last page ?>
                    <?php if ($pageNum_Rs_T < $totalPages_Rs_T) { // Show if not last page ?>
                        <a title="الصفحة الأخيرة" class="button-primary" href="<?php printf("%s?pageNum_Rs_T=%d%s", $currentPage, $totalPages_Rs_T, $queryString_Rs_T); ?>"
                           tabindex="-1"> >> </a>
                    <?php } else { // Show if not first page ?>
                        <a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
                    <?php } // Show if not last page ?>
                </div>
                <br>
                السجلات <?php echo($startRow_Rs_T + 1) ?> إلى <?php echo min($startRow_Rs_T + $maxRows_Rs_T, $totalRows_Rs_T) ?> من <?php echo $totalRows_Rs_T ?>
            </center>
        <?php } else {
            echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
        } ?>
    </div>
    <!--content-->
<?php include('../templates/footer.php');
