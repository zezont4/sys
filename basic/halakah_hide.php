<?php require_once('../functions.php');
if (login_check("admin,edarh,t3lem") == true) {
    $halakah_id = isset($_GET['AutoNo']) ? $_GET['AutoNo'] : null;
    if ($halakah_id) {
        $msg = null;
        //منع الحذف إذا كان هناك طلاب غير مطوي قيدهم ومربوطون مع الحلقة
        $students = sprintf('select * from 0_students where hide=0 and StHalaqah=%s', $halakah_id);
        $students_result = mysqli_query($localhost, $students) or die('$students : ' . mysqli_error($localhost));
        $students_count = mysqli_num_rows($students_result);
        $halakah_info = '<a href="kashf/students_data.php?HalaqatID=' . $halakah_id . '">لاستعراض طلاب ومعلمي الحلقة، اضغط هنا.</a>';
        if ($students_count) {
            $msg = 'عفوا... لا يمكن حذف الحلقة لوجود طالب / طلاب تابعين لها .' .
                '<br>' . ' يجب إخلاء طرف الطالب / الطلاب أولا حتى تتمكن من أخفاء الحلقة
                <br> ' . $halakah_info;
        }

        //منع الحذف إذا كان هناك معلمين غير مطوي قيدهم ومربوطون مع الحلقة
        $teachers = sprintf('select * from 0_teachers where hide=0 and THalaqah=%s', $halakah_id);
        $teachers_result = mysqli_query($localhost, $teachers) or die('$teachers : ' . mysqli_error($localhost));
        $teachers_count = mysqli_num_rows($teachers_result);
        if ($teachers_count) {
            $msg = 'عفوا... لا يمكن حذف الحلقة لوجود معلم تابع لها .' .
                '<br>' . ' يجب إخلاء طرف المعلم أولا حتى تتمكن من أخفاء الحلقة
                <br>' . $halakah_info;
        }

        if ($msg) {
            exit('
                <div class="sixteen columns">
                            <p style="text-align:center;font-size:24px;margin-top:50px ;color:red;direction:rtl">' . $msg . '</p>
                </div>');
        }

        $deleteSQL = sprintf("update `0_halakat` set `hide` =1 WHERE AutoNo=%s",
            GetSQLValueString($halakah_id, "int"));

        $Result1 = mysqli_query($localhost, $deleteSQL) or die('$deleteSQL :' . mysqli_error($localhost));

        $deleteGoTo = "halakah_add.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
            $deleteGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $deleteGoTo));
    }

} else {
    echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
}