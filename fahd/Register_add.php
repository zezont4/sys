<?php
require_once('../Connections/localhost.php');
require_once('../functions.php');
require_once '../secure/functions.php';
require_once('fahd_functions.php');

$open_g = true;//السماح للتسجيل للبنات
$open_b = true;//السماح للتسجيل للبنين

//متغير يحفظ نتيجة السماح بالتسجيل بحث يفحص جنس المستخدم الحالي مع المتغيرات في الأعلى
$allowRegester = false;

sec_session_start();
$PageTitle = 'التسجيل في مسابقة الفهد';
if (login_check('admin,ms,edarh') == true) {

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $userType = 0;
    if (isset($_SESSION['user_group'])) {
        $userType = $_SESSION['user_group'];
    }

    $EdarahIDS = $_SESSION['user_id'];


    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
        // search for dublicate musabakah ##############
        $dublicate_RsSTID = "-1";
        if (isset($_POST['StID'])) {
            $dublicate_RsSTID = $_POST['StID'];
        }
        $dublicate_RsMsbkhID = "-1";
        if (isset($_POST['MsbkhID'])) {
            $dublicate_RsMsbkhID = $_POST['MsbkhID'];
        }
        $study_fahd_start = get_fahd_year_start($_POST['RDate']);
        $study_fahd_end = get_fahd_year_end($_POST['RDate']);
        $study_fahd_name = get_fahd_year_name($_POST['RDate']);

        mysqli_select_db($localhost, $database_localhost);
        $query_Rsdublicate = sprintf("SELECT AutoNo,StID,MsbkhID,RDate FROM `ms_fahd_rgstr` WHERE `StID`=%s AND MsbkhID = %s AND RDate>=%s AND RDate<=%s", GetSQLValueString($dublicate_RsSTID, "int"), GetSQLValueString($dublicate_RsMsbkhID, "int"), GetSQLValueString($study_fahd_start, "int"), GetSQLValueString($study_fahd_end, "int"));
        $Rsdublicate = mysqli_query($localhost, $query_Rsdublicate) or die(mysqli_error($localhost));
        $row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
        $totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);
        if ($totalRows_Rsdublicate > 0) {
            include('../templates/header1.php');
            echo '<h1 style="text-align:center;margin:20px;font-size:22px;">' . "<br><br>" . get_gender_label('st', 'ال') . ' : ' . get_student_name($dublicate_RsSTID) . ' قام بالتسجيل سابقا في العام الدراسي : ' . $study_fahd_name . ' وذلك في تاريخ : ' . StringToDate($row_Rsdublicate['RDate']) . '<br><br><br>' . 'ولا يمكن تكرار المشاركة أكثر من مرة في العام الواحد' . '<br><br>' . '<a href="/sys/fahd/Register_edit.php?auto_no=' . $row_Rsdublicate['AutoNo'] . '">' . 'وإذا أردت تعديل المسابقة، اضغط هنا' . '</a>' . "</h1>";
            exit;
        }


        $insertSQL = sprintf("INSERT INTO ms_fahd_rgstr (EdarahID,HalakahID,TeacherID,StID,st_type,MsbkhID,SchoolLevelID,ErtiqaID,RDate) VALUES ( %s,%s,%s,%s,%s,%s,%s,%s,%s)", GetSQLValueString($_POST['EdarahID'], "int"), GetSQLValueString($_POST['HalakahID'], "int"), GetSQLValueString($_POST['TeacherID'], "double"), GetSQLValueString($_POST['StID'], "double"), GetSQLValueString($_POST['st_type'], "int"), GetSQLValueString($_POST['MsbkhID'], "int"), GetSQLValueString($_POST['SchoolLevelID'], "int"), GetSQLValueString($_POST['ErtiqaID'], "int"), GetSQLValueString(str_replace('/', '', $_POST['RDate']), "int"));

        //echo $insertSQL;
        //exit;
        mysqli_select_db($localhost, $database_localhost);
        $Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));


        if ($Result1) {
            $msg = "fahd";
            header("Location: ../ertiqa/statistics/studentexams.php?msg=" . $msg . "&StudentID=" . $_POST['StID']);
            //exit;
        }

    }

    ?>
    <?php include('../templates/header1.php'); ?>
<title><?php echo $PageTitle; ?></title>
<style type="text/css">
    .FieldsButton .note {
        color: #4FA64B;
    }
</style>
</head>
<body>
<?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->

<?php

// check birth day ###############
    $StID_RsHalakat = "-1";
    if (isset($_GET['StID'])) {
        $StID_RsHalakat = $_GET['StID'];
    }
    mysqli_select_db($localhost, $database_localhost);
    $query_RSbirthDate = sprintf("SELECT StBurthDate,guardian_name FROM `0_students` WHERE StID=%s", $StID_RsHalakat);
    $RSbirthDate = mysqli_query($localhost, $query_RSbirthDate) or die(mysqli_error($localhost));
    $row_RSbirthDate = mysqli_fetch_assoc($RSbirthDate);
    $birthDate = $row_RSbirthDate["StBurthDate"];
    $guardian_name = $row_RSbirthDate["guardian_name"];

