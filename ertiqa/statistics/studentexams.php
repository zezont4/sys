<?php
include_once("../../functions.php");
include_once('../../fahd/fahd_functions.php');
$logedInUser = false;
if (isset($_SESSION['username'])) {
    $logedInUser = true;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$student_id = "";
if (isset($_GET['StudentID'])) {
    $student_id = $_GET['StudentID'];
}

$query_RSStudentData = sprintf("SELECT Stfullname,O_BurthDate,arabic_name,HName,StMobileNo,StID FROM view_0_students WHERE StID = %s", GetSQLValueString($student_id, "double"));
$RSStudentData = mysqli_query($localhost, $query_RSStudentData) or die(mysqli_error($localhost));
$row_RSStudentData = mysqli_fetch_assoc($RSStudentData);
$totalRows_RSStudentData = mysqli_num_rows($RSStudentData);


?>
<?php include('../../templates/header1.php'); ?>
<?php $PageTitle = 'استعلام عن ' . get_gender_label('st', ''); ?>
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
                    <label for="StudentID">رقم السجل المدني <?php echo get_gender_label('st', 'لل'); ?></label>
                </div>
                <input name="StudentID" type="text" value="<?php echo $student_id; ?>" id="StudentID"
                       data-required="true" data-type="digits" data-maxlength="10">
            </div>
            <div class="four columns">
                <input type="submit" class="button-primary" id="submit" value="بحث"/>
            </div>
        </form>
    </div>
<?php if ($totalRows_RSStudentData > 0) { // Show if recordset not empty ?>
    <div class="content CSSTableGenerator">
        <?php echo get_st_info($_GET['StudentID']); ?>
    </div>
    <?php include 'student_history/memorize_history.php'; ?>
    <?php include 'student_history/ertiqa_history.php'; ?>
    <?php include 'student_history/bra3m_history.php'; ?>
    <?php include 'student_history/fahd_history.php'; ?>
    <?php include 'student_history/salman_history.php'; ?>
    <?php include 'student_history/mutqin_history.php'; ?>
    <?php include 'student_history/ameer_history.php'; ?>
    <?php include 'student_history/shabab_history.php'; ?>


<?php } else { // if there is no student with that id no ?>
    <?php
    if (isset($_GET['StudentID'])) { ?>
        <div class="FieldsButton"><p style="font-size:22px;color:red;">لايوجد <?php echo get_gender_label('st', ''); ?>
                بهذا السجل المدني : <?php echo $student_id; ?></p></div>
    <?php } ?>
<?php } ?>
    <script type="text/javascript">
        showError();
    </script>

<?php include('../../templates/footer.php');
