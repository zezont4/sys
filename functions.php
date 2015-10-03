<?php
if (!function_exists("sql_sex")) {
	function sql_sex($field_name)
	{
		if (isset($_SESSION['sex'])) {
			if ($_SESSION['sex'] != 2) {
				return sprintf(' and %s=%s', $field_name, $_SESSION['sex']);
			} else {
				return '';
			}
		}
	}
}
$sql_sex = sql_sex('sex');


$gender_labels = [['طالبة', 'طالب', 'st'], ['طالبات', 'طلاب', 'sts'], ['معلمة', 'معلم', 't'], ['معلمات', 'معلمين', 'ts'], ['دار', 'مجمع', 'e'], ['دور', 'إدارات', 'es'], ['المعلمة المختبرة', 'المعلم المختبر', 'mktbr'], ['مجتازات', 'مجتازون', 'najeh'], ['مستلمة', 'مستلم', 'mstlm'], ['مختبرات', 'مختبرون', 'mks']];

if (!function_exists("get_gender_label")) {
	function get_gender_label($Eng_lable, $AL = '')
	{
		$sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 1;
		global $gender_labels;
		$count_a = count($gender_labels);
		for ($i = 0; $i < $count_a; $i++) {
			if ($gender_labels[$i][2] == $Eng_lable) {
				if ($sex == '2') {
					return $AL . $gender_labels[$i][1] . '/' . $AL . $gender_labels[$i][0];
				} else {
					return $AL . $gender_labels[$i][$sex];
				}
			}
		}
	}
}

if (!function_exists("zlog")) {
	function zlog($MSG)
	{
		echo '<script> console.log("' . $MSG . '")</script>';
	}
}

if (!function_exists("get_st_info")) {
	function get_st_info($StID)
	{
		global $database_localhost, $localhost;
		mysqli_select_db($localhost, $database_localhost);
		$query_RS_all = sprintf("SELECT Stfullname,O_BurthDate,arabic_name,HName,StMobileNo,StID,StEdarah,StHalaqah,TID FROM view_0_students WHERE StID = %s", GetSQLValueString($StID, "double"));
		$RS_all = mysqli_query($localhost, $query_RS_all) or die('get_st_info ' . mysqli_error($localhost));
		$row_RS_all = mysqli_fetch_assoc($RS_all);
		$totalRows_RS_all = mysqli_num_rows($RS_all);
		if ($totalRows_RS_all > 0) {
			$returned_value = '<table>
				<caption>
					بيانات ' . get_gender_label('st', 'ال') . ' الأساسية
				</caption>
					<tr>
					<td>اسم ' . get_gender_label('st', 'ال') . '</td>
					<td>' . get_gender_label('e', 'ال') . '</td>
					<td>الحلقة</td>
					<td class="NoMobile">رقم الجوال</td>
					<td class="NoMobile">تاريخ الميلاد</td>
				</tr>
					<tr>
					<td>' . $row_RS_all['Stfullname'] . '</td>
					<td>' . $row_RS_all['arabic_name'] . '</td>
					<td>' . $row_RS_all['HName'] . '</td>
					<td class="NoMobile">' . $row_RS_all['StMobileNo'] . '</td>
					<td class="NoMobile">' . $row_RS_all['O_BurthDate'] . '</td>
				</tr>
			</table>';
			if ($row_RS_all['TID'] == '') {
				$returned_value .= '<p class="note">' . 'تنبيه: الحلقة ليس لها معلم,أو أن المعلم مخفي.' . '</p>';
			}
			$returned_value .= '<input name="EdarahID" type="hidden" value="' . $row_RS_all['StEdarah'] . '">' . '<input name="HalakahID" type="hidden" value="' . $row_RS_all['StHalaqah'] . '">' . '<input name="TeacherID" type="hidden" value="' . $row_RS_all['TID'] . '">' . '<input name="StID" type="hidden" value="' . $row_RS_all['StID'] . '">';
			mysqli_free_result($RS_all);

			return $returned_value;
		}
	}
}

if (!function_exists("get_edarah_name")) {
	function get_edarah_name($sent_value)
	{
		global $database_localhost, $localhost;
		mysqli_select_db($localhost, $database_localhost);
		$query_RS_all = sprintf("SELECT arabic_name FROM 0_users WHERE id=%s", $sent_value);
		$RS_all = mysqli_query($localhost, $query_RS_all) or die('get_edarah_name' . mysqli_error($localhost));
		$row_RS_all = mysqli_fetch_assoc($RS_all);
		$totalRows_RS_all = mysqli_num_rows($RS_all);
		if ($totalRows_RS_all > 0) {
			$returned_value = $row_RS_all["arabic_name"];
			mysqli_free_result($RS_all);

			return $returned_value;
		}
	}
}

