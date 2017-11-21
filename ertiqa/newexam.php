<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php
$userType = 0;
if (isset($_SESSION['user_group'])) {
	$userType = $_SESSION['user_group'];
}
?>
<?php
$EdarahIDS = $_SESSION['user_id'];
?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

//نبيه المستخدم لإضافة المرحلة الدراسية للطالب
$school_level_message = null;
mysqli_select_db($localhost, $database_localhost);
$StID = isset($_GET['StID']) ? $_GET['StID'] : null;
$query = sprintf("SELECT StID,StEdarah,school_level FROM 0_students WHERE StID=%s",
	GetSQLValueString($StID, "double"));
$rs = mysqli_query($localhost, $query) or die('$query : ' . mysqli_error($localhost));
$row = mysqli_fetch_assoc($rs);
$rows_count = mysqli_num_rows($rs);
if ($row['school_level'] === '' || $row['school_level'] === NULL) {
	$school_level_message = implode([
		'<p class="note">',
		'عفوا، يجب تحديد المرحلة الدراسية',
		get_gender_label('st', 'لل'),
		'قبل حجز الموعد',
		'<br>',
		'<a href="../basic/student_edit.php?StID=' . $row['StID']  . '" >اضغط هنا لتعديل بياناته الأساسية</a>',
		'</p>',
	], ' ');
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	// search for duplicate exams ##############
	mysqli_select_db($localhost, $database_localhost);
	$query_Rsdublicate = sprintf("SELECT AutoNo,FinalExamStatus FROM er_ertiqaexams WHERE StID=%s and ErtiqaID=%s and (FinalExamStatus=0 or FinalExamStatus=1 or FinalExamStatus=2)",
		GetSQLValueString($_POST['StID'], "double"),
		GetSQLValueString($_POST['MurtaqaID'], "int"));
	//zlog($query_Rsdublicate);
	$Rsdublicate = mysqli_query($localhost, $query_Rsdublicate) or die(mysqli_error($localhost));
	$row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
	$totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);
	if ($totalRows_Rsdublicate > 0) { ?>
		<?php include('../templates/header1.php'); ?>
		<br><br><br>
		<div style="direction:rtl;text-align:center;font-size:22px;padding: 10px;line-height: 46px">
			<p>
				لقد تم حجز موعد <?php echo get_gender_label('st', 'لل'); ?> سابقا ولنفس المرتقى، والنتيجة المسجلة للموعد السابق هي :
				<?php echo " " . get_array_1($statusName, $row_Rsdublicate["FinalExamStatus"]); ?>
			</p>
		</div>
		<?php exit;
	}

	// تنبيه المستخدم لإضافة رقم جوال الطالب لمرتقى الفاتحة
	mysqli_select_db($localhost, $database_localhost);
	$query = sprintf("SELECT StMobileNo FROM 0_students WHERE StID=%s",
		GetSQLValueString($_POST['StID'], "double"));
	$rs = mysqli_query($localhost, $query) or die('$query : ' . mysqli_error($localhost));
	$row = mysqli_fetch_assoc($rs);
	$rows_count = mysqli_num_rows($rs);
	if ($_POST['MurtaqaID'] == 9 && ($row['StMobileNo'] == '' || $row['StMobileNo'] == NULL)) { ?>
		<?php include('../templates/header1.php'); ?>
		<br><br><br>
		<div style="direction:rtl;text-align:center;font-size:22px;padding: 10px;line-height: 46px">
			<p>
				<?php
				echo implode([
					'يجب إضافة رقم جوال خاص',
					get_gender_label('st', 'بال'),
					'قبل حجز الموعد',
					'<br>',
					'وإذا كان',
					get_gender_label('st', 'ال'),
					'ليس لديه رقم جوال، فيرجى تسجيل رقم جوال ولي الأمر في خانة رقم جوال الطالب',
					'<br>',
					'<a href="../basic/student_edit.php?StID=' . $row['StID'] . '" >اضغط هنا لتعديل بياناته الأساسية</a>',
				], ' ');
				?>
			</p>
		</div>
		<?php exit;
	}

