<?php
require_once('../functions.php');
if (login_check("admin") == true) {

    if ((isset($_GET['AutoNo'])) && ($_GET['AutoNo'] != "")) {
        $deleteSQL = sprintf("DELETE FROM ms_fahd_rgstr WHERE AutoNo=%s",
            GetSQLValueString($_GET['AutoNo'], "int"));

        $Result1 = mysqli_query($localhost, $deleteSQL) or die(mysqli_error($localhost));

        header("location: view_registered.php", true, 301);
        exit;

    }
} else {
    echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
}