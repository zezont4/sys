<!DOCTYPE html>
<?php
//http://204.186.103.6/jkcounter/#
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Riyadh');
$start_or_end = isset($_GET['start_or_end']) ? $_GET['start_or_end'] : 'start';
$date = isset($_GET['date']) ? $_GET['date'] : 'null';
$year = 0;
$month = 0;
$day = 0;
$day_names = ['', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد',];
$countersArray = [
	['title' => 'توزيع ملفات الإدارة المتميزة على إدارات الحلقات','date1' => 20150823,'date2' => 20150903],
	['title' => 'التسجيل عن طريق النظام الالكتروني في مسابقة الفهد للحلقات','date1' => 20150906,'date2' => 20151009],
	['title' => 'إجراء التصفيات الأولية لمسابقة الفهد للأفراد ومزامير آل داود مع تسجيل أسماء المرشحين في النظام الالكتروني','date1' => 20150906,'date2' => 20151121],
	['title' => 'التسجيل وتحديث البيانات لجميع المعلمين والمعلمات عن طريق النظام الالكتروني','date1' => 20150906,'date2' => 20160303],
	['title' => 'إقامة مسابقة الحلقات','date1' => 20151026,'date2' => 20151127],
	['title' => 'إقامة تصفيات المرحلة الثانية بين مرشحي إدارات الحلقات في الجمعية','date1' => 20160125,'date2' => 20160218],
	['title' => 'تصفيات المرحلة الثانية والثالثة لمسابقة مزامير آل داود','date1' => 20160214,'date2' => 20160225],
	['title' => 'فرز بيانات المتأهلين للمرحلة النهائية من قبل قسم المسابقات ومكتب الإشراف','date1' => 20160221,'date2' => 20160311],
	['title' => 'تسجيل أسماء المرشحين في القسم الخامس عن طريق النظام الالكتروني','date1' => 20160306,'date2' => 20160401],
	['title' => 'رفع ملف الإدارة المتميزة','date1' => 20160321,'date2' => 20160401],
	['title' => 'رفع ملفات وسجلات المرشحين الخمسة الأوائل','date1' => 20160321,'date2' => 20160401],
	['title' => 'إقامة المرحلة النهائية لمسابقة الفهد للأفراد','date1' => 20160407,'date2' => 20160414],
	['title' => 'اعتماد نتائج المسابقة وإعلانها','date1' => 20160601,'date2' => 20160609],
];
?>

<html>
<head>
	<meta charset="utf-8">
	<title>مواعد مسابقة الفهد</title>

	<!--	<script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.min.js"></script>-->
	<script src="jquery-1.8.3.min.js"></script>

	<script type="text/javascript" src="jquery.responsive_countdown.min.js"></script>
	<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css" media="screen,projection"/>

	<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css"/>
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<style>
		body {
			background-color: #eee;
			font-family: 'DroidArabicKufiRegular', serif;
			font-weight: normal;
			font-style: normal;
		}

		.start-msg, .end-msg {
			text-align: center;
			padding: 12px 0 !important;
			width: 100%;
			color: #FFF;
		}

		.start-msg {
			background-color: #A0B848;
		}

		.end-msg {
			background-color: #646464;
		}
	</style>
</head>

<body>
<div class="container">
	<h2 class="header center lime-text text-darken-1"><i class="large material-icons">query_builder</i></h2>

	<div class="section center">
		<h4 class="header col s12 grey-text text-darken-1">البرنامج الزمني لجائزة الفهد الثامنة لعام ١٤٣٦ - ١٤٣٧ هـ</h4>
	</div>

	<br><br>

	<div class="section card z-depth-0 grey lighten-3">
		<div class="row">
			<div class="col s6 " style="background-color: #646464; color: #FFFFFF;">
				<h5 class="center-align">اللون الأسود : نهاية المرحلة</h5>
			</div>
			<div class="col s6 " style="background-color: #A0B848; color: #FFFFFF;">
				<h5 class="center-align">اللون الأخضر : بداية المرحلة</h5>
			</div>
		</div>
	</div>
	<?php
	$i = 0;
	foreach ($countersArray as $data) {
		$i++;
		if (is_numeric($data['date1']) && strlen($data['date1']) == 8 and is_numeric($data['date2']) && strlen($data['date2']) == 8) {
			$year1 = substr($data['date1'], 0, 4);
			$month1 = substr($data['date1'], 4, 2);
			$day1 = substr($data['date1'], 6, 2);

			$year2 = substr($data['date2'], 0, 4);
			$month2 = substr($data['date2'], 4, 2);
			$day2 = substr($data['date2'], 6, 2);

			$datetime11 = new DateTime('now');
			$datetime12 = new DateTime("$year1-$month1-$day1");
			$datetime22 = new DateTime("$year2-$month2-$day2");
			$interval1 = $datetime11->diff($datetime12);
			$interval2 = $datetime11->diff($datetime22);

			$days_diff1 = $interval1->format('%R%a');
			$days_diff2 = $interval2->format('%R%a');

			$day_digits1 = $days_diff1 > 99 ? 3 : 2;
			$day_digits2 = $days_diff2 > 99 ? 3 : 2; ?>
			<div class="col s12 card-panel">
				<h5 class="right-align grey-text text-darken-1"><?php echo $data['title']; ?></h5>

				<div class="section">
					<div id="start_countdown<?php echo $i; ?>" style="position: relative; width: 100%; height: 120px;"></div>
				</div>
				<div class="divider"></div>
				<div class="section">
					<div id="end_countdown<?php echo $i; ?>" style="position: relative; width: 100%; height: 120px;"></div>
				</div>
			</div>

			<script>
				$(document).ready(function () {
					$("#start_countdown<?php echo $i;?>").ResponsiveCountdown({
						target_date: "<?php echo $year1;?>/<?php echo $month1;?>/<?php echo $day1;?> 00:00:00",
						time_zone: 3, target_future: true,
						set_id: 1, pan_id: 2, day_digits: <?php echo $day_digits1;?>,
						fillStyleSymbol1: "rgba(255, 255, 255, 1)",
						fillStyleSymbol2: "rgba(255,255,255,1)",
						fillStylesPanel_g1_1: "rgba(160, 184, 72, 1)",
						fillStylesPanel_g1_2: "rgba(160, 184, 72, 1)",
						fillStylesPanel_g2_1: "rgba(160, 184, 72, 1)",
						fillStylesPanel_g2_2: "rgba(160, 184, 72, 1)",
						text_color: "rgba(0, 0, 0, 1)",
						text_glow: "rgba(100, 100, 100, 1)",
						show_ss: true, show_mm: true,
						show_hh: true, show_dd: true,
						f_family: "DroidArabicKufiRegular", show_labels: true,
						type3d: "group", max_height: 93,
						days_long: "يوم", days_short: "يوم",
						hours_long: "ساعة", hours_short: "ساعة",
						mins_long: "دقيقة", mins_short: "دقيقة",
						secs_long: "ثانية", secs_short: "ثانية",
						min_f_size: 16, max_f_size: 30,
						spacer: "none", groups_spacing: 2, text_blur: 0,
						font_to_digit_ratio: 0.33, labels_space: 1.2,

						complete: function () {
							$('#start_countdown<?php echo $i;?>').html(
								'<div class="row">' +
								'<div class="start-msg col s12 m8 offset-m2 l6 offset-l3">' +
								'<h5>' +
								'بدأت المرحلة قبل ' +
								"<?php echo str_replace('-','',$days_diff1).' يوم';?>" +
								'</h5>' +
								'</div>' +
								'</div>'
							);
						}
					});


					$("#end_countdown<?php echo $i;?>").ResponsiveCountdown({
						target_date: "<?php echo $year2;?>/<?php echo $month2;?>/<?php echo $day2;?> 00:00:00",
						time_zone: 3, target_future: true,
						set_id: 1, pan_id: 2, day_digits: <?php echo $day_digits2;?>,
						fillStyleSymbol1: "rgba(255, 255, 255, 1)",
						fillStyleSymbol2: "rgba(255,255,255,1)",
						fillStylesPanel_g1_1: "rgba(100, 100, 100, 1)",
						fillStylesPanel_g1_2: "rgba(100, 100, 100, 1)",
						fillStylesPanel_g2_1: "rgba(100, 100, 100, 1)",
						fillStylesPanel_g2_2: "rgba(100, 100, 100, 1)",
						text_color: "rgba(0, 0, 0, 1)",
						text_glow: "rgba(100, 100, 100, 1)",
						show_ss: true, show_mm: true,
						show_hh: true, show_dd: true,
						f_family: "DroidArabicKufiRegular", show_labels: true,
						type3d: "group", max_height: 93,
						days_long: "يوم", days_short: "يوم",
						hours_long: "ساعة", hours_short: "ساعة",
						mins_long: "دقيقة", mins_short: "دقيقة",
						secs_long: "ثانية", secs_short: "ثانية",
						min_f_size: 16, max_f_size: 30,
						spacer: "none", groups_spacing: 2, text_blur: 0,
						font_to_digit_ratio: 0.33, labels_space: 1.2,

						complete: function () {
							$('#end_countdown<?php echo $i;?>').html(
								'<div class="row">' +
								'<div class="end-msg col s12 m8 offset-m2 l6 offset-l3  grey darken-1">' +
								'<h5>' +
								'انتهت المرحلة قبل ' +
								"<?php echo str_replace('-','',$days_diff2).' يوم';?>" +
								'</h5>' +
								'</div>' +
								'</div>'
							);
						}
					});
				});
			</script>
		<?php } else {
			echo '<div class="col s12 card-panel">';
			echo '<h5 class="center-align grey-text text-darken-1">' . $data['title'] . '</h5>';
			echo '<hr>';
			echo '<h5 class="center-align grey-text text-darken-1">التاريخ غير مدرج أو صيغته غير صحيحة</h5>';
			echo '</div>';
		} ?>
	<?php } ?>
</div>

<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>-->
</body>
</html>