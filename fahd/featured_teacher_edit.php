<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once('fahd_functions.php'); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$userType = 'xx';
if (isset($_SESSION['user_group'])) {
    $userType = $_SESSION['user_group'];
}


$auto_no = "-1";
if (isset($_GET['auto_no'])) {
    $auto_no = $_GET['auto_no'];
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $f_t_chkbx_array = array('3a', '3b', '4b', '5a', '5b', '5c', '7a', '9a', '15a', '15b', '12a', '13a');
    $length = count($f_t_chkbx_array);
    for ($i = 0; $i < $length; $i ++) {
        $f_0_1 = ($_POST['f_' . $f_t_chkbx_array[$i] . '_n'] == 1) ? '1' : '0';
        $chk_sql = $chk_sql . ',f_' . $f_t_chkbx_array[$i] . '_n = ' . $f_0_1;

        $f_0_1 = ($_POST['f_' . $f_t_chkbx_array[$i] . '_d'] > 0) ? $_POST['f_' . $f_t_chkbx_array[$i] . '_d'] : '0';
        $chk_sql = $chk_sql . ',f_' . $f_t_chkbx_array[$i] . '_d = ' . $f_0_1;
    }

    if ($userType != "xx") {
        $f_t_txt2bx_array = array('1a', '4a', '8a', '10a', '11a', '14a');
    } else {
        $f_t_txt2bx_array = array('4a', '8a', '10a', '11a');
    }
    $length = count($f_t_txt2bx_array);
    for ($i = 0; $i < $length; $i ++) {
        $f_0_1 = ($_POST['f_' . $f_t_txt2bx_array[$i] . '_n'] > 0) ? $_POST['f_' . $f_t_txt2bx_array[$i] . '_n'] : '0';
        $txt2_sql = $txt2_sql . ',f_' . $f_t_txt2bx_array[$i] . '_n = ' . $f_0_1;

        $f_0_1 = ($_POST['f_' . $f_t_txt2bx_array[$i] . '_d'] > 0) ? $_POST['f_' . $f_t_txt2bx_array[$i] . '_d'] : '0';
        $txt2_sql = $txt2_sql . ',f_' . $f_t_txt2bx_array[$i] . '_d = ' . $f_0_1;
    }

    if ($userType != "xx") {
        $f_t_txt3bx_array = array('2a', '2b', '6a');
    } else {
        $f_t_txt3bx_array = array('2a', '2b');
    }
    $length = count($f_t_txt3bx_array);
    for ($i = 0; $i < $length; $i ++) {
        $f_0_1 = ($_POST['f_' . $f_t_txt3bx_array[$i] . '_n'] > 0) ? $_POST['f_' . $f_t_txt3bx_array[$i] . '_n'] : '0';
        $txt3_sql = $txt3_sql . ',f_' . $f_t_txt3bx_array[$i] . '_n = ' . $f_0_1;

        $f_0_1 = ($_POST['f_' . $f_t_txt3bx_array[$i] . '_t'] > 0) ? $_POST['f_' . $f_t_txt3bx_array[$i] . '_t'] : '0';
        $txt3_sql = $txt3_sql . ',f_' . $f_t_txt3bx_array[$i] . '_t = ' . $f_0_1;

        $f_0_1 = ($_POST['f_' . $f_t_txt3bx_array[$i] . '_d'] > 0) ? $_POST['f_' . $f_t_txt3bx_array[$i] . '_d'] : '0';
        $txt3_sql = $txt3_sql . ',f_' . $f_t_txt3bx_array[$i] . '_d = ' . $f_0_1;
    }
    $approved = isset($_POST['approved']) ? 1 : 0;
    $updateSQL = "UPDATE ms_fahd_featured_teacher SET
f_t_date='" . str_replace("/", "", $_POST['f_t_date']) . "',full_degree=" . $_POST['full_degree'] . ",approved=" . $approved . $chk_sql . $txt2_sql . $txt3_sql . ' WHERE auto_no=' . $auto_no;
    //    exit($updateSQL);
    mysqli_select_db($localhost, $database_localhost);
    $Result1 = mysqli_query($localhost, $updateSQL) or die(' update 1 : ' . mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
    }

}
mysqli_select_db($localhost, $database_localhost);
$query_rs_f_teacher = sprintf("SELECT * FROM ms_fahd_featured_teacher WHERE auto_no=%s", GetSQLValueString($auto_no, "int"));
$rs_f_teacher = mysqli_query($localhost, $query_rs_f_teacher) or die(mysqli_error($localhost));
$row_rs_f_teacher = mysqli_fetch_assoc($rs_f_teacher);
$totalRows_rs_f_teacher = mysqli_num_rows($rs_f_teacher);

