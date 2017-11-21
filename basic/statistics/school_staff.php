<?php require_once("../../functions.php");
if (!login_check($all_groups) == true) {
    die('عفوا ... لا تملك صلاحيات دخول لهذه الصفحة');
}

$fasl_get = isset($_GET['fasl']) && $_GET['fasl'] != null ? $_GET['fasl'] : -1;
$fatrah_get = isset($_GET['fatrah']) && $_GET['fatrah'] != null ? $_GET['fatrah'] : -1;
$year_get = isset($_GET['year']) && $_GET['year'] != null ? $_GET['year'] : -1;


$EdarahID_get = isset($_GET['EdarahID']) && $_GET['EdarahID'] != null ? $_GET['EdarahID'] : $_SESSION['user_id'];
//$EdarahID_get = $_SESSION['user_id'];

$mudeer_name = '()';

$sql_sex = sql_sex('e_sex');
//الإداريون
$EdarahIDSql = $EdarahID_get > 0 ? "and edarah_id = $EdarahID_get" : '';
$query_emps = "SELECT * FROM view_0_employees where is_hidden=0 $EdarahIDSql $sql_sex order by job_title,full_name";
$emps = mysqli_query($localhost, $query_emps) or die('$query_emps  ' . mysqli_error($localhost));
$row_emps = mysqli_fetch_assoc($emps);

$EdarahIDSql = $EdarahID_get > 0 ? "and TEdarah = $EdarahID_get" : '';
//المعلمون
$query_teachers = "SELECT * FROM view_0_teachers where TEdarah>0 $sql_sex $EdarahIDSql AND `hide`=0 order by Tfullname";
$teachers = mysqli_query($localhost, $query_teachers) or die('$query_teachers ' . mysqli_error($localhost));
$row_teachers = mysqli_fetch_assoc($teachers);

$pageTitle = "استمارة بيانات " . get_gender_label('e', '');
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
        <select name="fasl">
            <option value="">الفصل الدراسي ...</option>
            <?php foreach ($fasl_array as $index => $fasl) { ?>
                <option <?php echo (string)$fasl_get === (string)$index ? 'selected' : null; ?> value="<?php echo $index; ?>"><?php echo $fasl; ?></option>
            <?php } ?>

        </select>
        <select name="fatrah">
            <option value="">الفترة ...</option>
            <?php foreach ($fatrah_array as $index => $fatrah) { ?>
                <option <?php echo (string)$fatrah_get === (string)$index ? 'selected' : null; ?> value="<?php echo $index; ?>"><?php echo $fatrah; ?></option>
            <?php } ?>
        </select>
        <?php
        //الأعوام الدراسية
        $query_years = "SELECT y_id,year_name,default_y FROM 0_years order by y_start_date desc";
        $years = mysqli_query($localhost, $query_years) or die('absent.php years - ' . mysqli_error($localhost));
        $row_years = mysqli_fetch_assoc($years);
        ?>
        <select name="year">
            <option value="">العام الدراسي ...</option>
            <?php do { ?>
                <option <?php echo $row_years['default_y'] == 1 ? "selected" : ''; ?> value="<?php echo $row_years['year_name']; ?>"><?php echo $row_years['year_name']; ?></option>
            <?php } while ($row_years = mysqli_fetch_assoc($years)) ?>
        </select>
        <?php create_edarah_combo($EdarahID_get); ?>
        <input type="submit" value="موافق">
    </form>
</div>

