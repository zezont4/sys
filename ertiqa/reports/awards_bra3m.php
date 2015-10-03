<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once("../../functions.php"); ?>
<?php require_once '../../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php
$Date1_Rs1 = "";
if (isset($_GET['Date1'])) {
    if ($_GET['Date1']!=null) {$Date1_Rs1 = 'and DDate>=' . str_replace('/','',$_GET['Date1']);}
}
$Date2_Rs1 = "";
if (isset($_GET['Date2'])) {
    if ($_GET['Date2']!=null) {$Date2_Rs1 = 'and DDate<=' . str_replace('/','',$_GET['Date2']);}
}
mysqli_select_db($localhost,$database_localhost);
$sql_sex=sql_sex('sex');
if ($_SESSION['user_group']!='edarh'){
    $query_edarat = sprintf("SELECT id,arabic_name from 0_users where hidden=0 %s and user_group='edarh' order by arabic_name",$sql_sex);
} else {
    $query_edarat = sprintf("SELECT id,arabic_name from 0_users where hidden=0 %s and user_group='edarh' and id = %s order by arabic_name",$sql_sex,GetSQLValueString($_SESSION['user_id'],"int"));
}
$edarat = mysqli_query($localhost,$query_edarat)or die('awards_braem.php 1 - '.mysqli_error($localhost));
$row_edarat = mysqli_fetch_assoc($edarat);
$totalRows_edarat = mysqli_num_rows($edarat);

?>
<?php
$pageTitle="مكافآت البراعم";
$deptName="الشؤون التعليمية (بنين) / برنامج الارتقاء";
if (isset($_SESSION['sex'])){
    if($_SESSION['sex']==0){
        $deptName="الشؤون التعليمية (بنات) / برنامج الارتقاء";
    }
}
$secondLogo=true;
$secondLogoURL='<img class="ertiqafLogo" src="/sys/_images/ertiqa_160.png" width="140">';
?>
<?php require_once("../../templates/report_header1.php"); ?>
<style>
    .reportContent {font-size:12px}
</style>
<?php do {
    mysqli_select_db($localhost,$database_localhost);
    $query_st_count = sprintf("SELECT StID FROM er_bra3m where EdarahID=%s %s %s ",
        $row_edarat['id'],
        $Date1_Rs1,
        $Date2_Rs1);

    $st_count = mysqli_query($localhost,$query_st_count)or die('awards_braem.php 2 - '.mysqli_error($localhost));
    $row_st_count = mysqli_fetch_assoc($st_count);
    $totalRows_st_count = mysqli_num_rows($st_count);
    if ($totalRows_st_count>0){
        ?>
        <?php require("../../templates/report_header2.php"); ?>
<div class="reportContent">
   <p class="report_description">تفاصيل مكافأت سلم البراعم  (<?php echo $row_edarat['arabic_name']; ?> )  خلال الفترة من <?php echo (($_GET['Date1']!='') ? $_GET['Date1'] : '(بداية النظام الإلكتروني في 1434/08/29 هـ)') ; ?> إلى <?php echo  (($_GET['Date2']!='') ? $_GET['Date2'] : '(تاريخ اليوم)') ; ?> </p>
   <table width="100%" cellspacing="0" cellpadding="0">
      <tr>
         <th scope="col"><p>م</p></th>
         <th scope="col"><p><?php echo get_gender_label('st','ال'); ?></p></th>
         <th scope="col"><p>الحلقة</p></th>
         <th scope="col"><p><?php echo get_gender_label('t','ال') ?></p></th>
         <th scope="col"><p>الدرجة</p></th>
         <th scope="col"><p>المكافأة</p></th>
         <th scope="col"><p>التوقيع</p></th>
      </tr>
      <?php
        //ertiqa  #############################################
        mysqli_select_db($localhost,$database_localhost);
        $query_st_money = sprintf("SELECT * FROM er_bra3m WHERE EdarahID = %s %s %s",
            $row_edarat['id'],
            $Date1_Rs1,
            $Date2_Rs1);

        $st_money = mysqli_query($localhost,$query_st_money)or die('awards_braem.php 3 - '.mysqli_error($localhost));
        $row_st_money = mysqli_fetch_assoc($st_money);
        $totalRows_st_money = mysqli_num_rows($st_money);

        $i=1;
        $total_money=0;
        do{
            $total_money+=$row_st_money['Money'];
            ?>
            <tr>
                <th><?php echo $i;?></th>
                <td><?php echo get_student_name($row_st_money['StID']);?></td>
                <td><?php echo get_halakah_name($row_st_money['HalakahID']);?></td>
                <td><?php echo get_teacher_name($row_st_money['TeacherID']);?></td>
                <td><?php echo $bra3mName[$row_st_money['DarajahID']];?></td>
                <td><?php echo $row_st_money['Money'];?></td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <?php $i++; } while ($row_st_money = mysqli_fetch_assoc($st_money)); ?>
      <tr>
         <th colspan="5"><p>المجموع</p></th>
         <th><p><?php echo $total_money;?></p></th>
         <th><p></p></th>
      </tr>
   </table>
   <div class="reportFotter">
		<table class="no_border"  width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>أمين الصندوق</td>
				<td>&nbsp;</td>
				<td>مدير الشؤون المالية والإدارية</td>
			</tr>
			<tr>
				<td><?php echo $ameen_sondooq;?></td>
				<td>&nbsp;</td>
				<td><?php echo $maliah_edariah;?></td>
			</tr>
		</table>
	</div>
</div>
</div>
<div class="page-break"></div>
<?php } } while($row_edarat = mysqli_fetch_assoc($edarat)); ?>
</body>
</html>
