<?php require_once('../functions.php');
if (login_check("admin,edarh,t3lem") == true) {

    if ((isset($_GET['AutoNo'])) && ($_GET['AutoNo'] != "")) {
        $deleteSQL = sprintf("update `0_halakat` set `hide` =0 WHERE AutoNo=%s",
            GetSQLValueString($_GET['AutoNo'], "int"));

        $Result1 = mysqli_query($localhost, $deleteSQL) or die(mysqli_error($localhost));

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