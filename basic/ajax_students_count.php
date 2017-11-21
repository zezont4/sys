<?php
$TID = "-1";
if (isset($_GET['TID'])) {
    $TID = $_GET['TID'];
}
require('../Connections/localhost.php');
mysqli_select_db($localhost, $database_localhost);
$query_st_count = sprintf("SELECT count(s.StID) as st_count FROM `0_teachers` t,`0_students` s where t.THalaqah = s.StHalaqah and t.hide=0 and s.hide=0 and t.TID=%s", $TID);
$st_count = mysqli_query($localhost, $query_st_count) or die("ajax_hlkh_count.php 1: " . mysqli_error($localhost));
$row_st_count = mysqli_fetch_assoc($st_count);
$totalRows_st_count = mysqli_num_rows($st_count);
if ($totalRows_st_count > 0) {
    echo $row_st_count["st_count"];
}
?>