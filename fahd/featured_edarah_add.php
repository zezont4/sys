<?php
require_once('../functions.php');
require_once('fahd_functions.php');

$userType = 'xx';
if (isset($_SESSION['user_group'])) {
    $userType = $_SESSION['user_group'];
}

$user_id = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

$pdo = new DB();
$pdo2 = new DB();

$f_e_date = Input::get('f_e_date') ? Input::get('f_e_date') : getHijriDate()->date;
//die($f_e_date);
$fahd_year_start = get_fahd_year_start($f_e_date);
$fahd_year_end = get_fahd_year_end($f_e_date);
$fahd_year_name = get_fahd_year_name($f_e_date);


//عدد الذين اجتازوا اختبار المرتقيات
$query_success_count = sprintf("select count(e.AutoNo) as count_st 
from er_ertiqaexams e,er_shahadah sh 
where e.AutoNo=sh.ExamNo and e.EdarahID=%s and e.FinalExamDate between %s and %s",
    $user_id,
    $fahd_year_start,
    $fahd_year_end);
$success_count = mysqli_query($localhost, $query_success_count) or die("feature_edarah_add.php 1: " . mysqli_error($localhost));
$row_success_count = mysqli_fetch_assoc($success_count);
$totalRows_success_count = mysqli_num_rows($success_count);
$count_ertiqa = 0;
if ($totalRows_success_count > 0) {
    $count_ertiqa = $row_success_count["count_st"];
}

//عدد الذين استلموا مكافأة البراعم
$query_bra3m = sprintf("select count(AutoNo) as count_st from er_bra3m  where EdarahID=%s and DDate between %s and %s",
    $user_id,
    $fahd_year_start,
    $fahd_year_end);
$bra3m = mysqli_query($localhost, $query_bra3m) or die("feature_edarah_add.php 2: " . mysqli_error($localhost));
$row_bra3m = mysqli_fetch_assoc($bra3m);
$totalRows_bra3m = mysqli_num_rows($bra3m);
$count_bra3m = 0;
if ($totalRows_bra3m > 0) {
    $count_bra3m = $row_bra3m["count_st"];
}

//عدد طلاب الصف الرابع فما فوق للمرتقيات
$query_children = sprintf("SELECT count(st_no) AS count_st FROM  0_students WHERE school_level IN (14,0,1,2,13) AND StEdarah=%s AND hide=0",
    $user_id,
    $fahd_year_start,
    $fahd_year_end);
$children = mysqli_query($localhost, $query_children) or die("feature_edarah_add.php 3: " . mysqli_error($localhost));
$row_children = mysqli_fetch_assoc($children);
$totalRows_children = mysqli_num_rows($children);
$count_children = 0;
$bra3m_percentage = 0;
$full_degree_bra3m = 0;
$bra3m_degree = 0;
if ($totalRows_children > 0) {
    $count_children = $row_children["count_st"];

    //نسبة نجاح البراعم
//    $bra3m_percentage = 80;//round($count_bra3m / $count_children * 100, 1);
    $bra3m_percentage = ($count_bra3m && $count_children) ? round($count_bra3m / $count_children * 100, 1) : 0;
//للحصول على الدرجة الكاملة للبراعم
    $full_degree_bra3m = round($count_children * 0.80, 0);
//الدرجة التي حصل عليها في البراعم حسب الاجتياز والنسبة
    $bra3m_degree = round((5 / 80) * $bra3m_percentage, 1);
    $bra3m_degree = $bra3m_degree > 5 ? 5 : $bra3m_degree;
    $bra3m_degree = $bra3m_degree < 0 ? 0 : $bra3m_degree;
}

//عدد طلاب الصف الثالث فما دون للبراعم
$query_young = sprintf("SELECT count(st_no) AS count_st FROM  0_students WHERE school_level BETWEEN 3 AND 15 AND StEdarah=%s AND hide=0",
    $user_id,
    $fahd_year_start,
    $fahd_year_end);