$colname_Rs_T = $row_rs_f_teacher['teacher_id'];

mysqli_select_db($localhost, $database_localhost);
$query_Rs_T = sprintf("SELECT TID,Tfullname,arabic_name,HName,TEdarah FROM view_0_teachers WHERE TID = %s", $colname_Rs_T);
$Rs_T = mysqli_query($localhost, $query_Rs_T) or die(mysqli_error($localhost));
$row_Rs_T = mysqli_fetch_assoc($Rs_T);
$totalRows_Rs_T = mysqli_num_rows($Rs_T);
?>
<?php
if (isset($_SESSION['user_id'])) {
    $colname_RSEdarat = $_SESSION['user_id'];
}
?>
<?php
//انشاء عنوان القسم
function create_f_t_block_head($array_index) {
    //require('fahd_functions.php');
    global $featured_teacher_dep, $featured_teacher_title, $userType;
    if ($userType != 'xx') {
        $b1 = '
		<div class="content">
				
				<div class="FieldsTitle"><span class="dep">' . $featured_teacher_dep[$array_index] . ' </span> ' . $featured_teacher_title[$array_index] . '</div>';
    } else {
        $b1 = '
			<div class="content">
					<div class="FieldsTitle">' . $featured_teacher_title[$array_index] . '</div>';
    }
    echo $b1;
}

//انشاء بند نصي
function create_f_t_block_bnd_txt($bnd_title, $db_feild_name, $last_bnd = true, $ex_index = 0, $readonly = '') {
    global $degree_title, $row_rs_f_teacher, $featured_teacher_ex;
    $b1 = '
			<div class="six columns alpha omega top_padding">' . $bnd_title . '</div>
			<div class="three columns alpha">' . create_text("f_" . $db_feild_name . "_n", 'value="' . $row_rs_f_teacher["f_" . $db_feild_name . "_n"] . '"' . $readonly) . '</div>
			<div class="four columns alpha omega left">
				<div class="two columns  alpha top_padding">' . $degree_title . '</div>
				<div class="two columns omega">' . create_text("f_" . $db_feild_name . "_d", 'value="' . $row_rs_f_teacher["f_" . $db_feild_name . "_d"] . '" readonly class="bnd_degree"') . '</div>
			</div>
			<br class="clear"><div class="top_padding"></div>
		';
    echo $b1;
    if ($last_bnd == true) {
        echo $featured_teacher_ex[$ex_index] . "</div>";
    }
}

//انشاء عدد (2) بند نصي
function create_f_t_block_bnd_txt2($bnd_title1, $bnd_title2, $db_feild_name, $last_bnd = true, $ex_index = 0, $readonly = '') {
    global $degree_title, $row_rs_f_teacher, $featured_teacher_ex;
    $b1 = '
				<div class="three columns alpha omega top_padding">' . $bnd_title1 . '</div>
				<div class="two columns alpha">' . create_text("f_" . $db_feild_name . "_n", 'value="' . $row_rs_f_teacher["f_" . $db_feild_name . "_n"] . '"' . $readonly) . '</div>
				
				<div class="three columns alpha omega top_padding">' . $bnd_title2 . '</div>
				<div class="two columns">' . create_text("f_" . $db_feild_name . "_t", 'value="' . $row_rs_f_teacher["f_" . $db_feild_name . "_t"] . '" readonly') . '</div>
			
			<div class="four columns alpha omega left">
				<div class="two columns  alpha top_padding">' . $degree_title . '</div>
				<div class="two columns omega">' . create_text("f_" . $db_feild_name . "_d", 'value="' . $row_rs_f_teacher["f_" . $db_feild_name . "_d"] . '" readonly class="bnd_degree"') . '</div>
			</div>
			<br class="clear"><div class="top_padding"></div>
		';
    echo $b1;
    if ($last_bnd == true) {
        echo $featured_teacher_ex[$ex_index] . "</div>";
    }
}

