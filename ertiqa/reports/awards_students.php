<?php require_once('../../functions.php');
$Date1_Rs1 = Input::get('Date1') ? 'and x.FinalExamDate>=' . str_replace('/', '', Input::get('Date1')) : '';
$Date2_Rs1 = Input::get('Date2') ? 'and x.FinalExamDate<=' . str_replace('/', '', Input::get('Date2')) : '';

$user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 1;

$sql_sex = sql_sex('sex');
if ($user_group != 'edarh') {
    $query_edarat = sprintf("SELECT id,arabic_name from 0_users where hidden=0 %s and user_group='edarh' order by arabic_name", $sql_sex);
} else {
    $query_edarat = sprintf("SELECT id,arabic_name from 0_users where hidden=0 %s and user_group='edarh' and id = %s order by arabic_name", $sql_sex, GetSQLValueString($user_id, "int"));
}
$edarat = mysqli_query($localhost, $query_edarat) or die('awards_st 1 .php - ' . mysqli_error($localhost));
$row_edarat = mysqli_fetch_assoc($edarat);
$totalRows_edarat = mysqli_num_rows($edarat);

?>
<?php
$pageTitle = get_gender_label('sts', 'جوائز ال');
$deptName = "الشؤون التعليمية (بنين) / برنامج الارتقاء";
if ($sex == 0) {
    $deptName = "الشؤون التعليمية (بنات) / برنامج الارتقاء";
}
$secondLogo = true;
$secondLogoURL = '<img class="ertiqafLogo" src="/sys/_images/ertiqa_160.png" width="140">';
?>
<?php require_once("../../templates/report_header1.php"); ?>
<style>
    .reportContent {
        font-size: 12px
    }
</style>
<?php do {
    $sql_sex = sql_sex('edarah_sex');
    $query_st_count = sprintf("SELECT x.StID FROM er_shahadah sh left join er_ertiqaexams x on sh.ExamNo=x.AutoNo where x.EdarahID=%s %s %s ",
        $row_edarat['id'],
        $Date1_Rs1,
        $Date2_Rs1);

    $st_count = mysqli_query($localhost, $query_st_count) or die('awards_st 2 .php - ' . mysqli_error($localhost));
    $row_st_count = mysqli_fetch_assoc($st_count);
    $totalRows_st_count = mysqli_num_rows($st_count);
    if ($totalRows_st_count > 0) {
        ?>
        <?php require("../../templates/report_header2.php"); ?>
        <div class="reportContent">
            <p class="report_description">تفاصيل جوائز <?php echo get_gender_label('sts', '') ?> <?php echo $row_edarat['arabic_name']; ?> ) خلال الفترة
                من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : '(بداية النظام الإلكتروني في 1434/08/29 هـ)'); ?>
                إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : '(تاريخ اليوم)'); ?> </p>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <th scope="col"><p>م</p></th>
                    <th scope="col"><p><?php echo get_gender_label('st', 'ال'); ?></p></th>
                    <th scope="col"><p>الحلقة</p></th>
                    <th scope="col"><p><?php echo get_gender_label('t', 'ال'); ?></p></th>
                    <th scope="col"><p>المرتقى</p></th>
                    <th scope="col"><p>التقدير</p></th>
                    <th scope="col"><p>المكافأة</p></th>
                    <th scope="col"><p>التوقيع</p></th>
                </tr>
                <?php
                //ertiqa  #############################################

                $query_st_money = sprintf("SELECT x.EdarahID,sh.Money,x.StID,x.TeacherID,x.HalakahID,x.ErtiqaID,sh.MarkName_Short FROM er_shahadah sh left join er_ertiqaexams x on sh.ExamNo=x.AutoNo where x.EdarahID=%s %s %s order by x.TeacherID",
                    $row_edarat['id'],
                    $Date1_Rs1,
                    $Date2_Rs1);

                $st_money = mysqli_query($localhost, $query_st_money) or die('awards_st 2 .php - ' . mysqli_error($localhost));
                $row_st_money = mysqli_fetch_assoc($st_money);
                $totalRows_st_money = mysqli_num_rows($st_money);

                $i = 1;
                $total_money = 0;
                do {
                    $total_money += $row_st_money['Money'];
                    ?>
                    <tr>
                        <th><?php echo $i; ?></th>
                        <td><?php echo get_student_name($row_st_money['StID']); ?></td>
                        <td><?php echo get_halakah_name($row_st_money['HalakahID']); ?></td>
                        <td><?php echo get_teacher_name($row_st_money['TeacherID']); ?></td>
                        <td><?php echo get_array_1($murtaqaName, $row_st_money['ErtiqaID']); ?></td>
                        <td><?php echo $row_st_money['MarkName_Short']; ?></td>
                        <td><?php echo $row_st_money['Money']; ?></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <?php $i++;
                } while ($row_st_money = mysqli_fetch_assoc($st_money)); ?>
                <tr>
                    <th colspan="6"><p>المجموع</p></th>
                    <th><p><?php echo $total_money; ?></p></th>
                    <th><p></p></th>
                </tr>
            </table>
            <table class="no_border" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>أمين الصندوق</td>
                    <td>&nbsp;</td>
                    <td>مدير الشؤون المالية والإدارية</td>
                </tr>
                <tr>
                    <td><?php echo $ameen_sondooq; ?></td>
                    <td>&nbsp;</td>
                    <td><?php echo $maliah_edariah; ?></td>
                </tr>
            </table>
        </div>
        </div>
        <div class="page-break"></div>
    <?php }
} while ($row_edarat = mysqli_fetch_assoc($edarat)); ?>
</body>
</html>
