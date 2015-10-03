<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php require_once('fahd_functions.php'); ?>
<?php $PageTitle = 'المسجلون في مسابقة المعلم المتميز'; ?>
<?php if (login_check('admin,ms,t3lem,edarh') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $Date1_Rs1 = 'and f.f_t_date>=' . str_replace('/', '', $_SESSION ['default_start_date']);
    if (isset($_POST['Date1'])) {
        if ($_POST['Date1'] != null) {
            $Date1_Rs1 = 'and f.f_t_date>=' . str_replace('/', '', $_POST['Date1']);
        }
    }
    $Date2_Rs1 = 'and f.f_t_date<=' . str_replace('/', '', $_SESSION ['default_end_date']);
    if (isset($_POST['Date2'])) {
        if ($_POST['Date2'] != null) {
            $Date2_Rs1 = 'and f.f_t_date<=' . str_replace('/', '', $_POST['Date2']);
        }
    }
    $sql_sex = sql_sex('e.sex');
    $edarh = '';
    if ($_SESSION['user_group'] == 'edarh') {
        $edarh = ' AND t_edarah=' . $_SESSION['user_id'] . ' ';
    }
    mysqli_select_db($localhost, $database_localhost);
    $query_ReRegistered = "SELECT f.auto_no,f.full_degree,f.f_t_date,f.f_1a_n,f.f_14a_n,t_edarah,
                            concat_ws(' ',t.TName1,t.TName2,t.TName3,t.TName4) as t_name,e.arabic_name ,approved
                            FROM ms_fahd_featured_teacher f
                            left join 0_teachers t on f.teacher_id = t.TID
                            left join 0_users e on f.t_edarah = e.id
                            where f.auto_no>0  $Date1_Rs1  $Date2_Rs1 $sql_sex $edarh
                            ORDER BY f.full_degree DESC";

    $ReRegistered = mysqli_query($localhost, $query_ReRegistered) or die(mysqli_error($localhost));
    $row_ReRegistered = mysqli_fetch_assoc($ReRegistered);
    $totalRows_ReRegistered = mysqli_num_rows($ReRegistered);

    ?>
    <?php include('../templates/header1.php'); ?>
<style>
@media print {
	.FieldsTitle{text-align:center;}
	#content{direction:rtl;}
	#WrapperFull,#content{background: none!important;}
	#printButton,#header,#PageTitle,#footer,#hideInPrint,#header0{display: none!important;}
body {direction: rtl;text-align: center;font-family: 'al_jass_zq',arial,tahoma;font-size: 16px;white-space:nowrap;}
}
#printButton {text-align: center;margin: 15px 0px;border-top: 2px dashed #2B9FBB;padding-top: 5px;}
</style>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle">
<?php echo $PageTitle; ?>
</div><!--PageTitle-->

 <div id="hideInPrint">
 <div class="content">
  <P>
  * للاستعلام عن  جميع المعلمين المسجلين، اترك التواريخ فارغة
  <br>
  * يمكنك الاستعلام بالتاريخ الأول فقط أو الثاني فقط أو بالجميع
  </P>
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
			<div class="four columns">
					<input name="submit" type="submit" class="full-width button-primary" id="submit" value="استعلام"/>
			</div>
			<input type="hidden" name="MM_show" value="form1">
		</form>
		</div>
 		</div>
<div class="content">
<div class="FieldsTitle">
			المسجلون في مسابقة المعلم المتميز <?php echo(($_POST['Date1'] == '' && $_POST['Date2'] == '') ? ' للعام الدراسي ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?> خلال الفترة من <?php echo(($_POST['Date1'] != '') ? $_POST['Date1'] : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?> إلى <?php echo(($_POST['Date2'] != '') ? $_POST['Date2'] : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </div>
<div class="CSSTableGenerator">
<table>
<tr>
	<td>م</td>
	<td>الاسم</td>
	<td>المجمع</td>
	<td>تاريخ التسجيل</td>
	<td>تقييم المشرف</td>
	<td>المسابقات الخارجية</td>
	<td>الدرجة الكبرى</td>
	<td>الاعتماد</td>
	<?php if ($_SESSION['user_group'] != 'edarh') { ?>
        <td>تعديل</td>
    <?php } ?>
</tr>
<?php
    $i = 1;
    do { ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row_ReRegistered['t_name']; ?></td>
            <td><?php echo $row_ReRegistered['arabic_name']; ?></td>
            <td><?php echo StringToDate($row_ReRegistered['f_t_date']); ?></td>
            <td><?php echo $row_ReRegistered['f_1a_n']; ?></td>
            <td><?php echo get_array_1($f_14a, $row_ReRegistered['f_14a_n']); ?></td>
            <td><?php echo $row_ReRegistered['full_degree']; ?></td>
            <td <?php if ($row_ReRegistered['approved'] == 1) echo 'class="bg_green"' ?>>
                <?php echo ($row_ReRegistered['approved'] == 1) ? 'معتمد' : 'غير معتمد'; ?>
            </td>
            <?php if ($_SESSION['user_group'] != 'edarh') { ?>
                <td><a href="/sys/fahd/featured_teacher_edit.php?auto_no=<?php echo $row_ReRegistered['auto_no']; ?>">تعديل</a></td>
            <?php } ?>
        </tr>
        <?php $i ++;
    } while ($row_ReRegistered = mysqli_fetch_assoc($ReRegistered)); ?>
</table>
		</div>
		<div id="printButton">
			<input class="button-primary"  type="button" value="طباعة" onclick="window.print()">
		</div>

</div><!--content-->
<?php include('../templates/footer.php'); ?>
    <?php
    mysqli_free_result($ReRegistered);
    ?>
<?php } else {
    include('../templates/restrict_msg.php');
} ?>