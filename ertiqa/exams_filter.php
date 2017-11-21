<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php $PageTitle = 'تقرير مخصص لطلاب المرتقيات'; ?>
<?php if (login_check("admin,er") == true) { ?>
    <?php
    $EdarahIDS = $_SESSION['user_id'];

    $Date1 = isset($_GET['Date1']) ? $_GET['Date1'] : '';
    $Date2 = isset($_GET['Date2']) ? $_GET['Date2'] : '';
    $statusName_post = isset($_GET['statusName']) ? $_GET['statusName'] : '';
    $EdarahID = isset($_GET['EdarahID']) ? $_GET['EdarahID'] : '';
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $murtaqaName_post = isset($_GET['murtaqaName']) ? $_GET['murtaqaName'] : '';
    $degreeName = isset($_GET['degreeName']) ? $_GET['degreeName'] : '';
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }


    $statusName_sql = filter_array($statusName, 'جميع حالات الاختبار', 0, 'FinalExamStatus');
    $murtaqaName_sql = filter_array($murtaqaName, 'جميع المرتقيات', 1, 'ErtiqaID');
    $MarkName_Long_sql = filter_array($MarkName_Long, 'جميع التقديرات', 0, 'MarkName_Short');
    $totalRows_ReExams = 0;
    if (isset($_GET['MM_show'])) {

        if (isset($_GET['statusName'])) {
            if ($_GET['statusName'] == ' and FinalExamStatus =0 ') {
                $Date1_Rs1 = '';
                $Date2_Rs1 = '';
            } else {
                $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', $_SESSION ['default_start_date']);
                if (isset($Date1)) {
                    if ($Date1 != null) {
                        $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', $Date1);
                    }
                }
                $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', $_SESSION ['default_end_date']);
                if (isset($Date2)) {
                    if ($Date2 != null) {
                        $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', $Date2);
                    }
                }
            }
        }
        $edarah_id = '';
        if ($EdarahID != null) {
            $edarah_id = 'and EdarahID=' . str_replace('/', '', $_GET['EdarahID']);
        }


        mysqli_select_db($localhost, $database_localhost);
        $sql_sex = sql_sex('edarah_sex');
        if ($_SESSION['user_group'] != 'edarh') {
            $query_ReExams = sprintf("SELECT O_StudentName,Money,O_TeacherName3,O_FinalExamDate,FinalExamDate,
	FinalExamDegree,O_Edarah,EdarahID,O_HName,O_MurtaqaName,AutoNo,FinalExamStatus,MarkName_Short 
	FROM view_er_ertiqaexams 
	WHERE EdarahID>0 %s %s %s %s %s %s %s ORDER BY AutoNo DESC",
                $sql_sex,
                $statusName_post,
                $murtaqaName_post,
                $degreeName,
                $Date1_Rs1,
                $Date2_Rs1,
                $edarah_id
            );
        } else {
            $query_ReExams = sprintf("SELECT O_StudentName,Money,O_TeacherName3,O_FinalExamDate,FinalExamDate,
	FinalExamDegree,O_Edarah,EdarahID,O_HName,O_MurtaqaName,AutoNo,FinalExamStatus,MarkName_Short
	 FROM view_er_ertiqaexams 
	 WHERE EdarahID = %s %s %s %s %s %s %s ORDER BY AutoNo DESC",
                GetSQLValueString($_SESSION['user_id'], "int"),
                $sql_sex,
                $statusName_post,
                $murtaqaName_post,
                $degreeName,
                $Date1_Rs1,
                $Date2_Rs1
            );
        }
//	die($query_ReExams);
//echo $query_ReExams ;
        $ReExams = mysqli_query($localhost, $query_ReExams) or die(mysqli_error($localhost));
        $row_ReExams = mysqli_fetch_assoc($ReExams);
        $all_ReExams = mysqli_query($localhost, $query_ReExams);
        $totalRows_ReExams = mysqli_num_rows($all_ReExams);
    }
    ?>

    <?php include('../templates/header1.php'); ?>
    <title><?php echo $PageTitle; ?></title>
    <style>
        .hday {
            font-size: 12px
        }
    </style>
    </head>
    <body>
    <?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->
