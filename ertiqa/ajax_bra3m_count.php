<?php
require_once('../functions.php');
require_once('../fahd/fahd_functions.php');
$TID = "-1";
if (isset($_GET['TID'])) {
    $TID = $_GET['TID'];
}
$f_t_date = "-1";
if (isset($_GET['f_t_date'])) {
    $f_t_date = str_replace("/", "", $_GET['f_t_date']);
}

$fahd_year_start = get_fahd_year_start($f_t_date);
$fahd_year_end = get_fahd_year_end($f_t_date);

$query_bra3m = sprintf("select count(AutoNo) as count_st from er_bra3m  where TeacherID=%s and DDate between %s and %s",
    $TID,
    $fahd_year_start,
    $fahd_year_end);
//echo $query_bra3m;
$bra3m = mysqli_query($localhost, $query_bra3m) or die("ajax_bra3m.php 1: " . mysqli_error($localhost));
$row_bra3m = mysqli_fetch_assoc($bra3m);
$totalRows_bra3m = mysqli_num_rows($bra3m);
if ($totalRows_bra3m > 0) {
    echo $row_bra3m["count_st"];
}