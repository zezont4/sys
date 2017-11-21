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

$edarat = mysqli_query($localhost, $query_edarat) or die('maliah.php - 1 ' . mysqli_error($localhost));
$row_edarat = mysqli_fetch_assoc($edarat);
$totalRows_edarat = mysqli_num_rows($edarat);

$pageTitle = "أمر صرف";
$deptName = "الشؤون التعليمية (بنين) / برنامج الارتقاء";
if (isset($_SESSION['sex'])) {
    if ($_SESSION['sex'] == 0) {
        $deptName = "الشؤون التعليمية (بنات) / برنامج الارتقاء";
    }
}
$secondLogo = true;
$secondLogoURL = '<img class="ertiqafLogo" src="/sys/_images/ertiqa_160.png" width="140">';

require_once("../../templates/report_header1.php"); ?>
<div class="printButton">
    <label><input id="hide_m1" name="hide_m1" type="checkbox">اخفاء <?php echo get_gender_label('sts', '') ?> الإرتقاء</label>
    <label><input id="hide_m2" name="hide_m2" type="checkbox">اخفاء <?php echo get_gender_label('sts', '') ?> البراعم</label>
    <label><input id="hide_m3" name="hide_m3" type="checkbox">اخفاء حوافز <?php echo get_gender_label('ts', 'ال') ?> </label>
    <label><input id="hide_m4" name="hide_m4" type="checkbox">اخفاء حوافز <?php echo get_gender_label('e', 'ال') ?> </label>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
    $(function () {
        $("#hide_m1").click(function () {
            $(".m1").fadeToggle(300);
            recalculate();
        });
        $("#hide_m2").click(function () {
            $(".m2").fadeToggle(300);
            recalculate();
        });
        $("#hide_m3").click(function () {
            $(".t").fadeToggle(300);
            recalculate();
        });
        $("#hide_m4").click(function () {
            $(".e").fadeToggle(300);
            recalculate();
        });

        function recalculate() {
            var m = 0, b = 0, tb = 0, e = 0, all_total = 0;
            for (var y = 1; y < 40; y++) {
                if ($('#m1' + y).length !== 0) {
                    var n1, n2, n3, n4, n6;
                    n1 = ($('#m1' + y).html() != '-') ? $('#m1' + y).html() : 0;
                    n2 = ($('#m2' + y).html() != '-') ? $('#m2' + y).html() : 0;

                    n3 = ($('#t' + y).html() != '-') ? $('#t' + y).html() : 0;
                    n4 = ($('#e' + y).html() != '-') ? $('#e' + y).html() : 0;

                    if ($('#hide_m1').is(':checked')) {
                        m = 0;
                    } else {
                        m = n1;
                    }
                    if ($('#hide_m2').is(':checked')) {
                        b = 0;
                    } else {
                        b = n2;
                    }
                    if ($('#hide_m3').is(':checked')) {
                        tb = 0;
                    } else {
                        tb = n3;
                    }
                    if ($('#hide_m4').is(':checked')) {
                        tb = 0;
                    } else {
                        e = n4;
                    }

                    sum_row = parseInt(m, 10) + parseInt(b, 10) + parseInt(tb, 10) + parseInt(e, 10);
                    $('#sum_row' + y).html(sum_row);
                    all_total += parseInt(sum_row, 10);
                }
            }
            $('#all_total').html(all_total);
        }
    });
