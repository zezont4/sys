<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once("../../functions.php"); ?>
<?php require_once '../../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php
$Date1_Rs1 = "";
if (isset($_GET['Date1'])) {
    if ($_GET['Date1'] != null) {
        $Date1_Rs1 = 'and ex.FinalExamDate>=' . str_replace('/', '', $_GET['Date1']);
    }
}
$Date2_Rs1 = "";
if (isset($_GET['Date2'])) {
    if ($_GET['Date2'] != null) {
        $Date2_Rs1 = 'and ex.FinalExamDate<=' . str_replace('/', '', $_GET['Date2']);
    }
}
mysqli_select_db($localhost, $database_localhost);
$sql_sex = sql_sex('sex');
if ($_SESSION['user_group'] != 'edarh') {
    $query_edarat = sprintf("SELECT id,arabic_name FROM 0_users WHERE hidden=0 %s AND user_group='edarh' ORDER BY arabic_name", $sql_sex);
} else {
    $query_edarat = sprintf("SELECT id,arabic_name FROM 0_users WHERE hidden=0 %s AND user_group='edarh' AND id = %s ORDER BY arabic_name", $sql_sex, GetSQLValueString($_SESSION['user_id'], "int"));
}
$edarat = mysqli_query($localhost, $query_edarat) or die('no_ertiqa.php 1 - ' . mysqli_error($localhost));
$row_edarat = mysqli_fetch_assoc($edarat);
$totalRows_edarat = mysqli_num_rows($edarat);

?>
<?php
$pageTitle = "بيان بالذين لم يختبروا في المرتقيات";
/*$deptName = "الشؤون التعليمية (بنين) / برنامج الارتقاء";
if (isset($_SESSION['sex'])) {
    if ($_SESSION['sex'] == 0) {
        $deptName = "الشؤون التعليمية (بنات) / برنامج الارتقاء";
    }
}*/

$secondLogo = true;
$secondLogoURL = '<img class="ertiqafLogo" src="/sys/_images/ertiqa_160.png" width="100">';
?>
<?php require_once("../../templates/report_header1.php"); ?>
<style>
    .reportContent {
        font-size: 12px
    }
</style>
<?php do {
//    mysqli_select_db($localhost, $database_localhost);
//    $query_st_count = sprintf("SELECT StID FROM er_bra3m WHERE EdarahID=%s %s %s ",
    $query_st_ertiqa = sprintf("SELECT st.StID,st.Stfullname,st.HName,concat_ws(' ',t.TName1,t.TName2,t.TName4) AS Tfullname FROM view_0_students st
                              LEFT JOIN 0_teachers t ON st.TID = t.TID
                              WHERE st.hide=0 AND StEdarah=%s
                              AND st.StID NOT IN (SELECT StID FROM er_ertiqaexams ex WHERE StID>0 %s %s ) ORDER BY st.HName",
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
   <p class="report_description">بيان بالذين لم يتقدموا لاختبار المرتقيات خلال الفترة من <?php echo(($_GET['Date1'] != '') ? $_GET['Date1'] : '(بداية النظام الإلكتروني في 1434/08/29 هـ)'); ?> إلى <?php echo(($_GET['Date2'] != '') ? $_GET['Date2'] : '(تاريخ اليوم)'); ?> </p>   <table width="100%" cellspacing="0" cellpadding="0">      <tr>
         <th scope="col"><p>م</p></th>
         <th scope="col"><p><?php echo get_gender_label('st', 'ال'); ?></p></th>
         <th scope="col"><p>الحلقة</p></th>
         <th scope="col"><p><?php echo get_gender_label('t', 'ال') ?></p></th>
         <th scope="col" colspan="3"><p>أخر اختبار مسجل</p></th>
      </tr>
      <?php
        //ertiqa  #############################################
//        mysqli_select_db($localhost, $database_localhost);
        /* $query_st_ertiqa = sprintf("SELECT st.StID,st.Stfullname,st.HName,t.Tfullname FROM view_0_students st
                               LEFT JOIN view_0_teachers t ON st.TID = t.TID
                               WHERE st.hide=0 AND StEdarah=%s
                               AND st.StID NOT IN (SELECT StID FROM er_ertiqaexams ex WHERE StID>0 %s %s ) order by st.HName",
             $row_edarat['id'],
             $Date1_Rs1,
             $Date2_Rs1);

         $st_ertiqa = mysqli_query($localhost, $query_st_ertiqa) or die('no_ertiqa.php 3 - ' . mysqli_error($localhost));
         $row_st_ertiqa = mysqli_fetch_assoc($st_ertiqa);
         $totalRows_st_ertiqa = mysqli_num_rows($st_ertiqa);*/

        $i = 1;
//        $total_money = 0;
        do {
//            $total_money += $row_st_ertiqa['Money'];
            $query_last_ertiqa = sprintf("SELECT ErtiqaID,FinalExamStatus,FinalExamDate FROM er_ertiqaexams WHERE StID =%s ORDER BY FinalExamDate DESC",
                $row_st_ertiqa['StID']
            );

            $last_ertiqa = mysqli_query($localhost, $query_last_ertiqa) or die('no_ertiqa.php 3 - last_ertiqa ' . mysqli_error($localhost));
            $row_last_ertiqa = mysqli_fetch_assoc($last_ertiqa);
            $totalRows_last_ertiqa = mysqli_num_rows($last_ertiqa);

            ?>
            <tr>
                <th><?php echo $i; ?></th>
                <td><?php echo get_student_name($row_st_ertiqa['StID']); ?></td>
                <td><?php echo $row_st_ertiqa['HName']; ?></td>
                <td><?php echo $row_st_ertiqa['Tfullname']; ?></td>
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
            <?php $i ++;
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
