<?php
$b_4a = array(
    array("0", "لا يوجد أو لم تحدد النتائج"),
    array("1", "طالب واحد متأهل للنهائية"),
    array("2", "طالبين متأهلين للنهائية"),
    array("3", "3 طلاب متأهلين للنهائية"),
    array("4", "4 طلاب متأهلين للنهائية"),
    array("5", "لديه طالب أو أكثر فائز بالنهائية"));

$murtaqa_name = array(
    array("0", "لم يختبر"),
    array("1", "مرتقى الملك"),
    array("2", "مرتقى ق"),
    array("3", "مرتقى الزمر"),
    array("4", "مرتقى النمل"),
    array("5", "مرتقى الكهف"),
    array("6", "مرتقى يونس"),
    array("7", "مرتقى المائدة"),
    array("8", "مرتقى الفاتحة"),
    array("9", "شهادة إتمام الحفظ")
);
$f_14a = array(
    array("0", "لايوجد مشاركات"),
    array("1", "مشاركة واحدة", "مشاركتين"),
    array("2", "ثلاث مشاركات"),
    array("3", "فائز بالمرحلة النهائية"));

//مسابقة المعلم المتميز
//العنوان
$featured_teacher_title = array(
    /*0*/
    'تاريخ تسجيل البيانات'
    /*1*/, 'درجات الأداء الوظيفي'
    /*2*/, 'نسبة النجاح في المرتقيات أو سلم البراعم'
    /*3*/, 'المشاركة في المسابقات الداخلية'
    /*4*/, 'نتائج المسابقات الداخلية'
    /*5*/, 'حضور الدورات التدريبية في الجمعية'
    /*6*/, 'حضور الاجتماعات واللقاءات وتفعيل برامج الجمعية'
    /*7*/, 'استخدام منهجية لحفظ الطلاب ومراجعتهم'
    /*8*/, 'حفظه للقرآن الكريم'
    /*9*/, 'المشاركة في البرامج التعليمية ( شفاء الصدور، رتل، ما يستجد من برامج...)'
    /*10*/, 'المشاركة في الدورات التدريبية السابقة أو الخارجية'
    /*11*/, 'استخدام فكرة جديدة تحقق أهدافًا تربوية'
    /*12*/, 'حاصل على إجازة في حفظ القرآن'
    /*13*/, 'حاصل على الشاطبية'
    /*14*/, 'تمثيله أو أحد طلابه الجمعية في المسابقات الخارجية في نفس العام'
    /*15*/, 'حدد البرنامج الذي حضرته');

//التفسير
$featured_teacher_ex = array(
    /*0*/
    ''
    /*1*/, ''
    /*2*/, ''
    /*3*/, ''
    /*4*/, ''
    /*5*/, '<br class="clear"> <div class="three columns  alpha ex">يلزم وجود شهادة</div>'
    /*6*/, ''
    /*7*/, '<br class="clear"> <div class="four columns alpha ex">يلزم وجود مايثبت ذلك</div>'
    /*8*/, '<br class="clear"> <div class="seven columns alpha ex">يلزم وجود شهادة اجتياز المرتقى أو شهادة اتمام الحفظ</div>'
    /*9*/, '<br class="clear"> <div class="three columns alpha ex">يلزم وجود شهادة</div>'
    /*10*/, '<br class="clear"> <div class="three columns alpha ex">يلزم وجود شهادة</div>'
    /*11*/, '<br class="clear"> <div class="four columns alpha ex">يلزم وجود مايثبت ذلك</div>'
    /*12*/, '<br class="clear"> <div class="four columns alpha ex">يلزم وجود مايثبت ذلك</div>'
    /*13*/, '<br class="clear"> <div class="four columns alpha ex">يلزم وجود مايثبت ذلك</div>'
    /*14*/, ''
    /*15*/, '');

