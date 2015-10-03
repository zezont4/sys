<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf("UPDATE `0_students` SET
StName1=%s,StName2=%s,StName3=%s,StName4=%s,
StBurthDate=%s,StMobileNo=%s,FatherMobileNo=%s,guardian_name=%s,
StEdarah=%s,StHalaqah=%s,`hide`=%s WHERE StID=%s", GetSQLValueString($_POST['StName1'], "text"), GetSQLValueString($_POST['StName2'], "text"), GetSQLValueString($_POST['StName3'], "text"), GetSQLValueString($_POST['StName4'], "text"), GetSQLValueString(str_replace('/', '', $_POST['StBurthDate']), "int"), GetSQLValueString($_POST['StMobileNo'], "text"), GetSQLValueString($_POST['FatherMobileNo'], "text"), GetSQLValueString($_POST['guardian_name'], "text"), GetSQLValueString($_POST['EdarahID'], "int"), GetSQLValueString($_POST['HalaqatID'], "int"), GetSQLValueString($_POST['RadioGroup1'], "int"), GetSQLValueString($_POST['StID'], "double"));
    //echo $updateSQL;
    //exit;
    mysqli_select_db($localhost, $database_localhost);
    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        header("Location: " . $editFormAction);
        exit;
    }

    //$updateGoTo = "search_st_resault.php";
    //if (isset($_SERVER['QUERY_STRING'])) {
    //  $updateGoTo .= (strpos($updateGoTo,'?')) ? "&" : "?";
    //  $updateGoTo .= $_SERVER['QUERY_STRING'];
    //}
    //header(sprintf("Location: %s",$updateGoTo));
}


$colname_RsStudent = "-1";
if (isset($_GET['StID'])) {
    $colname_RsStudent = $_GET['StID'];
}
mysqli_select_db($localhost, $database_localhost);
$query_RsStudent = sprintf("SELECT StName1,StName2,StName3,StName4,StID,StMobileNo,FatherMobileNo,StEdarah,StHalaqah,O_BurthDate,hide FROM view_0_students WHERE StID = %s", GetSQLValueString($colname_RsStudent, "double"));
$RsStudent = mysqli_query($localhost, $query_RsStudent) or die(mysqli_error($localhost));
$row_RsStudent = mysqli_fetch_assoc($RsStudent);
$totalRows_RsStudent = mysqli_num_rows($RsStudent);

