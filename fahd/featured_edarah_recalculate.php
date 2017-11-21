<?php require_once('../functions.php'); ?>
<?php require_once('fahd_functions.php'); ?>
<?php $PageTitle = 'إعادة احتساب درجات مسابقة الإدارة المتميزة' ?>
<?php if (login_check('admin,ms,t3lem') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

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

        $query_all_f_edarah = sprintf("SELECT *
									FROM ms_fahd_featured_edarah 
									WHERE f_e_date BETWEEN %s AND %s", $fahd_year_start, $fahd_year_end);
        $all_f_edarah = mysqli_query($localhost, $query_all_f_edarah) or die('get fahd years 2 ' . mysqli_error($localhost));
        $row_all_f_edarah = mysqli_fetch_assoc($all_f_edarah);
        $totalRows_all_f_edarah = mysqli_num_rows($all_f_edarah);


        if ($totalRows_all_f_edarah > 0) {
            do {
                $edarah_id = $row_all_f_edarah['edarah_id'];

//عدد الذين اجتازوا اختبار المرتقيات
                $query_success_count = sprintf("select count(e.AutoNo) as count_st 
from er_ertiqaexams e,er_shahadah sh 
where e.AutoNo=sh.ExamNo and  e.EdarahID=%s and e.FinalExamDate between %s and %s",
                    $edarah_id,
                    $fahd_year_start,
                    $fahd_year_end);
//echo $query_success_count;
                $success_count = mysqli_query($localhost, $query_success_count) or die("feature_edarah_add.php 1: " . mysqli_error($localhost));
                $row_success_count = mysqli_fetch_assoc($success_count);
                $totalRows_success_count = mysqli_num_rows($success_count);
                $count_ertiqa = 0;
                if ($totalRows_success_count > 0) {
                    $count_ertiqa = $row_success_count["count_st"];
                }

//عدد الذين استلموا مكافأة البراعم
                $query_bra3m = sprintf("select count(AutoNo) as count_st from er_bra3m  where EdarahID=%s and DDate between %s and %s",
                    $edarah_id,
                    $fahd_year_start,
                    $fahd_year_end);
                $bra3m = mysqli_query($localhost, $query_bra3m) or die("feature_edarah_add.php 2: " . mysqli_error($localhost));
                $row_bra3m = mysqli_fetch_assoc($bra3m);
                $totalRows_bra3m = mysqli_num_rows($bra3m);
                $count_bra3m = 0;
                if ($totalRows_bra3m > 0) {
                    $count_bra3m = $row_bra3m["count_st"];
                }

//عدد طلاب الصف الثالث فما فوق للمرتقيات
                $query_children = sprintf("SELECT count(st_no) AS count_st FROM  0_students WHERE school_level IN (14,0,1,2,13) AND StEdarah=%s AND hide=0",
                    $edarah_id,
                    $fahd_year_start,
                    $fahd_year_end);
                $children = mysqli_query($localhost, $query_children) or die("feature_edarah_add.php 3: " . mysqli_error($localhost));
                $row_children = mysqli_fetch_assoc($children);
                $totalRows_children = mysqli_num_rows($children);
                $count_children = 0;
                $bra3m_percentage = 0;
                $full_degree_bra3m = 0;
                $bra3m_degree = 0;
                if ($totalRows_children > 0) {
                    $count_children = $row_children["count_st"];

                    //نسبة نجاح البراعم
                    $bra3m_percentage = round($count_bra3m / $count_children * 100, 1);
//للحصول على الدرجة الكاملة للبراعم
                    $full_degree_bra3m = round($count_children * 0.80, 0);
//الدرجة التي حصل عليها في البراعم حسب الاجتياز والنسبة
                    $bra3m_degree = round((20 / 80) * $bra3m_percentage, 1);
                    $bra3m_degree = $bra3m_degree > 5 ? 5 : $bra3m_degree;
                }

//عدد طلاب الصف الثاني فما دون للبراعم
                $query_young = sprintf("SELECT count(st_no) AS count_st FROM  0_students WHERE school_level BETWEEN 3 AND 15 AND StEdarah=%s AND hide=0",
                    $edarah_id,
                    $fahd_year_start,
                    $fahd_year_end);
                $young = mysqli_query($localhost, $query_young) or die("feature_edarah_add.php 4: " . mysqli_error($localhost));
                $row_young = mysqli_fetch_assoc($young);
                $totalRows_young = mysqli_num_rows($young);
                $count_young = 0;
                $ertiqa_percentage = 0;
                $full_degree_ertiqa = 0;
                $ertiqa_degree = 0;
                if ($totalRows_young > 0) {
                    $count_young = $row_young["count_st"];
                    //نسبة نجاح المرتقيات
                    $ertiqa_percentage = round($count_ertiqa / $count_young * 100, 1);
//للحصول على الدرجة الكاملة للمرتقيات
                    $full_degree_ertiqa = round($count_young * 0.75, 0);
//الدرجة التي حصل عليها في المرتقيات حسب الاجتياز والنسبة
                    $ertiqa_degree = round((20 / 75) * $ertiqa_percentage, 1);
                    $ertiqa_degree = $ertiqa_degree > 20 ? 20 : $ertiqa_degree;
                }



                $new_full_degree = $row_all_f_edarah['total_e'] - ($row_all_f_edarah['e4'] + $row_all_f_edarah['e5']) + ($ertiqa_degree + $bra3m_degree);

                $updateSQL = sprintf("UPDATE ms_fahd_featured_edarah SET e4=%s,e5=%s,total_e=%s WHERE id=%s", $ertiqa_degree, $bra3m_degree, $new_full_degree, $edarah_id);
                //echo $updateSQL.'<br>';
                $Result1 = mysqli_query($localhost, $updateSQL) or die(' update 1 : ' . mysqli_error($localhost) . '<br>' . $updateSQL);
                if ($Result1) {
                    $_SESSION['u1'] = "u1";
                }
            } while ($row_all_f_edarah = mysqli_fetch_assoc($all_f_edarah));
        } else {
            echo '<br><br><br><br><h1><center><strong>' . 'تأكد من العام الدراسي' . '</strong></center></h1><br><br>';
        }
        ?>

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
                <input name="submit" type="submit" class="full-width button-primary" id="submit"
                       value="اعادة احتساب المرتقيات والبراعم"/>
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