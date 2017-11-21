<?php require_once("../../functions.php");
$Date1_Rs1 = "";
if (isset($_GET['Date1'])) {
    if (Input::get('Date1') != null) {
        $Date1_Rs1 = 'and x.FinalExamDate>=' . str_replace('/', '', Input::get('Date1'));
    }
}
$Date2_Rs1 = "";
if (isset($_GET['Date2'])) {
    if (Input::get('Date2') != null) {
        $Date2_Rs1 = 'and x.FinalExamDate<=' . str_replace('/', '', Input::get('Date2'));
    }
}
$sql_sex = sql_sex('sex');
$query_edarat = sprintf("SELECT id,arabic_name from 0_users where hidden=0 %s and user_group='edarh' order by arabic_name", $sql_sex);

$edarat = mysqli_query($localhost, $query_edarat) or die('awards_st 1 .php - ' . mysqli_error($localhost));
$row_edarat = mysqli_fetch_assoc($edarat);
$totalRows_edarat = mysqli_num_rows($edarat);

?>
<?php
$pageTitle = get_gender_label('es', 'حوافز ال');
$deptName = "الشؤون التعليمية (بنين) / برنامج الارتقاء";
if (isset($_SESSION['sex'])) {
    if ($_SESSION['sex'] == 0) {
        $deptName = "الشؤون التعليمية (بنات) / برنامج الارتقاء";
    }
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
    $query_teacher_count = sprintf("SELECT x.TeacherID FROM er_shahadah sh left join er_ertiqaexams x on sh.ExamNo=x.AutoNo where x.EdarahID=%s %s %s ",
        $row_edarat['id'],
        $Date1_Rs1,
        $Date2_Rs1);

    $teacher_count = mysqli_query($localhost, $query_teacher_count) or die('awards_st 2 .php - ' . mysqli_error($localhost));
    $row_teacher_count = mysqli_fetch_assoc($teacher_count);
    $totalRows_teacher_count = mysqli_num_rows($teacher_count);
    if ($totalRows_teacher_count > 0) {
        ?>
        <?php require("../../templates/report_header2.php"); ?>
        <div class="reportContent">
            <p class="report_description">تفاصيل حوافز <?php echo get_gender_label('e', 'ال') ?> لـ ( <?php echo $row_edarat['arabic_name']; ?> ) خلال الفترة
                من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : '(بداية النظام الإلكتروني في 1434/08/29 هـ)'); ?>
                إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : '(تاريخ اليوم)'); ?> </p>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <th scope="col"><p>م</p></th>
                    <th scope="col"><p>اسم <?php echo get_gender_label('e', 'ال') ?></p></th>
                    <th scope="col"><p>عدد <?php echo get_gender_label('najeh', 'ال') ?></p></th>
                    <th scope="col"><p> <?php echo get_gender_label('e', 'حوافز ال') ?></p></th>
                    <th scope="col"><p>التوقيع</p></th>
                </tr>
                <?php
                //ertiqa  #############################################

                $query_teacher_money = sprintf("SELECT x.EdarahID,x.TeacherID,count(x.TeacherID) as count_success,sum(sh.teacher_money) as sum_teacher,sum(sh.edarah_money) as sum_edarah FROM er_shahadah sh left join er_ertiqaexams x on sh.ExamNo=x.AutoNo where x.EdarahID=%s %s %s group by x.EdarahID",
                    $row_edarat['id'],
                    $Date1_Rs1,
                    $Date2_Rs1);

                $teacher_money = mysqli_query($localhost, $query_teacher_money) or die('awards_st 2 .php - ' . mysqli_error($localhost));
                $row_teacher_money = mysqli_fetch_assoc($teacher_money);
                $totalRows_teacher_money = mysqli_num_rows($teacher_money);

                $i = 1;
                $count_success = 0;
                $sum_edarah = 0;
                do {
                    $count_success += $row_teacher_money['count_success'];
                    $sum_edarah += $row_teacher_money['sum_edarah'];
                    ?>
                    <tr>
                        <th><?php echo $i; ?></th>
                        <td><?php echo get_edarah_name($row_teacher_money['EdarahID']); ?></td>
                        <td><?php echo $row_teacher_money['count_success']; ?></td>
                        <td><?php echo $row_teacher_money['sum_edarah']; ?></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <?php $i++;
                } while ($row_teacher_money = mysqli_fetch_assoc($teacher_money)); ?>
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
        </div>
        <div class="page-break"></div>
    <?php }
} while ($row_edarat = mysqli_fetch_assoc($edarat)); ?>
</body>
</html>