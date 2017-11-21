<?php require_once('../functions.php');
if (login_check("admin,t3lem") == true) {
    if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
        $deleteSQL = sprintf("update `0_users` set `hidden` =1 WHERE id=%s",
            GetSQLValueString($_GET['id'], "int"));

        $Result1 = mysqli_query($localhost, $deleteSQL) or die(mysqli_error($localhost));

        $deleteGoTo = "edarah_add.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
            $deleteGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $deleteGoTo));
    }

} else {
    echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
}