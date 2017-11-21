<?php
require_once('../../functions.php');
$PageTitle = 'نقاط المعلمين في المرتقيات';
if (login_check($all_groups) == true) {

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }
    $sql_sex = sql_sex('edarah_sex');
    $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', $_SESSION ['default_start_date']);
    if (isset($_POST['Date1'])) {
        if (Input::get('Date1') != null) {
            $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', Input::get('Date1'));
        }
    }
    $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', $_SESSION ['default_end_date']);
    if (isset($_POST['Date2'])) {
        if (Input::get('Date2') != null) {
            $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', Input::get('Date2'));
        }
    }

    $query_RsTPoints = sprintf("SELECT
							 O_TeacherName
							,O_Edarah
							,teacher_hide
							,SUM(ExamPoints) AS sumP
							,count(ExamPoints) AS countp
							  FROM view_er_ertiqaexams
							  WHERE FinalExamStatus=2 %s %s %s
							  GROUP BY TeacherID
							  ORDER BY O_Edarah,sum(ExamPoints) DESC",
        $Date1_Rs1,
        $Date2_Rs1,
        $sql_sex);

    $RsTPoints = mysqli_query($localhost, $query_RsTPoints) or die('RsTPoints 1 : ' . mysqli_error($localhost));
    $row_RsTPoints = mysqli_fetch_assoc($RsTPoints);
    $totalRows_RsTPoints = mysqli_num_rows($RsTPoints);

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
    <?php if ($totalRows_RsTPoints > 0) { // Show if recordset not empty ?>
        <div class="content CSSTableGenerator">
            <table border="1" cellpadding="0" cellspacing="0">
                <caption>اجمالي النقاط (حسب المعلم)</caption>
                <tr>
                    <td>اسم المعلم</td>
                    <td>المجمع</td>
                    <td>اجمالي النقاط</td>
                    <td>عدد الطلاب المجتازين</td>
                </tr>
                <?php do {
                    $hidden_string = $row_RsTPoints['teacher_hide'] > 0 ? ' <span style="color: #ff3835">(مطوي قيده)</span>' : '';
                    ?>


                    <tr>
                        <td><?php echo $row_RsTPoints['O_TeacherName'] . $hidden_string; ?></td>
                        <td><?php echo str_replace("مجمع ", "", $row_RsTPoints['O_Edarah']); ?></td>
                        <td><?php echo $row_RsTPoints['sumP']; ?></td>
                        <td><?php echo $row_RsTPoints['countp']; ?></td>
                    </tr>
                <?php } while ($row_RsTPoints = mysqli_fetch_assoc($RsTPoints)); ?>
            </table>
        </div>
    <?php }

    include('../../templates/footer.php');
    mysqli_free_result($RsTPoints);
} else {
    include('../../templates/restrict_msg.php');
}