if (!function_exists("get_halakah_name")) {
	function get_halakah_name($sent_value)
	{
		global $database_localhost, $localhost;
		mysqli_select_db($localhost, $database_localhost);
		$query_RS_all = sprintf("SELECT HName FROM 0_halakat WHERE AutoNo=%s", $sent_value);
		$RS_all = mysqli_query($localhost, $query_RS_all) or die('get_halakah_name' . mysqli_error($localhost));
		$row_RS_all = mysqli_fetch_assoc($RS_all);
		$totalRows_RS_all = mysqli_num_rows($RS_all);
		if ($totalRows_RS_all > 0) {
			$returned_value = $row_RS_all["HName"];
			mysqli_free_result($RS_all);

			return $returned_value;
		}
	}
}

if (!function_exists("get_teacher_name")) {
	function get_teacher_name($sent_value)
	{
		if ($sent_value > 0) {
			global $database_localhost, $localhost;
			mysqli_select_db($localhost, $database_localhost);
			$query_RS_all = sprintf("SELECT concat_ws(' ',TName1,TName2,TName3,TName4) AS t_name FROM 0_teachers WHERE TID=%s", $sent_value);
			$RS_all = mysqli_query($localhost, $query_RS_all) or die('get_teacher_name ' . mysqli_error($localhost));
			$row_RS_all = mysqli_fetch_assoc($RS_all);
			$totalRows_RS_all = mysqli_num_rows($RS_all);
			if ($totalRows_RS_all > 0) {
				$returned_value = $row_RS_all["t_name"];
				mysqli_free_result($RS_all);

				return $returned_value;
			}
		} else {
			return "";
		}
	}
}

if (!function_exists("get_student_name")) {
	function get_student_name($sent_value)
	{
		global $database_localhost, $localhost;
		mysqli_select_db($localhost, $database_localhost);
		$query_RS_all = sprintf("SELECT concat_ws(' ',StName1,StName2,StName3,StName4) AS St_name FROM 0_students WHERE StID=%s", $sent_value);
		$RS_all = mysqli_query($localhost, $query_RS_all) or die('get_student_name' . mysqli_error($localhost));
		$row_RS_all = mysqli_fetch_assoc($RS_all);
		$totalRows_RS_all = mysqli_num_rows($RS_all);
		if ($totalRows_RS_all > 0) {
			$returned_value = $row_RS_all["St_name"];
			mysqli_free_result($RS_all);

			return $returned_value;
		}
	}
}

if (!function_exists("StringToDate")) {
	function StringToDate($strDate)
	{
		$tm_Date = ($strDate != "") ? substr($strDate, 0, 4) . "/" . substr($strDate, 4, 2) . "/" . substr($strDate, 6, 2) : null;

		return $tm_Date;
	}
}
$SESSIOn_sex = 2;
if (isset($_SESSION['sex'])) {
	$SESSIOn_sex = $_SESSION['sex'];
}
if ($SESSIOn_sex == 0) {
	$statusName = [["0", "الموعد لم يحدد"], ["1", "مقبولة"], ["2", "ناجحة"], ["3", "لم تجتز"], ["4", "غائبة"], ["5", "غائبة بعذر للطالبة"], ["6", "غائبة بعذر للدار"]];
} else {
	$statusName = [["0", "الموعد لم يحدد"], ["1", "مقبول"], ["2", "ناجح"], ["3", "لم يجتز"], ["4", "غائب"], ["5", "غائب بعذر للطالب"], ["6", "غائب بعذر للمجمع"]];
}

$murtaqaName = [["", "حدد المرتقى..."], ["1", "الملك"], ["2", "ق"], ["3", "الزمر"], ["4", "النمل"], ["5", "الكهف"], ["6", "يونس"], ["7", "المائدة"], ["8", "الفاتحة"], ["9", "برنامج المهرة"],];

$bra3mName = ["Null"                /* 0 */, "القارعة"                /* 1 */, "البلد"                    /* 2 */, "المطففين"                /* 3 */, "النبأ"                /* 4 */, "الجن"                /* 5 */, "مرتقى الملك"                /* 6 */];

$murtaqaEndSora = [0                  /*-*/, 67                /*الملك*/, 50                /*ق*/, 39                /*الزمر*/, 27                /*المنل*/, 18                /*الكهف*/, 10                /*يونس*/, 5                 /*المائدة*/, 1                 /*الفاتحة*/];

