<?php
// ----------------------------------------------------------------------------------------------------
// - Display Errors
// ----------------------------------------------------------------------------------------------------
ini_set('display_errors', 'On');
ini_set('html_errors', 0);

// ----------------------------------------------------------------------------------------------------
// - Error Reporting
// ----------------------------------------------------------------------------------------------------
error_reporting(-1);

// ----------------------------------------------------------------------------------------------------
// - Shutdown Handler
// ----------------------------------------------------------------------------------------------------
function ShutdownHandler()
{
    if (@is_array($error = @error_get_last())) {
        return (@call_user_func_array('ErrorHandler', $error));
    };

    return (TRUE);
}

// ----------------------------------------------------------------------------------------------------
// - Error Handler
// ----------------------------------------------------------------------------------------------------
function ErrorHandler($type, $message, $file, $line)
{
    $_ERRORS = Array(
        0x0001 => 'E_ERROR',
        0x0002 => 'E_WARNING',
        0x0004 => 'E_PARSE',
        0x0008 => 'E_NOTICE',
        0x0010 => 'E_CORE_ERROR',
        0x0020 => 'E_CORE_WARNING',
        0x0040 => 'E_COMPILE_ERROR',
        0x0080 => 'E_COMPILE_WARNING',
        0x0100 => 'E_USER_ERROR',
        0x0200 => 'E_USER_WARNING',
        0x0400 => 'E_USER_NOTICE',
        0x0800 => 'E_STRICT',
        0x1000 => 'E_RECOVERABLE_ERROR',
        0x2000 => 'E_DEPRECATED',
        0x4000 => 'E_USER_DEPRECATED',
    );

    if (!@is_string($name = @array_search($type, @array_flip($_ERRORS)))) {
        $name = 'E_UNKNOWN';
    };
    $log = new Log();
    if (isLocal()) {
        $log->write(@sprintf("%s Error in file ( %s ) at line %d: %s", $name, @basename($file), $line, $message), 'error');
        return (print(@sprintf("%s Error in file ( %s ) at line %d: %s\n", $name, @basename($file), $line, $message)));
    } else {
        $log->write(@sprintf("%s Error in file ( %s ) at line %d: %s", $name, @basename($file), $line, $message), 'error');
    }
}

$old_error_handler = set_error_handler("ErrorHandler");

register_shutdown_function('ShutdownHandler');
require_once(__DIR__ . '/Connections/localhost.php');
if (env('DEBUG_MODE')) {
    echo '<h1 style="color: red;text-align: center;font-size: 20px;">DEBUG_MODE</h1>';
}
require_once(__DIR__ . '/secure/functions.php');

spl_autoload_register(function ($class) {
    require_once 'classes/' . $class . '.php';
});

date_default_timezone_set('Asia/Riyadh');

if (!isset($_SESSION)) {
    sec_session_start();
}
//echo 5 / 0;
if (!function_exists("isLocal")) {
    function isLocal()
    {
        $white_list = array(
            '127.0.0.1',
            '::1',
        );

        return in_array($_SERVER['REMOTE_ADDR'], $white_list);
    }
}

$GLOBALS['config'] = [
    'root_dir' => '/sys/',
    'host'     => 'localhost',
    'user'     => 'quranzul_zezont4',
    'pass'     => 'Kla124816',
    'db_name'  => 'quranzul_sys',
];

if (isLocal()) {
    $GLOBALS['config'] = [
        'root_dir' => '/sys/',
        'host'     => 'localhost',
        'user'     => 'root',
        'pass'     => 'root',
        'db_name'  => 'quranzulfi',
    ];
}
function siteUrl()
{
    $pageURL = '';
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"];
    }
    return $pageURL . '/sys/';
}


//$server = $settings['host'];
//$user = $settings['user'];
//$pass = $settings['pass'];

