<?php
	require('../fahd/fahd_functions.php');
	require ('../Connections/localhost.php');
	$TID = "-1";
	if (isset($_GET['TID'])) {
	  $TID = $_GET['TID'];
	}
	$f_t_date = "-1";
	if (isset($_GET['f_t_date'])) {
	  $f_t_date = str_replace("/","",$_GET['f_t_date']);
	}
	
	$fahd_year_start=get_fahd_year_start( $f_t_date);
	$fahd_year_end=get_fahd_year_end( $f_t_date);
	
	mysqli_select_db($localhost,$database_localhost);
	$query_success_count = sprintf("select count(e.AutoNo) as count_st from er_ertiqaexams e,er_shahadah sh where e.AutoNo=sh.ExamNo and e.TeacherID=%s and e.FinalExamDate between %s and %s",
								 $TID,
								 $fahd_year_start,
								 $fahd_year_end);
	//echo $query_success_count;
	$success_count = mysqli_query($localhost,$query_success_count)or die("ajax_success_count.php 1: ".mysqli_error($localhost));
	$row_success_count = mysqli_fetch_assoc($success_count);
	$totalRows_success_count = mysqli_num_rows($success_count);
	if ($totalRows_success_count > 0) {
		echo $row_success_count["count_st"];
	}
?>