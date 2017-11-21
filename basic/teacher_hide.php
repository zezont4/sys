<?php require_once('../functions.php');
if (login_check("admin,edarh,t3lem") == true) {

    if ((isset($_GET['TID'])) && ($_GET['TID'] != "")) {

        $deleteSQL = sprintf("update `0_teachers` set `hide` =1 WHERE TID=%s",
            GetSQLValueString($_GET['TID'], "double"));

        $Result1 = mysqli_query($localhost, $deleteSQL) or die(mysqli_error($localhost));
        $deleteGoTo = "search_t_resault.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
            $deleteGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $deleteGoTo));
    }
} else {
    echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
}