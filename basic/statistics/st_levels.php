<?php require_once('../../Connections/localhost.php');
require_once("../../functions.php");

mysqli_select_db($localhost, $database_localhost);

$pageTitle = "احصائية بالمراحل الدراسية للطلاب ";
require_once("../../templates/report_header1.php"); ?>
<style>
	.reportContent {
		font-size: 14px
	}

	tr.total td {
		background-color: #eee;
	}
</style>
<?php
//تاريخ ميلاد طلاب الصف الأول الإبتدائي
$today_year = 1437;
$levels = [
	'ماقبل التمهيدية'               => [0, 5],

	'المرحلة التمهيدية'             => [5, 6],

	'أول ابتدائي'                   => [6, 7],
	'ثاني ابتدائي'                  => [7, 8],
	'ثالث ابتدائي'                  => [8, 9],

	'0إجمالي الصفوف الدنيا'         => [6, 9],

	'رابع ابتدائي'                  => [9, 10],
	'خامس ابتدائي'                  => [10, 11],
	'سادس ابتدائي'                  => [11, 12],

	'0إجمالي الصفوف العليا'         => [9, 12],

	'0إجمالي المرحلة الابتدائية'    => [6, 12],

	'أول متوسط'                     => [12, 13],
	'ثاني متوسط'                    => [13, 14],
	'ثالث متوسط'                    => [14, 15],

	'0إجمالي المرحلة المتوسطة'      => [12, 15],

	'أول ثانوي'                     => [15, 16],
	'ثاني ثانوي'                    => [16, 17],
	'ثالث ثانوي'                    => [17, 18],

	'0إجمالي المرحلة الثانوية'      => [15, 18],

	'جامعية - السنة الأولى'         => [18, 19],
	'جامعية - السنة الثانية'        => [19, 20],
	'جامعية - السنة الثالثة'        => [20, 21],
	'جامعية - السنة الرابعة'        => [21, 22],
	'جامعية - السنة الخامسة'        => [22, 23],

	'0إجمالي المرحلة الجامعية'      => [18, 23],

	'اجمالي تعليم الكبار والموظفين' => [23, 100],

	'٢٣ - ٣٠'                       => [23, 30],
	'٣٠ - ٤٠'                       => [30, 40],
	'٤٠ - ٥٠'                       => [40, 50],
	'٥٠ - ٦٠'                       => [50, 60],
	'٦٠ - ٧٠'                       => [60, 70],
	'٧٠ - ٨٠'                       => [70, 80],
];

?>
<div class="reportWrapper">
	<div class="reportContent">
		<p class="report_description">احصائية بالمراحل الدراسية للطلاب والطالبات
			<br>
			(تقديرية حسب تاريخ الميلاد)
			<br>
			للعام الدراسي ١٤٣٦ - ١٤٣٧ هـ
		</p>
		<br>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<th><p>المرحلة الدراسية</p></th>
				<th><p>بنين</p></th>
				<th><p>بنات</p></th>
				<th><p>الإجمالي</p></th>
				<th><p>من : تاريخ ميلاد</p></th>
				<th><p>إلى : تاريخ ميلاد</p></th>
			</tr>
			<?php
			foreach ($levels as $label => $age) {
//				print_r($levels);
//				exit();
				$selected_level_start_berth_date = ($today_year - $age[1]) . '0101';
				$selected_level_end_berth_date = ($today_year - $age[0] - 1) . '1230';
				//Boys
				$query_boys = "SELECT count(st_no) AS count_st FROM 0_students st LEFT JOIN 0_users u ON st.StEdarah = u.id
WHERE st.StBurthDate BETWEEN $selected_level_start_berth_date AND $selected_level_end_berth_date AND st.hide=0 AND u.sex=1";
				$boys = mysqli_query($localhost, $query_boys) or die('absent.php boys - ' . mysqli_error($localhost));
				$row_boys = mysqli_fetch_assoc($boys);
				$totalRows_boys = mysqli_num_rows($boys);
				//Girls
				$query_girls = "SELECT count(st_no) AS count_st FROM 0_students st LEFT JOIN 0_users u ON st.StEdarah = u.id
WHERE st.StBurthDate BETWEEN $selected_level_start_berth_date AND $selected_level_end_berth_date AND st.hide=0 AND u.sex=0";
				$girls = mysqli_query($localhost, $query_girls) or die('absent.php girls - ' . mysqli_error($localhost));
				$row_girls = mysqli_fetch_assoc($girls);
				$totalRows_girls = mysqli_num_rows($girls);

				$count_boys = $totalRows_boys ? $row_boys['count_st'] : 0;
				$count_girls = $totalRows_girls ? $row_girls['count_st'] : 0;

				$tr_class = strpos($label, '0') === 0 ? 'total' : '';
//				echo strpos($label, '0');
				?>
				<tr class="<?php echo $tr_class; ?>">
					<td><?php echo str_replace('0', '', $label); ?></td>
					<td><?php echo $count_boys; ?></td>
					<td><?php echo $count_girls; ?></td>
					<td><?php echo $count_boys + $count_girls; ?></td>
					<td><?php echo StringToDate($selected_level_start_berth_date); ?></td>
					<td><?php echo StringToDate($selected_level_end_berth_date); ?></td>
				</tr>
			<?php } ?>

		</table>
	</div>
</div>

</body>
</html>