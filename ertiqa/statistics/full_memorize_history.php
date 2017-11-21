<?php
include_once(__DIR__ . '/../../functions.php');
include_once(__DIR__ . '/../../fahd/fahd_functions.php');

$logedInUser = false;
if (isset($_SESSION['username'])) {
    $logedInUser = true;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$student_id = "";
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
}

$query_RSStudentData = sprintf("SELECT Stfullname,O_BurthDate,arabic_name,HName,StMobileNo,StID FROM view_0_students WHERE StID = %s", GetSQLValueString($student_id, "double"));
$RSStudentData = mysqli_query($localhost, $query_RSStudentData) or die(mysqli_error($localhost));
$row_RSStudentData = mysqli_fetch_assoc($RSStudentData);
$totalRows_RSStudentData = mysqli_num_rows($RSStudentData);

?>
<?php include('../../templates/header1.php'); ?>
<?php $PageTitle = 'سجل الحفظ والسلوك اليومي'; ?>
    <title><?php echo $PageTitle; ?></title>
    </head>
    <body>

<?php include('../../templates/header2.php'); ?>

<?php include('../../templates/nav_menu.php'); ?>

    <div id="PageTitle"> <?php echo $PageTitle; ?> </div>
    <!--PageTitle-->
    <div class="content lp">
        <form name="form1" id="form1" method="GET" action="<?php echo $editFormAction; ?>" data-validate="parsley">
            <div class="six columns alpha">
                <div class="LabelContainer">
                    <label for="student_id">رقم السجل المدني <?php echo get_gender_label('st', 'لل'); ?></label>
                </div>
                <input name="student_id" type="text" value="<?php echo $student_id; ?>" id="student_id"
                       data-required="true" data-type="digits" data-maxlength="10">
            </div>
            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="limit">عدد السجلات المطلوب عرضها</label>
                </div>
                <input name="limit" type="text" value="<?php echo Input::get('limit'); ?>" id="student_id"
                       data-required="true" data-type="digits" data-maxlength="3">
            </div>
            <div class="four columns">
                <input type="submit" class="button-primary" id="submit" value="بحث"/>
            </div>
        </form>
    </div>
<?php if ($totalRows_RSStudentData > 0) { // Show if recordset not empty ?>
    <div class="content CSSTableGenerator">
        <?php echo get_st_info($_GET['student_id']); ?>
    </div>

    <?php
    $from_memorize_page = true;
    include 'student_history/memorize_history.php';
    ?>

<?php } else { // if there is no student with that id no ?>
    <?php
    if (isset($_GET['student_id'])) { ?>
        <div class="FieldsButton"><p style="font-size:22px;color:red;">لايوجد <?php echo get_gender_label('st', ''); ?>
                بهذا السجل المدني : <?php echo $student_id; ?></p></div>
    <?php }
}
include('../../templates/footer.php');
