<?php
require_once('../functions.php');
require_once('fahd_functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$userType = 'xx';
if (isset($_SESSION['user_group'])) {
    $userType = $_SESSION['user_group'];
}
$pdo = new DB();

$user_id = $pdo->row('select edarah_id from ms_fahd_featured_edarah where id=:id', [':id' => Input::get('id')])->edarah_id;

if (isset($_POST['MM_insert'])) {
    $sqlValues = [
        'e1'      => Input::get('e1'),
        'e2'      => Input::get('e2'),
        'e3'      => Input::get('e3'),
        'e4'      => Input::get('e4'),
        'e5'      => Input::get('e5'),
        'e6'      => Input::get('e6'),
        'e7'      => Input::get('e7'),
        'e8'      => Input::get('e8'),
        'e9'      => Input::get('e9'),
        'e10'     => Input::get('e10'),
        'e11'     => Input::get('e11'),
        'e12'     => Input::get('e12'),
        'e13'     => Input::get('e13'),
        'e14'     => Input::get('e14'),
        'e15'     => Input::get('e15'),
        'e16'     => Input::get('e16'),
        'e17'     => Input::get('e17'),
        'e18'     => Input::get('e18'),
        'e19'     => Input::get('e19'),
        'total_e' => Input::get('total_e'),
    ];

    $dbResult = $pdo->zUpdate('ms_fahd_featured_edarah', $sqlValues, 'id=:id', array(':id' => Input::get('id')));

    if ($dbResult > 0) {
        $_SESSION['u1'] = "u1";
    }
}

$pdo = new DB();
$current_musabka_data = $pdo->row("select * from ms_fahd_featured_edarah where id=:id", [':id' => Input::get('id')]);

//$f_e_date = Input::get('f_e_date') ? Input::get('f_e_date') : getHijriDate()->date;
$f_e_date = $pdo->row('select f_e_date from ms_fahd_featured_edarah where id=:id', [':id' => Input::get('id')])->f_e_date;

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
    $bra3m_percentage = round($count_bra3m / $count_children * 100, 1);
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


$open_g = true;//السماح للتسجيل للبنات
$open_b = true;//السماح للتسجيل للبنين
//متغير يحفظ نتيجة السماح بالتسجيل بحث يفحص جنس المستخدم الحالي مع المتغيرات في الأعلى
$open = false;


include('../templates/header1.php');

$PageTitle = 'بيانات التسجيل في مسابقة الإدارة المتميزة'; ?>
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

        .CSSTableGenerator table input[disabled="disabled"] {
            background-color: rgba(243, 243, 243, 0);
            border: none;
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
<?php
if (login_check('admin,ms,edarh') == true) {
    include('../templates/header2.php');
    include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
    <!--PageTitle-->

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

    <form action="" method="post" name="form1" id="form1" data-validate="parsley">
        <!--        <input name="edarah_id" type="hidden" value="22">-->
        <!--        <input name="f_e_date" type="hidden" value="14381122">-->
        <!--بيانات الإدارة-->
        <div class="content">
            <div class="FieldsTitle">بيانات <?php echo get_gender_label('e', 'ال'); ?> الأساسية</div>

            <div class="three columns alpha top_padding">تاريخ تسجيل البيانات</div>
            <div class="three columns"><input name="f_e_date" type="text" id="f_e_date"
                                              value="<?php echo StringToDate($current_musabka_data->f_e_date); ?>"
                                              readonly
                                              data-required="true"/>
            </div>
            <div
                    class="three columns top_padding"><?php echo get_gender_label('e') . ' ' . get_edarah_name($user_id); ?>  </div>
        </div>

        <div class="content CSSTableGenerator">

            <table>
                <tr>
                    <td> م</td>
                    <td> البند</td>
                    <td> الدرجة</td>
                    <td> الدرجة الكلية</td>
                    <td> الشرح</td>
                    <td> المطلوب</td>
                    <td> الجهة المقيمة</td>
                </tr>
                <tbody>
                <tr>
                    <td> 1</td>
                    <td> المظهر العام للإدارة</td>
                    <td><input max="3" name="e1" type="number" step="any"
                               value="<?php echo $current_musabka_data->e1; ?>"></td>
                    <td>3</td>
                    <td><?php echo string_to_tooltip("
                        درجة لكل من:<br> 1- لوحة الإدارة الخارجية والداخلية <br> 2- تفعيل لوحة الشرف <br>3 - مظهر الإدارة والحلقات.
                    "); ?></td>
                    <td> زيارة اللجنة لتقييم البند</td>
                    <td> اللجنة</td>
                </tr>
                <tr>
                    <td> 2</td>
                    <td> برامج تطويرية أو تحفيزية<?php echo string_to_tooltip('شروط الأعمال التطويرية أو التحفيزية :<br>
1- لم يسبق إليه في الجمعية.<br>
2- أن يكون له أثر واضح في حفظ الطلاب وانضباطهم.<br>
3- أن يكون من الأعمال الجديدة لهذا العام. <br>
4- عند تطوير الإدارة لعمل سابق فإنه تحسب نصف الدرجة.<br>
5- رفع تقرير عن الأعمال التطويرية حسب نموذج معد مسبقاً.'); ?></td>
                    <td><input max="6" name="e2" type="number" step="any"
                               value="<?php echo $current_musabka_data->e2; ?>"></td>
                    <td> 6</td>
                    <td><?php echo string_to_tooltip("
                        لكل عمل تطويري أو تحفيزي درجتين .
                    "); ?></td>
                    <td> تعبئة نموذج ( 1 ) <a href="docs/f1.doc" download="نموذج 1">تحميل</a></td>
                    <td> الإشراف</td>
                </tr>
                <tr>
                    <td> 3</td>
                    <td> الدورات التدريبية</td>
                    <td><input max="8" name="e3" type="number" step="any"
                               value="<?php echo $current_musabka_data->e3; ?>"></td>
                    <td> 8</td>
                    <td><?php echo string_to_tooltip("
                        حضور 100% من العدد المطلوب في الدورات =8 <br>
                        حضور 95%  =7   <br>
                        حضور 90%  = 6 <br>
                        حضور 85%  =5   <br>
                        حضور 80%  = 4 وهكذا ... )
                    "); ?></td>
                    <td> تقرير من قسم التدريب</td>
                    <td> التدريب</td>
                </tr>
                <tr>
                    <td colspan="7" style="padding: 10px 2px;text-align: center">
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
                    <td> 4</td>


                    <td> الناجحون في برنامج الارتقاء</td>
                    <td><input max="20" name="e4" type="number" step="any"
                               value="<?php echo $current_musabka_data->e4; ?>"></td>
                    <td> 20</td>
                    <td><?php echo string_to_tooltip("
                        نجاح 75% فما فوق من أعداد طلاب المرتقيات =20 درجة <br>
                        65% =15 <br>
                        55% =10 <br>
                        45% =5
                    "); ?></td>
                    <td> تقرير من قسم الارتقاء</td>
                    <td> الارتقاء</td>
                </tr>
                <tr>
                    <td colspan="7" style="padding: 10px 2px;text-align: center">
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
                    <td> 5</td>
                    <td> الطلاب المستحقين لسلم البراعم</td>
                    <td><input max="5" name="e5" type="number" step="any"
                               value="<?php echo $current_musabka_data->e5; ?>"></td>
                    <td> 5</td>
                    <td><?php echo string_to_tooltip("
                        حصول 80% من أعداد طلاب البراعم على جوائز سلم البراعم <br>
                        ( من ليس لديهم براعم ترحل الدرجة للارتقاء )
                    "); ?></td>
                    <td> إرفاق صورة من كشوفات استلام برنامج البراعم</td>
                    <td> الارتقاء</td>
                </tr>
                <tr>
                    <td> 6</td>
                    <td> وجود وتفعيل السجلات والملفات</td>
                    <td><input max="5" name="e6" type="number" step="any"
                               value="<?php echo $current_musabka_data->e6; ?>"></td>
                    <td> 5</td>
                    <td><?php echo string_to_tooltip("
                        درجة لوجود وتفعيل كل من :<br>
                        1- سجل الحضور والانصراف <br>
                        2- ملفات المعلمين<br>
                        3- ملفات الطلاب<br>
                        4- سجل غياب الطلاب <br>
                        5- سجل زيارة المدير للمعلمين
                    "); ?></td>
                    <td> زيارة اللجنة لتقييم البند</td>
                    <td> اللجنة</td>
                </tr>
                <tr>
                    <td> 7</td>
                    <td> جودة الخطة العامة للإدارة ، ودقة تنفيذها</td>
                    <td><input max="4" name="e7" type="number" step="any"
                               value="<?php echo $current_musabka_data->e7; ?>"></td>
                    <td> 4</td>
                    <td><?php echo string_to_tooltip("
                        درجتين لكل من :<br>
                        1- تفعيل الخطة <br>
                        2- دقة تنفيذها .
                    "); ?></td>
                    <td> إرفاق الخطة مع تواريخ تنفيذها</td>
                    <td> الإشراف</td>
                </tr>
                <tr>
                    <td> 8</td>
                    <td> تفعيل مسابقة الفهد</td>
                    <td><input max="10" name="e8" type="number" step="any"
                               value="<?php echo $current_musabka_data->e8; ?>"></td>
                    <td> 10</td>
                    <td><?php echo string_to_tooltip("
                        درجتين لكل من :<br>
                        1- إقامة المسابقة الداخلية.<br>
                        2- تكريم الفائزين.<br>
                        3- رفع الأسماء في وقتها.<br>
                        4- حضور الطلاب للمسابقة في الجمعية. <br>
                        5- رفع تقرير عن المسابقة.
                    "); ?></td>
                    <td> إرفاق صورة من تقرير التصفيات الأولية داخل الإدارات</td>
                    <td> المسابقات</td>
                </tr>
                <tr>
                    <td> 9</td>
                    <td> حضور اجتماعات ولقاءات
                        الجمعية <?php echo string_to_tooltip('المقصود بالاجتماعات : اجتماعات الإشراف مع المديرين. والمقصود باللقاءات : ما يطرحه التدريب من لقاءات خلال العام. '); ?></td>
                    <td><input max="4" name="e9" type="number" step="any"
                               value="<?php echo $current_musabka_data->e9; ?>"></td>
                    <td> 4</td>
                    <td><?php echo string_to_tooltip("
                        حضور100% من العدد المطلوب في الاجتماعات واللقاءات =4 <br>
                        حضور 95%  =3   <br>
                        حضور 90%  = 2 <br>
                        حضور 85%  =1
                    "); ?></td>
                    <td> تقرير من قسمي الإشراف والتدريب</td>
                    <td> الإشراف والتدريب</td>
                </tr>
                <tr>
                    <td> 10</td>
                    <td> التفاعل مع أقسام الجمعية</td>
                    <td><input max="12" name="e10" type="number" step="any"
                               value="<?php echo $current_musabka_data->e10; ?>"></td>
                    <td> 12</td>
                    <td><?php echo string_to_tooltip("
                        درجتين للتفاعل مع كل من :<br>
                        1- الإشراف <br>
                        2- التدريب<br>
                        3- الارتقاء<br>
                        4- المالية <br>
                        5- العلاقات<br>
                        6- المسابقات .
                    "); ?></td>
                    <td> تقرير من الأقسام المعنية</td>
                    <td> اللجنة</td>
                </tr>
                <tr>
                    <td> 11</td>
                    <td> الفوز بجوائز الجمعية</td>
                    <td><input max="10" name="e11" type="number" step="any"
                               value="<?php echo $current_musabka_data->e11; ?>"></td>
                    <td> 10</td>
                    <td><?php echo string_to_tooltip("
                        كل معلم أو طالب فائز درجة وكل حلقة فائزة درجتان
                    "); ?></td>
                    <td> تعبئة نموذج ( 2 ) <a href="docs/f2.doc" download="نموذج 2">تحميل</a></td>
                    <td> المسابقات</td>
                </tr>
                <tr>
                    <td> 12</td>
                    <td> عقد اجتماعين سنويين بمنسوبي المجمع</td>
                    <td><input max="2" name="e12" type="number" step="any"
                               value="<?php echo $current_musabka_data->e12; ?>"></td>
                    <td> 2</td>
                    <td><?php echo string_to_tooltip("
                        كل اجتماع درجة بشرط وجود محضر
                    "); ?></td>
                    <td> إرفاق صورة من محضر الاجتماع</td>
                    <td> الإشراف</td>
                </tr>
                <tr>
                    <td> 13</td>
                    <td> التواصل مع البيئة المحيطة</td>
                    <td><input max="2" name="e13" type="number" step="any"
                               value="<?php echo $current_musabka_data->e13; ?>"></td>
                    <td> 2</td>
                    <td><?php echo string_to_tooltip("
                        درجة للتواصل مع كل من :جماعة المسجد ، أولياء الأمور.
                    "); ?></td>
                    <td> تعبئة نموذج ( 3 ) <a href="docs/f3.doc" download="نموذج 3">تحميل</a></td>
                    <td> الإشراف</td>
                </tr>
                <tr>
                    <td> 14</td>
                    <td> نسبة حضور الطلاب من المسجلين</td>
                    <td><input max="3" name="e14" type="number" step="any"
                               value="<?php echo $current_musabka_data->e14; ?>"></td>
                    <td> 3</td>
                    <td><?php echo string_to_tooltip("
                        نسبة الحضور اليومي 85% من الطلاب =درجة  <br>
                        90%=درجتان <br>
                        95% فأكثر= ثلاث درجات.
                    "); ?></td>
                    <td> كشف الحضور المرفوع للإشراف</td>
                    <td> الإشراف</td>
                </tr>
                <tr>
                    <td> 15</td>
                    <td> عدد الحافظين لهذا العام</td>
                    <td><input max="3" name="e15" type="number" step="any"
                               value="<?php echo $current_musabka_data->e15; ?>"></td>
                    <td> 3</td>
                    <td><?php echo string_to_tooltip("
                        درجة عن كل طالب مجتاز اختبار الحفظة
                    "); ?></td>
                    <td> تعبئة نموذج ( 4 ) <a href="docs/f4.doc" download="نموذج 4">تحميل</a></td>
                    <td> الحفظة</td>
                </tr>
                <tr>
                    <td> 16</td>
                    <td> التقرير لأعمال الإدارة</td>
                    <td><input max="3" name="e16" type="number" step="any"
                               value="<?php echo $current_musabka_data->e16; ?>"></td>
                    <td> 3</td>
                    <td><?php echo string_to_tooltip("
                        يستحق الدرجة الكاملة من يرفع التقرير في وقته مكتمل العناصر .
                    "); ?></td>
                    <td> تعبئة نموذج (6 ) <a href="docs/f6.doc" download="نموذج 6">تحميل</a></td>
                    <td> الإشراف</td>
                </tr>

                <tr>
                    <td>17</td>
                    <td>زيادة عدد الطلاب عن العام السابق</td>
                    <td><input max="2" name="e17" type="number" step="any"
                               value="<?php echo $current_musabka_data->e17; ?>"></td>
                    <td>2</td>
                    <td><?php echo string_to_tooltip("
                       كل زيادة 5%  من الطلاب درجة إلى 10%
                    "); ?></td>
                    <td>أخذ الأعداد من النظام الإلكتروني</td>
                    <td>الإشراف</td>
                </tr>

                <tr>
                    <td>18</td>
                    <td>تمثيل الجمعية في المسابقات الخارجية</td>
                    <td><input max="5" name="e18" type="number" step="any"
                               value="<?php echo $current_musabka_data->e18; ?>"></td>
                    <td>5</td>
                    <td><?php echo string_to_tooltip("
كل معلم أو طالب ممثل للجمعية درجتين<br>
 و3 درجات عند تأهله  للمرحلة النهائية<br>
 و5 درجات عند فوزه بالمرحلة النهائية
                     "); ?></td>
                    <td>تعبئة نموذج ( 5 ) <a href="docs/f5.doc" download="نموذج 5">تحميل</a></td>
                    <td>المسابقات</td>
                </tr>

                <tr>
                    <td>19</td>
                    <td>التميز في الثلاثة أعوام الماضية</td>
                    <td><input max="6" name="e19" type="number" step="any"
                               value="<?php echo $current_musabka_data->e19; ?>"></td>
                    <td>6</td>
                    <td><?php echo string_to_tooltip("
درجة واحدة لمن حصل على 75% فأكثر<br>
 ودرجتين للحصول على 85% فأكثر عن كل عام .
                     "); ?></td>
                    <td>تقرير من قسم المسابقات</td>
                    <td>اللجنة</td>
                </tr>

                <tr>
                    <td> المجموع</td>
                    <td>  </td>
                    <td><input max="113" name="total_e" id="total_e" step="any" type="number" disabled="disabled"
                               value="<?php echo $current_musabka_data->total_e; ?>"></td>
                    <td> 113</td>
                    <td>  </td>
                    <td>  </td>
                    <td>  </td>
                </tr>
                </tbody>
            </table>

            <div class="four columns omega left"><input name="submit" type="submit" class="button-primary" id="submit"
                                                        value="حفظ"/></div>

            <input name="MM_insert" type="hidden" value="form1">
        </div>
    </form>


    <script type="text/javascript">
        showError();

        var e_text_count = 19;
        function calculate() {
            var total_e = 0;
            for (var i = 1; i < e_text_count + 1; i++) {
                var current_e = document.getElementsByName('e' + i)[0];
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

        var allowed_fields_for_edarh = ['e2', 'e11', 'e13', 'e15', 'e16', 'e18'];
        for (var i = 1; i < e_text_count + 1; i++) {
            var e_text = document.getElementsByName('e' + i)[0];

            var user_type = "<?php echo $userType;?>";
            if (user_type == 'edarh' && allowed_fields_for_edarh.indexOf('e' + i) < 0) {
                e_text.setAttribute('disabled', 'disabled');
            }
            e_text.addEventListener("change", calculate);
            e_text.addEventListener("blur", calculate);

        }
    </script>

    <?php include('../templates/footer.php'); ?>
    <?php
    if (isset($_SESSION['u1'])) {
        echo '<script>$(document).ready(function() {alertify.success("تم تحديث البيانات بنجاح");});</script>';
        $_SESSION['u1'] = null;
        unset($_SESSION['u1']);
    }

} else {
    include('../templates/restrict_msg.php');
}