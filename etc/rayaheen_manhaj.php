<?php
require_once('../functions.php');
$day_names = [
    0 => 'الأحد',
    1 => 'الاثنين',
    2 => 'الثلاثاء',
    3 => 'الأربعاء',
    4 => 'الخميس',
    5 => 'الجمعة',
    6 => 'السبت',
];
$day_names = [
    0 => 'أحد',
    1 => 'اثنين',
    2 => 'ثلاثاء',
    3 => 'أربعاء',
    4 => 'خميس',
    5 => 'جمعة',
    6 => 'سبت',
];
$PageTitle = 'جدول المتابعة';

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$first_day = "-1";
if (isset($_GET['first_day'])) {
    $first_day = $_GET['first_day'];
}

//التواريخ الهجرية
$h_date1 = "-1";
if (isset($_GET['h_date1'])) {
    $h_date1 = $_GET['h_date1'];
}
$h_date2 = "-1";
if (isset($_GET['h_date2'])) {
    $h_date2 = $_GET['h_date2'];
}


//المنهجيات
$manhaj_class = "-1";
if (isset($_GET['manhaj_class'])) {
    $manhaj_class = $_GET['manhaj_class'];
}
$query_manhaj = sprintf("SELECT * FROM rayaheen_manhaj WHERE `class` = %s order by id limit 20",
    GetSQLValueString($manhaj_class, "int")
);
$manhaj = mysqli_query($localhost, $query_manhaj) or die(mysqli_error($localhost));
$row_manhaj = mysqli_fetch_assoc($manhaj);
//$totalRows_manhaj = mysqli_num_rows($manhaj);
$manhaj_array = [];
do {
    array_push($manhaj_array, [$row_manhaj['hifth'], $row_manhaj['tathbeet'], $row_manhaj['murajah']]);
} while ($row_manhaj = mysqli_fetch_assoc($manhaj));
//var_dump($manhaj_array);
//var_dump($h_dates_array);
include('../templates/header1.php'); ?>
    <title><?php echo $PageTitle; ?></title>
    <style>
        @media screen, print {
            tr, td, th {
                vertical-align: middle;
            }

            .CSSTableGenerator table tr td {
                padding: 0;
                padding-top: 2px;
            }

            .CSSTableGenerator table tr th {
                background-color: #ddd;
                border: 1px solid #bdbdbd;
                padding-top: 2px;
                line-height: 22px;
            }
        }
    </style>
    </head>
    <body>
<?php include('../templates/header2.php');
include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->

    <div class="content CSSTableGenerator">
        <table>
            <tr valign="middle">
                <th rowspan="2" width="50px">اليوم</th>
                <th rowspan="2">الحفظ</th>
                <th rowspan="2">التثبيت</th>
                <th rowspan="2">المراجعة</th>
                <th rowspan="2">الدرجة<br>من ٣</th>
                <th colspan="4">التقرير</th>
                <th rowspan="2">توقيع<br>ولي الأمر</th>
            </tr>
            <tr valign="middle">
                <th width="50px">ترديد</th>
                <th width="50px">حضور</th>
                <th width="50px">سلوك</th>
                <th width="50px">انتباه</th>
            </tr>
            <?php
            //    echo $table_head;
            $day_loop = $first_day;
            $weeks_count = 1;
            for ($i = 0; $i < 20; $i++) {
                if ($weeks_count <= 4) {
                    $day_loop = ($day_loop > 4) ? 0 : $day_loop;
                    $weeks_count = ($day_loop == 4) ? $weeks_count + 1 : $weeks_count;
                    echo ($day_loop == 0 && $i != 0) ? '<td style="background-color: #ddd">تقرير</td><td colspan="3"></td>' : '';

                    if ($day_loop == 2) {
                        ?>
                        <tr>
                            <td><?php echo $day_names[2]; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php } else {
                        ?>

                        <tr>
                            <td><?php echo $day_names[$day_loop]; ?></td>
                            <td><?php echo $manhaj_array[$i][0]; ?></td>
                            <td><?php echo $manhaj_array[$i][1]; ?></td>
                            <td><?php echo $manhaj_array[$i][2]; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <?php
                    }
                    $day_loop++;
                }
            }
            ?>
        </table>
    </div>
<?php include('../templates/footer.php'); ?>