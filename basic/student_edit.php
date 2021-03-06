<?php require_once(__DIR__ . '/../functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf("UPDATE `0_students` SET
StName1=%s,StName2=%s,StName3=%s,StName4=%s,
StBurthDate=%s,StStartDate=%s,StMobileNo=%s,FatherMobileNo=%s,guardian_name=%s,
StEdarah=%s,StHalaqah=%s,`hide`=%s,school_level=%s,nationality=%s WHERE StID=%s",
        GetSQLValueString($_POST['StName1'], "text"),
        GetSQLValueString($_POST['StName2'], "text"),
        GetSQLValueString($_POST['StName3'], "text"),
        GetSQLValueString($_POST['StName4'], "text"),
        GetSQLValueString(str_replace('/', '', $_POST['StBurthDate']), "int"),
        GetSQLValueString(str_replace('/', '', $_POST['StStartDate']), "int"),
        GetSQLValueString($_POST['StMobileNo'], "text"),
        GetSQLValueString($_POST['FatherMobileNo'], "text"),
        GetSQLValueString($_POST['guardian_name'], "text"),
        GetSQLValueString($_POST['EdarahID'], "int"),
        GetSQLValueString($_POST['HalaqatID'], "int"),
        GetSQLValueString($_POST['RadioGroup1'], "int"),
        GetSQLValueString($_POST['school_level'], "int"),
        GetSQLValueString($_POST['nationality'], "int"),
        GetSQLValueString($_POST['StID'], "double"));
    //echo $updateSQL;
    //exit;
    $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error('$Result1 : ' . $localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        header("Location: " . $editFormAction);
        exit;
    }
}


$colname_RsStudent = "-1";
if (isset($_GET['StID'])) {
    $colname_RsStudent = $_GET['StID'];
}
$query_RsStudent = sprintf("SELECT StName1,StName2,StName3,StName4,StID,StMobileNo,FatherMobileNo,guardian_name,StEdarah,StHalaqah,O_BurthDate,O_StartDate,hide,school_level,nationality
FROM view_0_students WHERE StID = %s", GetSQLValueString($colname_RsStudent, "double"));
$RsStudent = mysqli_query($localhost, $query_RsStudent) or die('$RsStudent ' . mysqli_error($localhost));
$row_RsStudent = mysqli_fetch_assoc($RsStudent);
$totalRows_RsStudent = mysqli_num_rows($RsStudent);

$EdarahNo_RsHalakat = "-1";
//if (isset($_GET['EdarahNo'])) {
//    $EdarahNo_RsHalakat = $_GET['EdarahNo'];
//}
$query_RsHalakat = sprintf("SELECT AutoNo,HName FROM `0_halakat` WHERE `hide`=0 AND EdarahID = %s", GetSQLValueString($row_RsStudent['StEdarah'], "int"));
$RsHalakat = mysqli_query($localhost, $query_RsHalakat) or die('$RsHalakat ' . mysqli_error($localhost));
$row_RsHalakat = mysqli_fetch_assoc($RsHalakat);
$totalRows_RsHalakat = mysqli_num_rows($RsHalakat);
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'تعديل ' . get_gender_label('st', 'ال'); ?>
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
                        <label for="StID">السجل المدني/الجواز</label>
                    </div>
                    <input name="StID" type="tel" id="StID"
                           value="<?php echo htmlentities($row_RsStudent['StID'], ENT_COMPAT, 'UTF-8'); ?>"
                           disabled="disabled">
                </div>

                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="StBurthDate">تاريخ الميلاد</label>
                    </div>
                    <input type="text" name="StBurthDate" id="StBurthDate"
                           value="<?php echo $row_RsStudent['O_BurthDate']; ?>"
                           zezo_date="true">
                </div>
                <div class="four columns ">
                    <div class="LabelContainer">الجنسية</div>
                    <?php echo create_combo("nationality", $nationality, 0, (int)$row_RsStudent['nationality'], 'data-required="true"'); ?>
                </div>

                <div class="four columns omega">
                    <div class="LabelContainer">المرحلة الدراسية</div>
                    <?php echo create_combo("school_level", $SchoolLevelNameAll, 0, $row_RsStudent['school_level'], 'data-required="true"'); ?>
                </div>

                <br class="clear"/>

                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="StName1">رقم جوال <?php echo get_gender_label('st', 'ال'); ?></label>
                    </div>
                    <input name="StMobileNo" type="tel" id="StMobileNo"
                           value="<?php echo htmlentities($row_RsStudent['StMobileNo'], ENT_COMPAT, 'UTF-8'); ?>"
                           data-type="digits" data-maxlength="10" data-minlength="10">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="FatherMobileNo">جوال ولي الأمر</label>
                    </div>
                    <input name="FatherMobileNo" type="tel" id="FatherMobileNo"
                           value="<?php echo htmlentities($row_RsStudent['FatherMobileNo'], ENT_COMPAT, 'UTF-8'); ?>"
                           data-type="digits" data-maxlength="10" data-minlength="10" data-required="true">
                </div>
                <div class="six columns omega">
                    <div class="LabelContainer">
                        <label for="guardian_name">اسم ولي الأمر</label>
                    </div>
                    <input name="guardian_name" type="text" id="guardian_name"
                           value="<?php echo $row_RsStudent['guardian_name']; ?>">
                </div>

                <br class="clear"/>

                <div class="eight columns alpha">
                    <div class="LabelContainer">
                        <label for="StStartDate">تاريخ بداية دراسة <?php echo get_gender_label('st', 'ال'); ?>بحلقات الزلفي (المباشرة)</label>
                    </div>
                    <input type="text" name="StStartDate" id="StStartDate"
                           value="<?php echo $row_RsStudent['O_StartDate']; ?>"
                           zezo_date="true">
                </div>

                <br class="clear"/>

                <div class="five columns alpha">
                    <div class="LabelContainer">
                        <label for="RadioGroup1">حالة القيد</label>
                    </div>
                    <label>
                        <input type="radio" name="RadioGroup1" value="0" id="RadioGroup1_0"
                               data-required="true" <?php echo (int)$row_RsStudent['hide'] === 0 ? 'checked="checked"' : ''; ?> >
                        مستمر في الدراسة
                    </label>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <label>
                        <input type="radio" name="RadioGroup1" value="1" id="RadioGroup1_1" <?php echo (int)$row_RsStudent['hide'] === 1 ? 'checked="checked"' : ''; ?>>
                        مطوي قيده
                    </label>
                </div>
                <br class="clear"/>

                <div class="four columns alpha">
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

                <div class="four columns left">
                    <input name="submit" type="submit" class="button-primary" id="submit" value="حفظ التعديلات"/>
                </div>

                <div class="clearfix"></div>

                <div class="four columns left">
                    <a href="update_student_id.php?old_student_id=<?php echo $row_RsStudent['StID']; ?>">تعديل السجل المدني</a>
                </div>

                <input type="hidden" name="MM_update" value="form1">
                <input type="hidden" name="StID" value="<?php echo $row_RsStudent['StID']; ?>">
            </form>
        </center>

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
    $(document).ready(function () {
        $('#EdarahID').change(function () {
            ClearList('HalaqatID');
            FillList(this.value, 'HalaqatID', 'AutoNo', 'HName', 'SELECT * FROM 0_halakat WHERE `hide`=0 and `EdarahID`=$ ORDER BY HName', 'لا يوجد حلقات', 'اختر الحلقة');
        });
        $('#EdarahID').trigger('change');
    });
    showError();
</script>
