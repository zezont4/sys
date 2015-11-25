<!DOCTYPE html>
<html lang="ar">
<head>
	<meta charset="utf-8">
	<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	date_default_timezone_set('Asia/Riyadh');
	$start_or_end = isset($_GET['start_or_end']) ? $_GET['start_or_end'] : 'start';
	$date = isset($_GET['date']) ? $_GET['date'] : 'null';
	$year = 0;
	$month = 0;
	$day = 0;
	$day_names = [
		'',
		'الإثنين',
		'الثلاثاء',
		'الأربعاء',
		'الخميس',
		'الجمعة',
		'السبت',
		'الأحد',
	];
	if (is_numeric($date) && strlen($date) != 7) {
		$year = substr($date, 0, 4);
		$month = substr($date, 4, 2);
		$day = substr($date, 6, 2);

		$datetime1 = new DateTime('now');
		$datetime2 = new DateTime("$year-$month-$day");
		echo $day_names[$datetime2->format('N')] . ' ' . $datetime2->format('d-m-Y');
		$interval = $datetime1->diff($datetime2);
		$days_diff = $interval->format('%R%a');
		$day_digits = $days_diff > 99 ? 3 : 2;
	} else {
		echo '<h2>التاريخ غير مدرج أو صيغته غير صحيحة</h2>';
	}
	?>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="jquery.responsive_countdown.min.js"></script>
	<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css" media="screen,projection"/>
	<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css"/>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<!--	<script src="jquery-1.8.3.min.js"></script>-->

	<style>
		body {
			margin: 0px;
			padding: 0px;
			background-color: #fff;
			overflow-x: hidden;
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


	<script>
		jQuery(document).ready(function ($) {
			$("#start_countdown").ResponsiveCountdown({
				target_date: "<?php echo $year;?>/<?php echo $month;?>/<?php echo $day;?> 00:00:00",
				time_zone: +3, target_future: true,
				set_id: 1, pan_id: 2, day_digits: <?php echo $day_digits;?>,
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
					$('#start_countdown').html(
						'<div class="row">' +
						'<div class="start-msg col s12 m8 offset-m2 l6 offset-l3">' +
						'<h5>' +
						'بدأت المرحلة قبل ' +
						"<?php echo str_replace('-','',$days_diff).' يوم';?>" +
						'</h5>' +
						'</div>' +
						'</div>'
					);
				}
			});


			$("#end_countdown").ResponsiveCountdown({
				target_date: "<?php echo $year;?>/<?php echo $month;?>/<?php echo $day;?> 00:00:00",
				time_zone: 3, target_future: true,
				set_id: 1, pan_id: 2, day_digits: <?php echo $day_digits;?>,
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
					$('#end_countdown').html(
						'<div class="row">' +
						'<div class="end-msg col s12 m8 offset-m2 l6 offset-l3  grey darken-1">' +
						'<h5>' +
						'انتهت المرحلة قبل ' +
						"<?php echo str_replace('-','',$days_diff).' يوم';?>" +
						'</h5>' +
						'</div>' +
						'</div>'
					);
				}
			});
		});
	</script>
</head>

<body>
<?php if ($start_or_end == 'start') { ?>
	<div id="start_countdown" style="position: relative; width: 100%; height: 50px;"></div>
<?php } else { ?>
	<div id="end_countdown" style="position: relative; width: 100%; height: 50px;"></div>
<?php } ?>
</body>
</html>