<?php require_once('../functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

    $colname_RsIfExistT = "-1";
    if (isset($_POST['TID'])) {
        $colname_RsIfExistT = $_POST['TID'];
    }
    $query_RsIfExistT = sprintf("SELECT TID,Tfullname FROM view_0_teachers WHERE TID = %s", GetSQLValueString($colname_RsIfExistT, "double"));
    $RsIfExistT = mysqli_query($localhost, $query_RsIfExistT) or die(mysqli_error($localhost));
    $row_RsIfExistT = mysqli_fetch_assoc($RsIfExistT);
    $totalRows_RsIfExistT = mysqli_num_rows($RsIfExistT);
    if ($totalRows_RsIfExistT > 0) {
        ?>
        <?php include('../templates/header1.php'); ?>
        <br><br><br>
        <div style="direction:rtl;text-align:center;font-size:22px;">
            <br/>
            <br/>
            <br/>
            <h1 dir="rtl" align="center">السجل المدني(<?php echo $row_RsIfExistT['TID']; ?>) موجود سابقا,وهو تابع للمعلم :<?php echo $row_RsIfExistT['Tfullname']; ?></h1>
            <h2 dir="rtl" align="center">قد يكون تابع لمجمع آخر ,لذا يرجى الاتصال بقسم الارتقاء ليقوم بنقل المعلم إلى مجمعكم.</h2>
        </div>
        <?php
        exit;
    }


    $insertSQL = sprintf("INSERT INTO 0_teachers (TID,TName1,TName2,TName3,TName4,TMobileNo,TEdarah,THalaqah,start_date,
qualification,is_memorizing_quran,has_quran_shahadah,has_jazariah,has_tu7fah,has_ejazah_in_quran,has_ejazah_in_shatibiah)
  						 VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['TID'], "double"),
        GetSQLValueString($_POST['TName1'], "text"),
        GetSQLValueString($_POST['TName2'], "text"),
        GetSQLValueString($_POST['TName3'], "text"),
        GetSQLValueString($_POST['TName4'], "text"),
        GetSQLValueString($_POST['TMobileNo'], "text"),
        GetSQLValueString($_POST['EdarahID'], "int"),
        GetSQLValueString($_POST['THalaqah'], "int"),
        GetSQLValueString(str_replace('/', '', $_POST['start_date']), "text"),
        GetSQLValueString($_POST['qualification'], "int"),
        GetSQLValueString($_POST['is_memorizing_quran'], "int"),
        GetSQLValueString($_POST['has_quran_shahadah'], "int"),
        GetSQLValueString($_POST['has_jazariah'], "int"),
        GetSQLValueString($_POST['has_tu7fah'], "int"),
        GetSQLValueString($_POST['has_ejazah_in_quran'], "int"),
        GetSQLValueString($_POST['has_ejazah_in_shatibiah'], "int")
    );


    $Result1 = mysqli_query($localhost, $insertSQL) or die('$Result1 : ' . mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
    }
    mysqli_free_result($RsIfExistT);

}
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'إضافة ' . get_gender_label('t', ''); ?>
<title><?php echo $PageTitle; ?></title>
<style type="text/css">
    form table {
        border: 1px solid #333;
    }

    form table tr th {
        /*background-color: #999;*/
        text-align: center;
        vertical-align: middle;
        border: 1px solid rgb(189, 189, 189);
        padding: 5px 2px;
    }

    form table tr td {
        text-align: center;
        vertical-align: top;
        border: 1px solid #666;
        padding: 5px 5px;
    }
</style>
</head>
<body>

