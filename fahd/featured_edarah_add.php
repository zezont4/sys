<?php require_once('../Connections/localhost.php');
require_once('../functions.php');
require_once('fahd_functions.php');

//$editFormAction = $_SERVER['PHP_SELF'];
//if (isset($_SERVER['QUERY_STRING'])) {
//    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
//}

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

if (isset($_POST['MM_insert'])) {

    $study_fahd_start = get_fahd_year_start(Input::get('f_e_date'));
    $study_fahd_end = get_fahd_year_end(Input::get('f_e_date'));
    $study_fahd_name = get_fahd_year_name(Input::get('f_e_date'));

    // search for dublicate musabakah ##############
    $whereSQL = "where edarah_id=:edarah_id and f_e_date>=:study_fahd_start and f_e_date<=:study_fahd_end";
    $parameters = [':edarah_id' => $user_id, ':study_fahd_start' => $study_fahd_start, ':study_fahd_end' => $study_fahd_end];
    $old_registration = $pdo2->row("select * from ms_fahd_featured_edarah {$whereSQL}",$parameters);

    if ($old_registration) {
        include('../templates/header1.php');
        echo implode(' ', ['<h1 style="text-align:center;margin:20px;font-size:22px;"><br><br>',
            get_gender_label('e', 'ال'),
            '(' . $_SESSION['arabic_name'] . ')',
            ' قام بالتسجيل سابقا في العام الدراسي : ',
            $study_fahd_name,
            ' وذلك في تاريخ : ',
            StringToDate($old_registration->f_e_date) . ' هـ',
            '<br><br><br>',
            'ولا يمكن تكرار المشاركة أكثر من مرة في العام الواحد',
            '<br><br><br>',
            '<a href="/sys/fahd/featured_edarah_edit.php?id='.$old_registration->id,
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
        'e2'        => Input::get('e2'),
        'e11'       => Input::get('e11'),
        'e13'       => Input::get('e13'),
        'e15'       => Input::get('e15'),
        'e16'       => Input::get('e16'),
        'e18'       => Input::get('e18'),
        'total_e'   => Input::get('total_e'),
    ];
    $dbResult = $pdo->zInsert('ms_fahd_featured_edarah', $sqlValues);

    if ($dbResult > 0) {
        echo $pdo->lastInsertId();
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

                <input max="113" name="total_e" id="total_e" type="hidden" disabled="disabled">
                </tbody>
            </table>

            <div class="four columns omega left"><input name="submit" type="submit" class="button-primary" id="submit"
                                                        value="حفظ"/></div>

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

    var e_fields = ['e2', 'e11', 'e13', 'e15', 'e16', 'e18'];
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
<!--<script> var teacher_id = '<?php //echo $_GET["TID"];?>';</script>-->
<!--<script> var tody_h_date = get_formated_hijri_date(zezo_get_hijri_date('now'));</script>-->

<!--<script src="fahd_functions.js"></script>-->
<!--<script>-->
<!-- get_teachers_st_count(teacher_id);-->
<!-- get_ertiqa_st_count(teacher_id, tody_h_date);-->
<!-- get_bra3m_st_count(teacher_id, tody_h_date);-->
<!--</script>-->