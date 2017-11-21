<?php require_once("../../functions.php");
if (!login_check($all_groups) == true) {
    die('عفوا ... لا تملك صلاحيات دخول لهذه الصفحة');
}

$EdarahID_get = isset($_GET['EdarahID']) && $_GET['EdarahID'] != null ? $_GET['EdarahID'] : -1;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : null;

$mudeer_name = '()';
$sql_sex = sql_sex('e_sex');

$EdarahIDSql = $EdarahID_get > 0 ? "and StEdarah = $EdarahID_get" : '';
//المعلمون
if($user_group=='edarh'){
    $query_students = "SELECT * FROM view_0_students where StEdarah>0 $sql_sex and StEdarah=$user_id AND `hide`=0 order by StEdarah,StHalaqah,Stfullname";
}else{
    $query_students = "SELECT * FROM view_0_students where StEdarah>0 $sql_sex $EdarahIDSql AND `hide`=0 order by StEdarah,StHalaqah,Stfullname";
}
$students = mysqli_query($localhost, $query_students) or die('$query_students ' . mysqli_error($localhost));
$row_students = mysqli_fetch_assoc($students);

$pageTitle = "كشف مرتقيات كل " . get_gender_label('st', '');
require_once("../../templates/report_header1.php");
?>
<style>
    body {
        font-size: 12px;
    }

    .reportWrapper {
        width: 100%;
        max-width: 31cm;
    }

    .reportContent table td, table.Marks td, .reportContent table th {
        line-height: 22px;
        padding: 0 2px;
    }

    th {
        font-weight: normal;
    }

    h1, h2, h3 {
        line-height: 24px;
        margin: 5px 5px 0 0;
        font-weight: normal;
    }

    .report_description {
        margin: 5px 0 0 0;
        font-size: 16px;
    }

    .failed {
        color: red;
        text-decoration: line-through;
    }
</style>
<?php
$fasl_array = [
    'الأول',
    'الثاني',
    'الصيفية',
];
$fatrah_array = [
    'الأول',
    'الثانية',
    'الثالثة',
];
?>
<div class="printButton">
    <form name="form1" method="get" action="#">

        <?php create_edarah_combo($EdarahID_get); ?>
        <input type="submit" value="موافق">
    </form>
</div>

<div class="reportWrapper">
    <div class="reportContent">
        <p class="report_description">
            <?php
            echo $pageTitle;
            echo $EdarahID_get > 0 ? ' ' . get_gender_label('e', 'ل') . ' ' . get_edarah_name($EdarahID_get) : '';
            ?>
        </p>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <th>م</th>
                <th>اسم <?php echo get_gender_label('st', 'ال'); ?><br>والسجل المدني</th>
                <th><?php echo get_gender_label('e', 'ال'); ?> <br>والحلقة</th>
                <th><?php echo get_gender_label('t', 'ال'); ?></th>
                <th>المرحلة الدراسية</th>
                <th>الاختبارات السابقة في برنامج الإرتقاء</th>
            </tr>
            <?php
            $i = 1;

            do {
                //اختبارات الطالب
                $query_exams = sprintf("SELECT ex.StID,ex.ErtiqaID,ex.FinalExamDate,ex.FinalExamStatus,sh.Degree
										FROM er_ertiqaexams ex LEFT JOIN er_shahadah sh on ex.AutoNo=sh.ExamNo
										where  ex.FinalExamStatus>1 AND ex.StID=%s ORDER BY ex.FinalExamDate",
                    GetSQLValueString($row_students['StID'], "double"));
                $exams = mysqli_query($localhost, $query_exams) or die('$query_exams ' . mysqli_error($localhost));
                $row_exams = mysqli_fetch_assoc($exams);
                $row_exams_count = mysqli_num_rows($exams);

//				var_dump($totalRows_st_money);
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>
                        <div><?php echo $row_students['Stfullname']; ?> </div>
                        <div><?php echo $row_students['StID']; ?> </div>
                    </td>
                    <td> <?php echo $row_students['arabic_name']; ?><br><?php echo $row_students['HName']; ?></td>
                    <td> <?php echo get_teacher_name($row_students['TID'], true); ?> </td>
                    <td> <?php echo get_array_1($SchoolLevelNameAll, $row_students['school_level']); ?></td>
                    <td>
                        <?php
                        if ($row_exams_count > 0) {
                            do {
                                $class = $row_exams['FinalExamStatus'] > 2 ? 'class="failed"' : '';
                                ?>
                                <div <?php echo $class; ?>>
                                    <?php echo implode(' - ', [
                                        get_array_1($murtaqaName, $row_exams['ErtiqaID']),
                                        StringToDate($row_exams['FinalExamDate']),
                                        get_array_1($statusName, $row_exams['FinalExamStatus']),
                                        $row_exams['Degree'],
                                    ]); ?>
                                </div>
                            <?php } while ($row_exams = mysqli_fetch_assoc($exams));
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $i++;

            } while ($row_students = mysqli_fetch_assoc($students)) ?>

        </table>
    </div>
</div>

</body>
</html>