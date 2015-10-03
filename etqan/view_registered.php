<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php $PageTitle = 'الطلاب المسجلون في مسابقة أمير الرياض'; ?>
<?php if (login_check('admin,ms,edarh') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $Date1_Rs1 = 'and RDate>=' . $_SESSION ['default_start_date'];
    if (isset($_POST['Date1'])) {
        if ($_POST['Date1'] != null) {
            $Date1_Rs1 = 'and RDate>=' . str_replace('/', '', $_POST['Date1']);
        }
    }
    $Date2_Rs1 = $Date2_Rs1 = 'and RDate<=' . $_SESSION ['default_end_date'];
    if (isset($_POST['Date2'])) {
        if ($_POST['Date2'] != null) {
            $Date2_Rs1 = 'and RDate<=' . str_replace('/', '', $_POST['Date2']);
        }
    }

    mysqli_select_db($localhost, $database_localhost);
    $sql_sex = sql_sex('u.sex');
    if ($_SESSION['user_group'] != 'edarh') {
        $query_ReRegistered = sprintf("SELECT *
FROM ms_etqan_rgstr et
LEFT JOIN 0_users u on et.EdarahID = u.id
where et.AutoNo>0 %s %s %s
ORDER BY et.EdarahID,et.MsbkhID,et.HalakahID",
            $Date1_Rs1,
            $Date2_Rs1,
            $sql_sex);
    } else {
        $query_ReRegistered = sprintf("SELECT *
FROM ms_etqan_rgstr
WHERE EdarahID = %s %s %s
ORDER BY EdarahID,MsbkhID,HalakahID",
            $_SESSION['user_id'],
            $Date1_Rs1,
            $Date2_Rs1);
    }

    $ReRegistered = mysqli_query($localhost, $query_ReRegistered) or die(mysqli_error($localhost));
    $row_ReRegistered = mysqli_fetch_assoc($ReRegistered);
    $totalRows_ReRegistered = mysqli_num_rows($ReRegistered);

    ?>
    <?php include('../templates/header1.php'); ?>
<style>

#printButton {
	text-align: center;
	margin: 15px 0px;
	border-top: 2px dashed #2B9FBB;
	padding-top: 5px;
}
</style>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?>
</div><!--PageTitle-->

 <div class="content">
 <div id="hideInPrint">
  <P>
  * للاستعلام عن  السنة الدراسية الحالية، اترك التواريخ فارغة
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
					<input name="submit" type="submit" class="button-primary" id="submit" value="استعلام"/>
			</div>
			<input type="hidden" name="MM_show" value="form1">
		</form>
		</div>
		</div>
		<div class="content CSSTableGenerator">
		<?php if (isset($Date1_Rs1)) { ?>
        <div class="FieldsTitle">المسجلون في مسابقة أمير الرياض <?php echo(($_POST['Date1'] == '' && $_POST['Date2'] == '') ? ' ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?> خلال الفترة من <?php echo(($_POST['Date1'] != '') ? $_POST['Date1'] : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?> إلى <?php echo(($_POST['Date2'] != '') ? $_POST['Date2'] : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </div>
    <?php } ?>
			<table>
				<tr>
					<td>م</td>
					<td>السجل المدني</td>
					<td>اسم <?php echo get_gender_label('st', 'ال'); ?></td>
					<td>الحلقة</td>
					<td>المجمع</td>
					<td>نوع المسابقة</td>
					<td>السنة الدراسية</td>
				</tr>
					<?php if ($totalRows_ReRegistered > 0) { // Show if recordset not empty ?>
        <?php $i = 1;
        do { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row_ReRegistered['StID']; ?></td>
                <td><?php echo get_student_name($row_ReRegistered['StID']); ?></td>
                <td><?php echo get_halakah_name($row_ReRegistered['HalakahID']); ?></td>
                <td><?php echo str_replace("مجمع ", "", get_edarah_name($row_ReRegistered['EdarahID'])); ?></td>
                <td><?php echo get_array_1($etqan_msbkh_type, $row_ReRegistered['MsbkhID']); ?></td>
                <td><?php echo get_array_1($SchoolLevelNameAll, $row_ReRegistered['SchoolLevelID']); ?></td>
            </tr>
            <?php $i ++;
        } while ($row_ReRegistered = mysqli_fetch_assoc($ReRegistered)); ?>
    <?php } else { ?>
        <td colspan="7">لايوجد طلاب مسجلون في مسابقة الإتقان حسب التاريخ المحدد</td>
    <?php } ?>
			</table>
		</div>
		<div id="printButton">
			<input class="button-primary"  type="button" value="طباعة" onclick="window.print()">
		</div>

</div><!--content-->
<?php include('../templates/footer.php');
} else {
    include('../templates/restrict_msg.php');
}