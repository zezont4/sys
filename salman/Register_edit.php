<?php
require_once('../functions.php');
require_once('../fahd/fahd_functions.php');

$PageTitle = 'تعديل بيانات ' . get_gender_label('st', '') . ' في مسابقة الملك سلمان'; ?>
<?php if (login_check('admin,ms') == true) { ?>
    <?php
    $userType = 0;
    if (isset($_SESSION['user_group'])) {
        $userType = $_SESSION['user_group'];
    }
    ?>
    <?php
    $auto_no = "-1";
    if (isset($_GET['auto_no'])) {
        $auto_no = $_GET['auto_no'];
    }

    //$editFormAction ="register_edit.php?AutoNo=".$auto_no;
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $query_RSFahdExam = sprintf("SELECT * FROM ms_salman_rgstr WHERE AutoNo=%s", GetSQLValueString($auto_no, "int"));
    $RSFahdExam = mysqli_query($localhost, $query_RSFahdExam) or die(mysqli_error($localhost));
    $row_RSFahdExam = mysqli_fetch_assoc($RSFahdExam);
    $totalRows_RSFahdExam = mysqli_num_rows($RSFahdExam);

    $StID = $row_RSFahdExam['StID'];
    $MsbkhID = $row_RSFahdExam['MsbkhID'];
    $ErtiqaID = $row_RSFahdExam['ErtiqaID'];
    $SchoolLevelID = $row_RSFahdExam['SchoolLevelID'];
    $HalakahID2 = $row_RSFahdExam['HalakahID2'];
    $EdarahID = $row_RSFahdExam['EdarahID'];
    $RDate = $row_RSFahdExam['RDate'];
    $date_of_memorize = $row_RSFahdExam['date_of_memorize'];


    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
        // search for dublicate musabakah ##############
        $study_fahd_start = get_fahd_year_start($RDate);
        $study_fahd_end = get_fahd_year_end($RDate);
        $study_fahd_name = get_fahd_year_name($RDate);

        $query_Rsdublicate = sprintf("SELECT * FROM ms_salman_rgstr WHERE StID=%s AND MsbkhID=%s AND AutoNo<>%s AND RDate>=%s AND RDate<=%s", GetSQLValueString($StID, "double"), GetSQLValueString($_POST['MsbkhID'], "int"), GetSQLValueString($auto_no, "int"), GetSQLValueString($study_fahd_start, "int"), GetSQLValueString($study_fahd_end, "int"));
        $Rsdublicate = mysqli_query($localhost, $query_Rsdublicate) or die(mysqli_error($localhost));
        $row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
        $totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);


        if ($totalRows_Rsdublicate > 0) {
            include('../templates/header1.php');
            echo '<script> function goBack() { window.history.back(); } </script>';
            echo '<h1 style="text-align:center;margin:20px;font-size:22px;">' . "<br><br>" . get_gender_label('st', 'ال') . ' : ' . get_student_name($StID) . ' قام بالتسجيل سابقا في العام الدراسي : ' . $study_fahd_name . ' وذلك في تاريخ : ' . StringToDate($row_Rsdublicate['RDate']) . '<br><br><br>' . 'ولا يمكن تكرار المشاركة أكثر من مرة في العام الواحد' . '<br><br>' . '<a href="/sys/fahd/Register_edit.php?AutoNo=' . $row_Rsdublicate['AutoNo'] . '">' . 'وإذا أردت تعديل المسابقة، اضغط هنا' . '</a>' . "</h1>";
            echo '<a href="" onclick="goBack()"><h1 style="text-align: center;font-size:18px;">رجوع لصفحة التسجيل</h1></a>';
            exit;
        }


        /*التأكد من ادخال تاريخ الختمة لأصغر حافظ*/
        if ($_POST['MsbkhID'] == 8 && $_POST['date_of_memorize'] == null) {
            include('../templates/header1.php');
            echo '<script> function goBack() { window.history.back(); } </script>';
            echo '<h1 style="text-align:center;margin:20px;font-size:22px;">' . "<br><br> يجب تسجيل تاريخ الختمة بالحلقة للمشاركين في مسابقة أصغر حافظ وحافظة</h1>";
            echo '<a href="" onclick="goBack()"><h1 style="text-align: center;font-size:18px;">رجوع لصفحة التسجيل</h1></a>';
            exit;
        }

        $insertSQL = sprintf("UPDATE  ms_salman_rgstr SET MsbkhID=%s,SchoolLevelID=%s,ErtiqaID=%s WHERE AutoNo=%s",
            GetSQLValueString($_POST['MsbkhID'], "int"),
            GetSQLValueString($_POST['SchoolLevelID'], "int"),
            GetSQLValueString($_POST['ErtiqaID'], "int"),
            GetSQLValueString($auto_no, "int")

        );
        $Result1 = mysqli_query($localhost, $insertSQL) or die(mysqli_error($localhost));
        if ($Result1) {
            $_SESSION['u1'] = "u1";
            header("Location: " . $editFormAction);
//            exit;
        }

    }
    $query_RsHalakat = sprintf("SELECT AutoNo,HName FROM `0_halakat` WHERE `hide`=0 AND EdarahID = %s", GetSQLValueString($EdarahID, "int"));
    $RsHalakat = mysqli_query($localhost, $query_RsHalakat) or die(mysqli_error($localhost));
    $row_RsHalakat = mysqli_fetch_assoc($RsHalakat);
    $totalRows_RsHalakat = mysqli_num_rows($RsHalakat);


    if (isset($_SESSION['user_id'])) {
        $colname_RSEdarat = $_SESSION['user_id'];
    }

    include('../templates/header1.php'); ?>

    <title><?php echo $PageTitle; ?></title>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/cupertino/jquery-ui.css"/>
    <!--	<link rel="stylesheet" href="../_css/del/ui-cupertino.calendars.picker.css"/>-->
    <style type="text/css">
        .FieldsButton .note {
            color: #4FA64B;
        }
    </style>
    </head>
    <body>
    <?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
    <!--PageTitle-->

    <?php $closed = 'no';
    if ($closed == 'no') { ?>
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
            <input name="MM_insert" type="hidden" value="form1">

            <div class="content CSSTableGenerator">
                <table>
                    <caption>بيانات <?php echo get_gender_label('st', 'ال'); ?> الأساسية (وقت التسجيل بالمرتقى)
                    </caption>
                    <tr>
                        <td>اسم <?php echo get_gender_label('st', 'ال'); ?></td>
                        <td>المجمع</td>
                        <td>الحلقة</td>
                        <td>المعلم</td>
                    </tr>
                    <tr>
                        <td><?php echo get_student_name($row_RSFahdExam['StID']); ?></td>
                        <td><?php echo get_edarah_name($row_RSFahdExam['EdarahID']); ?></td>
                        <td><?php echo get_halakah_name($row_RSFahdExam['HalakahID']); ?></td>
                        <td><?php echo get_teacher_name($row_RSFahdExam['TeacherID']); ?></td>
                    </tr>
                </table>
            </div>
            <div class="content">
                <div class="FieldsTitle">بيانات المسابقة</div>

                <div class="four columns">
                    <div class="LabelContainer">السنة الدراسي</div>
                    <?php echo create_combo("SchoolLevelID", $SchoolLevelNameAll, 0, $SchoolLevelID, 'class="full-width" data-required="true"'); ?>
                </div>
                <div class="four columns">
                    <div class="LabelContainer">آخر مرتقى اجتازه</div>
                    <?php echo create_combo("ErtiqaID", $murtaqaName, 1, $ErtiqaID, 'class="full-width" data-required="true"'); ?>
                </div>


                <div class="four columns omega">
                    <div class="LabelContainer">نوع المسابقة</div>
                    <?php echo create_combo("MsbkhID", $salman_msbkh_type, 0, $MsbkhID, 'class="full-width" data-required="true"'); ?>
                </div>
                <!--<div class="FieldsTitle">إذا كان الطالب ليس من طلاب الحلقة أعلاه، وتم نقله حتى يكمل العدد المسموح به في المسابقة، فيرجى كتابة اسم الحلقة المنقول منها (مؤقتا).</div>-->
                <br class="clear">

                <div class="four columns omega left">
                    <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
                </div>
            </div>
            <!--content-->
        </form>
    <?php } else {
        echo '<p class="WrapperMSG" >' . 'عفوا .. انتهت فترة التسجيل في مسابقة الملك سلمان' . '</p>';
    } ?>
    <?php include('../templates/footer.php'); ?>
    <?php
    if (isset($_SESSION['u1'])) {
        ?>
        <script>
            $(document).ready(function () {
                alertify.success("تم التعديل بنجاح");
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