// تنبيه المستخدم لتحديد الجنسية لطلاب مرتقى الفاتحة
	mysqli_select_db($localhost, $database_localhost);
	$query = sprintf("SELECT StID,nationality FROM 0_students WHERE StID=%s",
		GetSQLValueString($_POST['StID'], "double"));
	$rs = mysqli_query($localhost, $query) or die('$query : ' . mysqli_error($localhost));
	$row = mysqli_fetch_assoc($rs);
	$rows_count = mysqli_num_rows($rs);
	if ($_POST['MurtaqaID'] == 9 && ($row['nationality'] == '' || $row['nationality'] == NULL)) { ?>
		<?php include('../templates/header1.php'); ?>
		<br><br><br>
		<div style="direction:rtl;text-align:center;font-size:22px;padding: 10px;line-height: 46px">
			<p>
				<?php
				echo implode([
					'يجب تحديد جنسية',
					get_gender_label('st', 'ال'),
					'قبل حجز الموعد',
					'<br>',
					'<a href="../basic/student_edit.php?StID=' . $row['StID'] . '" >اضغط هنا لتعديل بياناته الأساسية</a>',
				], ' ');
				?>
			</p>
		</div>
		<?php exit;
	}


	if (login_check("edarh") == true) {
		$note = '<span style="color: red">عند وجود ملاحظات على ما ورد أعلاه، فنرجوا التواصل مع الدعم الفني</span>';
		// إذا رسب الطالب فإنه لا يسمح له بإعادة الإختبار إلى بعد اسبوعين ##############
		mysqli_select_db($localhost, $database_localhost);
		$query_rs_fail = sprintf("SELECT AutoNo,StID,FinalExamDate FROM er_ertiqaexams WHERE StID=%s and ErtiqaID=%s and FinalExamStatus=3",
			GetSQLValueString($_POST['StID'], "double"),
			GetSQLValueString($_POST['MurtaqaID'], "int"));
		//zlog($query_rs_fail);
		$rs_fail = mysqli_query($localhost, $query_rs_fail) or die(mysqli_error($localhost));
		$row_rs_fail = mysqli_fetch_assoc($rs_fail);
		$total_rows_rs_fail = mysqli_num_rows($rs_fail);
		if ($total_rows_rs_fail > 0) {
			$today = getHijriDate()->date;

			$date_diff = dateDiffHijri($row_rs_fail['FinalExamDate'], $today);
//			echo($date_diff);
//			exit();
			$max_allowed_days = 14;
			if ($date_diff < $max_allowed_days) {
				include('../templates/header1.php'); ?>
				<br><br><br>
				<div style="direction:rtl;text-align:center;font-size:22px;padding: 10px;line-height: 46px">
					<p>
						<?php echo implode(' ', [
							'عفوا ،  آخر اختبار ',
							get_gender_label('st', 'لل'),
							':',
							get_student_name($row_rs_fail['StID']),
							'كان قبل',
							$date_diff,
							'يوم / أيام وذلك في تاريخ',
							StringToDate($row_rs_fail['FinalExamDate']),
							'هـ',
							'<br>',
							'ونتيجة الإختبار هي :',
							get_array_1($statusName, 3),
							'<br>',
							'والمدة النظامية لإعادة اختبار  من يرسب هي أسبوعان ',
							'<br>',
							'ولا يمكنكم الحجز الآن لعدم اكتمال المدة النظامية حسب لائحة المرتقيات',
							'<br>',
							'وسيسمح لكم بالحجز بعد',
							$max_allowed_days - $date_diff,
							'يوما / أيام وذلك يوم',
							$day_name[getHijriDateAfter($today, $max_allowed_days - $date_diff)->day],
							'الموافق',
							StringToDate(getHijriDateAfter($today, $max_allowed_days - $date_diff)->date),
							'هـ',
							'<br>',
							'<br>',
							$note,
						]);
						?>
					</p>
				</div>
				<?php exit;
			}
		}

		// إذا اجتاز الطالب في أخر اختبار ويرغب باختبار المرتقى التالي قبل ٣٠ يوم ##############
		mysqli_select_db($localhost, $database_localhost);
		$query_rs_fail = sprintf("SELECT AutoNo,StID,FinalExamDate FROM er_ertiqaexams WHERE StID=%s and ErtiqaID=%s and FinalExamStatus=2",
			GetSQLValueString($_POST['StID'], "double"),
			GetSQLValueString($_POST['MurtaqaID'] - 1, "int"));
		$rs_fail = mysqli_query($localhost, $query_rs_fail) or die(mysqli_error($localhost));
		$row_rs_fail = mysqli_fetch_assoc($rs_fail);
		$total_rows_rs_fail = mysqli_num_rows($rs_fail);
		if ($total_rows_rs_fail > 0) {

			$today = getHijriDate()->date;

			$date_diff = dateDiffHijri($row_rs_fail['FinalExamDate'], $today);

			$max_allowed_days = 30;
			if ($date_diff < $max_allowed_days) {
				include('../templates/header1.php'); ?>
				<style>
					.with_fati7ah {
						display: none;
					}
				</style>
				<br><br><br>
				<div style="direction:rtl;text-align:center;font-size:22px;padding: 10px;line-height: 46px">
					<p>
						<?php echo implode(' ', [
							'عفوا ، آخر اختبار ',
							get_gender_label('st', 'لل'),
							':',
							get_student_name($row_rs_fail['StID']),
							'كان قبل',
							$date_diff,
							'يوم / أيام وذلك في تاريخ',
							StringToDate($row_rs_fail['FinalExamDate']),
							'هـ',
							'<br>',
							'ونتيجة الإختبار هي : ',
							get_array_1($statusName, 2),
							'<br>',
							'والمدة النظامية بين الإختبارات هي : شهر',
							'<br>',
							'ولا يمكنكم الحجز الآن لعدم اكتمال المدة النظامية حسب لائحة المرتقيات',
							'<br>',
							'وسيسمح لكم بالحجز بعد',
							$max_allowed_days - $date_diff,
							'يوما / أيام وذلك يوم',
							$day_name[getHijriDateAfter($today, $max_allowed_days - $date_diff)->day],
							'الموافق',
							StringToDate(getHijriDateAfter($today, $max_allowed_days - $date_diff)->date),
							'هـ',
							'<br>',
							'<br>',
							$note,
						]);
						?>
					</p>
				</div>
				<?php exit;
			}
		}
	}

	$insertSQL = sprintf("INSERT INTO er_ertiqaexams (EdarahID,HalakahID,TeacherID,StID,ErtiqaID,TestExamDegree,H_SolokGrade, H_MwadabahGrade,TestExamDate,quran_courses) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
		GetSQLValueString($_POST['EdarahID'], "int"),
		GetSQLValueString($_POST['HalakahID'], "int"),
		GetSQLValueString($_POST['TeacherID'], "double"),
		GetSQLValueString($_POST['StID'], "double"),
		GetSQLValueString($_POST['MurtaqaID'], "int"),
		GetSQLValueString($_POST['TestExamDegree'], "int"),
		GetSQLValueString($_POST['H_SolokGrade'], "int"),
		GetSQLValueString($_POST['H_MwadabahGrade'], "int"),
		GetSQLValueString(str_replace('/', '', $_POST['TestExamDate']), "int"),
		GetSQLValueString($_POST['quran_courses'], "text")
	);

	mysqli_select_db($localhost, $database_localhost);
	$Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));


	if ($Result1) {
		$msg = "ertiqa";
		header(sprintf("Location: statistics/studentexams.php?msg=%s&StudentID=%s", $msg, $_POST['StID']));
	}

}