$mudeerTanfethee = "عبدالرحمن بن خالد الحربي";
$raeesJam3iah = 'عبدالرحمن بن محمد الحمد';
$maliah_edariah = 'عبدالرحمن بن عبدالعزيز النصار';
$ameen_sondooq = 'فهد بن سعود العامر';
$ertiqa_mudeer = 'عبدالمحسن بن محمد القشعمي';
$jameiahName = "الجمعية الخيرية لتحفيظ القرآن الكريم بمحافظة الزلفي";
$reportHeaderText = "";

$mudeerEshraf = "حصة بنت عبدالرحمن الطوالة";
$mushrefErtiqa = "نورة بنت ناصر الجبر";

$soraName = ["Null", "الفاتحة", "البقرة", "آل عمران", "النساء", "المآئدة", "الأنعام", "الأعراف", "الأنفال", "التوبة", "يونس", "هود", "يوسف", "الرعد", "إبراهيم", "الحجر", "النحل", "الإسراء", "الكهف", "مريم", "طـه", "الأنبياء", "الحج", "المؤمنون", "النور", "الفرقان", "الشعراء", "النمل", "القصص", "العنكبوت", "الروم", "لقمان", "السجدة", "الأحزاب", "سبأ", "فاطر", "يس", "الصافات", "ص", "الزمر", "غافر", "فصلت", "الشورى", "الزخرف", "الدخان", "الجاثية", "الأحقاف", "محمد", "الفتح", "الحجرات", "ق", "الذاريات", "الطور", "النجم", "القمر", "الرحمن", "الواقعة", "الحديد", "المجادلة", "الحشر", "الممتحنة", "الصف", "الجمعة", "المنافقون", "التغابن", "الطلاق", "التحريم", "الملك", "القلم", "الحاقة", "المعارج", "نوح", "الجن", "المزمل", "المدثر", "القيامة", "الإنسان", "المرسلات", "النبأ", "النازعات", "عبس", "التكوير", "الإنفطار", "المطففين", "الإنشقاق", "البروج", "الطارق", "الأعلى", "الغاشية", "الفجر", "البلد", "الشمس", "الليل", "الضحى", "الشرح", "التين", "العلق", "القدر", "البيِّنة", "الزلزلة", "العاديات", "القارعة", "التكاثر", "العصر", "الهمزة", "الفيل", "قريش", "الماعون", "الكوثر", "الكافرون", "النصر", "المسد", "الإخلاص", "الفلق", "الناس"];


//تبع الرسائل النصية لموبايلي
if (!function_exists("utf2win")) {
	function utf2win($MSG)
	{
		return iconv("UTF-8", "Windows-1256", $MSG);
	}
}
$SchoolLevelName = ["غير ملتحق"                /* 0 */, "روضة"                /* 1 */, "تمهيدي"                    /* 2 */, "أول ابتدائي"                /* 3 */, "ثاني ابتدائي"                /* 4 */, "ثالث ابتدائي"                /* 5 */];

$SchoolLevelNameAll = [["", "حدد السنة الدراسية..."], ["0", "أول ابتدائي"], ["1", "ثاني ابتدائي"], ["2", "ثالث ابتدائي"], ["3", "رابع ابتدائي"], ["4", "خامس ابتدائي"], ["5", "سادس ابتدائي"], ["6", "أول متوسط"], ["7", "ثاني متوسط"], ["8", "ثالث متوسط"], ["9", "أول ثانوي"], ["10", "ثاني ثانوي"], ["11", "ثالث ثانوي"], ["12", "جامعي"]];

$MsbkhType = [["", "حدد نوع المسابقة..."], ["7", "حلقات"], ["0", "جزأين (دنيا)"], ["1", "جزأين (عليا)"], ["2", "خمس أجزاء"], ["3", "عشرة أجزاء"], ["4", "عشرون جزءاَ"], ["5", "القرآن كاملا"], ["6", "مزامير آل داود"], ["8", "أصغر حافظ"]];

$etqan_msbkh_type = [["2", "15 جزءَا ثانوي"], ["4", "15 جزءَا متوسط"], ["9", "جزأين ابتدائي"], ["", "حدد نوع المسابقة..."], ["10", "القرآن الكريم مع التفسير"], ["0", "القرآن الكريم كاملا"], ["1", "20 جزءَا ثانوي فما فوق"], ["3", "10 أجزاء ثانوي فما فوق"], ["5", "10 أجزاء متوسط"], ["6", "5 أجزاء متوسط"], ["7", "10 أجزاء ابتدائي"], ["8", "5 أجزاء ابتدائي"],];