<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<div class="content">
    <?php if (login_check("admin,edarh,t3lem") == true) { ?>
        <form method="post" id="form1" name="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="TName1">الاسم</label>
                </div>
                <input type="text" name="TName1" id="TName1" data-required="true">
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="TName2"> الأب</label>
                </div>
                <input type="text" name="TName2" id="TName2" data-required="true">
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="TName3">الجد</label>
                </div>
                <input type="text" name="TName3" id="TName3" data-required="true">
            </div>
            <div class="four columns omega">
                <div class="LabelContainer">
                    <label for="TName4">العائلة</label>
                </div>
                <input type="text" name="TName4" id="TName4" data-required="true">
            </div>

            <br class="clear"/>

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="TID">السجل المدني</label>
                </div>
                <input name="TID" type="text" id="TID" data-required="true" data-maxlength="10" data-minlength="10" data-type="digits">
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="TMobileNo">رقم جوال <?php echo get_gender_label('t', 'ال'); ?></label>
                </div>
                <input type="tel" name="TMobileNo" id="TMobileNo" data-required="true" data-type="digits" data-maxlength="10" data-minlength="10">
            </div>

            <div class="four columns ">
                <div class="LabelContainer">المؤهل العلمي</div>
                <?php echo create_combo("qualification", $qualification, 0, '', 'data-required="true"'); ?>
            </div>

            <div class="four columns omega">
                <div class="LabelContainer">
                    <label for="TMobileNo">تاريخ المباشرة</label>
                </div>
                <input type="text" name="start_date" id="start_date" zezo_date="true" data-required="true">
            </div>

            <br class="clear"/>
            <br class="clear"/>

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="is_memorizing_quran">هل يحفظ القرآن كاملا؟</label>
                </div>
                <label>
                    <input type="radio" name="is_memorizing_quran" value="1" id="is_memorizing_quran_1">
                    &nbsp;نعم
                </label>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <label>
                    <input type="radio" name="is_memorizing_quran" value="0" id="is_memorizing_quran_0" data-required="true">
                    &nbsp;لا
                </label>
            </div>

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="has_quran_shahadah">هل لديه شهادة إتمام الحفظ ؟</label>
                </div>
                <label>
                    <input type="radio" name="has_quran_shahadah" value="1" id="has_quran_shahadah_1">
                    &nbsp;نعم
                </label>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <label>
                    <input type="radio" name="has_quran_shahadah" value="0" id="has_quran_shahadah_0" data-required="true">
                    &nbsp;لا
                </label>
            </div>
            <br class="clear"/>
            <br class="clear"/>

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="has_jazariah">هل اجتاز دورة (الجزرية)</label>
                </div>
                <label>
                    <input type="radio" name="has_jazariah" value="1" id="has_jazariah_1">
                    &nbsp;نعم
                </label>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <label>
                    <input type="radio" name="has_jazariah" value="0" id="has_jazariah_0" data-required="true">
                    &nbsp;لا

                </label>
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="has_tu7fah">هل اجتاز دورة (التحفة)</label>
                </div>
                <label>
                    <input type="radio" name="has_tu7fah" value="1" id="has_tu7fah_1">
                    &nbsp;نعم
                </label>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <label>
                    <input type="radio" name="has_tu7fah" value="0" id="has_tu7fah_0" data-required="true">
                    &nbsp;لا

                </label>
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="has_ejazah_in_quran">هل لديه إجازة في (القرآن)</label>
                </div>
                <label>
                    <input type="radio" name="has_ejazah_in_quran" value="1" id="has_ejazah_in_quran_1">
                    &nbsp;نعم
                </label>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <label>
                    <input type="radio" name="has_ejazah_in_quran" value="0" id="has_ejazah_in_quran_0" data-required="true">
                    &nbsp;لا

                </label>
            </div>

            <div class="four columns omega">
                <div class="LabelContainer">
                    <label for="has_ejazah_in_shatibiah">هل لديه إجازة في (الشاطبية)</label>
                </div>
                <label>
                    <input type="radio" name="has_ejazah_in_shatibiah" value="1" id="has_ejazah_in_shatibiah_1">
                    &nbsp;نعم
                </label>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <label>
                    <input type="radio" name="has_ejazah_in_shatibiah" value="0" id="has_ejazah_in_shatibiah_0" data-required="true">
                    &nbsp;لا

                </label>
            </div>

            <br class="clear"/>
            <br class="clear"/>

            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="EdarahID"><?php echo get_gender_label('e', 'ال'); ?></label>
                </div>
                <?php create_edarah_combo(); ?>
            </div>

            <div class="four columns">
                <div class="LabelContainer">
                    <label for="THalaqah">الحلقة</label>
                </div>
                <select name="THalaqah" class="FullWidthCombo" id="THalaqah" data-required="true">
                    <option VALUE>--</option>
                </select>
            </div>

            <br class="clear"/>

            <br class="clear"/>
            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="إضافة">
            </div>
            <input type="hidden" name="MM_insert" value="form1">
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
            alertify.success("تمت الإضافة بنجاح");
        });
    </script>
    <?php
    $_SESSION['u1'] = NULL;
    unset($_SESSION['u1']);
} ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#EdarahID').change(function () {
            ClearList('THalaqah');
            FillList(this.value, 'THalaqah', 'AutoNo', 'HName', 'SELECT * FROM 0_halakat WHERE `hide`=0 and EdarahID = $ ORDER BY HName', 'لا يوجد حلقات', 'اختر الحلقة');
        });
        $('#EdarahID').trigger('change');
    });
    showError();
</script>