$young = mysqli_query($localhost, $query_young) or die("feature_edarah_add.php 4: " . mysqli_error($localhost));
$row_young = mysqli_fetch_assoc($young);
$totalRows_young = mysqli_num_rows($young);
$count_young = 0;
$ertiqa_percentage = 0;
$full_degree_ertiqa = 0;
$ertiqa_degree = 0;
if ($totalRows_young > 0) {
    $count_young = $row_young["count_st"];
    //نسبة نجاح المرتقيات
    $ertiqa_percentage = round($count_ertiqa / $count_young * 100, 1);
//للحصول على الدرجة الكاملة للمرتقيات
    $full_degree_ertiqa = round($count_young * 0.75, 0);
//الدرجة التي حصل عليها في المرتقيات حسب الاجتياز والنسبة
    $ertiqa_degree = round(0.5 * ($ertiqa_percentage - 35), 1);
    $ertiqa_degree = $ertiqa_degree > 20 ? 20 : $ertiqa_degree;
    $ertiqa_degree = $ertiqa_degree < 0 ? 0 : $ertiqa_degree;
}

//عدد الطلاب الذين لم يسجل لهم مرحلة دراسية
$query_no_school_level = sprintf("SELECT count(st_no) AS count_st FROM  0_students WHERE (school_level IS NULL OR school_level ='' and school_level <> '0') AND StEdarah=%s AND hide=0",
    $user_id,
    $fahd_year_start,
    $fahd_year_end);
$no_school_level = mysqli_query($localhost, $query_no_school_level) or die("feature_edarah_add.php 5: " . mysqli_error($localhost));
$row_no_school_level = mysqli_fetch_assoc($no_school_level);
$totalRows_no_school_level = mysqli_num_rows($no_school_level);
$count_no_school_level = 0;
if ($totalRows_no_school_level > 0) {
    $count_no_school_level = $row_no_school_level["count_st"];
}


if (isset($_POST['MM_insert'])) {

    // search for dublicate musabakah ##############

    $whereSQL = "where edarah_id=:edarah_id and f_e_date>=:study_fahd_start and f_e_date<=:study_fahd_end";
    $parameters = [':edarah_id' => $user_id, ':study_fahd_start' => $fahd_year_start, ':study_fahd_end' => $fahd_year_end];
    $old_registration = $pdo2->row("select * from ms_fahd_featured_edarah {$whereSQL}", $parameters);
    if ($old_registration) {
        include('../templates/header1.php');
        echo implode(' ', ['<h1 style="text-align:center;margin:20px;font-size:22px;"><br><br>',
            get_gender_label('e', 'ال'),
            '(' . $_SESSION['arabic_name'] . ')',
            ' قام بالتسجيل سابقا في العام الدراسي : ',
            $fahd_year_name,
            ' وذلك في تاريخ : ',
            StringToDate($old_registration->f_e_date) . ' هـ',
            '<br><br><br>',
            'ولا يمكن تكرار المشاركة أكثر من مرة في العام الواحد',
            '<br><br><br>',
            '<a href="/sys/fahd/featured_edarah_edit.php?id=' . $old_registration->id,
            '">',
            'وإذا أردت تعديل المسابقة، اضغط هنا',
            '</a>',
            "</h1>",
        ]);
        exit;
    }

    $sqlValues = [
        'edarah_id' => $user_id,
        'f_e_date'  => str_replace("/", "", Input::get('f_e_date')),
        'e2'        => Input::get('e2') ? Input::get('e2') : 0,
        'e4'        => Input::get('e4') ? Input::get('e4') : 0,
        'e5'        => Input::get('e5') ? Input::get('e5') : 0,
        'e11'       => Input::get('e11') ? Input::get('e11') : 0,
        'e13'       => Input::get('e13') ? Input::get('e13') : 0,
        'e15'       => Input::get('e15') ? Input::get('e15') : 0,
        'e16'       => Input::get('e16') ? Input::get('e16') : 0,
        'e18'       => Input::get('e18') ? Input::get('e18') : 0,
        'total_e'   => Input::get('total_e') ? Input::get('total_e') : 0,
    ];

    $dbResult = $pdo->zInsert('ms_fahd_featured_edarah', $sqlValues);

    if ($dbResult > 0) {
//        echo $pdo->lastInsertId();
        $_SESSION['u1'] = "u1";
        $_SESSION['RSMSG'][] = array(1, 'تمت العملية بجاح.');
        header("Location: featured_edarah_edit.php?id=" . $pdo->lastInsertId());
        exit;
    }
}