$shabab_msbkh_type = [["", "حدد نوع المسابقة..."], ["0", "20 جزءَا"], ["1", "10 أجزاء"], ["2", "5 أجزاء"],];

//	نوع المتسابقة
$MtsabikType = [["0", 'أساسي'], ["1", 'احتياط']];

$all_groups = "admin,edarh,er,ms,mktbr,t3lem";

/*// ############### Chech Autharized Log In ###### Start ########
// *** Restrict Access To Page: Grant or deny access to this page
	if (! function_exists ( "z_isAuthorized" )) {
		function z_isAuthorized($strUsers,$strGroups,$UserName,$UserGroup) {
			// For security,start by assuming the visitor is NOT authorized.
			$isValid = False;

			// When a visitor has logged into this site,the Session variable MM_Username set equal to their username.
			// Therefore,we know that a user is NOT logged in if that Session variable is blank.
			//zlog('isAuthorized :'.$UserName);
			if (!empty($UserName)) {
				//zlog("if not empty username");
				// Besides being logged in,you may restrict access to only certain users based on an ID established when they login.
				// Parse the strings into arrays.
				$arrUsers = Explode ( ",",$strUsers );
				$arrGroups = Explode ( ",",$strGroups );
				if (in_array ( $UserName,$arrUsers )) {
					//zlog("if in array username,arrusers");
					$isValid = true;
				}
				// Or,you may restrict access to only certain users based on their username.
				if (in_array ( $UserGroup,$arrGroups )) {
					//zlog("if in array UserGroup,arrGroups");
					$isValid = true;
				}
				if (($strUsers == "") && false) {
					//zlog("if strUsers = ''");
					$isValid = true;
				}
			}
			return $isValid;
		}
	}*/

/*if (! function_exists ( "z_restrict_access" )) {
		function z_restrict_access($z_authorizedUsers,$z_donotCheckaccess,$z_errorGoTo) {
			$MM_authorizedUsers = $z_authorizedUsers;
			$MM_donotCheckaccess = $z_donotCheckaccess;
			$MM_restrictGoTo = $z_errorGoTo;
			if (! ((isset ( $_SESSION ['MM_Username'] )) && (z_isAuthorized ( "",$MM_authorizedUsers,$_SESSION['username'],$_SESSION ['MM_UserGroup'] )))) {
				$MM_qsChar = "?";
				$MM_referrer = $_SERVER ['PHP_SELF'];
				if (strpos ( $MM_restrictGoTo,"?" ))
					$MM_qsChar = "&";
				if (isset ( $_SERVER ['QUERY_STRING'] ) && strlen ( $_SERVER ['QUERY_STRING'] ) > 0)
					$MM_referrer .= "?" . $_SERVER ['QUERY_STRING'];
				$MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode ( $MM_referrer );
				header ( "Location: " . $MM_restrictGoTo );
				exit ();
			}
		}
	}*/
// call like this:

// for any loged in user
// z_restrict_access("","true");

// for edarat only
// z_restrict_access($allowedEdarat,false);

// for edarat & admin
// z_restrict_access("admin","false".$allowedEdarat,false);

// for admin only
// z_restrict_access("admin","false","../msg.php?ID=AccessDenide");

// ############### Chech Autharized Log In ###### End ########


//$degree_title="الـــــــــدرجـــــــــــة";

//تحويل المصفوفة إلى نص مفصول بفاصلة
if (!function_exists("array_2_string")) {
	function array_2_string($my_array, $start_index = 0)
	{
		$length = count($my_array);
		for ($i = $start_index; $i < $length; $i++) {
			if ($i == $start_index) {
				$ar = $my_array[$i];
			} else {
				$ar = $ar . ',' . $my_array[$i];
			}
		}

		return $ar;
	}
}

//جلب العنصر من المصفوفة المزدوجة
if (!function_exists("get_array_1")) {
	function get_array_1($array_name, $array_0)
	{
		$length = count($array_name);
		for ($i = 0; $i < $length; $i++) {
			if ($array_name[$i][0] == $array_0) {
				return $array_name[$i][1];
			}
		}
	}
}

//انشاء مربع نص 
if (!function_exists("create_text")) {
	function create_text($input_name, $other_optins = "")
	{
		//if ($is_required==true){$isRequired='data-required="true"';}
		return sprintf('<input name="%s" id="%s" type="text" %s/>', $input_name, $input_name, $other_optins);
	}
}

