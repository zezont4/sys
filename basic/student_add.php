<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php $PageTitle = 'إضافة ' . ' ' . get_gender_label('st'); ?>
<?php if (login_check("admin,edarh,t3lem") == true) { ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

    $colname_RsIfExistSt = "-1";
    if (isset($_POST['StID'])) {
        $colname_RsIfExistSt = $_POST['StID'];
    }
    mysqli_select_db($localhost, $database_localhost);
    $query_RsIfExistSt = sprintf("SELECT StID,StEdarah FROM 0_students WHERE StID = %s", GetSQLValueString($colname_RsIfExistSt, "double"));
    $RsIfExistSt = mysqli_query($localhost, $query_RsIfExistSt) or die(mysqli_error($localhost));
    $row_RsIfExistSt = mysqli_fetch_assoc($RsIfExistSt);
    $totalRows_RsIfExistSt = mysqli_num_rows($RsIfExistSt);
    if ($totalRows_RsIfExistSt > 0) {
        ?>
        <?php include('../templates/header1.php'); ?>
        <br><br><br>
        <div style="direction:rtl;text-align:center;font-size:22px;">
            <p>
                السجل المدني(<?php echo $row_RsIfExistSt['StID'];?>) موجود سابقا,وهو تابع
                للطالب/ـة:( <?php echo get_student_name($row_RsIfExistSt['StID']);?>)
            </p>
            <br>

            <p>وهو تابع للمجمع / للدار التالية : (<?php echo get_edarah_name($row_RsIfExistSt['StEdarah']) ?>)</p>
            <br>

            <p>
                إذا كان تابع لمجمع أو دار اخرى وترغبون بنقله إليكم، فيرجى مراسلة مسؤول النظام ليقوم بعملية النقل.
            </p>
        </div>
        <?php
        exit;
    }


    $insertSQL = sprintf("INSERT INTO 0_students (StID,StName1,StName2,StName3,StName4,
StEdarah,StMobileNo,FatherMobileNo,guardian_name ,StBurthDate,StHalaqah)
  						 VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['StID'], "double"),
        GetSQLValueString($_POST['StName1'], "text"),
        GetSQLValueString($_POST['StName2'], "text"),
        GetSQLValueString($_POST['StName3'], "text"),
        GetSQLValueString($_POST['StName4'], "text"),
        GetSQLValueString($_POST['EdarahID'], "int"),
        GetSQLValueString($_POST['StMobileNo'], "text"),
        GetSQLValueString($_POST['FatherMNo'], "text"),
        GetSQLValueString($_POST['guardian_name'], "text"),
        GetSQLValueString(str_replace('/', '', $_POST['StBurthDate']), "int"),
        GetSQLValueString($_POST['HalaqatID'], "int")
    );
    //echo $insertSQL;
    //exit;
    mysqli_select_db($localhost, $database_localhost);
    $Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    //mysqli_free_result($RSMaxID2);

}
?>
<?php include('../templates/header1.php'); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->

<div class="content lp">
    <form method="post" id="form1" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="EdaratID"><?php echo get_gender_label('e', 'ال'); ?></label>
            </div>
            <?php create_edarah_combo(); ?>
        </div>
        <div class="four columns">
            <div class="LabelContainer">
                <label for="HalaqatID">الحلقة</label>
            </div>
            <select name="HalaqatID" class="FullWidthCombo" id="HalaqatID" data-required="true">
                <option VALUE>--</option>
            </select>
        </div>
        <div class="four columns">
            <div class="LabelContainer">
                <label for="StID">السجل المدني</label>
            </div>
            <input name="StID" type="tel" id="StID" data-required="true" data-type="digits" data-maxlength="10"
                   data-minlength="10">
        </div>
        <br class="clear"/>

        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="StName1">الاسم</label>
            </div>
            <input type="text" name="StName1" id="StName1" data-required="true">
        </div>
        <div class="four columns">
            <div class="LabelContainer">
                <label for="StName2">الأب</label>
            </div>
            <input type="text" name="StName2" id="StName2" data-required="true">
        </div>

        <div class="four columns">
            <div class="LabelContainer">
                <label for="StName3">الجد</label>
            </div>
            <input type="text" name="StName3" id="StName3" data-required="true">
        </div>
        <div class="four columns omega">
            <div class="LabelContainer">
                <label for="StName4">العائلة</label>
            </div>
            <input type="text" name="StName4" id="StName4" data-required="true">
        </div>
        <br class="clear"/>

        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="StName1">رقم جوال <?php echo get_gender_label('st', 'ال'); ?></label>
            </div>
            <input type="tel" name="StMobileNo" id="StMobileNo" data-type="digits" data-maxlength="10"
                   data-minlength="10">
        </div>

        <div class="four columns">
            <div class="LabelContainer">
                <label for="StBurthDate">تاريخ الميلاد</label>
            </div>
            <input name="StBurthDate" type="text" id="StBurthDate" data-required="true" zezo_date="true">
        </div>

        <br class="clear"/>
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="FatherMNo">جوال ولي الأمر</label>
            </div>
            <input name="FatherMNo" type="tel" id="FatherMNo" data-required="true" data-type="digits"
                   data-maxlength="10" data-minlength="10">
        </div>
        <div class="six columns">
            <div class="LabelContainer">
                <label for="guardian_name">اسم ولي الأمر</label>
            </div>
            <input name="guardian_name" type="text" id="guardian_name">
        </div>

        <br class="clear"/>

        <div class="four columns omega left">
            <input name="submit" type="submit" class="button-primary" id="submit" value="إضافة"/>
        </div>
        <input type="hidden" name="MM_insert" value="form1">
    </form>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#EdarahID').change(function () {
                ClearList('HalaqatID');
                FillList(this.value, 'HalaqatID', 'AutoNo', 'HName', 'SELECT * FROM 0_halakat WHERE `hide`=0 and EdarahID = %s ORDER BY HName', 'لا يوجد حلقات', 'اختر الحلقة');
            });
            $('#EdarahID').trigger("change");
        });
    </script>
</div>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
if (isset($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.success("تمت الإضافة بنجاح");
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

<?php } else {
    include('../templates/restrict_msg.php');
} ?>