<div class="reportWrapper">
    <div class="reportHeader">
        <p style="float: right">
            المملكة العربية السعودية
            <br/>
            وزارة الشؤون الإسلامية والدعوة والإرشاد
        </p>
        <img style="margin-right: 200px" class="jam3iahLogo" src="/sys/_images/Logo.png" width="100">
        <p style="float: left">
            <?php echo isset($jameiahName) ? $jameiahName : ''; ?>
            <br/>
            <?php if ($session_sex == 0) {
                echo 'إدارة شؤون الدور النسائية';
            } else {
                echo 'إدارة شؤون المجمعات القرآنية';
            }
            ?>
        </p>
    </div><!-- reportHeader -->

    <div class="reportContent">

        <p class="report_description">
            <?php
            echo implode(' ', [$pageTitle,
                $EdarahID_get != -1 ? ' ' . get_edarah_name($EdarahID_get) : '()',
                'الفصل الدراسي : ',
                $fasl_get != -1 ? '(' . $fasl_array[$fasl_get] . ')' : '()',
                'الفترة :',
                $fatrah_get != -1 ? '(' . $fatrah_array[$fatrah_get] . ')' : '()',
                'للعام الدراسي :',
                $year_get != -1 ? '(' . $year_get . 'هـ )' : '()',]);
            ?>
        </p>
        <h1><?php echo get_gender_label('emps', ''); ?></h1>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <th>م</th>
                <th>العمل</th>
                <th>الاسم</th>
                <th>السجل المدني</th>
                <th>تاريخ المباشرة</th>
                <th>رقم الجوال</th>
                <th>المؤهل العلمي</th>
            </tr>
            <?php
            $i = 1;
            do {
                if ($row_emps['job_title'] == 10) $mudeer_name = $row_emps['full_name'];;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo get_array_1($job, $row_emps['job_title']); ?></td>
                    <td><?php echo $row_emps['full_name']; ?></td>
                    <td><?php echo $row_emps['national_id']; ?></td>
                    <td><?php echo StringToDate($row_emps['start_date']); ?></td>
                    <td><?php echo $row_emps['mobile_no']; ?></td>
                    <td><?php echo get_array_1($qualification, $row_emps['qualification']); ?></td>
                </tr>
                <?php
                $i++;
            } while ($row_emps = mysqli_fetch_assoc($emps)) ?>

        </table>

        <h1><?php echo get_gender_label('ts', ''); ?></h1>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <th>م</th>
                <th>الاسم</th>
                <th>المؤهل العلمي</th>
                <th>رقم الجوال</th>
                <th>تاريخ المباشرة</th>
                <th>حفظ القرآن</th>
                <th>التحفة</th>
                <th>الجزرية</th>
                <th>اجازة (القرآن)</th>
                <th>اجازة (الشاطبية)</th>
                <th>الحلقة</th>
                <th>عدد <?php echo get_gender_label('sts', 'ال'); ?></th>
                <th>وقت الحلقة</th>
                <th>مكان الحلقة</th>
                <th>نوع الحلقة</th>
            </tr>
            <?php
            $i = 1;
            $count_st = 0;
            $is_memorizing_quran = 0;
            $has_tu7fah = 0;
            $has_jazariah = 0;
            $has_ejazah_in_quran = 0;
            $has_ejazah_in_shatibiah = 0;
            do { ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>
                        <?php echo $row_teachers['Tfullname']; ?>
                    </td>
                    <td><?php echo get_array_1($qualification, $row_teachers['qualification']); ?></td>
                    <td><?php echo $row_teachers['TMobileNo']; ?></td>
                    <td><?php echo StringToDate($row_teachers['start_date']); ?></td>

                    <td><?php echo $yes_no[(int)$row_teachers['is_memorizing_quran']]; ?></td>
                    <td><?php echo $yes_no[(int)$row_teachers['has_tu7fah']]; ?></td>
                    <td><?php echo $yes_no[(int)$row_teachers['has_jazariah']]; ?></td>
                    <td><?php echo $yes_no[(int)$row_teachers['has_ejazah_in_quran']]; ?></td>
                    <td><?php echo $yes_no[(int)$row_teachers['has_ejazah_in_shatibiah']]; ?></td>

                    <td><?php echo $row_teachers['HName']; ?></td>
                    <td><?php echo $row_teachers['count_st']; ?></td>
                    <td><?php echo get_array_1($halakah_time, $row_teachers['h_time']); ?></td>
                    <td><?php echo $row_teachers['h_place']; ?></td>
                    <td><?php echo get_halakah_types($row_teachers['h_type'])->toString; ?></td>
                </tr>
                <?php
                $i++;
                $count_st += $row_teachers['count_st'];

                if ($row_teachers['is_memorizing_quran']) $is_memorizing_quran++;
                if ($row_teachers['has_tu7fah']) $has_tu7fah++;
                if ($row_teachers['has_jazariah']) $has_jazariah++;
                if ($row_teachers['has_ejazah_in_quran']) $has_ejazah_in_quran++;
                if ($row_teachers['has_ejazah_in_shatibiah']) $has_ejazah_in_shatibiah++;
            } while ($row_teachers = mysqli_fetch_assoc($teachers)) ?>
            <tr>
                <th colspan="5">المجموع</th>
                <td><?php echo $is_memorizing_quran; ?></td>
                <td><?php echo $has_tu7fah; ?></td>
                <td><?php echo $has_jazariah; ?></td>
                <td><?php echo $has_ejazah_in_quran; ?></td>
                <td><?php echo $has_ejazah_in_shatibiah; ?></td>
                <td>&nbsp;</td>
                <td><?php echo $count_st; ?></td>
                <td colspan="3">&nbsp;</td>
            </tr>
        </table>
    </div>
    <?php if ($EdarahID_get > 0) {
        $space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        ?>
        <p style="font-size: 14px;text-align: center">
            <?php echo implode(' ', [
                    get_array_1($job, 10),
                    get_gender_label('e', ''),
                    get_edarah_name($EdarahID_get),
                    ' : ',
                    $mudeer_name,
                    $space . $space,
                    'التوقيع :',
                    $space . $space . $space . $space,
                    'التاريخ : ',
                    getHijriDate()->formatted_date . ' هـ ',
                ]
            )
            ?>
        </p>
    <?php } ?>
</div>

</body>
</html>