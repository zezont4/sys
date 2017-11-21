<?php
header('Content-Type: application/json');
$StID = "-1";
if (isset($_GET['StID'])) {
    $StID = $_GET['StID'];
}
require('../Connections/localhost.php');
mysqli_select_db($localhost, $database_localhost);
$sql = sprintf("SELECT * FROM 0_students where StID=%s", $StID);
$query = mysqli_query($localhost, $sql) or die("ajax_student_exists.php 1: " . mysqli_error($localhost));
$data = mysqli_fetch_assoc($query);
$data_count = mysqli_num_rows($query);
if ($data_count > 0) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
}