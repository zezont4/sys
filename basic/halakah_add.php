<?php
require_once('../functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$queryString_RSHalaqat = '';
$currentPage = '';
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $h_type = isset($_POST['h_type']) ? '|' . implode($_POST['h_type'], '|,|') . '|' : '';

    $insertSQL = sprintf("INSERT INTO 0_halakat (HName,EdarahID,h_time,h_place,h_type) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['HName'], "text"),
        GetSQLValueString($_POST['EdarahID'], "int"),
        GetSQLValueString($_POST['h_time'], "int"),
        GetSQLValueString($_POST['h_place'], "text"),
        GetSQLValueString($h_type, "text")
    );

    $Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));
    if ($Result1) {
        $_SESSION['u1'] = "u1";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$maxRows_RSHalaqat = 200;
$pageNum_RSHalaqat = 0;
//if (isset($_GET['pageNum_RSHalaqat'])) {
$pageNum_RSHalaqat = Input::get('pageNum_RSHalaqat');
//}
$startRow_RSHalaqat = (int)$pageNum_RSHalaqat * (int)$maxRows_RSHalaqat;
$sql_sex = sql_sex('Sex');
$query_RSHalaqat = '';
$user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : null;
if ($user_group != 'edarh') {
    $query_RSHalaqat = sprintf("SELECT * FROM view_0_halakat WHERE EdarahID > 0 %s order by `hide`,arabic_name,HName", $sql_sex);
} else {
    $query_RSHalaqat = sprintf("SELECT * FROM view_0_halakat WHERE EdarahID = %s %s order by `hide`,arabic_name,HName", $_SESSION['user_id'], $sql_sex);
}
$query_limit_RSHalaqat = sprintf("%s LIMIT %d,%d", $query_RSHalaqat, $startRow_RSHalaqat, $maxRows_RSHalaqat);

$RSHalaqat = mysqli_query($localhost, $query_limit_RSHalaqat) or die('query_RSHalaqat ' . mysqli_error($localhost));
$row_RSHalaqat = mysqli_fetch_assoc($RSHalaqat);
if (isset($_GET['totalRows_RSHalaqat'])) {
    $totalRows_RSHalaqat = $_GET['totalRows_RSHalaqat'];
} else {
    $all_RSHalaqat = mysqli_query($localhost, $query_RSHalaqat);
    $totalRows_RSHalaqat = mysqli_num_rows($all_RSHalaqat);
}
$totalPages_RSHalaqat = ceil($totalRows_RSHalaqat / $maxRows_RSHalaqat) - 1;

include('../templates/header1.php');
$PageTitle = 'الحلقات'; ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php');
include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<div class="content lp">
    <?php if (login_check("admin,edarh,t3lem") == true) { ?>
    <div class="FieldsTitle">بيانات الحلقة</div>
    <form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>" data-validate="parsley">

        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="HName">اسم الحلقة</label>
            </div>
            <input type="text" name="HName" id="HName" data-required="true">
        </div>

        <br class="clear"/>

        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="EdarahID"><?php echo get_gender_label('e', 'ال'); ?></label>
            </div>
            <?php create_edarah_combo(); ?>
        </div>

        <div class="four columns">
            <div class="LabelContainer">
                <label for="h_place">مكان الحلقة</label>
            </div>
            <input type="text" name="h_place" id="h_place" data-required="true">
        </div>

        <div class="four columns">
            <div class="LabelContainer">
                <label for="h_time">وقت الحلقة</label>
            </div>
            <?php echo create_combo('h_time', $halakah_time, 0, null, 'data-required="true"'); ?>
        </div>
        <br class="clear"/>
        <br class="clear"/>

        <div class="sixteen columns alpha">
            <div class="LabelContainer">
                <label for="h_type">نوع الحلقة (اختيار متعدد)</label>
            </div>
            <div style="line-height: 32px">
                <?php for ($i = 1; $i < count($halakah_type); $i++) { ?>
                    <label style="white-space: nowrap;">
                        <input type="checkbox" name="h_type[]" value="<?php echo $halakah_type[$i][0]; ?>" id="h_type_<?php echo $halakah_type[$i][0]; ?>">
                        <?php echo $halakah_type[$i][1]; ?>
                    </label>
                    &nbsp;&nbsp;
                <?php } ?>
            </div>
        </div>

        <br class="clear"/>

        <div class="four columns left">
            <input name="submit" type="submit" class="button-primary" id="submit" value="إضافة">
        </div>
        <input type="hidden" name="MM_insert" value="form1">
    </form>
</div>
<div class="content CSSTableGenerator">
    <table>
        <caption>الحلقات المسجلة</caption>
        <tr>
            <td>اسم الحلقة</td>
            <td><?php echo get_gender_label('e', 'ال'); ?></td>
            <td>وقت الحلقة</td>
            <td>نوع الحلقة</td>
            <td>الحالة</td>
            <td>تعديل</td>
        </tr>
        <?php do { ?>
            <tr <?php if ($row_RSHalaqat["hide"] == 1) { ?> class='hidenRow' <?php ;
            } ?>>
                <td><?php echo $row_RSHalaqat['HName']; ?></td>
                <td><?php echo $row_RSHalaqat['arabic_name']; ?></td>
                <td><?php echo get_array_1($halakah_time, $row_RSHalaqat['h_time']); ?></td>
                <td><?php echo get_halakah_types($row_RSHalaqat['h_type'])->toString; ?></td>
                <?php if ($row_RSHalaqat["hide"] == 1) { ?>
                    <td><a href="halakah_show.php?AutoNo=<?php echo $row_RSHalaqat['AutoNo']; ?>"><?php echo $row_RSHalaqat['o_hide']; ?></a></td>
                <?php } else { ?>
                    <td><a href="halakah_hide.php?AutoNo=<?php echo $row_RSHalaqat['AutoNo']; ?>"><?php echo $row_RSHalaqat['o_hide']; ?></a></td>
                <?php } ?>
                <td><a href="halakah_edit.php?AutoNo=<?php echo $row_RSHalaqat['AutoNo']; ?>">تعديل</a></td>
            </tr>
        <?php } while ($row_RSHalaqat = mysqli_fetch_assoc($RSHalaqat)); ?>
    </table>

    <br/>
    <center>
        <div class="button-group">
            <?php if ($pageNum_RSHalaqat > 0) { // Show if not first page ?>
                <a title="الصفحة الأولى" class="button-primary" href="<?php printf("%s?pageNum_RSHalaqat=%d%s", $currentPage, 0, $queryString_RSHalaqat); ?>"
                   tabindex="-1"> << </a>
            <?php } else { // Show if not first page ?>
                <a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
            <?php } ?>
            <?php if ($pageNum_RSHalaqat > 0) { // Show if not first page ?>
                <a title="السابق" class="button-primary"
                   href="<?php printf("%s?pageNum_RSHalaqat=%d%s", $currentPage, max(0, (int)$pageNum_RSHalaqat - 1), $queryString_RSHalaqat); ?>"
                   tabindex="-1"> < </a>
            <?php } else { // Show if not first page ?>
                <a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_RSHalaqat < $totalPages_RSHalaqat) { // Show if not last page ?>
                <a title="التالي" class="button-primary"
                   href="<?php printf("%s?pageNum_RSHalaqat=%d%s", $currentPage, min($totalPages_RSHalaqat, (int)$pageNum_RSHalaqat + 1), $queryString_RSHalaqat); ?>"
                   tabindex="-1">
                    > </a>
            <?php } else { // Show if not first page ?>
                <a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
            <?php } // Show if not last page ?>
            <?php if ($pageNum_RSHalaqat < $totalPages_RSHalaqat) { // Show if not last page ?>
                <a title="الصفحة الأخيرة" class="button-primary"
                   href="<?php printf("%s?pageNum_RSHalaqat=%d%s", $currentPage, $totalPages_RSHalaqat, $queryString_RSHalaqat); ?>"
                   tabindex="-1"> >> </a>
            <?php } else { // Show if not first page ?>
                <a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
            <?php } // Show if not last page ?>
        </div>
        <br>
        السجلات <?php echo($startRow_RSHalaqat + 1) ?> إلى <?php echo min((int)$startRow_RSHalaqat + (int)$maxRows_RSHalaqat, $totalRows_RSHalaqat) ?>
        من <?php echo $totalRows_RSHalaqat ?>
    </center>
    <br/>
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
            $('#toggleCSS').href = "../CSS/alertify/alertify.default.css";
            alertify.set({
                delay: 5000,
            });
            alertify.success("تمت الإضافة بنجاح");
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
