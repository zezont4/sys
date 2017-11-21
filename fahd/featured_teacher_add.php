<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once('fahd_functions.php'); ?>
<?php

$open_g = true;//السماح للتسجيل للبنات
$open_b = true;//السماح للتسجيل للبنين
//متغير يحفظ نتيجة السماح بالتسجيل بحث يفحص جنس المستخدم الحالي مع المتغيرات في الأعلى
$open = false;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$userType = 'xx';
if (isset($_SESSION['user_group'])) {
    $userType = $_SESSION['user_group'];
}


$TID = "-1";
if (isset($_GET['TID'])) {
    $TID = $_GET['TID'];
} else {
    echo '<br>' . 'المعلم لم يحدد !!' . '<br>';
    exit;
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    //$study_year_id= get_study_year_id($_POST['f_t_date']);
    $study_fahd_start = get_fahd_year_start($_POST['f_t_date']);
    $study_fahd_end = get_fahd_year_end($_POST['f_t_date']);
    $study_fahd_name = get_fahd_year_name($_POST['f_t_date']);
    //echo $_SESSION ['default_year_id'].'<br>';
    //echo $study_year_id.' - '.$study_year_start.' - '.$study_year_end;
    //exit;
    // search for dublicate musabakah ##############
    mysqli_select_db($localhost, $database_localhost);
    $query_Rsdublicate = sprintf("SELECT auto_no,teacher_id,f_t_date FROM ms_fahd_featured_teacher WHERE teacher_id=%s and f_t_date>=%s and f_t_date<=%s", GetSQLValueString($TID, "int"), GetSQLValueString($study_fahd_start, "int"), GetSQLValueString($study_fahd_end, "int"));
    $Rsdublicate = mysqli_query($localhost, $query_Rsdublicate) or die('Rsdublicate ' . mysqli_error($localhost));
    $row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
    $totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);
    if ($totalRows_Rsdublicate > 0) {
        include('../templates/header1.php');
        echo '<h1 style="text-align:center;margin:20px;font-size:22px;">' . "<br><br>" . 'المعلم : ' . get_teacher_name($TID) . ' قام بالتسجيل سابقا في العام الدراسي : ' . $study_fahd_name . ' وذلك في تاريخ : ' . StringToDate($row_Rsdublicate['f_t_date']) . '<br><br><br>' . 'ولا يمكن تكرار المشاركة أكثر من مرة في العام الواحد' . '<br><br>' . '<a href="/sys/fahd/featured_teacher_edit.php?auto_no=' . $row_Rsdublicate['auto_no'] . '">' . 'وإذا أردت تعديل المسابقة، اضغط هنا' . '</a>' . "</h1>";
        exit;
    }
    $field_names = '';
    $field_values = '';
    $f_t_chkbx_array = ['3a', '3b', '4b', '5a', '5b', '5c', '7a', '9a', '15a', '15b', '12a', '13a'];
    $length = count($f_t_chkbx_array);
    for ($i = 0; $i < $length; $i++) {
        $f_0_1 = ($_POST['f_' . $f_t_chkbx_array[$i] . '_n'] == 1) ? '1' : '0';
        $field_names = $field_names . ',f_' . $f_t_chkbx_array[$i] . '_n ';
        $field_values = $field_values . ',' . $f_0_1;

        $field_names = $field_names . ',f_' . $f_t_chkbx_array[$i] . '_d';
        $f_0_1 = ($_POST['f_' . $f_t_chkbx_array[$i] . '_d'] > 0) ? $_POST['f_' . $f_t_chkbx_array[$i] . '_d'] : '0';
        $field_values = $field_values . ',' . $f_0_1;
    }

    $f_t_txt2bx_array = ['4a', '8a', '10a', '11a'];
    $length = count($f_t_txt2bx_array);
    for ($i = 0; $i < $length; $i++) {
        $field_names = $field_names . ',f_' . $f_t_txt2bx_array[$i] . '_n';
        $f_0_1 = ($_POST['f_' . $f_t_txt2bx_array[$i] . '_n'] > 0) ? $_POST['f_' . $f_t_txt2bx_array[$i] . '_n'] : '0';
        $field_values = $field_values . ',' . $f_0_1;

        $field_names = $field_names . ',f_' . $f_t_txt2bx_array[$i] . '_d';
        $f_0_1 = ($_POST['f_' . $f_t_txt2bx_array[$i] . '_d'] > 0) ? $_POST['f_' . $f_t_txt2bx_array[$i] . '_d'] : '0';
        $field_values = $field_values . ',' . $f_0_1;
    }

    $f_t_txt3bx_array = ['2a', '2b'];
    $length = count($f_t_txt3bx_array);
    for ($i = 0; $i < $length; $i++) {
        $field_names = $field_names . ',f_' . $f_t_txt3bx_array[$i] . '_n';
        $f_0_1 = ($_POST['f_' . $f_t_txt3bx_array[$i] . '_n'] > 0) ? $_POST['f_' . $f_t_txt3bx_array[$i] . '_n'] : '0';
        $field_values = $field_values . ',' . $f_0_1;

        $field_names = $field_names . ',f_' . $f_t_txt3bx_array[$i] . '_t';
        $f_0_1 = ($_POST['f_' . $f_t_txt3bx_array[$i] . '_t'] > 0) ? $_POST['f_' . $f_t_txt3bx_array[$i] . '_t'] : '0';
        $field_values = $field_values . ',' . $f_0_1;

        $field_names = $field_names . ',f_' . $f_t_txt3bx_array[$i] . '_d';
        $f_0_1 = ($_POST['f_' . $f_t_txt3bx_array[$i] . '_d'] > 0) ? $_POST['f_' . $f_t_txt3bx_array[$i] . '_d'] : '0';
        $field_values = $field_values . ',' . $f_0_1;
        //$field_names=$field_names.',f_2b_t';
        //$field_values=$field_values.','.$_POST['f_'.$f_t_txt3bx_array[$i].'_t'];
    }

    $result_increment = mysqli_query($localhost, "SHOW TABLE STATUS WHERE `Name` = 'ms_fahd_featured_teacher'");
    $data = mysqli_fetch_assoc($result_increment);
    $next_increment = $data['Auto_increment'];
    mysqli_select_db($localhost, $database_localhost);
    $insertSQL = sprintf("insert into ms_fahd_featured_teacher
 (auto_no,teacher_id,f_t_date,teacher_type,t_edarah,full_degree,max_degree %s)
values
 (%s,%s,%s,%s,%s,%s,110 %s)",
        $field_names,
        $next_increment, $_GET['TID'],
        str_replace("/", "", $_POST['f_t_date']),
        $_POST['teacher_type'],
        $_POST['t_edarah'],
        $_POST['full_degree'],
        $field_values);

