<?php
$_token = isset($_GET['_token']) ? $_GET['_token'] : '';
if ($_token == 'STW6gPk8QrrZdF1gW2tO') {
    require('../Connections/localhost.php');
//    mysqli_select_db($localhost, $database_localhost);
    $sql = "select * from 0_student_transfer where is_transferred=0";
    $query = mysqli_query($localhost, $sql) or die("ajax_chrome_extension.php 1: " . mysqli_error($localhost));
    $data_count = mysqli_num_rows($query);
    if ($data_count > 0) {
        echo $data_count;
    }
}