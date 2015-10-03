<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php require_once('fahd_functions.php'); ?>
<?php $PageTitle = 'إعادة احتساب درجات مسابقة المعلم المتميز' ?>
<?php if (login_check('admin,ms,t3lem') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }
    mysqli_select_db($localhost, $database_localhost);

    $query_fahd_year = "SELECT *
					FROM ms_fahd_years ORDER BY y_start_date DESC ";
    $fahd_year = mysqli_query($localhost, $query_fahd_year) or die('get fahd years' . mysqli_error($localhost));
    $row_fahd_year = mysqli_fetch_assoc($fahd_year);
    $totalRows_fahd_year = mysqli_num_rows($fahd_year);

    if ((isset($_POST["f_form1"])) && ($_POST["f_form1"] == "form1")) {
        $query_fahd_year1 = sprintf("SELECT *
								FROM ms_fahd_years 
								WHERE y_id=%s", $_POST["fahd_year"]);
        $fahd_year1 = mysqli_query($localhost, $query_fahd_year1) or die('get fahd years1 ' . mysqli_error($localhost));
        $row_fahd_year1 = mysqli_fetch_assoc($fahd_year1);
        $totalRows_fahd_year1 = mysqli_num_rows($fahd_year1);

        $fahd_year_start = $row_fahd_year1['y_start_date'];
        $fahd_year_end = $row_fahd_year1['y_end_date'];

        $query_all_f_teachers = sprintf("SELECT *
									FROM ms_fahd_featured_teacher 
									WHERE f_t_date BETWEEN %s AND %s", $fahd_year_start, $fahd_year_end);
        $all_f_teachers = mysqli_query($localhost, $query_all_f_teachers) or die('get fahd years 2 ' . mysqli_error($localhost));
        $row_all_f_teachers = mysqli_fetch_assoc($all_f_teachers);
        $totalRows_all_f_teachers = mysqli_num_rows($all_f_teachers);
        ?>
        <!--<div class="CSSTableGenerator">
        <table>
            <tr>
                <td>TID</td>

                <td>old st count</td>
                <td>new st count</td>

                <td>old ertiqa count</td>
                <td>new ertiqa count</td>

                <td>old ertiqa_degree</td>
                <td>new ertiqa_degree</td>

                <td>old_bra3m_count</td>
                <td>new_bra3m_count</td>

                <td>old_bra3m_degree</td>
                <td>new_bra3m_degree</td>

                <td>old_full_degree</td>
                <td>new_full_degree</td>
            </tr>
        -->
        <?php
        if ($totalRows_all_f_teachers > 0) {
            do {
                $TID = $row_all_f_teachers['teacher_id'];

                $count_meetings = $_POST['f6'];
                //d_resault = roundMe(txt_val * 5 / parseInt($('#f_' + txt_id + "_t").val(), 10), 2);
                //عدد الطلاب
                $query_st_count = sprintf("SELECT count(s.StID) AS st_count
									FROM `0_teachers` t,`0_students` s 
									WHERE t.THalaqah = s.StHalaqah AND t.hide=0 AND s.hide=0 AND t.TID=%s", $TID);

                $st_count = mysqli_query($localhost, $query_st_count) or die("get students count: " . mysqli_error($localhost));
                $row_st_count = mysqli_fetch_assoc($st_count);
                $totalRows_st_count = mysqli_num_rows($st_count);
                $st_count = 0;
                if ($totalRows_st_count > 0) {
                    $st_count = $row_st_count["st_count"];
                }

                //المرتقيات
                $query_rs_ertica_count = sprintf("SELECT count(e.AutoNo) AS count_st
										FROM er_ertiqaexams e,er_shahadah sh
										WHERE e.AutoNo=sh.ExamNo AND e.TeacherID=%s AND e.FinalExamDate BETWEEN %s AND %s", $TID, $fahd_year_start, $fahd_year_end);

                //echo $query_rs_ertica_count;
                $rs_ertica_count = mysqli_query($localhost, $query_rs_ertica_count) or die("get ertiqa count 1: " . mysqli_error($localhost));
                $row_rs_ertica_count = mysqli_fetch_assoc($rs_ertica_count);
                $totalRows_rs_ertica_count = mysqli_num_rows($rs_ertica_count);
                $ertiqa_count = 0;
                if ($totalRows_rs_ertica_count > 0) {
                    $ertiqa_count = $row_rs_ertica_count["count_st"];
                }

                //البراعم
                $query_rs_bra3m_count = sprintf("SELECT count(AutoNo) AS count_st
										FROM er_bra3m
										WHERE TeacherID=%s AND DDate BETWEEN %s AND %s", $TID, $fahd_year_start, $fahd_year_end);
                //echo $query_rs_bra3m_count;
                $rs_bra3m_count = mysqli_query($localhost, $query_rs_bra3m_count) or die("get bra3m count 1: " . mysqli_error($localhost));
                $row_rs_bra3m_count = mysqli_fetch_assoc($rs_bra3m_count);
                $totalRows_rs_bra3m_count = mysqli_num_rows($rs_bra3m_count);
                $new_bra3m_count = 0;
                if ($totalRows_rs_bra3m_count > 0) {
                    $new_bra3m_count = $row_rs_bra3m_count["count_st"];
                }
                if ($st_count > 0) {
                    $new_ertiqa_degree = round($ertiqa_count * 20 / $st_count, 2);
                    $new_bra3m_degree = round($new_bra3m_count * 20 / $st_count, 2);
                } else {
                    $new_ertiqa_degree = 0;
                    $new_bra3m_degree = 0;
                }
                if ($new_ertiqa_degree > 20) {
                    $new_ertiqa_degree = 20;
                };
                //echo 'now ertiqa degree : ',$new_ertiqa_degree,'<br>';

                if ($new_bra3m_degree > 20) {
                    $new_bra3m_degree = 20;
                };
                //echo 'now bra3m degree : ',$new_bra3m_degree,'<hr>';

                $new_full_degree = $row_all_f_teachers['full_degree'] - ($row_all_f_teachers['f_2a_d'] + $row_all_f_teachers['f_2b_d']) + ($new_ertiqa_degree + $new_bra3m_degree);
                ?>
                <!--<tr>
		<td><?php //echo $TID ;?></td>

		<td><?php //echo $row_all_f_teachers['f_2a_t'] ;?></td>
		<td><?php //echo $st_count ;?></td>
		
		<td><?php //echo $row_all_f_teachers['f_2a_n'] ;?></td>
		<td><?php //echo $ertiqa_count ;?></td>
		
		<td><?php //echo $row_all_f_teachers['f_2a_d'] ;?></td>
		<td><?php //echo $new_ertiqa_degree ;?></td>

		<td><?php //echo $row_all_f_teachers['f_2b_n'] ;?></td>
		<td><?php //echo $new_bra3m_count ;?></td>
		
		<td><?php //echo $row_all_f_teachers['f_2b_d'] ;?></td>
		<td><?php //echo $new_bra3m_degree ;?></td>

		<td><?php //echo $row_all_f_teachers['full_degree'] ;?></td>
		<td><?php //echo $new_full_degree ;?></td>
	</tr>-->
                <?php
                $updateSQL = sprintf("UPDATE ms_fahd_featured_teacher SET f_2a_t=%s,f_2a_n=%s,f_2a_d=%s,f_2b_n=%s,f_2b_d=%s,full_degree=%s WHERE auto_no=%s", $st_count, $ertiqa_count, $new_ertiqa_degree, $new_bra3m_count, $new_bra3m_degree, $new_full_degree, $row_all_f_teachers['auto_no']);
                //echo $updateSQL.'<br>';
                mysqli_select_db($localhost, $database_localhost);
                $Result1 = mysqli_query($localhost, $updateSQL) or die(' update 1 : ' . mysqli_error($localhost) . '<br>' . $updateSQL);
                if ($Result1) {
                    $_SESSION['u1'] = "u1";
                }
            } while ($row_all_f_teachers = mysqli_fetch_assoc($all_f_teachers));
        } else {
            echo '<br><br><br><br><h1><center><strong>'. 'تأكد من العام الدراسي'.'</strong></center></h1><br><br>';
        }
        ?>
        <!--</table>
        </div>-->
    <?php
    }
    ?>
    <?php include('../templates/header1.php'); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
		<div id="PageTitle"> <?php echo $PageTitle; ?> </div>
		<!--PageTitle-->
		
		<div class="content">
			<form action="<?php echo $editFormAction; ?>" method="post" name="form1" data-validate="parsley">
				<input type="hidden" name="f_form1" value="form1">
				<div class="five columns alpha">
					<div class="LabelContainer">
						<label for="fahd_year">العام الدراسي</label>
					</div>
					<select name="fahd_year" class="full-width" data-required="true">
						<option VALUE>حدد العام الدراسي للمسابقة</option>
						<?php do { ?>
        <option
            value="<?php echo $row_fahd_year['y_id']; ?>"><?php echo $row_fahd_year['year_name'], ' ( ', StringToDate($row_fahd_year['y_start_date']), ' - ', StringToDate($row_fahd_year['y_end_date']), ' )'; ?></option>
    <?php } while ($row_fahd_year = mysqli_fetch_assoc($fahd_year)); ?>
					</select>
				</div>
				<div class="four columns">
				<div class="LabelContainer">
                <label for="TeacherID">اجمالي عدد الاجتماعات</label>
                </div>
					<input name="f6" type="text" class="full-width"  data-required="true"/>
				</div>
				<div class="four columns">
					<input name="submit" type="submit" class="full-width button-primary" id="submit" value="اعادة احتساب المرتقيات والبراعم"/>
				</div>
			</form>
</div>
		<!--content-->
<?php include('../templates/footer.php'); ?>
    <?php if (isset($_SESSION['u1'])) { ?>
        <script>
            $(document).ready(function () {
                alertify.success("تم التعديل بنجاح");
            });
        </script>
        <?php
        $_SESSION['u1'] = null;
        unset($_SESSION['u1']);
    }
    ?>

<script type="text/javascript">
	showError();
</script>	    
<?php } else {
    include('../templates/restrict_msg.php');
} ?>