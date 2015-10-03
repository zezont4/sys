<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php $PageTitle = 'تفاصيل الاختبار'; ?>
<?php if(login_check("admin,er") == true) { ?>
<?php
$maxRows_RsN1N2N3N4 = 100;
$maxRows_RsN1N2N4 = 100;
$pageNum_RsN1N2N3N4 = 0;
if (isset($_GET['pageNum_RsN1N2N3N4'])) {
  $pageNum_RsN1N2N3N4 = $_GET['pageNum_RsN1N2N3N4'];
}
$startRow_RsN1N2N3N4 = $pageNum_RsN1N2N3N4 * $maxRows_RsN1N2N3N4;

$GetName1_RsN1N2N3N4 = "-1";
if (isset($_GET['Name1'])) {
  $GetName1_RsN1N2N3N4 = $_GET['Name1'];
}
$GetName2_RsN1N2N3N4 = "-1";
if (isset($_GET["Name2"])) {
  $GetName2_RsN1N2N3N4 = $_GET["Name2"];
}
$GetName3_RsN1N2N3N4 = "-1";
if (isset($_GET["Name3"])) {
  $GetName3_RsN1N2N3N4 = $_GET["Name3"];
}
$GetName4_RsN1N2N3N4 = "-1";
if (isset($_GET["Name4"])) {
  $GetName4_RsN1N2N3N4 = $_GET["Name4"];
}
mysqli_select_db($localhost,$database_localhost);
$query_RsN1N2N3N4 = sprintf("SELECT concat_ws(' ',Name1,Name2,Name3,Name4) as stFullName,TName,Halakah,Edarah,Murtaqa,Degree,Mark,StMoney,ExamDate,Mokhtaber FROM er_old_exam WHERE Name1 = %s  and Name2 = %s and Name3 = %s and Name4 = %s ORDER BY ExamDate",GetSQLValueString($GetName1_RsN1N2N3N4,"text"),GetSQLValueString($GetName2_RsN1N2N3N4,"text"),GetSQLValueString($GetName3_RsN1N2N3N4,"text"),GetSQLValueString($GetName4_RsN1N2N3N4,"text"));
$query_limit_RsN1N2N3N4 = sprintf("%s LIMIT %d,%d",$query_RsN1N2N3N4,$startRow_RsN1N2N3N4,$maxRows_RsN1N2N3N4);
$RsN1N2N3N4 = mysqli_query($localhost,$query_limit_RsN1N2N3N4)or die(mysqli_error($localhost));
$row_RsN1N2N3N4 = mysqli_fetch_assoc($RsN1N2N3N4);

if (isset($_GET['totalRows_RsN1N2N3N4'])) {
  $totalRows_RsN1N2N3N4 = $_GET['totalRows_RsN1N2N3N4'];
} else {
  $all_RsN1N2N3N4 = mysqli_query($localhost,$query_RsN1N2N3N4);
  $totalRows_RsN1N2N3N4 = mysqli_num_rows($all_RsN1N2N3N4);
}
$totalPages_RsN1N2N3N4 = ceil($totalRows_RsN1N2N3N4/$maxRows_RsN1N2N3N4)-1;

/****************************************/

$GetName1_RsN1N2N4 = "-1";
if (isset($_GET['Name1'])) {
  $GetName1_RsN1N2N4 = $_GET['Name1'];
}
$GetName2_RsN1N2N4 = "-1";
if (isset($_GET["Name2"])) {
  $GetName2_RsN1N2N4 = $_GET["Name2"];
}
$GetName3_RsN1N2N4 = "-1";
if (isset($_GET["Name3"])) {
  $GetName3_RsN1N2N4 = $_GET["Name3"];
}
$GetName4_RsN1N2N4 = "-1";
if (isset($_GET["Name4"])) {
  $GetName4_RsN1N2N4 = $_GET["Name4"];
}
mysqli_select_db($localhost,$database_localhost);
$query_RsN1N2N4 = sprintf("SELECT concat_ws(' ',Name1,Name2,Name3,Name4) as stFullName,TName,Halakah,Edarah,Murtaqa,Degree,Mark,StMoney,ExamDate,Mokhtaber FROM er_old_exam
						   WHERE Name1 = %s and Name2 = %s and Name3 <> %s and Name4 = %s ORDER BY ExamDate",
						    GetSQLValueString($GetName1_RsN1N2N4,"text"),
							GetSQLValueString($GetName2_RsN1N2N4,"text"),
							GetSQLValueString($GetName3_RsN1N2N4,"text"),
							GetSQLValueString($GetName4_RsN1N2N4,"text")
							);
//echo $query_RsN1N2N4;
//exit;							
$RsN1N2N4 = mysqli_query($localhost,$query_RsN1N2N4)or die(mysqli_error($localhost));
$row_RsN1N2N4 = mysqli_fetch_assoc($RsN1N2N4);
$totalRows_RsN1N2N4 = mysqli_num_rows($RsN1N2N4);

/****************************************/

$GetName1_RsN1N4 = "-1";
if (isset($_GET['Name1'])) {
  $GetName1_RsN1N4 = $_GET['Name1'];
}
$GetName2_RsN1N4 = "-1";
if (isset($_GET["Name2"])) {
  $GetName2_RsN1N4 = $_GET["Name2"];
}
$GetName3_RsN1N4 = "-1";
if (isset($_GET["Name3"])) {
  $GetName3_RsN1N4 = $_GET["Name3"];
}
$GetName4_RsN1N4 = "-1";
if (isset($_GET["Name4"])) {
  $GetName4_RsN1N4 = $_GET["Name4"];
}
mysqli_select_db($localhost,$database_localhost);
$query_RsN1N4 = sprintf("SELECT concat_ws(' ',Name1,Name2,Name3,Name4) as stFullName,TName,Halakah,Edarah,Murtaqa,Degree,Mark,StMoney,ExamDate,Mokhtaber FROM er_old_exam
						   WHERE Name1 = %s and Name2 <> %s and Name3 <> %s and Name4 = %s ORDER BY ExamDate",
						    GetSQLValueString($GetName1_RsN1N4,"text"),
							GetSQLValueString($GetName2_RsN1N4,"text"),
							GetSQLValueString($GetName3_RsN1N4,"text"),
							GetSQLValueString($GetName4_RsN1N4,"text")
							);
//echo $query_RsN1N4;
//exit;							
$RsN1N4 = mysqli_query($localhost,$query_RsN1N4)or die(mysqli_error($localhost));
$row_RsN1N4 = mysqli_fetch_assoc($RsN1N4);
$totalRows_RsN1N4 = mysqli_num_rows($RsN1N4);



?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'بحث عن الاختبارات السابقة'; ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include ('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->
<div class="content lp CSSTableGenerator">
		<?php echo get_st_info($_GET['StID']); ?>
		<br>
	<center>
	<h1>
	المرتقى المتقدم له :
	<?php echo $_GET["O_MurtaqaName"].' . '; ?>
	</h1>
	</center>
</div>
<div class="content CSSTableGenerator">
	<table>
	<caption>تشابه بالاسم الرباعي</caption>
		<tr>
			<td>اسم <?php echo get_gender_label('st','ال'); ?></td>
			<td>اسم المعلم</td>
			<td>الحلقة</td>
			<td>المجمع</td>
			<td>المرتقى</td>
			<td>الدرجة</td>
			<td>التقدير</td>
			<td>المكافأة</td>
			<td>تاريخ الاختبار</td>
			<td>المعلم المختبر</td>
		</tr>
		<?php do { ?>
			<tr>
				<td><?php echo $row_RsN1N2N3N4['stFullName']; ?></td>
				<td><?php echo $row_RsN1N2N3N4['TName']; ?></td>
				<td><?php echo $row_RsN1N2N3N4['Halakah']; ?></td>
				<td><?php echo $row_RsN1N2N3N4['Edarah']; ?></td>
				<td><?php echo $row_RsN1N2N3N4['Murtaqa']; ?></td>
				<td><?php echo $row_RsN1N2N3N4['Degree']; ?></td>
				<td><?php echo $row_RsN1N2N3N4['Mark']; ?></td>
				<td><?php echo $row_RsN1N2N3N4['StMoney']; ?></td>
				<td><?php echo substr($row_RsN1N2N3N4['ExamDate'],0,4)."/".substr($row_RsN1N2N3N4['ExamDate'],4,2)."/".substr($row_RsN1N2N3N4['ExamDate'],6,2) ; ?></td>
				<td><?php echo $row_RsN1N2N3N4['Mokhtaber']; ?></td>
			</tr>
			<?php } while ($row_RsN1N2N3N4 = mysqli_fetch_assoc($RsN1N2N3N4)); ?>
	</table>
	</div>
	<div class="content CSSTableGenerator">
	<table>
	<caption>تشابه بالاسم الثلاثي</caption>
		<tr>
			<td>اسم <?php echo get_gender_label('st','ال'); ?></td>
			<td>اسم المعلم</td>
			<td>الحلقة</td>
			<td>المجمع</td>
			<td>المرتقى</td>
			<td>الدرجة</td>
			<td>التقدير</td>
			<td>المكافأة</td>
			<td>تاريخ الاختبار</td>
			<td>المعلم المختبر</td>
		</tr>
		<?php do { ?>
			<tr>
				<td><?php echo $row_RsN1N2N4['stFullName']; ?></td>
				<td><?php echo $row_RsN1N2N4['TName']; ?></td>
				<td><?php echo $row_RsN1N2N4['Halakah']; ?></td>
				<td><?php echo $row_RsN1N2N4['Edarah']; ?></td>
				<td><?php echo $row_RsN1N2N4['Murtaqa']; ?></td>
				<td><?php echo $row_RsN1N2N4['Degree']; ?></td>
				<td><?php echo $row_RsN1N2N4['Mark']; ?></td>
				<td><?php echo $row_RsN1N2N4['StMoney']; ?></td>
				<td><?php echo substr($row_RsN1N2N4['ExamDate'],0,4)."/".substr($row_RsN1N2N4['ExamDate'],4,2)."/".substr($row_RsN1N2N4['ExamDate'],6,2) ; ?></td>
				<td><?php echo $row_RsN1N2N4['Mokhtaber']; ?></td>
			</tr>
			<?php } while ($row_RsN1N2N4 = mysqli_fetch_assoc($RsN1N2N4)); ?>
	</table>
	</div>
		<div class="content CSSTableGenerator">
	<table>
	<caption>تشابه بالاسم الثنائي</caption>
		<tr>
			<td>اسم <?php echo get_gender_label('st','ال'); ?></td>
			<td>اسم المعلم</td>
			<td>الحلقة</td>
			<td>المجمع</td>
			<td>المرتقى</td>
			<td>الدرجة</td>
			<td>التقدير</td>
			<td>المكافأة</td>
			<td>تاريخ الاختبار</td>
			<td>المعلم المختبر</td>
		</tr>
		<?php do { ?>
			<tr>
				<td><?php echo $row_RsN1N4['stFullName']; ?></td>
				<td><?php echo $row_RsN1N4['TName']; ?></td>
				<td><?php echo $row_RsN1N4['Halakah']; ?></td>
				<td><?php echo $row_RsN1N4['Edarah']; ?></td>
				<td><?php echo $row_RsN1N4['Murtaqa']; ?></td>
				<td><?php echo $row_RsN1N4['Degree']; ?></td>
				<td><?php echo $row_RsN1N4['Mark']; ?></td>
				<td><?php echo $row_RsN1N4['StMoney']; ?></td>
				<td><?php echo substr($row_RsN1N4['ExamDate'],0,4)."/".substr($row_RsN1N4['ExamDate'],4,2)."/".substr($row_RsN1N4['ExamDate'],6,2) ; ?></td>
				<td><?php echo $row_RsN1N4['Mokhtaber']; ?></td>
			</tr>
			<?php } while ($row_RsN1N4 = mysqli_fetch_assoc($RsN1N4)); ?>
	</table></div>


<?php include('../templates/footer.php'); ?>
<?php
mysqli_free_result($RsN1N2N3N4);

mysqli_free_result($RsN1N2N4);
?>
<?php }else{include('../templates/restrict_msg.php');}?>