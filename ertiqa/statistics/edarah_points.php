<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once('../../functions.php'); ?>
<?php require_once '../../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php $PageTitle = 'نقاط الإدارات في المرتقيات'; ?>
<?php if(login_check($all_groups) == true) { ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/','',$_SESSION ['default_start_date']);
if (isset($_POST['Date1'])) {
  if ($_POST['Date1']!=null) {$Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/','',$_POST['Date1']);}
}
$Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/','',$_SESSION ['default_end_date']);
if (isset($_POST['Date2'])) {
  if ($_POST['Date2']!=null) {$Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/','',$_POST['Date2']);}
}
mysqli_select_db($localhost,$database_localhost);
$sql_sex=sql_sex('edarah_sex');
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

$RsEPoints = mysqli_query($localhost,$query_RsEPoints)or die('RsEPoints 2 : '.mysqli_error($localhost));
$row_RsEPoints = mysqli_fetch_assoc($RsEPoints);
$totalRows_RsEPoints = mysqli_num_rows($RsEPoints);
?>
<?php include('../../templates/header1.php'); ?>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/cupertino/jquery-ui.css"/>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../../templates/header2.php'); ?>
<?php include ('../../templates/nav_menu.php'); ?>
<div id="PageTitle">
<?php echo $PageTitle; ?>
</div><!--PageTitle-->

 <div class="content">
 <P>
  * للاستعلام عن  السنة الدراسية الحالية، اترك التواريخ فارغة
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
			<p>احصائية <?php echo (($_POST['Date1']=='' && $_POST['Date2']=='') ? ' للعام الدراسي ( '.$_SESSION ['default_year_name'].' ) ': '' );?> خلال الفترة من <?php echo (($_POST['Date1']!='') ? $_POST['Date1'] : StringToDate($_SESSION ['default_start_date']).' هـ ') ; ?> إلى <?php echo  (($_POST['Date2']!='') ? $_POST['Date2'] : StringToDate($_SESSION ['default_end_date']).' هـ ') ; ?> </p></div>
			<?php } ?>
</div>
 <?php if ($totalRows_RsEPoints > 0) { // Show if recordset not empty ?>
		
		<div class="content CSSTableGenerator">
		<div class="FieldsTitle">اجمالي النقاط (حسب <?php echo get_gender_label('e','ال'); ?>)</div>
			<?php
			$table1_head='';$table2_head='';$table1_body='';$table2_body='';
			$table1_head='
			<table>
				<tr>
					<td nowrap>'.get_gender_label('e','ال').'</td>
					<td>اجمالي النقاط</td>
					<td>متوسط النقاط</td>
					<td>اجمالي '.get_gender_label('sts','').' '.get_gender_label('e','ال').'</td>
				</tr>';
			$table2_head='
			<table>
				<tr>
					<td class="only_mobile">'.get_gender_label('e','ال').'</td>
					<td>عدد '.get_gender_label('najeh','ال').'</td>
					<td>نسبة '.get_gender_label('mks','ال').' من اجمالي '.get_gender_label('sts','ال').'</td>
					<td>الدرجة النهائية</td>
				</tr>';
				 do { 
				$query_RsEdarahCount = sprintf("SELECT
							  count(StID) as countS
							  FROM 0_students
							  where hide=0 and StEdarah=%s",
							  $row_RsEPoints['EdarahID']);
				//echo $query_RsEdarahCount;
				$RsEdarahCount = mysqli_query($localhost,$query_RsEdarahCount)or die('RsEdarahCount : '.mysqli_error($localhost));
				$row_RsEdarahCount = mysqli_fetch_assoc($RsEdarahCount);
				$totalRows_RsEdarahCount = mysqli_num_rows($RsEdarahCount);

				//متوسط النقاط 
				$neqat = round($row_RsEPoints['sumP']   / $row_RsEPoints['countp'],2);
				//نسبة المختبرين
				$percent1 = round($row_RsEPoints['countp'] * 100 / $row_RsEdarahCount['countS'],2);
				//النسبة النهائية
				$percent2 = round($neqat * $percent1,2);
				
				$table1_body=$table1_body.'
					<tr>
						<td nowrap>'.str_replace("مجمع ","",$row_RsEPoints['O_Edarah']).'</td>
						<td>'.$row_RsEPoints['sumP'].'</td>
						<td>'.$neqat.'</td>
						<td>'.$row_RsEdarahCount['countS'].'</td>
					</tr>';
				$table2_body=$table2_body.'
					<tr>
						<td class="only_mobile">'.str_replace("مجمع ","",$row_RsEPoints['O_Edarah']).'</td>
						<td>'.$row_RsEPoints['countp'].'</td>
						<td>'.$percent1.'</td>
						<td>'.$percent2.'</td>
					</tr>';
					 } while ($row_RsEPoints = mysqli_fetch_assoc($RsEPoints)); 
				echo '<div class="eight columns alpha omega">'.$table1_head.$table1_body.'</table></div>';
				echo '<div class="eight columns alpha omega">'.$table2_head.$table2_body.'</table></div>';
			?>
		</div>
		<?php } ?>
		

<?php include('../../templates/footer.php'); ?>
<?php mysqli_free_result($RsEPoints);?>
<?php }else{include('../../templates/restrict_msg.php');}?>