//	echo $insertSQL;
//	exit;
    $Result1 = mysqli_query($localhost, $insertSQL) or die(' insertSQL 1 : ' . mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        header("Location: featured_teacher_edit.php?auto_no=" . $next_increment);
        exit;
    }
}

mysqli_select_db($localhost, $database_localhost);
$query_Rs_T = sprintf("SELECT TID,Tfullname,arabic_name,HName,TEdarah FROM view_0_teachers WHERE TID = %s", $TID);
$Rs_T = mysqli_query($localhost, $query_Rs_T) or die('query_Rs_T ' . mysqli_error($localhost));
$row_Rs_T = mysqli_fetch_assoc($Rs_T);
$totalRows_Rs_T = mysqli_num_rows($Rs_T);
//echo $query_Rs_T;
?>
<?php
if (isset($_SESSION['user_id'])) {
    $colname_RSEdarat = $_SESSION['user_id'];
}
?>
<?php
//انشاء عنوان القسم
function create_f_t_block_head($array_index)
{
    global $featured_teacher_dep, $featured_teacher_title;
    $b1 = '
	<div class="content">
		<div class="LabelContainer">
			<div class="FieldsTitle">' . $featured_teacher_title[$array_index] . '</div>
		</div>';
    echo $b1;
}

//انشاء بند نصي
function create_f_t_block_bnd_txt($bnd_title, $db_feild_name, $last_bnd = true, $ex_index = 0, $readonly = '')
{
    global $degree_title, $row_rs_f_teacher, $featured_teacher_ex;
    $b1 = '
				<div class="six columns alpha omega top_padding">' . $bnd_title . '</div>
				<div class="three columns alpha">' . create_text("f_" . $db_feild_name . "_n", $readonly . ' data-type="number"') . '</div>

				<div class="four columns alpha omega left">
					<div class="two columns  alpha top_padding">' . $degree_title . '</div>
					<div class="two columns omega">' . create_text("f_" . $db_feild_name . "_d", ' readonly class="bnd_degree"') . '</div>
				</div>
		';
    echo $b1;
    if ($last_bnd == true) {
        echo $featured_teacher_ex[$ex_index] . "</div>";
    }
}

