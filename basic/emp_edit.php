<?php require_once('../functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf("UPDATE `0_employees` SET name1=%s,name2=%s,name3=%s,name4=%s,mobile_no=%s,edarah_id=%s,start_date=%s, qualification=%s, job_title=%s
WHERE id=%s",
        GetSQLValueString($_POST['name1'], "text"),
        GetSQLValueString($_POST['name2'], "text"),
        GetSQLValueString($_POST['name3'], "text"),
        GetSQLValueString($_POST['name4'], "text"),
        GetSQLValueString($_POST['mobile_no'], "text"),
        GetSQLValueString($_POST['EdarahID'], "int"),
        GetSQLValueString(str_replace('/', '', $_POST['start_date']), "text"),
        GetSQLValueString($_POST['qualification'], "int"),
        GetSQLValueString($_POST['job_title'], "int"),
        GetSQLValueString($_POST['id'], "double"));

    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        header("Location: " . $editFormAction);
        exit;
    }
}

$id = "-1";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
$query = sprintf("SELECT * FROM 0_employees WHERE id = %s", GetSQLValueString($id, "double"));
$rs = mysqli_query($localhost, $query) or die(mysqli_error($localhost));
$row = mysqli_fetch_assoc($rs);

include('../templates/header1.php');
$PageTitle = 'تعديل ' . get_gender_label('emp', '');; ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<div class="content lp">
    <?php if (login_check("admin,edarh,t3lem") == true) { ?>
        <form method="post" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="name1">الاسم</label>
                </div>
                <input type="text" name="name1" id="name1" value="<?php echo $row['name1']; ?>" data-required="true">
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="name2">الأب</label>
                </div>
                <input type="text" name="name2" id="name2" value="<?php echo $row['name2']; ?>" data-required="true">
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="name3">الجد</label>
                </div>
                <input type="text" name="name3" id="name3" value="<?php echo $row['name3']; ?>" data-required="true">
            </div>

            <div class="four columns omega">
                <div class="LabelContainer">
                    <label for="name4">العائلة</label>
                </div>
                <input type="text" name="name4" id="name4" value="<?php echo $row['name4']; ?>" data-required="true">
            </div>

            <br class="clear"/>

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="national_id">السجل المدني</label>
                </div>
                <input type="text" name="national_id" id="national_id" value="<?php echo $row['national_id']; ?>" size="32" disabled="disabled">
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="mobile_no">رقم جوال المعلم</label>
                </div>
                <input type="tel" name="mobile_no" id="mobile_no" value="<?php echo $row['mobile_no']; ?>" data-type="digits" data-maxlength="10" data-minlength="10"
                       data-required="true">
            </div>

            <div class="four columns">
                <div class="LabelContainer">المؤهل العلمي</div>
                <?php echo create_combo("qualification", $qualification, 0, $row['qualification'], 'data-required="true"'); ?>
            </div>

            <div class="four columns omega">
                <div class="LabelContainer">
                    <label for="start_date">تاريخ المباشرة</label>
                </div>
                <input type="text" name="start_date" id="start_date" value="<?php echo StringToDate($row['start_date']); ?>" zezo_date="true">
            </div>

            <br class="clear"/>
            <br class="clear"/>

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="TEdarah">المجمع</label>
                </div>
                <?php create_edarah_combo($row['edarah_id']); ?>
            </div>

            <div class="four columns ">
                <div class="LabelContainer">العمل في <?php echo get_gender_label('e', 'ال'); ?></div>
                <?php echo create_combo("job_title", $job, 0, $row['job_title'], 'data-required="true"'); ?>
            </div>


            <br class="clear"/>

            <div class="four columns omega left">
                <input type="submit" class="button-primary" value="حفظ التعديلات">
            </div>

            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        </form>
    <?php } else {
        echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
    } ?>

</div>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
if (isset($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.success("تمت التعديل بنجاح");
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