</script>
<?php require_once("../../templates/report_header2.php"); ?>
<div class="reportContent">
    <p class="report_description">أمر صرف جوائز الارتقاء والبراعم من
        الفترة <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : '(بداية النظام الإلكتروني في 1434/08/29 هـ)'); ?>
        إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : '(تاريخ اليوم)'); ?> </p>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th><p>م</p></th>
            <th><p>اسم</p>
                <p><?php echo get_gender_label('e', 'ال') ?></p></th>
            <th class="m1"><p>المرتقيات</p>
                <p>(<?php echo get_gender_label('sts', '') ?>)</p></th>
            <th class="t"><p>حوافز</p>
                <p>(<?php echo get_gender_label('ts', 'ال') ?>)</p></th>
            <th class="e"><p>حوافز</p>
                <p>(<?php echo get_gender_label('e', 'ال') ?>)</p></th>
            <th class="m2"><p>البراعم</p>
                <p>(<?php echo get_gender_label('sts', '') ?>)</p></th>
            <th><p>المجموع</p></th>
            <th><p>اسم <?php echo get_gender_label('mstlm', 'ال') ?></p></th>
            <th><p>توقيع</p>
                <p><?php echo get_gender_label('mstlm', 'ال') ?></p></th>
        </tr>
        <?php
        $i = 1;
        $s_money_total = 0;
        $t_money_total = 0;
        $e_money_total = 0;
        $bra3m_money_total = 0;
        $sum_all_total = 0;
        do {
//ertiqa  #############################################
            $query_rs_money = sprintf("SELECT x.EdarahID,count(sh.ExamNo) as count_st,sum(sh.Money) as s_money,sum(sh.teacher_money) as t_money,sum(sh.edarah_money) as e_money FROM er_shahadah sh left join er_ertiqaexams x on sh.ExamNo=x.AutoNo where x.EdarahID=%s %s %s ",
                $row_edarat['id'],
                $Date1_Rs1,
                $Date2_Rs1);

            $rs_money = mysqli_query($localhost, $query_rs_money) or die('maliah.php - 2 ' . mysqli_error($localhost));
            $row_rs_money = mysqli_fetch_assoc($rs_money);
            $totalRows_rs_money = mysqli_num_rows($rs_money);


//bra3m  #############################################
            $Date3_Rs1 = "";
            if (isset($_GET['Date1'])) {
                if (Input::get('Date1') != null) {
                    $Date3_Rs1 = 'and DDate>=' . str_replace('/', '', Input::get('Date1'));
                }
            }
            $Date4_Rs1 = "";
            if (isset($_GET['Date2'])) {
                if (Input::get('Date2') != null) {
                    $Date4_Rs1 = 'and DDate<=' . str_replace('/', '', Input::get('Date2'));
                }
            }
            $query_rs_bra3m_money = sprintf("SELECT sum(Money) as bra3m_money from er_bra3m where EdarahID=%s %s %s",
                $row_edarat['id'],
                $Date3_Rs1,
                $Date4_Rs1);

            $rs_bra3m_money = mysqli_query($localhost, $query_rs_bra3m_money) or die('maliah.php - 3 ' . mysqli_error($localhost));
            $row_rs_bra3m_money = mysqli_fetch_assoc($rs_bra3m_money);
            $totalRows_rs_bra3m_money = mysqli_num_rows($rs_bra3m_money);

            $sum_all = intval($row_rs_money['s_money']) + intval($row_rs_money['t_money']) + intval($row_rs_money['e_money']) + intval($row_rs_bra3m_money['bra3m_money']);


            $s_money_total += $row_rs_money['s_money'];
            $t_money_total += $row_rs_money['t_money'];
            $e_money_total += $row_rs_money['e_money'];
            $bra3m_money_total += $row_rs_bra3m_money['bra3m_money'];
            $sum_all_total += $sum_all;
            ?>
            <tr>
                <th><?php echo $i; ?></th>
                <td><?php echo $row_edarat['arabic_name']; ?></td>
                <td id="m1<?php echo $i; ?>" class="m1"><?php echo ($row_rs_money['s_money'] > 0) ? $row_rs_money['s_money'] : '-'; ?></td>
                <td id="t<?php echo $i; ?>" class="t"><?php echo ($row_rs_money['t_money']) ? $row_rs_money['t_money'] : '-'; ?></td>
                <td id="e<?php echo $i; ?>" class="e"><?php echo ($row_rs_money['e_money']) ? $row_rs_money['e_money'] : '-'; ?></td>
                <td id="m2<?php echo $i; ?>" class="m2"><?php echo ($row_rs_bra3m_money['bra3m_money']) ? $row_rs_bra3m_money['bra3m_money'] : '-'; ?></td>
                <th id="sum_row<?php echo $i; ?>"><?php echo ($sum_all > 0) ? $sum_all : '-'; ?></th>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <?php $i++;
        } while ($row_edarat = mysqli_fetch_assoc($edarat)); ?>
        <tr>
            <th colspan="2">المجموع</th>
            <th id="m1_total" class="m1"><?php echo $s_money_total; ?></th>
            <th id="t_total" class="t"><?php echo $t_money_total; ?></th>
            <th id="e_total" class="e"><?php echo $e_money_total; ?></th>
            <th id="m2_total" class="m2"><?php echo $bra3m_money_total; ?></th>
            <th id="all_total"><?php echo $sum_all_total; ?></th>
            <th></th>
            <th></th>
        </tr>
    </table>
</div>
<div class="reportFotter">
    <table class="no_border" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td>أمين الصندوق</td>
            <td>مدير الشؤون المالية والإدارية</td>
            <td>رئيس الجمعية</td>
        </tr>
        <tr>
            <td><?php echo $ameen_sondooq; ?></td>
            <td><?php echo $maliah_edariah; ?></td>
            <td><?php echo $raeesJam3iah; ?></td>
        </tr>
    </table>
</div>
</div>
</body>
</html>