//انشاء عدد (2) بند نصي
function create_f_t_block_bnd_txt2($bnd_title1, $bnd_title2, $db_feild_name, $last_bnd = true, $ex_index = 0, $readonly = '')
{
    global $degree_title, $row_rs_f_teacher, $featured_teacher_ex;
    $b1 = '
				<div class="three columns alpha omega top_padding">' . $bnd_title1 . '</div>
				<div class="two columns alpha">' . create_text("f_" . $db_feild_name . "_n", $readonly) . '</div>
				
				<div class="three columns omega top_padding">' . $bnd_title2 . '</div>
				<div class="two columns">' . create_text("f_" . $db_feild_name . "_t", 'value="5"  readonly') . '</div>

				<div class="four columns alpha omega left">
					<div class="two columns  alpha top_padding">' . $degree_title . '</div>
					<div class="two columns omega">' . create_text("f_" . $db_feild_name . "_d", ' readonly class="bnd_degree"') . '</div>
				</div>
				<br class="clear"><div class="top_padding"></div>
		';
    echo $b1;
    if ($last_bnd == true) {
        echo $featured_teacher_ex[$ex_index] . "</div>";
    }
}

//انشاء بند مربع اختيار
function create_f_t_block_bnd_chk($bnd_title, $db_feild_name, $last_bnd = true, $ex_index = 0)
{
    global $degree_title, $row_rs_f_teacher, $featured_teacher_ex;
    $b1 = '
				
				<div class="six columns alpha omega top_padding">' . create_chkbox("f_" . $db_feild_name . "_n") . $bnd_title . '</div>
				
				<div class="four columns alpha omega left">
					<div class="two columns  alpha top_padding">' . $degree_title . '</div>
					<div class="two columns omega">' . create_text("f_" . $db_feild_name . "_d", ' readonly class="bnd_degree"') . '</div>
				</div>
				<br class="clear"><div class="top_padding"></div>
		';
    echo $b1;
    if ($last_bnd == true) {
        echo $featured_teacher_ex[$ex_index] . "</div>";
    }
}

//انشاء بند نصي
function create_f_t_block_bnd_cmbo($bnd_title, $db_feild_name, $vals, $last_bnd = true, $ex_index = 0, $readonly = '')
{
    global $degree_title, $row_rs_f_teacher, $featured_teacher_ex;
    $b1 = '
				<div class="four columns alpha omega top_padding">' . $bnd_title . '</div>';
    if ($readonly !== 'readonly') {
        $b1 .= '<div class="four columns alpha">' . create_combo("f_" . $db_feild_name . "_n", $vals, 0, '', 'class="full-width"') . '</div>';
    }

    $b1 .= '<div class="four columns alpha omega left">
					<div class="two columns  alpha top_padding">' . $degree_title . '</div>
					<div class="two columns omega">' . create_text("f_" . $db_feild_name . "_d", ' readonly class="bnd_degree"') . '</div>
				</div>
				<br class="clear"><div class="top_padding"></div>
		';
    echo $b1;
    if ($last_bnd == true) {
        echo $featured_teacher_ex[$ex_index] . "</div>";
    }
}

