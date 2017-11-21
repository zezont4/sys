<?php require_once('../Connections/localhost.php');
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

$user_id = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}
//$userType = 'edarh';

if (isset($_POST['MM_insert'])) {
    $pdo = new DB();
    $sqlValues = [
        'edarah_id' => Input::get('edarah_id'),
        'f_e_date'  => str_replace("/", "", Input::get('f_e_date')),
        'e1'        => Input::get('e1'),
        'e2'        => Input::get('e2'),
        'e3'        => Input::get('e3'),
        'e4'        => Input::get('e4'),
        'e5'        => Input::get('e5'),
        'e6'        => Input::get('e6'),
        'e7'        => Input::get('e7'),
        'e8'        => Input::get('e8'),
        'e9'        => Input::get('e9'),
        'e10'       => Input::get('e10'),
        'e11'       => Input::get('e11'),
        'e12'       => Input::get('e12'),
        'e13'       => Input::get('e13'),
        'e14'       => Input::get('e14'),
        'e15'       => Input::get('e15'),
        'e16'       => Input::get('e16'),
        'e17'       => Input::get('e17'),
        'e18'       => Input::get('e18'),
        'e19'       => Input::get('e19'),
        'total_e'   => Input::get('total_e'),
    ];

    $dbResult = $pdo->zUpdate('ms_fahd_featured_edarah', $sqlValues, 'id=:id', array(':id' => Input::get('id')));

    if ($dbResult > 0) {
        $_SESSION['u1'] = "u1";
    }
}

$pdo = new DB();
$whereSQL = "where id=:id";
$parameters = [':id' => Input::get('id')];
$current_musabka_data = $pdo->row("select * from ms_fahd_featured_edarah {$whereSQL}", $parameters);

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

<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
    <!--PageTitle-->

    <form action="" method="post" name="form1" id="form1" data-validate="parsley">
        <input name="edarah_id" type="hidden" value="22">
        <input name="f_e_date" type="hidden" value="14381122">
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
                class="three columns top_padding"><?php echo get_gender_label('e') . ' ' . $_SESSION['arabic_name']; ?>  </div>
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
?>