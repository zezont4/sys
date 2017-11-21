<?php require_once('../../functions.php');
$PageTitle = '(الارتقاء) الغياب والجودة حسب ' . get_gender_label('e', 'ال');
if (login_check($all_groups) == true) {
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', $_SESSION ['default_start_date']);
    $Date1 = isset($_GET['Date1']) ? $_GET['Date1'] : null;

    if ($Date1 != null) {
        $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', Input::get('Date1'));
    }

    $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', $_SESSION ['default_end_date']);
    $Date2 = isset($_GET['Date2']) ? $_GET['Date2'] : null;

    if ($Date2 != null) {
        $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', Input::get('Date2'));
    }

    $e_type = isset($_GET['e_type']) ? $_GET['e_type'] : null;
    if ($e_type != null) {
        $e_type = 'and e_type=' . $_GET['e_type'];
    }

    $sql_sex = sql_sex('edarah_sex');
    $query_Rs1 = sprintf("SELECT
					(count(AutoNo) - SUM(FinalExamStatus = 6)) as CountE
					,EdarahID
					,O_Edarah
					,(SUM(FinalExamDegree) / SUM(FinalExamStatus = 2)) as avg_degree
					,SUM(FinalExamStatus = 1) AS wait
					,SUM(FinalExamStatus = 2) AS successed
					,(SUM(FinalExamStatus = 2)*100/count(AutoNo)) AS success_percent
					,SUM(FinalExamStatus = 3) AS fieled
					,SUM(FinalExamStatus = 4) AS appsent
					,SUM(FinalExamStatus = 5) AS apsentWithCouse
					FROM `view_er_ertiqaexams`
					WHERE edarah_hide=0 %s and FinalExamDate>0 %s %s %s GROUP BY EdarahID order by success_percent desc,EdarahID",
        $e_type,
        $Date1_Rs1,
        $Date2_Rs1,
        $sql_sex);

    $Rs1 = mysqli_query($localhost, $query_Rs1) or die('Rs1 : ' . mysqli_error($localhost));
    $row_Rs1 = mysqli_fetch_assoc($Rs1);
    $totalRows_Rs1 = mysqli_num_rows($Rs1);
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
        <p>
            * للاستعلام عن السنة الدراسية الحالية، اترك التواريخ فارغة
            <br>
            * يمكنك الاستعلام بالتاريخ الأول فقط أو الثاني فقط أو بالجميع
        </p>
    </div>
<div class="content">
    <div class="FieldsTitle">تاريخ الاحصائية</div>
    <form name="form1" method="get" action="<?php echo $editFormAction; ?>">
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
    <?php //if ((isset($_GET["MM_show"])) && ($_GET["MM_show"] == "form1")) { ?>
    <?php if (isset($Date1_Rs1)) { ?>
        <br class="clear">
        <div class="sixteen">
            <br>
            <p>احصائية <?php echo(($Date1 == '' && $Date2 == '') ? ' للعام الدراسي ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?> خلال
                الفترة من <?php echo(($Date1 != '') ? $Date1 : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
                إلى <?php echo(($Date2 != '') ? $Date2 : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </p></div>
    <?php } ?>
    <?php if ($totalRows_Rs1 > 0) { // Show if recordset not empty ?>
</div>
<div class="content CSSTableGenerator">
    <div class="FieldsTitle">تقييم <?php echo get_gender_label('es', 'ال'); ?> في غياب <?php echo get_gender_label('sts', 'ال'); ?> والرسوب وجودة الحفظ</div>
    <?php
    $table1_head = '';
    $table2_head = '';
    $table1_body = '';
    $table2_body = '';
    $table1_head = '
			<table>
				<tr>
					<td nowrap>' . get_gender_label('e', 'ال') . '</td>
					<td>اجمالي<br>
						المختبرين</td>
					<td>' . get_array_1($statusName, 0) . '</td>
					<td>' . get_array_1($statusName, 2) . '</td>
					<td>' . get_array_1($statusName, 3) . '</td>
				</tr>';
    $table2_head = '
			<table>
				<tr>
					<td class="only_mobile">' . get_gender_label('e', 'ال') . '</td>
					<td>' . get_array_1($statusName, 4) . '<br>
						بدون عذر</td>
					<td>' . get_array_1($statusName, 5) . '</td>
					<td>نسبة الرسوب<br>والغياب</td>
					<td>متوسط نتيجة<br>الاختبارات</td>
				</tr>';

    do {
        $s1 = $row_Rs1['success_percent'];
        if ($s1 >= 80 && $s1 <= 100) {
            $cls1 = 'bg_green';
        }
        if ($s1 >= 60 && $s1 <= 79.99) {
            $cls1 = 'bg_yellow';
        }
        if ($s1 >= 0 && $s1 <= 59.99) {
            $cls1 = 'bg_red';
        }

        $s2 = $row_Rs1['avg_degree'];
        if ($s2 >= 92 && $s2 <= 100) {
            $cls2 = 'bg_green';
        }
        if ($s2 >= 84 && $s2 <= 91.99) {
            $cls2 = 'bg_yellow';
        }
        if ($s2 >= 0 && $s2 <= 83.99) {
            $cls2 = 'bg_red';
        }
        $table1_body = $table1_body . '
					<tr>
						<td nowrap>' . str_replace("مجمع ", "", $row_Rs1['O_Edarah']) . '</td>
						<td>' . $row_Rs1['CountE'] . '</td>
						<td>' . $row_Rs1['wait'] . '</td>
						<td>' . $row_Rs1['successed'] . '</td>
						<td>' . $row_Rs1['fieled'] . '</td>
					</tr>';
        $table2_body = $table2_body . '
					<tr>
						<td class="only_mobile">' . str_replace("مجمع ", "", $row_Rs1['O_Edarah']) . '</td>
						<td>' . $row_Rs1['appsent'] . '</td>
						<td>' . $row_Rs1['apsentWithCouse'] . '</td>
						<td class="' . $cls1 . '">' . round($row_Rs1['success_percent'], 2) . '</td>
						<td class="' . $cls2 . '">' . round($row_Rs1['avg_degree'], 2) . '</td>
					</tr>';
    } while ($row_Rs1 = mysqli_fetch_assoc($Rs1));
    echo '<div class="eight columns alpha omega">' . $table1_head . $table1_body . '</table></div>';
    echo '<div class="eight columns  alpha omega">' . $table2_head . $table2_body . '</table></div>';
} else { // Show if recordset not empty ?>
    <h1>لا توجد اختبارات حسب التاريخ المحدد</h1>
<?php } ?>
</div>
    <?php include('../../templates/footer.php');
} else {
    include('../../templates/restrict_msg.php');
}