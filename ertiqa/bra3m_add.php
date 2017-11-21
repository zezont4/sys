<?php require_once('../functions.php');

$userType = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}
$st_id = isset($_GET['StID']) ? $_GET['StID'] : null;
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

    if (!isset($_POST['StID'])) {
        exit('<h1 style="text-align:center;font-size:20px;color:red;">' .
            'لا يوجد ' . get_gender_label('st') . ' بهذا السجل المدني : ' . $st_id .
            '</h1>');
    }

    // search for dublicate musabakah ##############
    $query_Rsdublicate = sprintf("SELECT AutoNo FROM er_bra3m WHERE StID=%s and DarajahID>=%s",
        GetSQLValueString($_POST['StID'], "double"),
        GetSQLValueString($_POST['DarajahID'], "int"));
    $Rsdublicate = mysqli_query($localhost, $query_Rsdublicate) or die(mysqli_error($localhost));
    $row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
    $totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);
    if ($totalRows_Rsdublicate > 0) { ?>
        <?php include('../templates/header1.php'); ?>
        <br><br><br>
        <div style="direction:rtl;text-align:center;font-size:22px;">
            <p>
                عفوا... لايمكن التسجيل في نفس الدرجة أو درجة أدنى منها
            </p>
        </div>
        <?php exit;
    }
    $insertSQL = sprintf("INSERT INTO er_bra3m (EdarahID,HalakahID,TeacherID,StID,DarajahID,SchoolLevelID,Money,DDate) VALUES ( %s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['EdarahID'], "int"),
        GetSQLValueString($_POST['HalakahID'], "int"),
        GetSQLValueString($_POST['TeacherID'], "double"),
        GetSQLValueString($_POST['StID'], "double"),
        GetSQLValueString($_POST['DarajahID'], "int"),
        GetSQLValueString($_POST['SchoolLevelID'], "int"),
        GetSQLValueString($_POST['Money'], "int"),
        GetSQLValueString(getHijriDate()->date, "int")
    );
    //echo $insertSQL; exit;
    $Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));

    if ($Result1) {
        $msg = "bra3m";
        header("Location: ../ertiqa/statistics/studentexams.php?msg=" . $msg . "&StudentID=" . $_POST['StID']);
    }

}

?>

<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'سلم البراعم'; ?>
<title><?php echo $PageTitle; ?></title>
<style type="text/css">
    .FieldsButton .note {
        color: #4FA64B;
    }
</style>
<script>
    $(document).ready(function () {
        $('#DarajahID').change(function () {
            $('#Money').val(GetBra3mMoney(this.value));
        });
    });
</script>

</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
    <div class="content lp CSSTableGenerator">
        <?php if (login_check("admin,edarh") == true) { ?>
        <?php echo get_st_info($_GET['StID']); ?>
    </div>
    <div class="content">
        <input name="MM_insert" type="hidden" value="form1">
        <div class="FieldsTitle">بيانات الدرجة</div>
        <div class="four columns alpha">
            <div class="LabelContainer">الصف الدراسي</div>
            <select class="FullWidthCombo" name="SchoolLevelID" id="SchoolLevelID" data-required="true">
                <option value>اختر الصف الدراسي</option>
                <?php $ii = 0;
                do { ?>
                    <option value="<?php echo $ii ?>"><?php echo $SchoolLevelName[$ii] ?></option>
                    <?php $ii++;
                } while ($ii < count($SchoolLevelName)); ?>
            </select>
        </div>
        <div class="four columns">
            <div class="LabelContainer">الدرجة في السلم</div>
            <select class="FullWidthCombo" name="DarajahID" id="DarajahID" data-required="true">
                <option value>اختر الدرجة</option>
                <?php $ii = 0;
                do {
                    $ii++; ?>
                    <option value="<?php echo $ii ?>"><?php echo $bra3mName[$ii] ?></option>
                <?php } while ($ii < 5); ?>
            </select>
        </div>

        <div class="four columns">
            <div class="LabelContainer">تاريخ الدرجة</div>
            <input name="DDate" type="text" id="DDate" data-required="true" disabled>
        </div>

        <div class="four columns omega">
            <div class="LabelContainer">الجائزة</div>
            <input name="Money" type="text" id="Money" value="" readonly data-required="true">
        </div>
        <br class="clear"/>
        <div class="four columns omega left">
            <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
        </div>
        <br class="clear"/>
        <div class="sixteen columns">
            <p class="note">ملحوظة: لا يقبل لسلم البراعم من هم أعلى من الصف الثالث الابتدائي.</p>
        </div>
        <?php } else {
            echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
        } ?>
    </div>

</form>
<script>
    $(document).ready(function () {
        $('#DDate').val(get_formated_hijri_date(zezo_get_hijri_date('now')));
    });
</script>

<?php include('../templates/footer.php'); ?>

<?php
if (isset($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            $('#DDate').val(get_formated_hijri_date(zezo_get_hijri_date('now')));
            alertify.success("تم تسجيل الدرجة بنجاح");
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