?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'تسجيل في مسابقة المعلم المتميز'; ?>
<title><?php echo $PageTitle; ?></title>
<link rel="stylesheet" type="text/css" href="fahd_style.css">


</head>
<body>
<?php

$degree_title = "الـــــــــدرجـــــــــــة"; ?>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->

<?php

if ($_SESSION['sex'] == 1 && $open_b == true) {
    $open = true;
} elseif ($_SESSION['sex'] == 0 && $open_g == true) {
    $open = true;
} elseif ($_SESSION['sex'] == 2) {
    $open = true;
}

if ($open === true) { ?>

    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
        <!--بيانات المعلم-->
        <div class="content CSSTableGenerator">
            <div class="FieldsTitle">بيانات المعلم الأساسية</div>
            <table>
                <tr>
                    <td class="NoMobile">السجل المدني</td>
                    <td>اسم المعلم</td>
                    <td>المجمع</td>
                    <td>الحلقة</td>
                </tr>
                <tr <?php if ($row_Rs_T["hide"] == 1) { ?> class='hidenRow' <?php ;
                } ?>>
                    <td rowspan="2" class="NoMobile"><?php echo $row_Rs_T['TID']; ?></td>
                    <td><?php echo $row_Rs_T['Tfullname']; ?></td>
                    <td><?php echo $row_Rs_T['arabic_name']; ?></td>
                    <td><?php echo $row_Rs_T['HName']; ?></td>
                </tr>
                <tr <?php if ($row_Rs_T["hide"] == 1) { ?> class='hidenRow' <?php ;
                } ?>>
                    <td colspan="2" class="y_bg">إذا كان إدخالك للمعلومات دقيقاَ فتكون درجتك هي :</td>
                    <td class="y_bg"><?php echo create_text('full_degree', 'readonly'); ?><br>

                        <p>الدرجة الكبرى 110</p></td>
                </tr>
            </table>
        </div>
        <div class="content">
            <div class="LabelContainer">
                <div class="FieldsTitle"></div>
            </div>
            <div class="three columns alpha top_padding">تاريخ تسجيل البيانات</div>
            <div class="three columns"><input name="f_t_date" type="text" id="f_t_date" readonly data-required="true"/>
            </div>

            <div class="two columns">&nbsp;</div>

            <div class="three columns alpha top_padding">تصنيف المعلم</div>
            <div
                class="four columns"><?php echo create_combo('teacher_type', $teacher_types, 1, 0, 'class="full-width"') ?></div>
        </div>
        <?php

        //درجات الأداء الوظيفي
        create_f_t_block_head(1);
        create_f_t_block_bnd_txt("درجة تقييم المشرف(يضاف من قبل المسؤول)", "1a", true, 1, 'readonly');

        //نسبة النجاح في المرتقيات أو سلم البراعم
        create_f_t_block_head(2);
        create_f_t_block_bnd_txt2("عدد المجتازين للمرتقيات", "عــدد طــلاب الحـــلـــقـــة", "2a", false);
        create_f_t_block_bnd_txt2("عدد الناجحين في البراعم", "عــدد طــلاب الحـــلـــقـــة", "2b", true, 2);


        //المشاركة في المسابقات الداخلية
        create_f_t_block_head(3);
        create_f_t_block_bnd_chk("شاركت في مسابقة الأفراد", "3a", false);
        create_f_t_block_bnd_chk("شاركت في مسابقة الحلقات", "3b", true, 3);

        //نتائج المسابقات الداخلية
        create_f_t_block_head(4);
        create_f_t_block_bnd_cmbo("عدد طلابك المتأهلين أو الفائزين بالأفراد", "4a", $b_4a, false);
        create_f_t_block_bnd_chk("فزت بمسابقة الحلقات", "4b", true, 4);

        //حضور الدورات التدريبية في الجمعية
        create_f_t_block_head(5);
        create_f_t_block_bnd_chk("حصلت على التحفة", "5b", false);
        create_f_t_block_bnd_chk("حصلت على الجزرية", "5a", false);
        create_f_t_block_bnd_chk("حضرت دورة تطويرية (٤ ساعات فأكثر) لهذا العام", "5c", true, 5);

        //حضور الاجتماعات واللقاءات وتفعيل برامج الجمعية
        create_f_t_block_head(6);
        create_f_t_block_bnd_txt("عدد الاجتماعات(يضاف من قبل المسؤول)", "6a", true, 1, 'readonly');

        //        create_f_t_block_head(6);
        //        create_f_t_block_bnd_txt2("عدد الاجتماعات واللقاءات", "اجمالي الاجتماعات واللقاءات", "6a", true, 6, 'readonly');

        //المشاركة في الدورات التدريبية السابقة أو الخارجية
        create_f_t_block_head(10);
        create_f_t_block_bnd_txt("كم عدد ساعات الدورات التطويرية في السنتين السابقتين", "10a", true, 10);

        //استخدام منهجية لحفظ الطلاب ومراجعتهم
        create_f_t_block_head(7);
        create_f_t_block_bnd_chk("استخدمت منهجية الجمعية أو أعلى منها", "7a", true, 7);

        //حفظه للقرآن الكريم
        create_f_t_block_head(8);
        create_f_t_block_bnd_cmbo("ماهو أخر مرتقى اجتزته", "8a", $murtaqa_name, true, 8);

        //استخدام منهجية لحفظ الطلاب ومراجعتهم
        create_f_t_block_head(9);
        create_f_t_block_bnd_chk("حضرت في أحد البرامج التعليمية", "9a", true, 9);

        //شفاء الصدور
        create_f_t_block_head(15);
        create_f_t_block_bnd_chk("شفاء الصدور", "15a", false);
        create_f_t_block_bnd_chk("رتل", "15b", true, 15);


        //استخدام فكرة جديدة تحقق أهدافًا تربوية
        create_f_t_block_head(11);
        create_f_t_block_bnd_txt("كم فكرة جديدة استخدمتها", "11a", true, 11);

        //حاصل على إجازة في حفظ القرآن
        create_f_t_block_head(12);
        create_f_t_block_bnd_chk("حاصل على إجازة", "12a", true, 12);

        //حاصل على الشاطبية
        create_f_t_block_head(13);
        create_f_t_block_bnd_chk("حاصل على الشاطبية", "13a", true, 13);

        //المسابقات الخارجية
        create_f_t_block_head(14);
        create_f_t_block_bnd_cmbo("المشاركة أو الفوز بالمسابقات الخارجية (يضاف من قبل المسؤول)", "14a", $f_14a, true, 14, 'readonly'); ?>

        <div class="content">
            <div class="four columns omega left"><input name="submit" type="submit" class="button-primary" id="submit"
                                                        value="موافق"/></div>
        </div>
        <input name="MM_insert" type="hidden" value="form1">
        <input name="t_edarah" id="t_edarah" type="hidden" value="<?php echo $row_Rs_T['TEdarah']; ?>"/>
    </form>

<?php } else { ?>
    <hr>
    <h1 class="WrapperMSG">عفوا .. انتهت فترة التسجيل في مسابقة المعلم المتميز</h1>
    <br>
<?php } ?>

<?php include('../templates/footer.php'); ?>
<?php
if (isset($_SESSION['u1'])) {
    echo '<script>$(document).ready(function() {alertify.success("تم التسجيل في المسابقة بنجاح");});</script>';
    $_SESSION['u1'] = null;
    unset($_SESSION['u1']);
}
?>
<script type="text/javascript">
    showError();
    $('#f_t_date').val(get_formated_hijri_date(zezo_get_hijri_date('now')));

</script>
<script> var teacher_id = '<?php echo $_GET["TID"];?>';</script>
<script> var tody_h_date = get_formated_hijri_date(zezo_get_hijri_date('now'));</script>

<script src="fahd_functions.js"></script>
<script>
    get_teachers_st_count(teacher_id);
    get_ertiqa_st_count(teacher_id, tody_h_date);
    get_bra3m_st_count(teacher_id, tody_h_date);
</script>