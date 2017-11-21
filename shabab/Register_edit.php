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
$auto_no = "-1";
if (isset($_GET['auto_no'])) {
    $auto_no = $_GET['auto_no'];
}

//$editFormAction ="register_edit.php?AutoNo=".$auto_no;
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$query_RSshababExam = sprintf("SELECT * FROM ms_shabab_rgstr WHERE AutoNo=%s", GetSQLValueString($auto_no, "int"));
$RSshababExam = mysqli_query($localhost, $query_RSshababExam) or die(mysqli_error($localhost));
$row_RSshababExam = mysqli_fetch_assoc($RSshababExam);
$totalRows_RSshababExam = mysqli_num_rows($RSshababExam);

$StID = $row_RSshababExam['StID'];
$MsbkhID = $row_RSshababExam['MsbkhID'];
$ErtiqaID = $row_RSshababExam['ErtiqaID'];
$SchoolLevelID = $row_RSshababExam['SchoolLevelID'];
$EdarahID = $row_RSshababExam['EdarahID'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    // search for dublicate musabakah ##############
    //if ($OldMsbkhID!=$dublicate_RsMsbkhID){
    //zlog("dublicate found");
    $query_Rsdublicate = sprintf("SELECT AutoNo FROM ms_shabab_rgstr WHERE StID=%s and MsbkhID=%s and AutoNo<>%s",
        GetSQLValueString($StID, "double"),
        GetSQLValueString($_POST['MsbkhID'], "int"),
        GetSQLValueString($auto_no, "int"));
    //zlog($query_Rsdublicate);
    $Rsdublicate = mysqli_query($localhost, $query_Rsdublicate) or die(mysqli_error($localhost));
    $row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
    $totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);
    if ($totalRows_Rsdublicate > 0) {
        include('../templates/header1.php');
        echo '<h1 style="text-align:center;margin:20px;font-size:22px;">'
            . "<br><br>" . "تم تسجيل " . get_gender_label('st', 'ال') . " في هذه المسابقة" . "</h1>";
        exit;
    }
    //}


    $insertSQL = sprintf("update  ms_shabab_rgstr set  MsbkhID=%s,SchoolLevelID=%s,ErtiqaID=%s where AutoNo=%s",
        GetSQLValueString($_POST['MsbkhID'], "int"),
        GetSQLValueString($_POST['SchoolLevelID'], "int"),
        GetSQLValueString($_POST['ErtiqaID'], "int"),
        GetSQLValueString($auto_no, "int")
    );
    $Result1 = mysqli_query($localhost, $insertSQL) or die('$insertSQL ' . mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        header("Location: " . $editFormAction);
        exit;

        //header("Location: ".$editFormAction);
        //exit;
    }

}
?>
<?php
if (isset($_SESSION['user_id'])) {
    $colname_RSEdarat = $_SESSION['user_id'];
}
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'تعديل بيانات ' . get_gender_label('st', '') . ' في مسابقة الهيئة العامة للرياضة'; ?>
<title><?php echo $PageTitle; ?></title>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/cupertino/jquery-ui.css"/>
<!--<link rel="stylesheet" href="../_css/del/ui-cupertino.calendars.picker.css"/>-->
<style type="text/css">
    .FieldsButton .note {
        color: #4FA64B;
    }
</style>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->

<!-- closed -->
<?php $closed = 'no';
if ($closed == 'no') { ?>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
        <input name="MM_insert" type="hidden" value="form1">
        <div class="content CSSTableGenerator">
            <table>
                <caption>بيانات <?php echo get_gender_label('st', 'ال'); ?> الأساسية (وقت التسجيل بالمرتقى)</caption>
                <tr>
                    <td>اسم <?php echo get_gender_label('st', 'ال'); ?></td>
                    <td>المجمع</td>
                    <td>الحلقة</td>
                    <td>المعلم</td>
                </tr>
                <tr>
                    <td><?php echo get_student_name($row_RSshababExam['StID']); ?></td>
                    <td><?php echo get_edarah_name($row_RSshababExam['EdarahID']); ?></td>
                    <td><?php echo get_halakah_name($row_RSshababExam['HalakahID']); ?></td>
                    <td><?php echo get_teacher_name($row_RSshababExam['TeacherID']); ?></td>
                </tr>
            </table>
        </div>
        <div class="content">
            <div class="FieldsTitle">بيانات المسابقة</div>
            <div class="four columns alpha">
                <div class="LabelContainer">السنة الدراسي</div>
                <?php echo create_combo("SchoolLevelID", $SchoolLevelNameAll, 0, (int)$row_RSshababExam['SchoolLevelID'], 'data-required="true"'); ?>
            </div>
            <div class="four columns">
                <div class="LabelContainer">آخر مرتقى اجتازه</div>
                <?php echo create_combo("ErtiqaID", $murtaqaName, 1, $row_RSshababExam['ErtiqaID'], 'data-required="true"'); ?>
            </div>

            <div class="four columns">
                <div class="LabelContainer">نوع المسابقة</div>
                <?php echo create_combo("MsbkhID", $shabab_msbkh_type, 0, $row_RSshababExam['MsbkhID'], 'data-required="true"'); ?>
            </div>
            <div class="four columns omega">
                <div class="LabelContainer">تاريخ التسجيل</div>
                <input name="RDate" type="text" id="RDate"
                       value="<?php echo StringToDate($row_RSshababExam['RDate']); ?>" data-required="true" READONLY/>
            </div>
            <br class="clear">
            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
            </div>
    </form>
    <!-- closed -->
<?php } else {
    echo '<p class="WrapperMSG" >' . 'عفوا .. انتهت فترة التسجيل في مسابقة الفهد (حلقات)' . '</p>';
} ?>

<?php
if (isset($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.success("تم التعديل بنجاح");
        });

    </script>
    <?php
    $_SESSION['u1'] = NULL;
    unset($_SESSION['u1']);
}
?>
<?php include('../templates/footer.php'); ?>

<script type="text/javascript">
    showError();
</script>	    
  
