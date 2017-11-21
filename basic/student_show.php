<?php require_once('../functions.php');
if (login_check("admin,edarh,t3lem") == true) {

    if ((isset($_GET['StID'])) && ($_GET['StID'] != "")) {
        $deleteSQL = sprintf("update `0_students` set `hide` =0 WHERE StID=%s",
            GetSQLValueString($_GET['StID'], "double"));

        $Result1 = mysqli_query($localhost, $deleteSQL) or die(mysqli_error($localhost));

        $deleteGoTo = "search_st_resault.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
            $deleteGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $deleteGoTo));
    }

} else {
    echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
}