$EdarahNo_RsHalakat = "-1";
if (isset($_GET['EdarahNo'])) {
    $EdarahNo_RsHalakat = $_GET['EdarahNo'];
}
mysqli_select_db($localhost, $database_localhost);
$query_RsHalakat = sprintf("SELECT AutoNo,HName FROM `0_halakat` WHERE `hide`=0 AND EdarahID = %s", GetSQLValueString($EdarahNo_RsHalakat, "int"));
$RsHalakat = mysqli_query($localhost, $query_RsHalakat) or die(mysqli_error($localhost));
$row_RsHalakat = mysqli_fetch_assoc($RsHalakat);
$totalRows_RsHalakat = mysqli_num_rows($RsHalakat);
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'تعديل' . get_gender_label('st', 'ال'); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<div class="content lp">
    <?php if (login_check("admin,edarh,t3lem") == true) { ?>

        <center>
            <form method="post" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">
                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="StName1">الاسم</label>
                    </div>
                    <input type="text" name="StName1"
                           value="<?php echo htmlentities($row_RsStudent['StName1'], ENT_COMPAT, 'UTF-8'); ?>"
                           data-required="true">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="StName2">الأب</label>
                    </div>
                    <input type="text" name="StName2"
                           value="<?php echo htmlentities($row_RsStudent['StName2'], ENT_COMPAT, 'UTF-8'); ?>"
                           data-required="true">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="StName3">الجد</label>
                    </div>
                    <input type="text" name="StName3"
                           value="<?php echo htmlentities($row_RsStudent['StName3'], ENT_COMPAT, 'UTF-8'); ?>"
                           data-required="true">
                </div>
                <div class="four columns omega">
                    <div class="LabelContainer">
                        <label for="StName4">العائلة</label>
                    </div>
                    <input type="text" name="StName4"
                           value="<?php echo htmlentities($row_RsStudent['StName4'], ENT_COMPAT, 'UTF-8'); ?>"
                           data-required="true">
                </div>
                <br class="clear"/>

                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="HalaqatID">تاريخ الميلاد</label>
                    </div>
                    <input type="text" name="StBurthDate" id="StBurthDate"
                           value="<?php echo htmlentities($row_RsStudent['O_BurthDate'], ENT_COMPAT, 'UTF-8'); ?>"
                           zezo_date="true">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="StName1">رقم جوال <?php echo get_gender_label('st', 'ال'); ?></label>
                    </div>
                    <input name="StMobileNo" type="tel" id="StMobileNo"
                           value="<?php echo htmlentities($row_RsStudent['StMobileNo'], ENT_COMPAT, 'UTF-8'); ?>"
                           data-type="digits" data-maxlength="10" data-minlength="10">
                </div>
                <div class="sfive columns">
                    <div class="LabelContainer">
                        <label for="RadioGroup1">حالة القيد</label>
                    </div>

                    <div>
                        <label>
                            <input <?php if (!(strcmp($row_RsStudent['hide'], "0"))) {
                                echo "checked=\"checked\"";
                            } ?> type="radio" name="RadioGroup1" value="0" id="RadioGroup1_0" data-required="true">
                            مستمر في الدراسة</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input <?php if (!(strcmp($row_RsStudent['hide'], "1"))) {
                                echo "checked=\"checked\"";
                            } ?> type="radio" name="RadioGroup1" value="1" id="RadioGroup1_1">
                            مطوي قيده</label>

                    </div>
                </div>
                <br class="clear"/>

                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="StID">السجل المدني</label>
                    </div>
                    <input name="StID" type="tel" id="StID"
                           value="<?php echo htmlentities($row_RsStudent['StID'], ENT_COMPAT, 'UTF-8'); ?>"
                           disabled="disabled">
                </div>

                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="EdaratID">المجمع</label>
                    </div>
                    <?php create_edarah_combo($row_RsStudent['StEdarah']); ?>
                </div>
                <div class="four columns omega">
                    <div class="LabelContainer">
                        <label for="HalaqatID">الحلقة</label>
                    </div>
                    <select name="HalaqatID" class="FullWidthCombo" id="HalaqatID" data-required="true">
                        <?php
                        do {
                            ?>
                            <option
                                value="<?php echo $row_RsHalakat['AutoNo'] ?>"<?php if ($row_RsHalakat['AutoNo'] == $row_RsStudent['StHalaqah']) {
                                echo "selected=\"selected\"";
                            } ?>><?php echo $row_RsHalakat['HName'] ?></option>
                        <?php
                        } while ($row_RsHalakat = mysqli_fetch_assoc($RsHalakat));
                        $rows = mysqli_num_rows($RsHalakat);
                        if ($rows > 0) {
                            mysqli_data_seek($RsHalakat, 0);
                            $row_RsHalakat = mysqli_fetch_assoc($RsHalakat);
                        }
                        ?>
                    </select>
                </div>
                <br class="clear"/>


                <br class="clear"/>

                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="guardian_name">اسم ولي الأمر</label>
                    </div>
                    <input name="guardian_name" type="text" id="guardian_name"
                           value="<?php echo $row_RsStudent['guardian_name']; ?>">
                </div>
                <div class="four columns omega">
                    <div class="LabelContainer">
                        <label for="FatherMobileNo">جوال ولي الأمر</label>
                    </div>
                    <input name="FatherMobileNo" type="tel" id="FatherMobileNo"
                           value="<?php echo htmlentities($row_RsStudent['FatherMobileNo'], ENT_COMPAT, 'UTF-8'); ?>"
                           data-type="digits" data-maxlength="10" data-minlength="10" data-required="true">
                </div>
                <div class="four columns left">
                    <input name="submit" type="submit" class="button-primary" id="submit" value="حفظ التعديلات"/>
                </div>
                <input type="hidden" name="MM_update" value="form1">
                <input type="hidden" name="StID" value="<?php echo $row_RsStudent['StID']; ?>">
            </form>
        </center>
        <script>
            $(document).ready(function () {
                $('#EdarahID').change(function () {
                    ClearList('HalaqatID');
                    FillList(this.value, 'HalaqatID', 'AutoNo', 'HName', 'SELECT * FROM 0_halakat WHERE `hide`=0 and EdarahID = %s ORDER BY HName', 'لا يوجد حلقات', 'اختر الحلقة');
                });
                //$('#EdarahID').trigger("change");
            });
        </script>
    <?php } else {
        echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
    } ?>
</div>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
mysqli_free_result($RsStudent);

mysqli_free_result($RsHalakat);
?>
<?php
if (isset($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.success("تم تحديث البيانات بنجاح");
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