//الجهة المقية
$featured_teacher_dep = array(
    /*0*/
    'NULL'
    /*1*/, 'الإشراف'
    /*2*/, 'المرتقيات'
    /*3*/, 'المسابقات'
    /*4*/, 'المسابقات'
    /*5*/, 'التدريب'
    /*6*/, 'العلاقات، التدريب، الإشراف'
    /*7*/, 'الإشراف'
    /*8*/, 'اللجنة المقيمة'
    /*9*/, 'البرامج التعليمية'
    /*10*/, 'اللجنة المقيمة'
    /*11*/, 'اللجنة المقيمة'
    /*12*/, 'اللجنة المقيمة'
    /*13*/, 'التدريب'
    /*14*/, 'المسابقات'
    /*14*/, 'البرامج التعليمية');

//الدرجة
$featured_teacher_degree = array(
    /*0*/
    null
    /*1*/, 20
    /*2*/, 20
    /*3*/, 6
    /*4*/, 10
    /*5*/, 10
    /*6*/, 5
    /*7*/, 7
    /*8*/, 12
    /*9*/, 5
    /*10*/, 5
    /*11*/, 2
    /*12*/, 3
    /*13*/, 2
    /*14*/, 3
    /*15*/, 0);

//جلب رقم العام الدراسي
/**
 * @param $h_date
 * @return mixed
 */
function get_fahd_year_id($h_date)
{
    $h_date = str_replace("/", "", $h_date);
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query_fahd_year = sprintf("SELECT y_id FROM ms_fahd_years where y_start_date<=%s and y_end_date>=%s", $h_date, $h_date);
    $fahd_year = mysqli_query($localhost, $query_fahd_year) or die('get_student_name01' . mysqli_error($localhost));
    $row_fahd_year = mysqli_fetch_assoc($fahd_year);
    $totalRows_fahd_year = mysqli_num_rows($fahd_year);
    if ($totalRows_fahd_year > 0) {
        return $row_fahd_year["y_id"];
        mysqli_free_result($fahd_year);
    }
}

//جلب تاريخ البداية للعام الداسي
/**
 * @param $h_date
 * @return mixed
 */
function get_fahd_year_start($h_date)
{
    $h_date = str_replace("/", "", $h_date);
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query_fahd_year = sprintf("SELECT y_start_date FROM ms_fahd_years where y_start_date<=%s and y_end_date>=%s", $h_date, $h_date);
    $fahd_year = mysqli_query($localhost, $query_fahd_year) or die('get_student_name02' . mysqli_error($localhost));
    $row_fahd_year = mysqli_fetch_assoc($fahd_year);
    $totalRows_fahd_year = mysqli_num_rows($fahd_year);
    if ($totalRows_fahd_year > 0) {
        return $row_fahd_year["y_start_date"];
        mysqli_free_result($fahd_year);
    }
}

//جلب تاريخ النهاية للعام الداسي
/**
 * @param $h_date
 * @return mixed
 */
function get_fahd_year_end($h_date)
{
    $h_date = str_replace("/", "", $h_date);
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query_fahd_year = sprintf("SELECT y_end_date FROM ms_fahd_years where y_start_date<=%s and y_end_date>=%s", $h_date, $h_date);
    $fahd_year = mysqli_query($localhost, $query_fahd_year) or die('get_student_name03' . mysqli_error($localhost));
    $row_fahd_year = mysqli_fetch_assoc($fahd_year);
    $totalRows_fahd_year = mysqli_num_rows($fahd_year);
    if ($totalRows_fahd_year > 0) {
        return $row_fahd_year["y_end_date"];
        mysqli_free_result($fahd_year);
    }
}

//جلب تاريخ النهاية للعام الداسي
/**
 * @param $h_date
 * @return mixed
 */
function get_fahd_year_name($h_date)
{
    $h_date = str_replace("/", "", $h_date);
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query_fahd_year = sprintf("SELECT year_name FROM ms_fahd_years where y_start_date<=%s and y_end_date>=%s", $h_date, $h_date);
    $fahd_year = mysqli_query($localhost, $query_fahd_year) or die('get_student_name04' . mysqli_error($localhost));
    $row_fahd_year = mysqli_fetch_assoc($fahd_year);
    $totalRows_fahd_year = mysqli_num_rows($fahd_year);
    if ($totalRows_fahd_year > 0) {
        return $row_fahd_year["year_name"];
        mysqli_free_result($fahd_year);
    }
}