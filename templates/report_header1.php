<?php define('ROOT', '/sys');?>
<!doctype html>
	<html>
		<head>
			<title><?php echo $pageTitle; ?></title>
			<meta charset="utf-8">
			<link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/_css/report.css">
			<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/earlyaccess/droidarabickufi.css">
			<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/earlyaccess/droidarabicnaskh.css">
		</head>
		<body>
			<div class="printButton">
				<input class="button-primary"  type="button" value="طباعة" onclick="window.print()">
			</div><!-- printButton -->