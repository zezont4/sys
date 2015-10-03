<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once("../../functions.php"); ?>
<?php
if (isset($_POST['EdarahID'])) {
  $EdarahID = $_POST['EdarahID'];
}
$HalaqatID=" ";
if (isset($_POST['HalaqatID'])) {
  if($_POST['HalaqatID']==null){
	$HalaqatID="";
  }else{
  	$HalaqatID =  "and AutoNo = ".$_POST['HalaqatID'];
  }
}
if (isset($_POST['kashf_date'])) {
  $h_date = $_POST['kashf_date'];
}

mysqli_select_db($localhost,$database_localhost);
$query_halakat = sprintf("SELECT AutoNo,HName from 0_halakat where hide=0 and EdarahID=%s %s order by HName",$EdarahID,$HalaqatID);
//zlog($query_halakat );
$halakat = mysqli_query($localhost,$query_halakat)or die('absent.php 1 - '.mysqli_error($localhost));
$row_halakat = mysqli_fetch_assoc($halakat);
$totalRows_halakat = mysqli_num_rows($halakat);

?>
<?php $pageTitle="كشف تحضير";?>
<?php require_once("../../templates/report_header1.php"); ?>
<style>
.reportContent {font-size:14px}
</style>
<?php do { 
mysqli_select_db($localhost,$database_localhost);
$query_teachers = sprintf("SELECT TID FROM 0_teachers where THalaqah=%s and hide=0",
							  $row_halakat['AutoNo']);
$teachers = mysqli_query($localhost,$query_teachers)or die('absent.php teacher - '.mysqli_error($localhost));
$row_teachers = mysqli_fetch_assoc($teachers);
$totalRows_teachers = mysqli_num_rows($teachers);


mysqli_select_db($localhost,$database_localhost);
$query_st_count = sprintf("SELECT StID,StEdarah,FatherMobileNo FROM 0_students where StHalaqah=%s and hide=0",
							  $row_halakat['AutoNo']);
$st_count = mysqli_query($localhost,$query_st_count)or die('absent.php students - '.mysqli_error($localhost));
$row_st_count = mysqli_fetch_assoc($st_count);
$totalRows_st_count = mysqli_num_rows($st_count);
if ($totalRows_st_count>0){
?>
<div class="reportWrapper">
	<div class="reportContent">
		<p class="report_description"> كشف تحضير - ( <?php echo $h_date; ?> )		</p>
			<table width="100%">
				<tr>
					<th><?php echo get_gender_label('e','ال'); ?></th>
					<td><?php echo get_edarah_name($row_st_count['StEdarah']); ?></td>
					<th>الحلقة</th>
					<td><?php echo $row_halakat['HName']; ?></td>
					<th><?php echo get_gender_label('t','ال'); ?></th>
					<td><?php echo get_teacher_name($row_teachers['TID']); ?></td>
				</tr>
			</table>
			<br>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<th><p>م</p></th>
				<th><p><?php echo get_gender_label('st','ال'); ?></p></th>
				<th><p>جوال الولي</p></th>
				<th><p>أحــد</p></th>
				<th><p>اثنين</p></th>
				<th><p>ثلاثاء</p></th>
				<th><p>أربعاء</p></th>
				<th><p>خميس</p></th>
				<th><p>ملاحظــــــــــات</p></th>
			</tr>
			<?php
//ertiqa  #############################################
mysqli_select_db($localhost,$database_localhost);
$query_st_money = sprintf("SELECT * FROM 0_students WHERE StHalaqah = %s and hide=0",
							  $row_halakat['AutoNo']);

$st_money = mysqli_query($localhost,$query_st_money)or die('absent.php 3 - '.mysqli_error($localhost));
$row_st_money = mysqli_fetch_assoc($st_money);
$totalRows_st_money = mysqli_num_rows($st_money);
	
	$i=1;
	//$total_money=0;
	do{
		//$total_money+=$row_st_money['Money'];
		?>
			<tr>
				<th><?php echo $i;?></th>
				<td><?php echo get_student_name($row_st_money['StID']);?></td>
				<td><?php echo $row_st_money['FatherMobileNo'];?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php $i++; } while ($row_st_money = mysqli_fetch_assoc($st_money)); ?>
<!--			<tr>
				<th colspan="6"><p>المجموع</p></th>
				<th><p><?php //echo $total_money;?></p></th>
				<th><p></p></th>
				</tr>
-->		</table>
	</div>
</div>
<div class="page-break"></div>
<?php } } while($row_halakat = mysqli_fetch_assoc($halakat)); ?>
</body>
</html>
<?php
mysqli_free_result($halakat);
mysqli_free_result($st_count);
?>