<?php require_once('functions.php');
define( 'ROOT', '/sys' );
// get default year
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
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/_css/app.min.css?ver=5">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/_css/login.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/_css/theme.css">
    <!--    <link rel="stylesheet" type="text/css" href="_css/reset.css">-->

    <link rel="icon" type="image/ico" href="favicon.ico">

    <script type="text/javascript" src="<?php echo ROOT;?>/_js/app.min.js"></script>

</head>

<body>
<div id="wrapper">
    <?php
    //    storeCookie(29);
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
    <hr>
    <div class="sixteen columns alpha omega"><a class="inline_menu" href="http://sys.quranzulfi.com/">للإنتقال إلى نظام الحلقات (راصد) ، اضغط هنا</a></div>
</div>

<?php //echo $_SESSION['logz'];?>
</body>
</html>
