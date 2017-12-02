<?php define('ROOT', '/sys'); ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="turbolinks-root" content="/sys">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="النظام الإلكتروني لجمعية تحفيظ القرآن الكريم بمحافظة الزلفي">
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>/_css/app.min.css?ver=5">

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo ROOT; ?>/_images/favicon/apple-touch-icon.png?v=m2lxymqBbO">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo ROOT; ?>/_images/favicon/favicon-32x32.png?v=m2lxymqBbO">
    <link rel="icon" type="image/png" sizes="194x194" href="<?php echo ROOT; ?>/_images/favicon/favicon-194x194.png?v=m2lxymqBbO">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo ROOT; ?>/_images/favicon/android-chrome-192x192.png?v=m2lxymqBbO">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo ROOT; ?>/_images/favicon/favicon-16x16.png?v=m2lxymqBbO">
    <link rel="manifest" href="<?php echo ROOT; ?>/_images/favicon/manifest.json?v=m2lxymqBbO">
    <link rel="mask-icon" href="<?php echo ROOT; ?>/_images/favicon/safari-pinned-tab.svg?v=m2lxymqBbO" color="#0f6c58">
    <link rel="shortcut icon" href="<?php echo ROOT; ?>/_images/favicon/favicon.ico?v=m2lxymqBbO">
    <meta name="apple-mobile-web-app-title" content="النظام الإلكتروني">
    <meta name="application-name" content="النظام الإلكتروني">
    <meta name="msapplication-config" content="<?php echo ROOT; ?>/_images/favicon/browserconfig.xml?v=m2lxymqBbO">
    <meta name="theme-color" content="#0f6c58">

    <script type="text/javascript" src="<?php echo ROOT; ?>/_js/turbolinks.js"></script>

    <script type="text/javascript" src="<?php echo ROOT; ?>/_js/app.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT; ?>/_js/jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('nav#menu').mmenu({
                position: "right",
                searchfield: {
                    add: true,
                    search: true,
                    placeholder: "بحث",
                    noResults: "لايوجد نتائج",
                    showLinksOnly: true
                }
            });
        });
    </script>