//التحقق من تاريخ الميلاد
    if ($birthDate == null) {
        $allowRegester = false;
//        echo 'asdf' . $allowRegester;
        $noRegesterMSG = "لايمكن قبول " . get_gender_label('st', 'ال') . " للسبب التالي:" . "<br><br>" . "يجب تسجيل تاريخ الميلاد في بيانات " . get_gender_label('st', 'ال') . " الأساسية.";
    } else {


        if ($_SESSION['sex'] == 1) {
            $b_date = 14120824;
        } elseif ($_SESSION['sex'] == 0) {
            $b_date = 13970824;
        }


        if (intval($birthDate) > intval($b_date)) {
            $allowRegester = true;
            $noRegesterMSG = "";
        } else {
            $allowRegester = false;
            $noRegesterMSG = "لايمكن قبول " . get_gender_label('st', 'ال') . " للسبب التالي:" . "<br><br>" . "عدم استيفاء شرط العمر.";
        }

        if ($_SESSION['sex'] === 2) {
            $allowRegester = false;
            $noRegesterMSG = "عفوا.. يرجى تسجيل الخروج ثم تسجيل الدخول بمستخدم خاص بالبنين أو البنات فقط";
        }

        //اسم ولي الأمر للبنات
        if ($_SESSION['sex'] == 0) {
            if ($guardian_name == null) {
                $allowRegester = false;
                $noRegesterMSG = "لايمكن قبول " .
                    get_gender_label('st', 'ال') 
                    . " للسبب التالي:" .
                    "<br><br>" .
                    "لعدم تسجيل اسم ولي الأمر في بيانات " .
                    get_gender_label('st', 'ال').
                    " الأساسية." .
                                 '<br>'.
    '<a href="../basic/student_edit.php?StID='.$_GET['StID'].'&EdarahNo=55'.$_GET['EdarahNo'].'" target="_blank">اضغط هنا للتعديل</a>'
;
            }
        }
    }
                                                                                                     $open = false;

    if ($_SESSION['sex'] == 1 && $open_b == true) {
        $open = true;
    } elseif ($_SESSION['sex'] == 0 && $open_g == true) {
        $open = true;
    }

    if ($open == true) { ?>
        <?php if ($allowRegester === true) { ?>
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
        <?php } ?>
        <input name="MM_insert" type="hidden" value="form1">

        <div class="content  CSSTableGenerator">
            <?php echo get_st_info($_GET['StID']); ?>
        </div>

        <div class="content">
            <div class="four columns alpha">
                <div class="LabelContainer">نوع المتسابقة</div>
                <p>
                    <label>
                        <input type="radio" name="st_type" value="0" checked="checked" id="st_type_0">
                        أساسي</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" name="st_type" value="1" id="st_type_1">
                        احتياط</label>
                    <br>
                </p>
            </div>

            <div class="four columns">
                <div class="LabelContainer">السنة الدراسي</div>
                <?php echo create_combo("SchoolLevelID", $SchoolLevelNameAll, 0, '', 'class="full-width" data-required="true"'); ?>
            </div>

            <?php
            //مؤقت
            //البيانات الأساسية موجودة في ملف الفنكشن
            $MsbkhType = [
            ["", "حدد نوع المسابقة..."],
              ["7", "حلقات"]
              ];
            ?>

            <div class="four columns">
                <div class="LabelContainer">آخر مرتقى اجتازه</div>
                <?php echo create_combo("ErtiqaID", $murtaqaName, 0, '', 'class="full-width" data-required="true"'); ?>
            </div>

            <div class="four columns omega">
                <div class="LabelContainer">نوع المسابقة</div>
                <?php echo create_combo("MsbkhID", $MsbkhType, 0, '', 'class="full-width" data-required="true"'); ?>
            </div>

            <div class="LabelAndFieldContainer">
                <div class="LabelContainer">تاريخ التسجيل (اليوم)</div>
                <input name="RDate" type="text" id="RDate" value="" data-required="true" READONLY/>
            </div>


            <?php if ($allowRegester == true) { ?>
                <div class="four columns omega left">
                    <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
                </div>
            <?php } else { ?>
                <div class="sixteen columns">
                    <p style="text-align:center;font-size:20px;color:red;"><?php echo $noRegesterMSG; ?></p>
                </div>
            <?php } ?>
        </div>
        </form>

    <?php } else {
        echo '<div class="content"><p class="WrapperMSG" >' . 'عفوا .. انتهت فترة التسجيل في مسابقة الفهد' . '</p></div>';
    } ?>

    <?php include('../templates/footer.php'); ?>

    <?php
    if (isset($_SESSION['u1'])) { ?>
        <script>
            $(document).ready(function () {
                alertify.success("تم التسجيل في المسابقة بنجاح");
            });
        </script>
        <?php
        $_SESSION['u1'] = null;
        unset($_SESSION['u1']);
    } ?>

<script type="text/javascript">
    showError();
</script>

<script>
    $(document).ready(function () {
        $('#RDate').val(get_formated_hijri_date(zezo_get_hijri_date('now')));
    });
</script>
<?php } else {
    include('../templates/restrict_msg.php');
} ?>