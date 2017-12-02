<?php require_once('../functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}
$edarah_id = $_SESSION['user_id'];
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $insertSQL = sprintf("INSERT INTO 0_student_transfer (register_date,StID,StName1,StName2,StName3,StName4,
StEdarah,transfer_from,StMobileNo,FatherMobileNo,guardian_name ,StBurthDate,StHalaqah,school_level,nationality)
  						 VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString(str_replace('/', '', $_POST['register_date']), "int"),
        GetSQLValueString($_POST['StID'], "double"),
        GetSQLValueString($_POST['StName1'], "text"),
        GetSQLValueString($_POST['StName2'], "text"),
        GetSQLValueString($_POST['StName3'], "text"),
        GetSQLValueString($_POST['StName4'], "text"),
        GetSQLValueString($_POST['EdarahID'], "int"),
        GetSQLValueString($_POST['transfer_from'], "int"),
        GetSQLValueString($_POST['StMobileNo'], "text"),
        GetSQLValueString($_POST['FatherMobileNo'], "text"),
        GetSQLValueString($_POST['guardian_name'], "text"),
        GetSQLValueString(str_replace('/', '', $_POST['StBurthDate']), "int"),
        GetSQLValueString($_POST['HalaqatID'], "int"),
        GetSQLValueString($_POST['school_level'], "int"),
        GetSQLValueString($_POST['nationality'], "int")
    );
//    global $localhost;
    $Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        send_mail('zezont@gmail.com',
            'نقل طالب / طالبة',
            '<div style="direction: rtl;font-size: 18px">' .
            'نرجوا نقل الطالب / الطالبة : ' . $_POST['StName1'] . ' ' . $_POST['StName2'] . ' ' . $_POST['StName3'] . ' ' . $_POST['StName4'] . '<br>' .
            '<a href="http://quranzulfi.com/sys/basic/transfer_st_approve.php?_token=STW6gPk8QrrZdF1gW2tO">فتح صفحة الاعتماد</a>' .
            '</div>'
        );
        
        header("Location: " . '/sys/basic/transfer_st_search.php');
        exit;
    }

}


$colname_RsStudent = "-1";
if (isset($_GET['StID'])) {
    $colname_RsStudent = $_GET['StID'];
}
$query_RsStudent = sprintf("SELECT * FROM 0_students WHERE StID = %s", GetSQLValueString($colname_RsStudent, "double"));
$RsStudent = mysqli_query($localhost, $query_RsStudent) or die('$RsStudent ' . mysqli_error($localhost));
$row_RsStudent = mysqli_fetch_assoc($RsStudent);
$totalRows_RsStudent = mysqli_num_rows($RsStudent);

$EdarahNo_RsHalakat = "-1";

$query_RsHalakat = sprintf("SELECT AutoNo,HName FROM `0_halakat` WHERE `hide`=0 AND EdarahID = %s", GetSQLValueString($edarah_id, "int"));
$RsHalakat = mysqli_query($localhost, $query_RsHalakat) or die('$RsHalakat ' . mysqli_error($localhost));
$row_RsHalakat = mysqli_fetch_assoc($RsHalakat);
$totalRows_RsHalakat = mysqli_num_rows($RsHalakat);
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'طلب نقل  ' . get_gender_label('st', 'ال'); ?>
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
                <input name="register_date" type="hidden" id="register_date" value="" data-required="true"/>
                <input type="hidden" name="transfer_from"
                       value="<?php echo $row_RsStudent['StEdarah']; ?>"
                       data-required="true">
                <div class="sixteen columns alpha omega">
                    <p style="color: #c50909;font-size: 16px;">فضلا... راجع البيانات وحدد الحلقة</p>
                </div>
                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="StName1">الاسم</label>
                    </div>
                    <input type="text" name="StName1"
                           value="<?php echo escape($row_RsStudent['StName1']); ?>"
                           data-required="true">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="StName2">الأب</label>
                    </div>
                    <input type="text" name="StName2"
                           value="<?php echo escape($row_RsStudent['StName2']); ?>"
                           data-required="true">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="StName3">الجد</label>
                    </div>
                    <input type="text" name="StName3"
                           value="<?php echo escape($row_RsStudent['StName3']); ?>"
                           data-required="true">
                </div>
                <div class="four columns omega">
                    <div class="LabelContainer">
                        <label for="StName4">العائلة</label>
                    </div>
                    <input type="text" name="StName4"
                           value="<?php echo escape($row_RsStudent['StName4']); ?>"
                           data-required="true">
                </div>

                <br class="clear"/>

                <input name="StID" type="hidden" id="StID"
                       value="<?php echo escape($row_RsStudent['StID']); ?>">


                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="HalaqatID">تاريخ الميلاد</label>
                    </div>
                    <input type="text" name="StBurthDate" id="StBurthDate"
                           value="<?php echo StringToDate($row_RsStudent['StBurthDate']); ?>"
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
                           value="<?php echo escape($row_RsStudent['StMobileNo']); ?>"
                           data-type="digits" data-maxlength="10" data-minlength="10">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="FatherMobileNo">جوال ولي الأمر</label>
                    </div>
                    <input name="FatherMobileNo" type="tel" id="FatherMobileNo"
                           value="<?php echo escape($row_RsStudent['FatherMobileNo']); ?>"
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
                <input type="hidden" name="RadioGroup1" value="0">
                <br class="clear"/>

                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="EdaratID">المجمع</label>
                    </div>
                    <?php create_edarah_combo($edarah_id); ?>
                </div>
                <div class="four columns omega">
                    <div class="LabelContainer">
                        <label for="HalaqatID">الحلقة</label>
                    </div>
                    <select name="HalaqatID" class="FullWidthCombo" id="HalaqatID" data-required="true">
                        <option value="">حدد الحلقة ...</option>
                        <?php
                        do {
                            ?>
                            <option
                                    value="<?php echo $row_RsHalakat['AutoNo'] ?>"><?php echo $row_RsHalakat['HName'] ?></option>
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
                    <input name="submit" type="submit" class="button-primary" id="submit" value="اعتماد طلب النقل"/>
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
            alertify.success("تم تقديم طلب النقل بنجاح");
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
            FillList(this.value, 'HalaqatID', 'AutoNo', 'HName', 'SELECT * FROM 0_halakat WHERE `hide`=0 and EdarahID = $ ORDER BY HName', 'لا يوجد حلقات', 'اختر الحلقة');
        });
    });
    $('#EdarahID').trigger('change');
    showError();
</script>
<script>
    $(document).ready(function () {
        $('#register_date').val(get_formated_hijri_date(zezo_get_hijri_date('now')));
    });
</script>