<?php require_once("../../functions.php");
$Date1_Rs1 = "";
if (isset($_GET['Date1'])) {
    if (Input::get('Date1') != null) {
        $Date1_Rs1 = 'and ex.FinalExamDate>=' . str_replace('/', '', Input::get('Date1'));
    }
}
$Date2_Rs1 = "";
if (isset($_GET['Date2'])) {
    if (Input::get('Date2') != null) {
        $Date2_Rs1 = 'and ex.FinalExamDate<=' . str_replace('/', '', Input::get('Date2'));
    }
}
$sql_sex = sql_sex('sex');

$user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : null;
if ($user_group != 'edarh') {
    $query_edarat = sprintf("SELECT id,arabic_name FROM 0_users WHERE hidden=0 %s AND user_group='edarh' ORDER BY arabic_name", $sql_sex);
} else {
    $query_edarat = sprintf("SELECT id,arabic_name FROM 0_users WHERE id=%s", GetSQLValueString($_SESSION['user_id'], "int"));
}
$edarat = mysqli_query($localhost, $query_edarat) or die('no_ertiqa.php 1 - ' . mysqli_error($localhost));
$row_edarat = mysqli_fetch_assoc($edarat);
$totalRows_edarat = mysqli_num_rows($edarat);

?>
<?php
$pageTitle = "بيان بالمعلمين الذين لم يختبر لديهم طلاب";
$secondLogo = true;
$secondLogoURL = '<img class="ertiqafLogo" src="/sys/_images/ertiqa_160.png" width="100">';
?>
<?php require_once("../../templates/report_header1.php"); ?>
<style>
    .reportContent {
        font-size: 12px
    }

    .reportContent table td {
        line-height: 20px;
        padding-top: 7px;
    }
</style>
<?php do {

    $query_st_ertiqa = sprintf("SELECT * FROM view_0_teachers
                              WHERE hide=0 AND TEdarah=%s
                              AND TID NOT IN (SELECT TeacherID FROM er_ertiqaexams ex WHERE TeacherID>0 %s %s ) ORDER BY TEdarah",
        $row_edarat['id'],
        $Date1_Rs1,
        $Date2_Rs1);

    $st_ertiqa = mysqli_query($localhost, $query_st_ertiqa) or die('no_ertiqa.php 3 - ' . mysqli_error($localhost));
    $row_st_ertiqa = mysqli_fetch_assoc($st_ertiqa);
    $totalRows_st_ertiqa = mysqli_num_rows($st_ertiqa);
    if ($totalRows_st_ertiqa > 0) {
        ?>
        <?php require("../../templates/report_header2.php"); ?>
        <div class="reportContent">
            <p class="report_description"><?php echo $row_edarat['arabic_name']; ?></p>
            <p class="report_description">المعلمون الذين لم يخرجوا طلاب في المرتقيات خلال الفترة
                من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : '(بداية النظام الإلكتروني في 1434/08/29 هـ)'); ?>
                إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : '(تاريخ اليوم)'); ?> </p>


            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <th scope="col"><p>م</p></th>
                    <th scope="col"><p><?php echo get_gender_label('e', 'ال'); ?></p></th>
                    <th scope="col"><p><?php echo get_gender_label('t', 'ال') ?></p></th>
                    <th scope="col"><p>الحلقة <br>ونوع الحلقة</p></th>
                    <th scope="col"><p>تاريخ المباشرة</p></th>
                    <th scope="col" colspan="3"><p>أخر اختبار مسجل لأحد طلابه</p></th>
                </tr>
                <?php


                $i = 1;
                do {
                    $query_last_ertiqa = sprintf("SELECT ErtiqaID,FinalExamStatus,FinalExamDate FROM er_ertiqaexams WHERE TeacherID =%s ORDER BY FinalExamDate DESC",
                        $row_st_ertiqa['TID']
                    );

                    $last_ertiqa = mysqli_query($localhost, $query_last_ertiqa) or die('no_ertiqa.php 3 - last_ertiqa ' . mysqli_error($localhost));
                    $row_last_ertiqa = mysqli_fetch_assoc($last_ertiqa);
                    $totalRows_last_ertiqa = mysqli_num_rows($last_ertiqa);

                    ?>
                    <tr>
                        <th><?php echo $i; ?></th>
                        <td><?php echo $row_st_ertiqa['arabic_name']; ?></td>
                        <td><?php echo $row_st_ertiqa['Tfullname']; ?></td>
                        <td><?php echo $row_st_ertiqa['HName'] . '<br>' . '<span style="color:#2020D5">' . get_halakah_types($row_st_ertiqa['h_type'])->toString . '</span>'; ?></td>
                        <td><?php
                            if ($row_st_ertiqa['start_date']) {
                                echo StringToDate($row_st_ertiqa['start_date']);
                            } else {
                                echo '<span style="color:#b22930">' . 'المباشرة لم تسجل !' . '</span>';
                            }
                            ?>
                        </td>
                        <td><?php if ($totalRows_last_ertiqa > 0) {
                                echo stringToDate($row_last_ertiqa['FinalExamDate']);
                            } else {
                                echo '-';
                            } ?></td>
                        <td><?php if ($totalRows_last_ertiqa > 0) {
                                echo get_array_1($murtaqaName, $row_last_ertiqa['ErtiqaID']);
                            } else {
                                echo '-';
                            } ?></td>
                        <td><?php if ($totalRows_last_ertiqa > 0) {
                                echo get_array_1($statusName, $row_last_ertiqa['FinalExamStatus']);
                            } else {
                                echo '-';
                            } ?></td>
                    </tr>
                    <?php $i++;
                } while ($row_st_ertiqa = mysqli_fetch_assoc($st_ertiqa)); ?>

            </table>
            <div class="reportFotter"></div>
        </div>
        </div>
        <div class="page-break"></div>
    <?php }
} while ($row_edarat = mysqli_fetch_assoc($edarat)); ?>
</body>
</html>