if (!function_exists("escape")) {
    function escape($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists("show_msg")) {
    /**
     * عرض رسالة بعد عملة الإضافة أو التعديل على قاعدة البيانات
     * @param Array [$sessionName =array(] مصفوفة تحتوي على بيانات الرسالة
     */
    function show_msg($sessionName = array())
    {
        foreach ($sessionName as $key => $msg) {
            $msg_type = $msg[0];
            $msg_text = $msg[1];
            $delay = 60000;
            switch ($msg_type) {
                case 1:
                    $msg_type = 'success';
                    break;
                case 2:
                    $msg_type = 'log';
                    break;
                case 3:
                    $msg_type = 'error';
                    break;
            }
            echo '<script>
                    $(function(){
                        alertify.set({ delay: ' . $delay . ' });
                        alertify.' . $msg_type . '("' . $msg_text . '");
                    });
                 </script>';
        }
        Session::delete(Config::get('session/sql_result_msg'));
    }
}

if (!function_exists("StringToDate")) {
    function StringToDate($strDate)
    {
        $tm_Date = ($strDate != "") ? substr($strDate, 0, 4) . "/" . substr($strDate, 4, 2) . "/" . substr($strDate, 6, 2) : null;

        return $tm_Date;
    }
}

if (!function_exists("queryToArray")) {
    /**
     * تنفيذ استعلام مكون من حقلين فقط
     * ثم تعبئة النتيجة في مصفوفة
     * @param   String $sqlString عبارة الاستعلام
     * @returns Array  النتيجة في مصفوفة
     */
    function queryToArray($sqlString, $where = null)
    {
        $queryArray = '';
        $pdoQuery = new DB();
        $queryResult = $pdoQuery->query($sqlString, $where, PDO:: FETCH_NUM);
        foreach ($queryResult as $row) {
            $queryArray[$row[0]] = $row[1];
        }

        return $queryArray;
    }
}

if (!function_exists("writeToFile")) {

    function writeToFile($fineName, $content)
    {
        $myfile = fopen($fineName, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);
    }
}
//######################
require_once 'settings.php';

function sql_sex($field_name)
{
    if (isset($_SESSION['sex'])) {
        if ($_SESSION['sex'] != 2) {
            return sprintf(' and %s=%s', $field_name, $_SESSION['sex']);
        } else {
            return '';
        }
    }
}

$sql_sex = sql_sex('sex');

function get_gender_label($Eng_lable, $AL = '')
{
    $sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 1;
    global $gender_labels;
    $count_a = count($gender_labels);
    for ($i = 0; $i < $count_a; $i++) {
        if ($gender_labels[$i][2] == $Eng_lable) {
            if ($sex == '2') {
                return $AL . $gender_labels[$i][1] . '/' . $AL . $gender_labels[$i][0];
            } else {
                return $AL . $gender_labels[$i][$sex];
            }
        }
    }
}

function zlog($MSG)
{
    echo '<script> console.log("' . $MSG . '")</script>';
}

function get_st_info($StID)
{
    if (!$StID ) return null;

    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query_RS_all = sprintf("SELECT Stfullname,O_BurthDate,arabic_name,HName,StMobileNo,StID,StEdarah,StHalaqah,TID FROM view_0_students WHERE StID = %s", GetSQLValueString($StID, "double"));
    $RS_all = mysqli_query($localhost, $query_RS_all) or die('get_st_info ' . mysqli_error($localhost));
    $row_RS_all = mysqli_fetch_assoc($RS_all);
    $totalRows_RS_all = mysqli_num_rows($RS_all);
    if ($totalRows_RS_all > 0) {
        $returned_value = '<table>
				<caption>
					بيانات ' . get_gender_label('st', 'ال') . ' الأساسية
				</caption>
					<tr>
					<td>اسم ' . get_gender_label('st', 'ال') . '</td>
					<td>' . get_gender_label('e', 'ال') . '</td>
					<td>الحلقة</td>
					<td class="NoMobile">رقم الجوال</td>
					<td class="NoMobile">تاريخ الميلاد</td>
				</tr>
					<tr>
					<td>' . $row_RS_all['Stfullname'] . '</td>
					<td>' . $row_RS_all['arabic_name'] . '</td>
					<td>' . $row_RS_all['HName'] . '</td>
					<td class="NoMobile">' . $row_RS_all['StMobileNo'] . '</td>
					<td class="NoMobile">' . $row_RS_all['O_BurthDate'] . '</td>
				</tr>
			</table>';
        if ($row_RS_all['TID'] == '') {
            $returned_value .= '<p class="note">' . 'تنبيه: الحلقة ليس لها معلم,أو أن المعلم مخفي.' . '</p>';
        }
        $returned_value .= '<input name="EdarahID" type="hidden" value="' . $row_RS_all['StEdarah'] . '">' . '<input name="HalakahID" type="hidden" value="' . $row_RS_all['StHalaqah'] . '">' . '<input name="TeacherID" type="hidden" value="' . $row_RS_all['TID'] . '">' . '<input name="StID" type="hidden" value="' . $row_RS_all['StID'] . '">';
        mysqli_free_result($RS_all);

        return $returned_value;
    } else {
        return '<h1 style="text-align:center;font-size:20px;color:red;">'.
            'لا يوجد ' . get_gender_label('st') . ' بهذا السجل المدني : ' . $StID . ' !'.
            '</h1>';
    }
}

function get_edarah_name($sent_value)
{
    if (!$sent_value ) return null;

    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query_RS_all = sprintf("SELECT arabic_name FROM 0_users WHERE id=%s", $sent_value);
    $RS_all = mysqli_query($localhost, $query_RS_all) or die('get_edarah_name' . mysqli_error($localhost));
    $row_RS_all = mysqli_fetch_assoc($RS_all);
    $totalRows_RS_all = mysqli_num_rows($RS_all);
    if ($totalRows_RS_all > 0) {
        $returned_value = $row_RS_all["arabic_name"];
        mysqli_free_result($RS_all);

        return $returned_value;
    }
}

function get_halakah_name($sent_value)
{
    if (!$sent_value ) return null;

    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query_RS_all = sprintf("SELECT HName FROM 0_halakat WHERE AutoNo=%s", $sent_value);
    $RS_all = mysqli_query($localhost, $query_RS_all) or die('get_halakah_name' . mysqli_error($localhost));
    $row_RS_all = mysqli_fetch_assoc($RS_all);
    $totalRows_RS_all = mysqli_num_rows($RS_all);
    if ($totalRows_RS_all > 0) {
        $returned_value = $row_RS_all["HName"];
        mysqli_free_result($RS_all);

        return $returned_value;
    }
}

function get_teacher_name($TID, $only_three = false)
{
    if (!$TID ) return null;

    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    if ($only_three) {
        $query_RS_all = sprintf("SELECT concat_ws(' ',TName1,TName2,TName4) AS t_name FROM 0_teachers WHERE TID=%s", $TID);
    } else {
        $query_RS_all = sprintf("SELECT concat_ws(' ',TName1,TName2,TName3,TName4) AS t_name FROM 0_teachers WHERE TID=%s", $TID);
    }
    $RS_all = mysqli_query($localhost, $query_RS_all) or die('get_teacher_name ' . mysqli_error($localhost));
    $row_RS_all = mysqli_fetch_assoc($RS_all);
    $totalRows_RS_all = mysqli_num_rows($RS_all);
    if ($totalRows_RS_all > 0) {
        $returned_value = $row_RS_all["t_name"];
        mysqli_free_result($RS_all);

        return $returned_value;
    }
}

function get_student_name($sent_value)
{
    if(!$sent_value) return null;
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query_RS_all = sprintf("SELECT concat_ws(' ',StName1,StName2,StName3,StName4) AS St_name FROM 0_students WHERE StID=%s", $sent_value);
    $RS_all = mysqli_query($localhost, $query_RS_all) or die('get_student_name :' . mysqli_error($localhost));
    $row_RS_all = mysqli_fetch_assoc($RS_all);
    $totalRows_RS_all = mysqli_num_rows($RS_all);
    if ($totalRows_RS_all > 0) {
        $returned_value = $row_RS_all["St_name"];
        mysqli_free_result($RS_all);

        return $returned_value;
    }
}

function StringToDate($strDate)
{
    $tm_Date = ($strDate != "") ? substr($strDate, 0, 4) . "/" . substr($strDate, 4, 2) . "/" . substr($strDate, 6, 2) : null;

    return $tm_Date;
}


//تبع الرسائل النصية لموبايلي
//function utf2win($MSG)
//{
//	return iconv("UTF-8", "Windows-1256", $MSG);
//}


//$degree_title="الـــــــــدرجـــــــــــة";

//تحويل المصفوفة إلى نص مفصول بفاصلة
function array_2_string($my_array, $start_index = 0)
{
    $ar = '';
    $length = count($my_array);
    for ($i = $start_index; $i < $length; $i++) {
        if ($i == $start_index) {
            $ar = $my_array[$i];
        } else {
            $ar .= ',' . $my_array[$i];
        }
    }

    return $ar;
}

//جلب العنصر من المصفوفة المزدوجة
function get_array_1($array_name, $key)
{
    if ($key != '') {
        $length = count($array_name);
        for ($i = 0; $i < $length; $i++) {
            if ($array_name[$i][0] == $key) {
                return $array_name[$i][1];
            }
        }
    }
}

//انشاء مربع نص 
function create_text($input_name, $other_options = "")
{
    //if ($is_required==true){$isRequired='data-required="true"';}
    return sprintf('<input name="%s" id="%s" type="text" %s/>', $input_name, $input_name, $other_options);
}

//انشاء مربع اختيار 
function create_chkbox($input_name, $other_options = "")
{
    return sprintf('<input name="%s" id="%s" type="checkbox" value="1" %s>', $input_name, $input_name, $other_options);
}

//انشاء قائمة منسدلة
function create_combo($input_name, $combo_values, $start_index = 0, $selected_value = '', $other_options = "")
{
    $c1 = sprintf('<select name="%s" id="%s" %s>', $input_name, $input_name, $other_options);
    $c2 = '';
    $c3 = "</select>";
    $length = count($combo_values);
    for ($i = $start_index; $i < $length; $i++) {
        $slctd = "";
        if ($combo_values[$i][0] === (integer)$selected_value && $selected_value !== '' && $selected_value !== null) {
            $slctd = 'selected';
        }
        $c2 = $c2 . sprintf('<option value="%s" %s>%s</option>', $combo_values[$i][0], $slctd, $combo_values[$i][1]);
    }

    return $c1 . $c2 . $c3;
}

function create_edarah_combo($selected_index = '')
{
    //require ('Connections/localhost.php');
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $sql_sex = sql_sex('sex');
    $query_RS_all = sprintf("SELECT id,arabic_name FROM `0_users` WHERE `hidden`=0 %s AND user_group='edarh' ORDER BY Sex,arabic_name ASC", $sql_sex);
    $RS_all = mysqli_query($localhost, $query_RS_all) or die('create_edarah_combo ' . mysqli_error($localhost));
    $row_RS_all = mysqli_fetch_assoc($RS_all);
    $totalRows_RS_all = mysqli_num_rows($RS_all);
    if ($totalRows_RS_all > 0) {
        //echo $query_RS_all;
        $ed = get_gender_label('e', 'ال');
        $s1 = '<select name="EdarahID" class="full-width" id="EdarahID"  data-required="true">' . '<option value>اختر ' . $ed . '</option>';
        $s2 = '';
        $user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : null;
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        do {
            $o1 = '';
            if ($selected_index != '') {
                if ($selected_index == $row_RS_all['id']) {
                    $o1 = 'selected="selected"';
                } else {
                    if ($user_group == 'edarh') {
                        $o1 = 'disabled="disabled"';
                    }
                }
            } else {
                if ($user_id == $row_RS_all['id']) {
                    $o1 = 'selected="selected"';
                } else {
                    $user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : '';
                    if ($user_group == 'edarh') {
                        $o1 = 'disabled="disabled"';
                    }
                }
            }
            $o2 = '<option ' . $o1 . ' value="' . $row_RS_all['id'] . '">' . $row_RS_all['arabic_name'] . '</option>';
            $s2 = $s2 . $o2;
        } while ($row_RS_all = mysqli_fetch_assoc($RS_all));
        $s3 = '</select>';
        mysqli_free_result($RS_all);
        echo $s1 . $s2 . $s3;
    }
}

function ar_search_helper($ar_name = '')
{
    $new_ar_name = $ar_name;
    $new_ar_name = str_replace(' ', '', $new_ar_name);
    $new_ar_name = str_replace('أ', 'ا', $new_ar_name);
    $new_ar_name = str_replace('إ', 'ا', $new_ar_name);
    $new_ar_name = str_replace('ة', 'ه', $new_ar_name);
    $new_ar_name = str_replace('ى', 'ا', $new_ar_name);

    return $new_ar_name;
}

function filter_array($array1, $first_title, $start_index, $sql)
{
    $statusName_sql = [];
    $ca = count($array1);
    array_push($statusName_sql, ['', $first_title]);

    for ($i = $start_index; $i < $ca; $i++) {
        $a0 = $array1[$i][0];
        $a1 = $array1[$i][1];
        if (is_string($a0)) {
            array_push($statusName_sql, [' and ' . $sql . ' =\'' . $a0 . '\' ', $a1]);
        } else {
            array_push($statusName_sql, [' and ' . $sql . ' =' . $a0 . ' ', $a1]);
        }
    }
    return $statusName_sql;
}

function getFile($url)
{
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $contents = curl_exec($ch);
    curl_close($ch);

    return $contents;
}

/**
 * send sms message and return respond message
 *
 * @param $numbers
 * @param $msg_text
 * @return mixed
 */
function sendSMS($numbers, $msg_text)
{
    $username = env('SMS_USERNAME');
    $password = env('SMS_PASSWORD');
    $senderName = env('SMS_SENDER_NAME');
//    $debugMode = env('DEBUG_MODE');
    $log = new Log();
    if (isLocal()) {
        $log->write("$numbers : $msg_text");
        return 1;
    }

    $url = 'http://www.fss-sms.com/smartsms/api/sendsms.php?username=' . $username . '&password=' . $password . '&numbers=' . $numbers . '&message=' . urlencode($msg_text) . '&sender=' . $senderName . '&unicode=E&lang=ar&Rmduplicated=1&return=json';

//    if ($respond = @file_get_contents($url)) {
    if ($respond = @getFile($url)) {
        $respond_json = json_decode($respond);
    } else {
        $respond_json = json_decode('{"MessageIs" : " يوجد خلل في موقع الرسائل","Code":"error"}');
    }

    if ($respond_json->Code == 100) {
        $log->write("$numbers : $msg_text", 'success');
        return 1;
    } else {
        $msg = $respond_json->MessageIs;
        $log->write("$numbers : $msg : $msg_text <br>".print_r($respond), 'error');
        return $msg;
    }
}

function dateDiffHijri($date1, $date2)
{
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query = sprintf("SELECT count(*) as count_days FROM g_h_dates WHERE h_date BETWEEN  %s and %s",
        GetSQLValueString($date1, "int"),
        GetSQLValueString($date2, "int")
    );
    $rs = mysqli_query($localhost, $query) or die('dateDiffHijri ' . mysqli_error($localhost));
    $result = mysqli_fetch_assoc($rs);
    $total_rows = mysqli_num_rows($rs);
    if ($total_rows > 0) {
        return $result['count_days'];
    }
}

function dateDiff360($date1, $date2)
{
    $date1 = str_replace('/', '', $date1);
    $date2 = str_replace('/', '', $date2);

    $min_date = min($date1, $date2);
    $max_date = max($date1, $date2);

    $date1 = (object)[
        'year'  => substr($min_date, 0, 4),
        'month' => substr($min_date, 4, 2),
        'day'   => substr($min_date, 6, 2),
    ];

    $date2 = (object)[
        'year'  => substr($max_date, 0, 4),
        'month' => substr($max_date, 4, 2),
        'day'   => substr($max_date, 6, 2),
    ];

    $total_days =
        (($date2->year - $date1->year) * 360) +
        ($date2->month - $date1->month) * 30 +
        ($date2->day - $date1->day);

    $years = floor($total_days / 360);
    $months = floor(($total_days - ($years * 360)) / 30);
    $days = $total_days - ($years * 360) - ($months * 30);
    return (object)[
        'years'      => $years,
        'months'     => $months,
        'days'       => $days,
        'total_days' => $total_days,
    ];

}

function getHijriDate($gregorian_date = 'now')
{
    $g_date = $gregorian_date == 'now' ? date('Ymd') : $gregorian_date;

    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query = sprintf("SELECT * FROM g_h_dates WHERE g_date =  %s",
        GetSQLValueString($g_date, "int")
    );
    $rs = mysqli_query($localhost, $query) or die('getHijriDate ' . mysqli_error($localhost));
    $result = mysqli_fetch_assoc($rs);
    $total_rows = mysqli_num_rows($rs);
    if ($total_rows > 0) {
        return (object)[
            'date'           => $result['h_date'],
            'day'            => $result['day_no'],
            'formatted_date' => StringToDate($result['h_date']),
            'year'           => substr($result['h_date'], 0, 4),
        ];
    }
}

function getHijriDateAfter($date1, $days_count)
{
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query1 = sprintf("SELECT id FROM g_h_dates WHERE h_date =  %s",
        GetSQLValueString($date1, "int"));
    $rs1 = mysqli_query($localhost, $query1) or die('getHijriDateAfter ' . mysqli_error($localhost));
    $result1 = mysqli_fetch_assoc($rs1);
    $total_rows1 = mysqli_num_rows($rs1);
    if ($total_rows1 > 0) {
        $query2 = sprintf("SELECT * FROM g_h_dates WHERE id =  %s",
            GetSQLValueString($result1['id'] + $days_count, "int"));
        $rs2 = mysqli_query($localhost, $query2) or die('getHijriDateAfter ' . mysqli_error($localhost));
        $result2 = mysqli_fetch_assoc($rs2);
        $total_rows2 = mysqli_num_rows($rs2);
        if ($total_rows2 > 0) {
            return (object)[
                'date' => $result2['h_date'],
                'day'  => $result2['day_no'],
            ];
        }
    }
}

function getHijriDayName($date1)
{
    global $database_localhost, $localhost, $day_name;
    mysqli_select_db($localhost, $database_localhost);
    $query1 = sprintf("SELECT * FROM g_h_dates WHERE h_date =  %s",
        GetSQLValueString($date1, "int"));
    $rs1 = mysqli_query($localhost, $query1) or die('getHijriDateAfter ' . mysqli_error($localhost));
    $result1 = mysqli_fetch_assoc($rs1);
    $total_rows1 = mysqli_num_rows($rs1);
    if ($total_rows1 > 0) {
        return $day_name[$result1['day_no']];

    }
}

function getStudyingYearInfo($hijri_date)
{
    global $database_localhost, $localhost;
    mysqli_select_db($localhost, $database_localhost);
    $query = "SELECT * FROM `0_years` WHERE y_start_date<={$hijri_date} and y_end_date>={$hijri_date}";
    $rs = mysqli_query($localhost, $query) or die('getStudyingYearInfo : ' . mysqli_error($localhost));
    $row = mysqli_fetch_assoc($rs);
    $total_rows = mysqli_num_rows($rs);
    if ($total_rows > 0) {
//	var_dump($row);
        return (object)[
            'id'         => $row['y_id'],
            'name'       => $row['year_name'],
            'start_date' => $row['y_start_date'],
            'end_date'   => $row['y_end_date'],
        ];
    }
}

function get_halakah_types($halakah_db_row_string)
{
    global $halakah_type;
    $h_type_explode = explode(',', $halakah_db_row_string);
    $string_types = [];
    $h_type_ids = [];
    foreach ($h_type_explode as $type) {
        array_push($string_types, get_array_1($halakah_type, str_replace('|', '', $type)));
        array_push($h_type_ids, str_replace('|', '', $type));
    }
    return (object)[
        'toArray'  => $h_type_ids,
        'toString' => implode(' ، ', $string_types),
    ];
}


function send_mail($to, $subject, $message)
    {
    
    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';

    // Additional headers
    $headers[] = 'To: عبدالعزيز الطيار <zezont@gmail.com>';
    $headers[] = 'From: تحفيظ القرآن بالزلفي <admin@qz.org.sa>';

    // Mail it
    mail($to, $subject, $message, implode("\r\n", $headers));
}


function sendMsgToModerator($student_id, $edarah_id, $exam_status)
{
//    $log = new Log();
//    $log->write("sendMsgToModerator : {$student_id} \t {$edarah_id} \t {$exam_status} \n");
    global $localhost;
    $msg = '';
    $msg_2 = '';
    $msg_3 = '';
    $mudeer_sql = sprintf("SELECT e.id,e.mobile_no,u.sex FROM 0_employees e 
                    LEFT JOIN 0_users u on e.edarah_id = u.id
                    WHERE e.is_hidden=0 and e.edarah_id=%s and e.job_title=10",
        GetSQLValueString($edarah_id, "int")
    );
    $mudeer_query = mysqli_query($localhost, $mudeer_sql) or die('sendMsgToModerator1 : ' . mysqli_error($localhost));
    $mudeer_result = mysqli_fetch_assoc($mudeer_query);
    $mudeer_rows_count = mysqli_num_rows($mudeer_query);
    if ($mudeer_rows_count) {
        $student_sql = sprintf("SELECT concat_ws(' ', StName1,StName2,StName4) as st_full_name FROM `0_students` WHERE StID=%s",
            GetSQLValueString($student_id, "text")
        );
//        var_dump($student_sql);
        $student_query = mysqli_query($localhost, $student_sql) or die('sendMsgToModerator2 : ' . mysqli_error($localhost));
        $student_result = mysqli_fetch_assoc($student_query);
        $student_rows_count = mysqli_num_rows($student_query);
        if ($student_rows_count) {
            $sex = $mudeer_result['sex'];
            $mudeer_number = '966' . substr($mudeer_result['mobile_no'], 1, 9);
            $st_full_name = $student_result['st_full_name'];
            if ($sex == 1) {
                $msg_2 = sprintf("نحيطكم علما بأن الطالب (%s) قد اجتاز اختبار الارتقاء بنجاح.", $st_full_name);
                $msg_3 = sprintf("نحيطكم علما بأن الطالب (%s) لم يوفق في اجتياز اختبار الارتقاء.", $st_full_name);
            } elseif ($sex == 0) {
                $msg_2 = sprintf("نحيطكم علما بأن الطالبة (%s) قد اجتازت اختبار الارتقاء بنجاح.", $st_full_name);
                $msg_3 = sprintf("نحيطكم علما بأن الطالبة (%s) لم توفق في اجتياز اختبار الارتقاء.", $st_full_name);
            }

            if ($exam_status == 2) {
                $msg = $msg_2;
            } else if ($exam_status == 3) {
                $msg = $msg_3;
            }
//            echo $mudeer_number;
//            echo $msg;
//            exit();
            if ($msg) {
//    exit($msg);
                $SendingAnswer = sendSMS($mudeer_number, $msg);
                if ($SendingAnswer == 1) {
                    $_SESSION['msgMudeerSent'] = '';
                } else {
                    $_SESSION['msgMudeerNotSent'] = $SendingAnswer;
                }
            }
        }

    }

}

/**
 * تغيير حالة المراجعة في جدول اليومية من مراجعة إلى حفظ إذا اجتاز الطالب المرتقى
 * @param $exam_no
 * @param $final_exam_status
 */
function updateMurajaahStatus($exam_no, $final_exam_status)
{
    $pdo = new DB();
//    die('sdf');

    //جلب بيانات الطالب وحلقته ومعلمه واختباره من جداول متعددة
    $data_for_daily = $pdo->row("select s.StEdarah,s.StHalaqah,s.st_no,t.t_no,er.FinalExamDate, er.FinalExamStatus 
from er_ertiqaexams er , 0_students s,0_teachers t 
where er.AutoNo=:exam_no and s.StID=er.StID and t.TID=er.TeacherID",
        [':exam_no' => $exam_no]);

    if ($data_for_daily) {//إذا كانت بيانات الاختبار موجوده
        $edarah_id = (int)$data_for_daily->StEdarah;//رقم المجمع
        $halakah_id = (int)$data_for_daily->StHalaqah;//رقم الحلقة
        $teacher_id = (int)$data_for_daily->t_no;//رقم المعلم
        $st_id = (int)$data_for_daily->st_no;//رقم الطالب
        $h_date = (int)$data_for_daily->FinalExamDate;//تاريخ اختبار المرتقى

        //البحث في جدول اليومية عن بيانات للطالب بنفس تاريخ اختبار المرتقى
        $daily_record = $pdo->row("select id from daily where st_id=:st_id and h_date=:h_date",
            [':st_id' => $st_id, ':h_date' => $h_date]);

        if ($daily_record) {//إذا وجد بيانات في جدول اليومية للطالب بنفس تاريخ اختبار المرتقى
            //تغيير حالة المراجعة في جدول اليومية حسب نتيجة الاختبار
            $pdo->zUpdate('daily', ['murajaah_status' => ($final_exam_status == 2) ? 2 : 0], "id=:daily_id", [':daily_id' => (int)$daily_record->id]);
        } else {//إذا لم توجد بيانات للطالب في جدول اليومية بنفس تاريخ اختبار المرتقى

            if ($final_exam_status == 2) {
                //اضافة سجل جديد في جدول اليومية وفيه حالة المراجعة
                $update = $pdo->zInsert('daily', [
                    'edarah_id'         => $edarah_id,
                    'halakah_id'        => $halakah_id,
                    'teacher_id'        => $teacher_id,
                    'st_id'             => $st_id,
                    'h_date'            => str_replace('/', '', $h_date),
                    'murajaah_status'   => ($final_exam_status == 2) ? 2 : 0,
                    'attendance_status' => 1,
                    'created_at'        => (new DateTime())->format('Y-m-d H:i:s'),
                ]);
            }
        }

        //اضافة نقاط للطالب إذا اجتاز مرتقى
        addMurtaqaBehaviorPoints($st_id, $h_date, $final_exam_status, $exam_no);
    }

}

/**
 * إضافة نقاط لمن يجتاز مرتقى بحيث تضاف كسلوك إيجابي
 * @param $st_id
 * @param $h_date
 * @param $final_exam_status
 * @param $exam_no
 * @return mixed
 */
function addMurtaqaBehaviorPoints($st_id, $h_date, $final_exam_status, $exam_no)
{

    $pdo = new DB();

    // جلب بيانات اليومية للطالب حسب تاريخ اختبار المرتقى
    $daily_record = $pdo->row("select id from daily where st_id=:st_id and h_date=:h_date",
        [':st_id' => $st_id, ':h_date' => $h_date]);

    if ($daily_record) {
        // حذف أي سلوك سابق يخص نقاط المرتقى وذلك لعدم التكرار
        $pdo->query("delete from daily_behavior where daily_id=:daily_id and behavior_type_id=6",
            [':daily_id' => (int)$daily_record->id]);
    }

    if ($final_exam_status == 2) {//إذا كانت النتيجة : ناجح

        //جلب بيانات الاختبار
        $exam = $pdo->row("select ex.AutoNo,ex.ErtiqaID,sh.Degree from er_ertiqaexams ex,er_shahadah sh where ex.AutoNo=sh.ExamNo and ex.AutoNo=:exam_no",
            [':exam_no' => $exam_no]);

        //٦ نقاط عن كل مرتقى
        $extra_points = 6 * intval($exam->ErtiqaID > 8 ? 8 : $exam->ErtiqaID);
        //٩ نقاط أساسية زائد نقاط عن كل مرتقى ثم تقسم حسب نسبة النجاح
        $ertiqa_points = intval($extra_points) * $exam->Degree / 100;

        //اضافة نقاط الطالب لجدول السلوك
        $insert = $pdo->zInsert('daily_behavior', [
            'daily_id'         => (int)$daily_record->id,
            'behavior_type_id' => 6,
            'points'           => $ertiqa_points,
        ]);
    }
}