$open_g = true;//السماح للتسجيل للبنات
$open_b = true;//السماح للتسجيل للبنين
//متغير يحفظ نتيجة السماح بالتسجيل بحث يفحص جنس المستخدم الحالي مع المتغيرات في الأعلى
$open = false;


include('../templates/header1.php');

$PageTitle = 'تسجيل في مسابقة الإدارة المتميزة'; ?>
    <title><?php echo $PageTitle; ?></title>
    <style>
        .CSSTableGenerator td:nth-child(4), .CSSTableGenerator td:nth-child(2), .CSSTableGenerator td:nth-child(3) {
            text-align: center;
        }

        .CSSTableGenerator td {
            text-align: right;
            padding: 2px 2px;
        }

        .CSSTableGenerator table input[type="number"] {
            width: 50px;
            padding: 1px 0;
            height: 18px;
            border: 1px solid #838383;
        }

        input.error-value {
            color: red;
            background-color: #ffcaca;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            /*border-bottom: 1px dotted black; !* If you want dots under the hoverable text *!*/
        }

        .tooltip {
            width: 34px;
            text-align: center;
        }

        .tooltip img:hover {
            opacity: 1;
        }

        .tooltip img {
            opacity: 0.7;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 300px;
            top: 1%;
            right: 90%;
            /*margin-left: -150px;*/
            direction: rtl;
            background-color: #FDFFC2;
            color: #000;
            text-align: right;
            padding: 5px;
            border: 1px solid #ddd;
            /*margin: 5px;*/
            border-radius: 5px;
            box-shadow: -5px 5px 8px #c7c7c7;
            position: absolute;
            z-index: 1;
        }

        .tooltip:hover .tooltiptext, .tooltip:focus .tooltiptext, .tooltip:active .tooltiptext {
            visibility: visible;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="fahd_style.css">

    </head>
    <body>
<?php
if (login_check('admin,edarh') == true) {
    include('../templates/header2.php');
    include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
    <!--PageTitle-->

<?php
if ($_SESSION['sex'] == 1 && $open_b == true) {
    $open = true;
} elseif ($_SESSION['sex'] == 0 && $open_g == true) {
    $open = true;
} elseif ($_SESSION['sex'] == 2) {
    $open = true;
}

if ($open === true) { ?>
    <?php if ($count_no_school_level){ ?>
<br>
    <h2 style="color: red;text-align: center;padding: 10px 5px;font-size: 16px">تنبيه : يوجد عدد (
        <?php echo $count_no_school_level; ?>
        )
        <?php echo get_gender_label('st', ''); ?>
        بدون مرحلة دراسية
        يرجى مراجعة بيانات <?php echo get_gender_label('sts', 'ال'); ?>
        وإكمال بياناتهم حتى لا تتأثر نقاط <?php echo get_gender_label('e', 'ال'); ?>
        <br>
        <br>
        <br>
        <a href="/sys/basic/kashf/kashf_form.php">اضغط هنا لاستعراض بيانات
            <?php echo get_gender_label('sts', 'ال'); ?></a>
    </h2>
<?php } ?>
    <form method="post" name="form1" id="form1" data-validate="parsley">
        <input name="edarah_id" type="hidden" value="22">
        <input name="f_e_date" type="hidden" value="14381122">
        <!--بيانات الإدارة-->
        <div class="content">
            <div class="FieldsTitle">بيانات <?php echo get_gender_label('e', 'ال'); ?> الأساسية</div>

            <div class="three columns alpha top_padding">تاريخ تسجيل البيانات</div>
            <div class="three columns"><input name="f_e_date" type="text" id="f_e_date" readonly data-required="true"/>
            </div>
            <div
                    class="three columns top_padding"><?php echo get_gender_label('e') . ' ' . $_SESSION['arabic_name']; ?>  </div>

        </div>


        <div class="content CSSTableGenerator">
            <table>
                <tbody>
                <tr>
                    <td> البند</td>
                    <td> الدرجة</td>
                    <td> الدرجة الكلية</td>
                    <td> الشرح</td>
                    <td> المطلوب</td>
                    <td> الجهة المقيمة</td>
                </tr>

                <tr>
                    <td> برامج تطويرية أو تحفيزية<?php echo string_to_tooltip('شروط الأعمال التطويرية أو التحفيزية :<br>
1- لم يسبق إليه في الجمعية.<br>
2- أن يكون له أثر واضح في حفظ الطلاب وانضباطهم.<br>
3- أن يكون من الأعمال الجديدة لهذا العام. <br>
4- عند تطوير الإدارة لعمل سابق فإنه تحسب نصف الدرجة.<br>
5- رفع تقرير عن الأعمال التطويرية حسب نموذج معد مسبقاً.'); ?></td>
                    <td><input max="6" name="e2" type="number" step="any"></td>
                    <td> 6</td>
                    <td><?php echo string_to_tooltip("
                        لكل عمل تطويري أو تحفيزي درجتين .
                    "); ?></td>
                    <td> تعبئة نموذج ( 1 ) <a href="docs/f1.doc" download="نموذج 1">تحميل</a></td>
                    <td> الإشراف</td>
                </tr>

                <tr>
                    <td colspan="6" style="padding: 10px 2px;text-align: center">
                        <?php echo implode(' ', [
                            'المرتقيات : اجمالي عدد ',
                            get_gender_label('sts'),
                            ' الصف الرابع ابتدائي فما فوق:',
                            $count_young,
                            '. ',
                            'عدد من اجتاز مرتقى خلال هذا العام :',
                            $count_ertiqa,
                            '. ',
                            ' نسبة الاجتياز :',
                            $ertiqa_percentage,
                            '%',
                            '<br>',
                            ' للحصول على الدرجة الكاملة، يجب أن يكون اجمالي الناجحون في المرتقيات :',
                            $full_degree_ertiqa,
                            'ناجحا فأكثر بنسبة 75% فأكثر',
                        ]);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td> الناجحون في برنامج الارتقاء</td>
                    <td><input max="20" name="e4" type="number" step="any" readonly
                               value="<?php echo $ertiqa_degree; ?>"></td>
                    <td> 20</td>
                    <td><?php echo string_to_tooltip("
                        نجاح 75% فما فوق من أعداد طلاب المرتقيات =20 درجة <br>
                        65% =15 <br>
                        55% =10 <br>
                        45% =5
                    "); ?></td>
                    <td>يتم احتساب النقاط بشكل آلي</td>
                    <td> الارتقاء</td>
                </tr>

                <tr>
                    <td colspan="6" style="padding: 10px 2px;text-align: center">
                        <?php echo implode(' ', [
                            'البراعم : اجمالي عدد ',
                            get_gender_label('sts'),
                            ' الصف الثالث ابتدائي فما دون:',
                            $count_children,
                            '. ',
                            'عدد من اجتاز درجات سلم البراعم خلال هذا العام :',
                            $count_bra3m,
                            '. ',
                            ' نسبة الاجتياز :',
                            $bra3m_percentage,
                            '%',
                            '<br>',
                            ' للحصول على الدرجة الكاملة، يجب أن يكون اجمالي الحاصلين على جوائز البراعم :',
                            $full_degree_bra3m,
                            ' فأكثر بنسبة 80% فأكثر',
                        ]);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td> الطلاب المستحقين لسلم البراعم</td>
                    <td><input max="5" name="e5" type="number" step="any" readonly
                               value="<?php echo $bra3m_degree; ?>"></td>
                    <td> 5</td>
                    <td><?php echo string_to_tooltip("
                        حصول 80% من أعداد طلاب البراعم على جوائز سلم البراعم <br>
                        ( من ليس لديهم براعم ترحل الدرجة للارتقاء )
                    "); ?></td>
                    <td>يتم احتساب النقاط بشكل آلي</td>
                    <td> الارتقاء</td>
                </tr>

                <tr>
                    <td> الفوز بجوائز الجمعية</td>
                    <td><input max="10" name="e11" type="number" step="any"></td>
                    <td> 10</td>
                    <td><?php echo string_to_tooltip("
                        كل معلم أو طالب فائز درجة وكل حلقة فائزة درجتان
                    "); ?></td>
                    <td> تعبئة نموذج ( 2 ) <a href="docs/f2.doc" download="نموذج 2">تحميل</a></td>
                    <td> المسابقات</td>
                </tr>

                <tr>
                    <td> التواصل مع البيئة المحيطة</td>
                    <td><input max="2" name="e13" type="number" step="any"></td>
                    <td> 2</td>
                    <td><?php echo string_to_tooltip("
                        درجة للتواصل مع كل من :جماعة المسجد ، أولياء الأمور.
                    "); ?></td>
                    <td> تعبئة نموذج ( 3 ) <a href="docs/f3.doc" download="نموذج 3">تحميل</a></td>
                    <td> الإشراف</td>
                </tr>

                <tr>
                    <td> عدد الحافظين لهذا العام</td>
                    <td><input max="3" name="e15" type="number" step="any"></td>
                    <td> 3</td>
                    <td><?php echo string_to_tooltip("
                        درجة عن كل طالب مجتاز اختبار الحفظة
                    "); ?></td>
                    <td> تعبئة نموذج ( 4 ) <a href="docs/f4.doc" download="نموذج 4">تحميل</a></td>
                    <td> الحفظة</td>
                </tr>
                <tr>
                    <td> التقرير لأعمال الإدارة</td>
                    <td><input max="3" name="e16" type="number" step="any"></td>
                    <td> 3</td>
                    <td><?php echo string_to_tooltip("
                        يستحق الدرجة الكاملة من يرفع التقرير في وقته مكتمل العناصر .
                    "); ?></td>
                    <td> تعبئة نموذج (6 ) <a href="docs/f6.doc" download="نموذج 6">تحميل</a></td>
                    <td> الإشراف</td>
                </tr>

                <tr>
                    <td>تمثيل الجمعية في المسابقات الخارجية</td>
                    <td><input max="5" name="e18" type="number" step="any"></td>
                    <td>5</td>
                    <td><?php echo string_to_tooltip("
كل معلم أو طالب ممثل للجمعية درجتين<br>
 و3 درجات عند تأهله  للمرحلة النهائية<br>
 و5 درجات عند فوزه بالمرحلة النهائية
                     "); ?></td>
                    <td>تعبئة نموذج ( 5 ) <a href="docs/f5.doc" download="نموذج 5">تحميل</a></td>
                    <td>المسابقات</td>
                </tr>

                <input max="113" name="total_e" id="total_e" type="hidden" value="<?php echo $bra3m_degree + $ertiqa_degree; ?>">
                </tbody>
            </table>

            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="حفظ"/>
            </div>

            <input name="MM_insert" type="hidden" value="form1">
    </form>

<?php } else { ?>
<hr>
    <h1 class="WrapperMSG">عفوا .. انتهت فترة التسجيل في مسابقة الإدارة المتميزة</h1>
<br>
<?php } ?>

<?php include('../templates/footer.php'); ?>
<?php
if (isset($_SESSION['u1'])) {
    echo '<script>$(document).ready(function() {alertify.success("تم التسجيل في المسابقة بنجاح");});</script>';
    $_SESSION['u1'] = null;
    unset($_SESSION['u1']);
}
?>
    <script type="text/javascript">
        showError();
        $('#f_e_date').val(get_formated_hijri_date(zezo_get_hijri_date('now')));

        var e_fields = ['e2', 'e4', 'e5', 'e11', 'e13', 'e15', 'e16', 'e18'];
        var e_text_count = e_fields.length;
        function calculate() {
            var total_e = 0;
            for (var i = 0; i < e_text_count; i++) {
                var current_e = document.getElementsByName(e_fields[i])[0];
                var max_value = Number(current_e.getAttribute('max'));

                total_e += Number(current_e.value);
                document.getElementById('total_e').value = total_e;

                if (current_e.value > max_value) {
                    current_e.classList.add("error-value");
                    current_e.setAttribute('title', 'العدد أكبر من الدرجة الكلية' + '( ' + max_value + ')');
                } else {
                    current_e.classList.remove("error-value");
                    current_e.removeAttribute('title');
                }
            }
        }

        for (var i = 0; i < e_text_count; i++) {
            var e_text = document.getElementsByName(e_fields[i])[0];
            e_text.addEventListener("change", calculate);
            e_text.addEventListener("blur", calculate);
        }
    </script>

<?php } else {
    include('../templates/restrict_msg.php');
}