?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'حجز موعد جديد'; ?>
<title><?php echo $PageTitle; ?></title>
<style type="text/css">
	.FieldsButton .note {
		color: #4FA64B;
	}
</style>
<script>
	$(document).ready(function () {
		$('#TestExamDegree').keyup(function () {
			$('#TestExamResult').val(GetMarkName($('#TestExamDegree').val(), 'long'));
		});

//	الدورات مطلوبة إذا كان المرتقى هو الفاتحة
		$('#form1').parsley('removeItem', '#quran_courses');
		$(".with_fati7ah").hide();
		function fati7ah() {
			$('#form1').parsley('destroy');

			if ($("#MurtaqaID").val() == 9) {

				$('#quran_courses').parsley('addConstraint', {'data-required': true});

				$('#form1').parsley('addItem', '#quran_courses');

				$(".with_fati7ah").show();

			} else {

				$('#quran_courses').parsley('removeConstraint', 'data-required');

				$('#form1').parsley('removeItem', '#quran_courses');

				$(".with_fati7ah").hide();
			}

			$('#form1').parsley();
		}

		fati7ah();
		$("#MurtaqaID").on('change', function () {
			fati7ah();
		});
	});
</script>

</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->
<?php if ($school_level_message == null){ ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
	<?php }
	?>
	<div class="content lp CSSTableGenerator">
		<?php if (login_check("admin,edarh") == true) { ?>
		<?php echo get_st_info($_GET['StID']); ?>
	</div>
	<div class="content">
		<?php echo $school_level_message; ?>
		<input name="MM_insert" type="hidden" value="form1">
		<div class="FieldsTitle">الاختبار التجريبي</div>

		<div class="four columns alpha">
			<div class="LabelContainer">المرتقى</div>
			<?php echo create_combo("MurtaqaID", $murtaqaName, 1, 0, 'data-required="true"'); ?>
		</div>

		<div class="four columns">
			<div class="LabelContainer">درجة السلوك</div>
			<input name="H_SolokGrade" type="text" value="20" data-required="true" data-type="digits" data-max="20" autocomplete="off">
		</div>
		<div class="four columns">
			<div class="LabelContainer">درجة المواظبة</div>
			<input name="H_MwadabahGrade" type="text" value="20" data-required="true" data-type="digits" data-max="20" autocomplete="off">
		</div>
		<div class="four columns omega">
			<div class="LabelContainer">درجة الاختبار التجريبي</div>
			<input name="TestExamDegree" id="TestExamDegree" type="text" value="" data-required="true" data-type="digits" data-range="[85,100]"
				   autocomplete="off">
		</div>
		<div class="four columns alpha">
			<div class="LabelContainer">التقدير</div>
			<input name="TestExamResult" id="TestExamResult" type="text" value="" READONLY tabindex="-1"/>
		</div>
		<div class="four columns ">
			<div class="LabelContainer">تاريخ الاختبار التجريبي</div>
			<input name="TestExamDate" type="text" id="TestExamDate" data-required="true" value="" zezo_date="true">
		</div>


		<br class="clear with_fati7ah"/>
		<div class="eight columns alpha with_fati7ah">
			<div class="LabelContainer">الدورات التجويدية التي التحق بها الطالب</div>
			<textarea name="quran_courses" type="text" id="quran_courses" rows="8" data-required="true"></textarea>
		</div>
		<div class="four columns omega left">
			<input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
		</div>

	</div><!--content-->
</form>
<?php } else {
	echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.' . '</div>';
} ?>
<?php include('../templates/footer.php'); ?>

<?php
if (isset($_SESSION['u1'])) {
	?>
	<script>
		$(document).ready(function () {
			alertify.success("تم حجز الموعد بنجاح");
		});
	</script>
	<?php
	$_SESSION['u1'] = NULL;
	unset($_SESSION['u1']);
}
?>

<script type="text/javascript">
	showError();
</script>
