<?php require_once('../../Connections/localhost.php'); ?>
<?php require_once('../../functions.php'); ?>
<?php require_once '../../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php
	$EdarahIDS=$_SESSION['user_id'];
?>
<?php include('../../templates/header1.php'); ?>
	<?php $PageTitle = 'الكشوفات'; ?>
	<title><?php echo $PageTitle; ?></title>
	</head>
	<body>
<?php include('../../templates/header2.php'); ?>
		<?php include ('../../templates/nav_menu.php'); ?>
		<div id="PageTitle"><?php echo $PageTitle; ?></div>
		<!--PageTitle-->
		  <div class="content lp">
			<?php if(login_check($all_groups) == true) { ?>
				<form name="form1" method="POST" action="redirect.php" data-validate="parsley">
				<div class="FieldsTitle">حدد التاريخ والحلقة</div>
				<div class="four columns alpha">
					<div class="LabelContainer">
						<label for="EdarahID"><?php echo get_gender_label('e','ال'); ?></label>
					</div>
						<?php create_edarah_combo();?>
				</div>
				<div class="four columns">
					<div class="LabelContainer">
						<label for="HalaqatID">الحلقة</label>
					</div>
						<select name="HalaqatID" class="FullWidthCombo" id="HalaqatID">
						<option VALUE>--</option>
					</select>
				</div>
				<div class="four columns omega">
					<div class="LabelContainer">
						<label for="kashf_date">تاريخ البداية</label>
					</div>
						<input name="kashf_date" type="text" id="kashf_date" data-required="true" zezo_date="true" >
				</div>
				<br class="clear" />
				<div class="four columns omega left">
                    <input name="absent" type="submit" class="button-primary"  value="كشف التحضير"/>
                </div>
                <div class="four columns omega left">
                    <input name="students_data" type="submit" class="button-primary" value="بيانات <?php echo get_gender_label('sts','ال'); ?>"/>
                </div>
			</form>
			<?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>

			</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#EdarahID').change(function() {
			ClearList('HalaqatID');
			FillList(this.value,'HalaqatID','AutoNo','HName','SELECT * FROM 0_halakat WHERE `hide`=0 and EdarahID = %s ORDER BY HName','لا يوجد حلقات','اختر الحلقة');
		});
		$('#EdarahID').trigger("change");
	});
	</script>
		
		<!--content-->
		<?php include('../../templates/footer.php'); ?>