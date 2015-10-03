<?php require_once('Connections/localhost.php'); ?>
<?php include_once('secure/functions.php'); ?>
<?php sec_session_start(); ?>
<?php

// get default year
mysqli_select_db($localhost, $database_localhost);
$query_default_y = "SELECT * FROM `0_years` WHERE default_y=1";
$default_y = mysqli_query($localhost, $query_default_y) or die(mysqli_error($localhost));
$row_default_y = mysqli_fetch_assoc($default_y);
$totalRows_default_y = mysqli_num_rows($default_y);
$_SESSION ['default_year_id'] = $row_default_y['y_id'];
$_SESSION ['default_year_name'] = $row_default_y['year_name'];
$_SESSION ['default_start_date'] = $row_default_y['y_start_date'];
$_SESSION ['default_end_date'] = $row_default_y['y_end_date'];

?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=2.0,user-scalable=yes"/>
    <meta name="description" content="النظام الإلكتروني لجمعية تحفيظ القرآن الكريم بمحافظة الزلفي">
    <link rel="author" href="https://plus.google.com/109425146940625116637"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=2.0,user-scalable=yes"/>
    <meta charset="utf-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" type="text/css" href="/sys/_css/app.min.css">
    <link rel="stylesheet" type="text/css" href="_css/login.css">
    <link rel="stylesheet" type="text/css" href="_css/theme.css">
    <!--    <link rel="stylesheet" type="text/css" href="_css/reset.css">-->

    <link rel="icon" type="image/ico" href="favicon.ico">

    <script type="text/javascript" src="/sys/_js/app.min.js"></script>


<!--    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
<!--    <script src="_js/alertify.min.js"></script>-->
<!--    <link rel="stylesheet" href="_css/alertify.core.css">-->
<!--    <link rel="stylesheet" href="_css/alertify.default.css" id="toggleCSS"/>-->

<!--    <script src="_js/ajax.js"></script>-->
<!--    <script src="_js/functions.js"></script>-->
<!--    <script src="_js/parsley.min.js"></script>-->
<!--    <script src="_js/messages.ar.js"></script>-->
<!--    <link rel="stylesheet" type="text/css" href="_css/parsley.css">-->
</head>

<body>
<div id="wrapper">
    <?php
    if (isset($_GET['error']))
        if ($_GET['error'] == 1) {
            {
                ?>
                <script>
                    alertify.error("اسم المستخدم أو كلمة المرور خاطئة");
                </script>
                <?php
                $_SESSION['u1'] = NULL;
                unset($_SESSION['u1']);
            }
        }
    ?>

    <form name="login-form" id="form1" class="login-form" method="POST" action="secure/process_login.php"
          data-validate="parsley">

        <div class="header">
            <div id="logo">
                <img src="_images/Logo.png" width="150" alt="logo">
            </div>
            <h1>تسجيل الدخول</h1>
		<span>مرحبا بك في النظام الإلكتروني     
            <br/>
            فضلا... اكتب اسم المستخدم وكلمة المرور.
            </span>
        </div>

        <div class="content">
            <input name="username" type="text" class="input username" placeholder="اسم المستخدم" data-required="true"/>

            <div class="user-icon"></div>
            <input name="password" type="password" class="input password" placeholder="كلمة المرور"
                   data-required="true"/>

            <div class="pass-icon"></div>
        </div>

        <div class="footer">
            <input type="submit" name="submit" value="تسجيل الدخول" class="button"/>
        </div>

    </form>
</div>

<?php //echo $_SESSION['logz'];?>
</body>
</html>