<div class="content no_print">
    <div class="FieldsTitle">تاريخ الاحصائية</div>
    <form name="form1" method="get" action="<?php echo $editFormAction; ?>">
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="Date1">التاريخ الأول</label>
            </div>
            <input type="text" name="Date1" value="<?php echo $Date1 ?>" id="Date1" zezo_date="true">
        </div>
        <div class="four columns">
            <div class="LabelContainer">
                <label for="Date2">التاريخ الثاني</label>
            </div>
            <input type="text" name="Date2" value="<?php echo $Date2 ?>" id="Date2" zezo_date="true">
        </div>
        <br class="clear">
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="Date2">نتيجة الاختبار</label>
            </div>
            <?php echo create_combo('statusName', $statusName_sql, 0, $statusName_post); ?>
        </div>
        <div class="four columns">
            <div class="LabelContainer">
                <label for="Date2">المجمع/الدار</label>
            </div>
            <?php
            if ($_SESSION['user_group'] == 'edarh') {
                create_edarah_combo($user_id);
            } else {
                create_edarah_combo($EdarahID);
            }
            ?>
        </div>
        <br class="clear">
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="Date2">المرتقى</label>
            </div>
            <?php echo create_combo('murtaqaName', $murtaqaName_sql, 0, $murtaqaName); ?>
        </div>
        <div class="four columns">
            <div class="LabelContainer">
                <label for="Date2">التقدير</label>
            </div>
            <?php echo create_combo('degreeName', $MarkName_Long_sql, 0, $degreeName); ?>
        </div>
        <div class="four columns omega left">
            <input name="submit" type="submit" class="button-primary" id="submit" value="استعلام"/>
        </div>
        <input type="hidden" name="MM_show" value="form1">
    </form>
    <?php if (isset($Date1_Rs1)) { ?>
        <br class="clear">
        <div class="sixteen">
            <p>احصائية <?php echo(($Date1 == '' && $Date2 == '') ? ' للعام الدراسي ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?>
                خلال الفترة
                من <?php echo(($Date1 != '') ? $Date1 : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
                إلى <?php echo(($Date2 != '') ? $Date2 : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </p></div>
    <?php } ?>
    <?php if ($totalRows_ReExams > 0) { // Show if recordset not empty ?>
</div>

    <div class="content CSSTableGenerator">
        <table class="table" id="table_data">
            <caption>مواعيد ونتائج الطلاب في اختبارات المرتقيات</caption>
            <tr>
                <td>م</td>
                <td>اسم <?php echo get_gender_label('st', 'ال'); ?></td>
                <td class="NoMobile">المجمع</td>
                <td>الحلقة</td>
                <td>المرتقى</td>
                <td>التاريخ</td>
                <td>الحالة</td>
                <td>الدرجة</td>
                <td>التقدير</td>
                <td>المكافأة</td>
            </tr>
            <?php
            $ii = 0;
            do { ?>
                <tr class="Status_<?php echo $row_ReExams['FinalExamStatus']; ?>">
                    <td><?php echo $ii + 1; ?></td>
                    <td><?php echo $row_ReExams['O_StudentName']; ?></td>
                    <td class="NoMobile"><?php echo str_replace("مجمع ", "", $row_ReExams['O_Edarah']); ?></td>
                    <td><?php echo $row_ReExams['O_HName']; ?></td>
                    <td><?php echo $row_ReExams['O_MurtaqaName']; ?></td>
                    <td><?php echo $row_ReExams['O_FinalExamDate']; ?></td>
                    <td><?php echo get_array_1($statusName, $row_ReExams['FinalExamStatus']); ?></a></td>
                    <td><?php echo $row_ReExams['FinalExamDegree']; ?></td>
                    <td><?php echo $row_ReExams['MarkName_Short']; ?></td>
                    <td><?php echo $row_ReExams['Money']; ?></td>
                </tr>
                <?php
                $ii++;
            } while ($row_ReExams = mysqli_fetch_assoc($ReExams)); ?>
        </table>
        <div id="lastDataTable"></div>
    </div><!--content-->
<?php } ?>
    <?php include('../templates/footer.php'); ?>
<?php } else {
    include('../templates/restrict_msg.php');
} ?>