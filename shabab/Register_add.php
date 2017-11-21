<?php require_once('../Connections/localhost.php');
require_once('../functions.php');
require_once '../secure/functions.php';
sec_session_start();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$userType = 0;
if (isset($_SESSION['user_group'])) {
    $userType = $_SESSION['user_group'];
}

$EdarahIDS = $_SESSION['user_id'];
// check birth day ###############
$StID = "-1";
if (isset($_GET['StID'])) {
    $StID = $_GET['StID'];
}
$query_RSbirthDate = sprintf("SELECT StBurthDate FROM `0_students` where StID=%s", $StID);
$RSbirthDate = mysqli_query($localhost, $query_RSbirthDate) or die(mysqli_error($localhost));
$row_RSbirthDate = mysqli_fetch_assoc($RSbirthDate);
$birthDate = (int)$row_RSbirthDate["StBurthDate"];
$today = 14380201;
$years_diff = 0;
$age = '';
if ($birthDate) {
    $years_diff = dateDiff360($today, $birthDate)->years;
    $months_diff = dateDiff360($today, $birthDate)->months;
    $days_diff = dateDiff360($today, $birthDate)->days;

    $age = implode(' ', [
        'في تاريخ :',
        StringToDate($today),
        'هـ',
        'سيكون عمر',
        get_gender_label('st', 'ال'),
        $years_diff,
        '(سنة)',
        'و',
        $months_diff,
        '(شهر)',
        'و',
        $days_diff,
        '(يوم)',
    ]);
}

$allowRegister = true;
$noRegisterMSG = "";
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    // search for dublicate musabakah ##############
    $dublicate_RsSTID = "-1";
    if (isset($_POST['StID'])) {
        $dublicate_RsSTID = $_POST['StID'];
    }
    $MsbkhID = "-1";
    if (isset($_POST['MsbkhID'])) {
        $MsbkhID = $_POST['MsbkhID'];
    }
    $query_Rsdublicate = sprintf('SELECT StID,MsbkhID FROM `ms_shabab_rgstr` WHERE `StID`=%s and MsbkhID = %s', GetSQLValueString($dublicate_RsSTID, "int"), GetSQLValueString($MsbkhID, "int"));
    $Rsdublicate = mysqli_query($localhost, $query_Rsdublicate) or die(mysqli_error($localhost));
    $row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
    $totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);
    if ($totalRows_Rsdublicate > 0) {
        include('../templates/header1.php');
        echo '<h1 style="text-align:center;margin:20px;font-size:22px;">'
            . "<br><br>" . "تم تسجيل " . get_gender_label('st', 'ال') . " في هذه المسابقة" . "</h1>";
        exit;
    }

    if ($birthDate == null) {
        $allowRegister = false;
        $noRegisterMSG = "لايمكن قبول " . get_gender_label('st', 'ال') . " للسبب التالي:" . "<br><br>" . "يجب تسجيل تاريخ الميلاد في بيانات " . get_gender_label('st', 'ال') . " الأساسية.";
    } else {

        if (($MsbkhID == 0 && $years_diff >= 21 && $years_diff < 25) ||// 20 جزء
            ($MsbkhID == 1 && $years_diff >= 16 && $years_diff < 21) ||// 10 أجزاء
            ($MsbkhID == 2 && $years_diff >= 11 && $years_diff < 16)// 5 أجزاء
        ) {
            $allowRegister = true;
            $noRegisterMSG = '';
        } else {
            $allowRegister = false;
            $noRegisterMSG = "لايمكن قبول " . get_gender_label('st', 'ال') . " للسبب التالي:" . "<br><br>" . "عدم استيفاء شرط العمر.";

        }
    }
    if ($allowRegister) {
        $insertSQL = sprintf("INSERT INTO ms_shabab_rgstr (EdarahID,HalakahID,TeacherID,StID, MsbkhID,SchoolLevelID,ErtiqaID,RDate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['EdarahID'], "int"),
            GetSQLValueString($_POST['HalakahID'], "int"),
            GetSQLValueString($_POST['TeacherID'], "double"),
            GetSQLValueString($_POST['StID'], "double"),
            GetSQLValueString($_POST['MsbkhID'], "int"),
            GetSQLValueString($_POST['SchoolLevelID'], "int"),
            GetSQLValueString($_POST['ErtiqaID'], "int"),
            GetSQLValueString(str_replace('/', '', $_POST['RDate']), "int")
        );
        $Result1 = mysqli_query($localhost, $insertSQL) or die(' $insertSQL ' . mysqli_error($localhost));


        if ($Result1) {
            $msg = "shabab";
            header("Location: ../ertiqa/statistics/studentexams.php?msg=" . $msg . "&StudentID=" . $_POST['StID']);
            //exit;
        }
    }
}

?>
<?php
if (isset($_SESSION['user_id'])) {
    $colname_RSEdarat = $_SESSION['user_id'];
}
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'التسجيل في مسابقة الهيئة العامة للرياضة'; ?>
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

<?php $closed = 'no';
if ($closed == 'no') { ?>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
        <input name="MM_insert" type="hidden" value="form1">

        <div class="content  CSSTableGenerator">
            <?php if (login_check("admin,edarh,ms") == true) { ?>
            <?php echo get_st_info($_GET['StID']); ?>
            <br>
            <p style="text-align: center;color: darkblue"><?php echo $age; ?></p>
        </div>

        <div class="content">
            <div class="FieldsTitle">بيانات المسابقة</div>
            <div class="four columns alpha">
                <div class="LabelContainer">السنة الدراسي</div>
                <?php echo create_combo("SchoolLevelID", $SchoolLevelNameAll, 0, '', 'class="full-width" data-required="true"'); ?>
            </div>
            <div class="four columns">
                <div class="LabelContainer">آخر مرتقى اجتازه</div>
                <?php echo create_combo("ErtiqaID", $murtaqaName, 1, '', 'class="full-width" data-required="true"'); ?>
            </div>

            <div class="four columns">
                <div class="LabelContainer">نوع المسابقة</div>
                <?php echo create_combo("MsbkhID", $shabab_msbkh_type, 0, $MsbkhID, 'class="full-width" data-required="true"'); ?>
            </div>
            <div class="four columns omega">
                <div class="LabelContainer">تاريخ التسجيل (اليوم)</div>
                <input name="RDate" type="text" id="RDate" value="" data-required="true" READONLY/>
            </div>

            <div class="clear"></div>
            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
            </div>
            <?php if (!$allowRegister) { ?>
                <div class="sixteen columns">
                    <p style="text-align:center;font-size:18px;margin-top:10px ;color:red;"><?php echo $noRegisterMSG; ?></p>
                </div>
            <?php } ?>


            <?php } else {
                echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
            } ?>
        </div>
    </form>

    <script type="text/javascript">
        //FillList(<?php //echo $EdarahIDS; ?>,'HalakahID','AutoNo','HName','SELECT * FROM 0_halakat WHERE EdarahID = %s ORDER BY HName','لا يوجد حلقات','اختر الحلقة');
    </script>

    <!-- closed -->
<?php } else {
    echo '<p class="WrapperMSG" >' . 'عفوا .. انتهت فترة التسجيل في مسابقة الهيئة العامة للرياضة' . '</p>';
} ?>

<?php include('../templates/footer.php'); ?>

<?php
if (isset($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.success("تم التسجيل في المسابقة بنجاح");
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

<script>
    $(document).ready(function () {
        $('#RDate').val(get_formated_hijri_date(zezo_get_hijri_date('now')));
    });
</script>
