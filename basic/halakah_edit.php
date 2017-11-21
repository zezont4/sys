<?php require_once('../functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

    $h_type = isset($_POST['h_type']) ? '|' . implode($_POST['h_type'], '|,|') . '|' : '';

    $updateSQL = sprintf("UPDATE `0_halakat` SET HName=%s,EdarahID=%s,h_time=%s,h_place=%s,h_type=%s WHERE AutoNo=%s",
        GetSQLValueString($_POST['HName'], "text"),
        GetSQLValueString($_POST['EdarahID'], "int"),
        GetSQLValueString($_POST['h_time'], "int"),
        GetSQLValueString($_POST['h_place'], "text"),
        GetSQLValueString($h_type, "text"),
        GetSQLValueString($_POST['AutoNo'], "int")
    );

    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "";
    }

}


$colname_RsHalakat = "-1";
if (isset($_GET['AutoNo'])) {
    $colname_RsHalakat = $_GET['AutoNo'];
}
$query_RsHalakat = sprintf("SELECT * FROM `0_halakat` WHERE AutoNo = %s", GetSQLValueString($colname_RsHalakat, "int"));
$RsHalakat = mysqli_query($localhost, $query_RsHalakat) or die(mysqli_error($localhost));
$row_RsHalakat = mysqli_fetch_assoc($RsHalakat);
$totalRows_RsHalakat = mysqli_num_rows($RsHalakat);
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'تعديل الحلقة'; ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->

<div class="content">
    <?php if (login_check("admin,edarh,t3lem") == true) { ?>
        <div class="FieldsTitle">بيانات الحلقة</div>
        <form method="post" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">


            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="HName">اسم الحلقة</label>
                </div>
                <input type="text" name="HName" value="<?php echo htmlentities($row_RsHalakat['HName'], ENT_COMPAT, 'UTF-8'); ?>" data-required="true">
            </div>

            <br class="clear"/>

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="EdarahID"><?php echo get_gender_label('e', 'ال'); ?></label>
                </div>
                <?php create_edarah_combo($row_RsHalakat['EdarahID']); ?>
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="h_place">مكان الحلقة</label>
                </div>
                <input type="text" name="h_place" id="h_place" value="<?php echo $row_RsHalakat['h_place']; ?>" data-required="true">
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="h_time">وقت الحلقة</label>
                </div>
                <?php echo create_combo('h_time', $halakah_time, 0, $row_RsHalakat['h_time'], 'data-required="true"'); ?>
            </div>
            <br class="clear"/>
            <br class="clear"/>

            <div class="sixteen columns alpha">
                <div class="LabelContainer">
                    <label for="h_type">نوع الحلقة (اختيار متعدد)</label>
                </div>
                <p style="line-height: 32px">
                    <?php for ($i = 1; $i < count($halakah_type); $i++) { ?>
                        <label style="white-space: nowrap;">
                            <input type="checkbox" name="h_type[]" value="<?php echo $halakah_type[$i][0]; ?>"
                                   id="h_type_<?php echo $halakah_type[$i][0]; ?>"
                                <?php echo in_array($halakah_type[$i][0], get_halakah_types($row_RsHalakat['h_type'])->toArray) ? 'checked="checked"' : ''; ?>
                            >
                            <?php echo $halakah_type[$i][1]; ?>
                        </label>
                        &nbsp;&nbsp;
                    <?php } ?>
                </p>
            </div>
            <br class="clear"/>

            <div class="four columns omega left">
                <input type="submit" class="button-primary" value="حفظ التعديلات">
            </div>

            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="AutoNo" value="<?php echo $row_RsHalakat['AutoNo']; ?>">
        </form>
    <?php } else {
        echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
    } ?>

</div><!--content-->
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