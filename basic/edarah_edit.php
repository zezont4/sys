<?php require_once('../functions.php'); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}
//"SELECT id,username,password,arabic_name,o_hide,hidden,o_sex,`user_group`
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf("UPDATE `0_users` SET username=%s,PASSWORD=%s,user_group=%s,arabic_name=%s,mobile_no=%s,sex=%s,hidden=%s WHERE id=%s",
        GetSQLValueString($_POST['username'], "text"),
        GetSQLValueString($_POST['password'], "text"),
        GetSQLValueString($_POST['user_group'], "text"),

        GetSQLValueString($_POST['arabic_name'], "text"),
        GetSQLValueString($_POST['mobile_no'], "text"),
        GetSQLValueString($_POST['sex'], "int"),
        GetSQLValueString($_POST['hidden'], "int"),
        GetSQLValueString($_POST['id'], "int"));

    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "";
        //header("Location: " . $editFormAction);
        //exit;
    }

}

$colname_RsEdarat = "-1";
if (isset($_GET['id'])) {
    $colname_RsEdarat = $_GET['id'];
}
$query_RsEdarat = sprintf("SELECT * FROM `0_users` WHERE id = %s", GetSQLValueString($colname_RsEdarat, "int"));
$RsEdarat = mysqli_query($localhost, $query_RsEdarat) or die(mysqli_error($localhost));
$row_RsEdarat = mysqli_fetch_assoc($RsEdarat);
$totalRows_RsEdarat = mysqli_num_rows($RsEdarat);
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'تعديل الإدارات والمستخدمون'; ?>
<title><?php echo $PageTitle; ?></title>
</head>

<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->

<div class="content">
    <?php if (login_check("admin,t3lem") == true) { ?>
        <div class="FieldsTitle">بيانات المجمع</div>
        <form method="post" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">
            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="username">اسم المستخدم</label>
                </div>
                <input type="text" name="username" value="<?php echo $row_RsEdarat['username']; ?>" data-required="true">
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="password">كلمة المرور</label>
                </div>
                <input type="text" name="password" value="<?php echo $row_RsEdarat['password']; ?>" data-required="true">
            </div>

            <div class="four columns omega">
                <div class="LabelContainer">
                    <label for="user_group">المجموعة</label>
                </div>
                <?php $ug = count($user_groups); ?>
                <select name="user_group" data-required="true" class="full-width">
                    <?php for ($i1 = 0; $i1 < $ug; $i1++) { ?>
                        <option <?php if ($row_RsEdarat['user_group'] == $user_groups[$i1][0]) echo 'selected'; ?>
                                value="<?php echo $user_groups[$i1][0]; ?>"><?php echo $user_groups[$i1][1]; ?></option>
                    <?php } ?>
                </select>
            </div>

            <br class="clear">
            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="arabic_name">اسم المستخدم أو المجمع بالعربي</label>
                </div>
                <input type="text" name="arabic_name" value="<?php echo $row_RsEdarat['arabic_name']; ?>" data-required="true">
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="mobile_no">رقم جوال المجمع</label>
                </div>
                <input type="tel" name="mobile_no" id="mobile_no" value="<?php echo $row_RsEdarat['mobile_no']; ?>" data-type="digits" data-maxlength="10" data-minlength="10">
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="sex">الجنس</label>
                </div>
                <div>
                    <label for="Sex_1"><input type="radio" name="sex" id="Sex_1" value="1" <?php if ($row_RsEdarat['sex'] == 1) {
                            echo 'checked="checked"';
                        } ?> data-required="true">
                        بنين</label>
                    &nbsp; &nbsp; | &nbsp; &nbsp;
                    <label for="Sex_0"><input type="radio" name="sex" id="Sex_0" value="0" <?php if ($row_RsEdarat['sex'] == 0) {
                            echo 'checked="checked"';
                        } ?>>
                        بنات</label>
                </div>
            </div>
            <div class="four columns omega">
                <div class="LabelContainer">
                    <label for="hidden">حالة المستخدم</label>
                </div>
                <div>
                    <label for="hidden_1"><input type="radio" name="hidden" id="hidden_1" value="1" <?php if ($row_RsEdarat['hidden'] == 1) {
                            echo 'checked="checked"';
                        } ?> data-required="true">
                        ملغي</label>
                    &nbsp; &nbsp; | &nbsp; &nbsp;
                    <label for="hidden_0"><input type="radio" name="hidden" id="hidden_0" value="0" <?php if ($row_RsEdarat['hidden'] == 0) {
                            echo 'checked="checked"';
                        } ?>>
                        فعال</label>
                </div>
            </div>
            <br class="clear">
            <div class="four columns omega left">
                <input type="submit" class="button-primary" value="حفظ التعديل">
            </div>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="id" value="<?php echo $row_RsEdarat['id']; ?>">
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