//انشاء بند مربع اختيار
function create_f_t_block_bnd_chk($bnd_title, $db_feild_name, $last_bnd = true, $ex_index = 0) {
    global $degree_title, $row_rs_f_teacher, $featured_teacher_ex;
    if ($row_rs_f_teacher["f_" . $db_feild_name . "_n"] == 1) {
        $checked = ' checked="checked" ';
    }
    $b1 = '
			<div class="six columns alpha omega top_padding">' . create_chkbox("f_" . $db_feild_name . "_n", $checked) . $bnd_title . '</div>
			
			<div class="four columns alpha omega left">
				<div class="two columns  alpha top_padding">' . $degree_title . '</div>
				<div class="two columns omega">' . create_text("f_" . $db_feild_name . "_d", 'value="' . $row_rs_f_teacher["f_" . $db_feild_name . "_d"] . '" readonly class="bnd_degree"') . '</div>
			</div>
			<br class="clear"><div class="top_padding"></div>
		';
    echo $b1;
    if ($last_bnd == true) {
        echo $featured_teacher_ex[$ex_index] . "</div>";
    }
}

//انشاء بند نصي
function create_f_t_block_bnd_cmbo($bnd_title, $db_feild_name, $vals, $last_bnd = true, $ex_index = 0, $readonly = '') {
    global $degree_title, $row_rs_f_teacher, $featured_teacher_ex;
    $b1 = '
				<div class="six columns alpha omega top_padding">' . $bnd_title . '</div>';
    if ($readonly !== 'readonly') {
        $b1 .= '<div class="four columns alpha">' . create_combo("f_" . $db_feild_name . "_n", $vals, 0, $row_rs_f_teacher["f_" . $db_feild_name . "_n"], 'class="full-width"') . '</div>';
    }
    $b1 .= '<div class="four columns alpha omega left">
				<div class="two columns  alpha top_padding">' . $degree_title . '</div>
				<div class="two columns omega">' . create_text("f_" . $db_feild_name . "_d", 'value="' . $row_rs_f_teacher["f_" . $db_feild_name . "_d"] . '" readonly class="bnd_degree"') . '</div>
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
<?php $PageTitle = 'تعديل بيانات معلم في مسابقة المعلم المتميز'; ?>
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


<!-- closed -->
<?php $closed = 'no';
if ($closed == 'no') { ?>
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
                <div class="FieldsTitle">التاريخ</div>
            </div>
            <div class="three columns alpha top_padding">تاريخ تسجيل البيانات</div>
            <div class="three columns"><input name="f_t_date" type="text" id="f_t_date"
                                              value="<?php echo StringToDate($row_rs_f_teacher["f_t_date"]); ?>"
                                              data-required="true" readonly/></div>
        </div>
        <?php
        //if is admin
        //zlog('xx  : '.$userType);
        //درجات الأداء الوظيفي
        create_f_t_block_head(1);
        if ($userType != "xx") {
            create_f_t_block_bnd_txt("درجة تقييم المشرف", "1a", true, 1);
        } else {
            create_f_t_block_bnd_txt("درجة تقييم المشرف", "1a", true, 1, 'readonly');
        }
        //نسبة النجاح في المرتقيات أو سلم البراعم
        create_f_t_block_head(2);
        create_f_t_block_bnd_txt2("عدد المجتازين للمرتقيات", "عــدد طــلاب الحـــلـــقـــة", "2a", false);
        create_f_t_block_bnd_txt2("عدد الناجحين في البراعم", "عــدد طــلاب الحـــلـــقـــة", "2b", true, 2);

        //المشاركة في المسابقات الداخلية
        create_f_t_block_head(3);
        create_f_t_block_bnd_chk("المشاركة في مسابقة الأفراد", "3a", false);
        create_f_t_block_bnd_chk("المشاركة في مسابقة الحلقات", "3b", true, 3);

        //نتائج المسابقات الداخلية
        create_f_t_block_head(4);
        create_f_t_block_bnd_cmbo("التأهيل أو الفوز بالأفراد", "4a", $b_4a, false);
        create_f_t_block_bnd_chk("فائز بمسابقة الحلقات", "4b", true, 4);

        //حضور الدورات التدريبية في الجمعية
        create_f_t_block_head(5);
        create_f_t_block_bnd_chk("حاصل على التحفة", "5b", false);
        create_f_t_block_bnd_chk("حاصل على الجزرية", "5a", false);
        create_f_t_block_bnd_chk("حضر دورة تطويرية (٤ ساعات فأكثر)", "5c", true, 5);

        //المشاركة في الدورات التدريبية السابقة أو الخارجية
        create_f_t_block_head(10);
        create_f_t_block_bnd_txt("عدد ساعات الدورات التطويرية في السنتين السابقتين", "10a", true, 10);


        //استخدام منهجية لحفظ الطلاب ومراجعتهم
        create_f_t_block_head(7);
        create_f_t_block_bnd_chk("استخدام منهجية الجمعية أو أعلى منها", "7a", true, 7);

        //حفظه للقرآن الكريم
        create_f_t_block_head(8);
        create_f_t_block_bnd_cmbo("حفظه للقرآن الكريم", "8a", $murtaqa_name, true, 8);

        //استخدام منهجية لحفظ الطلاب ومراجعتهم
        create_f_t_block_head(9);
        create_f_t_block_bnd_chk("حاضر في أحد البرامج", "9a", true, 9);

        //حضور الاجتماعات واللقاءات وتفعيل برامج الجمعية
        create_f_t_block_head(6);
        if ($userType != "xx") {
            create_f_t_block_bnd_txt2("عدد الاجتماعات واللقاءات", "اجمالي الاجتماعات واللقاءات", "6a", true, 6);
        } else {
            create_f_t_block_bnd_txt2("عدد الاجتماعات واللقاءات", "اجمالي الاجتماعات واللقاءات", "6a", true, 6, 'readonly');
        }


        //شفاء الصدور
        create_f_t_block_head(15);
        create_f_t_block_bnd_chk("شفاء الصدور", "15a", false);
        create_f_t_block_bnd_chk("رتل", "15b", true, 15);


        //استخدام فكرة جديدة تحقق أهدافًا تربوية
        create_f_t_block_head(11);
        create_f_t_block_bnd_txt("عدد الأفكار الجديدة", "11a", true, 11);

        //حاصل على إجازة في حفظ القرآن
        create_f_t_block_head(12);
        create_f_t_block_bnd_chk("حاصل على إجازة", "12a", true, 12);

        //حاصل على الشاطبية
        create_f_t_block_head(13);
        create_f_t_block_bnd_chk("حاصل على الشاطبية", "13a", true, 13);

        //المسابقات الخارجية
        create_f_t_block_head(14);
        if ($userType != "xx") {
            create_f_t_block_bnd_cmbo("المشاركة أو الفوز بالمسابقات الخارجية", "14a", $f_14a, true, 14);
        } else {
            create_f_t_block_bnd_cmbo("المشاركة أو الفوز بالمسابقات الخارجية", "14a", $f_14a, true, 14, 'readonly');
        }

        if ($userType != "xx") { ?>
            <div class="content">
                <label>
                    <input type="checkbox" name="approved" id="approved" <?php if ($row_rs_f_teacher['approved'] == 1)
                        echo 'checked'; ?> value="1">
                    اعتماد نهائي وإيقاف التعديلات على المعلم
                </label>
            </div>
        <?php } ?>
        <div class="content">
            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
            </div>
            <input name="MM_insert" type="hidden" value="form1">
        </div>
    </form>
    <!-- closed -->
<?php } else {
    echo '<p class="WrapperMSG" >' . 'عفوا .. انتهت فترة التسجيل في مسابقة المعلم المتميز' . '</p>';
} ?>
</div>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
if (isset($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.success("تم تعديل المسابقة بنجاح");
        });

    </script>
    <?php
    $_SESSION['u1'] = null;
    unset($_SESSION['u1']);
}
?>
<script type="text/javascript">
    showError();
</script>
<script> var teacher_id = '<?php echo $_GET["TID"];?>';</script>
<script src="fahd_functions.js"></script>