<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php $PageTitle = 'الطلاب المسجلون في مسابقة الهيئة العامة للرياضة'; ?>
<?php if (login_check('admin,ms,edarh,alaqat') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $Date1_Rs1 = 'and RDate>=' . $_SESSION ['default_start_date'];
    if (isset($_POST['Date1'])) {
        if (Input::get('Date1') != null) {
            $Date1_Rs1 = 'and RDate>=' . str_replace('/', '', Input::get('Date1'));
        }
    }
    $Date2_Rs1 = $Date2_Rs1 = 'and RDate<=' . $_SESSION ['default_end_date'];
    if (isset($_POST['Date2'])) {
        if (Input::get('Date2') != null) {
            $Date2_Rs1 = 'and RDate<=' . str_replace('/', '', Input::get('Date2'));
        }
    }

    if ($_SESSION['user_group'] != 'edarh') {
        $query_ReRegistered = sprintf("SELECT * FROM ms_shabab_rgstr where AutoNo>0  %s %s ORDER BY EdarahID,MsbkhID,HalakahID", $Date1_Rs1, $Date2_Rs1);
    } else {
        $query_ReRegistered = sprintf("SELECT * FROM ms_shabab_rgstr WHERE EdarahID = %s %s %s ORDER BY EdarahID,MsbkhID,HalakahID", $_SESSION['user_id'], $Date1_Rs1, $Date2_Rs1);
    }
    /*$query_ReRegistered = sprintf("SELECT
                                 O_TeacherName
                                ,O_Edarah
                                ,SUM(ExamPoints) as sumP
                                ,count(ExamPoints) as countp
                                  FROM view_er_ertiqaexams
                                  where FinalExamStatus=2 %s %s
                                  group by TeacherID
                                  order by sum(ExamPoints) desc",
                                  $Date1_Rs1,
                                  $Date2_Rs1);*/

    $ReRegistered = mysqli_query($localhost, $query_ReRegistered) or die(mysqli_error($localhost));
    $row_ReRegistered = mysqli_fetch_assoc($ReRegistered);
    $totalRows_ReRegistered = mysqli_num_rows($ReRegistered);

    ?>
    <?php include('../templates/header1.php'); ?>
    <style>

        #printButton {
            text-align: center;
            margin: 15px 0px;
            border-top: 2px dashed #2B9FBB;
            padding-top: 5px;
        }
    </style>
    <title><?php echo $PageTitle; ?></title>
    </head>
    <body>
    <?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?> </div><!--PageTitle-->

    <div class="content">
        <div id="hideInPrint">
            <P>
                * للاستعلام عن السنة الدراسية الحالية، اترك التواريخ فارغة
                <br>
                * يمكنك الاستعلام بالتاريخ الأول فقط أو الثاني فقط أو بالجميع
            </P>
            <form name="form1" method="post" action="<?php echo $editFormAction; ?>">
                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="Date1">التاريخ الأول</label>
                    </div>
                    <input type="text" name="Date1" id="Date1" zezo_date="true">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="Date2">التاريخ الثاني</label>
                    </div>
                    <input type="text" name="Date2" id="Date2" zezo_date="true">
                </div>
                <div class="four columns">
                    <input name="submit" type="submit" class="button-primary" id="submit" value="استعلام"/>
                </div>
                <input type="hidden" name="MM_show" value="form1">
            </form>
        </div>
    </div>
    <div class="content CSSTableGenerator">
        <?php if (isset($Date1_Rs1)) { ?>
            <div class="FieldsTitle">
                المسجلون في مسابقة الهيئة العامة للرياضة
                <?php echo((Input::get('Date1') == '' && Input::get('Date2') == '') ? ' ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?>
                خلال الفترة
                من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
                إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </div>
        <?php } ?>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td>م</td>
                <td>السجل المدني</td>
                <td>اسم <?php echo get_gender_label('st', 'ال'); ?></td>
                <td>الحلقة</td>
                <td>المجمع</td>
                <td>نوع المسابقة</td>
                <td>السنة الدراسية</td>
            </tr>
            <?php if ($totalRows_ReRegistered > 0) { // Show if recordset not empty ?>
                <?php $i = 1;
                do { ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row_ReRegistered['StID']; ?></td>
                        <td><?php echo get_student_name($row_ReRegistered['StID']); ?></td>
                        <td><?php echo get_halakah_name($row_ReRegistered['HalakahID']); ?></td>
                        <td><?php echo str_replace("مجمع ", "", get_edarah_name($row_ReRegistered['EdarahID'])); ?></td>
                        <td><?php echo get_array_1($shabab_msbkh_type, $row_ReRegistered['MsbkhID']); ?></td>
                        <td><?php echo get_array_1($SchoolLevelNameAll, $row_ReRegistered['SchoolLevelID']); ?></td>
                    </tr>
                    <?php $i++;
                } while ($row_ReRegistered = mysqli_fetch_assoc($ReRegistered)); ?>
            <?php } else { ?>
                <td colspan="7">لايوجد طلاب مسجلون في مسابقة الهيئة العامة للرياضة حسب التاريخ المحدد</td>
            <?php } ?>
        </table>
    </div>
    <div id="printButton">
        <input class="button-primary" type="button" value="طباعة" onclick="window.print()">
    </div>

    <?php include('../templates/footer.php'); ?>
    <?php
    mysqli_free_result($ReRegistered);
    ?>
<?php } else {
    include('../templates/restrict_msg.php');
} ?>