//انشاء مربع اختيار 
if (!function_exists("create_chkbox")) {
	function create_chkbox($input_name, $other_optins = "")
	{
		if ($is_required == true) {
			$isRequired = 'data-required="true"';
		}

		return sprintf('<input name="%s" id="%s" type="checkbox" value="1" %s>', $input_name, $input_name, $other_optins);
	}
}

//انشاء قائمة منسدلة
if (!function_exists("create_combo")) {
	function create_combo($input_name, $combo_values, $start_index = 0, $selected_value = '', $other_optins = "")
	{
		$c1 = sprintf('<select name="%s" id="%s" %s>', $input_name, $input_name, $other_optins);
		$c2 = '';
		$c3 = "</select>";
		//$combo_v = Explode ( ",",$combo_values );
		$length = count($combo_values);
		for ($i = $start_index; $i < $length; $i++) {
			//zlog($i);
			$slctd = "";
			//if($i=='0'){$slctd='selected';}
			if ($combo_values[$i][0] == $selected_value) {
				$slctd = 'selected';
			}
			$c2 = $c2 . sprintf('<option value="%s" %s>%s</option>', $combo_values[$i][0], $slctd, $combo_values[$i][1]);
			//zlog($c2);
		}

		return $c1 . $c2 . $c3;
	}
}


if (!function_exists("create_edarah_combo")) {
	function create_edarah_combo($selected_index = '')
	{
		//require ('Connections/localhost.php');
		global $database_localhost, $localhost;
		mysqli_select_db($localhost, $database_localhost);
		$sql_sex = sql_sex('sex');
		$query_RS_all = sprintf("SELECT id,arabic_name FROM `0_users` WHERE `hidden`=0 %s AND user_group='edarh' ORDER BY Sex,arabic_name ASC", $sql_sex);
		$RS_all = mysqli_query($localhost, $query_RS_all) or die('create_edarah_combo ' . mysqli_error($localhost));
		$row_RS_all = mysqli_fetch_assoc($RS_all);
		$totalRows_RS_all = mysqli_num_rows($RS_all);
		if ($totalRows_RS_all > 0) {
			//echo $query_RS_all;
			$ed = get_gender_label('e', 'ال');
			$s1 = '<select name="EdarahID" class="full-width" id="EdarahID"  data-required="true">' . '<option value>اختر ' . $ed . '</option>';
			$s2 = '';
			do {
				$o1 = '';
				if ($selected_index != '') {
					if ($selected_index == $row_RS_all['id']) {
						$o1 = 'selected="selected"';
					} else {
						if ($_SESSION['user_group'] == 'edarh') {
							$o1 = 'disabled="disabled"';
						}
					}
				} else {
					if ($_SESSION['user_id'] == $row_RS_all['id']) {
						$o1 = 'selected="selected"';
					} else {
						if ($_SESSION['user_group'] == 'edarh') {
							$o1 = 'disabled="disabled"';
						}
					}
				}
				$o2 = '<option ' . $o1 . ' value="' . $row_RS_all['id'] . '">' . $row_RS_all['arabic_name'] . '</option>';
				$s2 = $s2 . $o2;
			} while ($row_RS_all = mysqli_fetch_assoc($RS_all));
			$s3 = '</select>';
			mysqli_free_result($RS_all);
			echo $s1 . $s2 . $s3;
		}
	}
}


$MarkName_Long = [["'أ+'", 'ممتاز مرتفع'], ["'أ'", 'ممتاز'], ["'ب+'", 'جيد جدا مرتفع'], ["'ب'", 'جيد جدا'], ["'ج+'", 'جيد مرتفع'], ["'ج'", 'جيد'], ["'د'", 'راسب']];


$user_groups = [
	["", "حدد الصلاحية..."],
	["admin", "كامل الصلاحيات"],
	["t3lem", "الشؤون التعليمية"],
	["er", "مسؤول المرتقيات"],
	["ms", "مسؤول المسابقات"],
	["mktbr", "معلم مختبر"],
	["edarh", "مجمع أو دار"],
];
$all_groups_space = 'admin t3lem er ms mktbr edarh';

if (!function_exists("ar_search_helper")) {
	function ar_search_helper($ar_name = '')
	{
		$ar_name = $ar_name;
		$ar_name = str_replace(' ', '', $ar_name);
		$ar_name = str_replace('أ', 'ا', $ar_name);
		$ar_name = str_replace('إ', 'ا', $ar_name);
		$ar_name = str_replace('ة', 'ه', $ar_name);
		$ar_name = str_replace('ى', 'ا', $ar_name);

		return $ar_name;
	}
}

?>