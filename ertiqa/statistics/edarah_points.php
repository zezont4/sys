<?php require_once('../../functions.php');
$PageTitle = 'نقاط الإدارات في المرتقيات';
if (login_check($all_groups) == true) {

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', $_SESSION ['default_start_date']);
    if (isset($_POST['Date1'])) {
        if (Input::get('Date1') != null) {
            $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', Input::get('Date1'));
        }
    }
    $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', $_SESSION ['default_end_date']);
    if (isset($_POST['Date2'])) {
        if ($_POST['Date2'] != null) {
            $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', Input::get('Date2'));
        }
    }
    $sql_sex = sql_sex('edarah_sex');
    $query_RsEPoints = sprintf("SELECT
							  O_Edarah
							,EdarahID
							,SUM(ExamPoints) as sumP
							,count(ExamPoints) as countp
							  FROM view_er_ertiqaexams
							  where edarah_hide=0 %s and FinalExamStatus=2 %s %s 
							  group by EdarahID 
							  order by sum(ExamPoints) desc",
        $sql_sex,
        $Date1_Rs1,
        $Date2_Rs1);

    $RsEPoints = mysqli_query($localhost, $query_RsEPoints) or die('RsEPoints 2 : ' . mysqli_error($localhost));
    $row_RsEPoints = mysqli_fetch_assoc($RsEPoints);
    $totalRows_RsEPoints = mysqli_num_rows($RsEPoints);
    ?>
    <?php include('../../templates/header1.php'); ?>

    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/cupertino/jquery-ui.css"/>
    <title><?php echo $PageTitle; ?></title>
    </head>
    <body>
    <?php include('../../templates/header2.php'); ?>
    <?php include('../../templates/nav_menu.php'); ?>
    <div id="PageTitle">
        <?php echo $PageTitle; ?>
    </div><!--PageTitle-->

    <div class="content">
        <P>
            * للاستعلام عن السنة الدراسية الحالية، اترك التواريخ فارغة
            <br>
            * يمكنك الاستعلام بالتاريخ الأول فقط أو الثاني فقط أو بالجميع
        </P>
    </div>
    <div class="content">
        <div class="FieldsTitle">تاريخ الاحصائية</div>
        <form name="form1" method="post" action="<?php echo $editFormAction; ?>">
            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="Date1">التاريخ الأول</label>
                </div>
                <input type="text" name="Date1" id="Date1" zezo_date="true">
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="Date2">التاريخ الثاني</label>
                </div>
                <input type="text" name="Date2" id="Date2" zezo_date="true">
            </div>
            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="استعلام"/>
            </div>
            <input type="hidden" name="MM_show" value="form1">
        </form>
        <?php //if ((isset($_POST["MM_show"])) && ($_POST["MM_show"] == "form1")) { ?>
        <?php if (isset($Date1_Rs1)) { ?>
            <br class="clear">
            <div class="sixteen">
                <br>
                <p>احصائية <?php echo((Input::get('Date1') == '' && Input::get('Date2') == '') ? ' للعام الدراسي ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?> خلال الفترة
                    من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
                    إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </p></div>
        <?php } ?>
    </div>
    <?php if ($totalRows_RsEPoints > 0) { // Show if recordset not empty ?>

<div class="content CSSTableGenerator">
    <div class="FieldsTitle">اجمالي النقاط (حسب <?php echo get_gender_label('e', 'ال'); ?>)</div>


    <table>
    <tr>
        <td nowrap><?php echo get_gender_label('e', 'ال'); ?></td>
        <td>اجمالي النقاط</td>
        <td>متوسط النقاط</td>
        <td>اجمالي <?php echo get_gender_label('sts', '') . ' ' . get_gender_label('e', 'ال'); ?> </td>
        <td class="only_mobile"><?php echo get_gender_label('e', 'ال'); ?></td>
        <td>عدد <?php echo get_gender_label('najeh', 'ال'); ?></td>
        <td>نسبة <?php echo get_gender_label('mks', 'ال'); ?> من اجمالي <?php echo get_gender_label('sts', 'ال'); ?></td>
        <td>الدرجة النهائية</td>
    </tr>
    <?php
    $reordered_result = [];
    $array_index = 0;
    do {
        $query_RsEdarahCount = sprintf("SELECT
							  count(StID) as countS
							  FROM 0_students
							  where hide=0 and StEdarah=%s",
            $row_RsEPoints['EdarahID']);
        //echo $query_RsEdarahCount;
        $RsEdarahCount = mysqli_query($localhost, $query_RsEdarahCount) or die('RsEdarahCount : ' . mysqli_error($localhost));
        $row_RsEdarahCount = mysqli_fetch_assoc($RsEdarahCount);
        $totalRows_RsEdarahCount = mysqli_num_rows($RsEdarahCount);

        //متوسط النقاط
        $neqat = round($row_RsEPoints['sumP'] / $row_RsEPoints['countp'], 2);
        //نسبة المختبرين
        $percent1 = round($row_RsEPoints['countp'] * 100 / $row_RsEdarahCount['countS'], 2);
        //النسبة النهائية
        $percent2 = round($neqat * $percent1, 2);

        $reordered_result[$array_index]['0_Edarah'] = str_replace("مجمع ", "", $row_RsEPoints['O_Edarah']);
        $reordered_result[$array_index]['sumP'] = $row_RsEPoints['sumP'];
        $reordered_result[$array_index]['neqat'] = $neqat;
        $reordered_result[$array_index]['countS'] = $row_RsEdarahCount['countS'];
        $reordered_result[$array_index]['countp'] = $row_RsEPoints['countp'];
        $reordered_result[$array_index]['percent1'] = $percent1;
        $reordered_result[$array_index]['percent2'] = $percent2;
        $array_index++;
    } while ($row_RsEPoints = mysqli_fetch_assoc($RsEPoints));
    usort($reordered_result, function ($a, $b) {
        return $a['percent2'] < $b['percent2'];
    });
    for ($i = 0; $i < count($reordered_result); $i++) {
        ?>
        <tr>
            <td nowrap><?php echo $reordered_result[$i]['0_Edarah']; ?></td>
            <td><?php echo $reordered_result[$i]['sumP']; ?></td>
            <td><?php echo $reordered_result[$i]['neqat']; ?></td>
            <td><?php echo $reordered_result[$i]['countS']; ?></td>
            <td><?php echo $reordered_result[$i]['countp']; ?></td>
            <td><?php echo $reordered_result[$i]['percent1']; ?></td>
            <td><?php echo $reordered_result[$i]['percent2']; ?></td>
        </tr>
        <?php
    }
}
    while ($row_RsEPoints = mysqli_fetch_assoc($RsEPoints)) ;
    ?>
    </table>
</div>

    <?php include('../../templates/footer.php');
} else {
    include('../../templates/restrict_msg.php');
}