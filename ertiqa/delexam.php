<?php require_once('../functions.php');
if (login_check("admin") == true) {

    if ((isset($_GET['AutoNo'])) && ($_GET['AutoNo'] != "")) {
        $deleteSQL = sprintf("DELETE FROM er_ertiqaexams WHERE AutoNo=%s",
            GetSQLValueString($_GET['AutoNo'], "int"));

        $Result1 = mysqli_query($localhost, $deleteSQL) or die(mysqli_error($localhost));

        $deleteGoTo = "addexamdate.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
            $deleteGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $deleteGoTo));
    }
} else {
    echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
}