<?php
require_once('functions.php');
include_once('templates/header1.php');
sendSMS('966559955', 'منتامنتامنا نتا تاما م');
$PageTitle = 'الرئيسية'; ?>
    <title><?php echo $PageTitle; ?></title>
    <style>
        .columns a.inline_menu {
            text-align: right;
            display: inline;
            margin: 0;
            padding: 0;
            background-repeat: no-repeat;
            background-position: 16px;
        }

        div.columns {
            padding: 10px 0;
            text-align: center;
            font-size: 18px;
        }

        .kufi {
            font-family: 'Droid Arabic Kufi';
            color: #038618;
        }
    </style>
    </head>
    <body>
<?php include_once('templates/nav_menu.php'); ?>
<?php include_once('templates/header2.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
<?php login_check(''); ?>
<?php if (isset($_SESSION['arabic_name']) && $_SESSION['arabic_name'] != '') { ?>
    <div class="content">
        <div class="FieldsTitle">تم تسجيل الدخول بنجاح</div>
        <div class="sixteen columns alpha omega"> مرحبا <span class="kufi">( <?php echo $_SESSION['arabic_name']; ?> ) </span> .</div>
        <div class="sixteen columns alpha omega">تم تسجيل الدخول بنجاح.</div>
        <?php if (isset($_SESSION['sex']) && $_SESSION['sex'] == 0) { ?>
            <div class="sixteen columns alpha omega">فضلا اضغطي على <a class="inline_menu" href="#menu">القائمة الرئيسية</a> للتنقل بين خدمات النظام.</div>
        <?php } else { ?>
            <div class="sixteen columns alpha omega">فضلا اضغط على <a class="inline_menu" href="#menu">القائمة الرئيسية</a> للتنقل بين خدمات النظام.</div>
        <?php } ?>
        <br><br>
        <hr>
        <div class="sixteen columns alpha omega"><a class="inline_menu" href="http://sys.quranzulfi.com/">للإنتقال إلى نظام الحلقات (راصد) ، اضغط هنا</a></div>
    </div>
<?php } else { ?>

    <div class="content">
        <div class="FieldsTitle">يرجى تحديد الصفحة المناسبة</div>
        <div class="sixteen columns alpha omega kufi"> مرحبا بك أخي المبارك \ أختي المباركة</div>
        <div class="sixteen columns alpha omega"> إذا كان لديك اسم مستخدم في النظام الإلكتروني ، فيرجى التوجه إلى <a class="inline_menu" href="/sys/login.php">صفحة تسجيل الدخول</a>
        </div>
        <div class="sixteen columns alpha omega"> إذا كنت أحد معلمي أو معلمات الجمعية ، فهذه <a class="inline_menu" href="/sys/ertiqa/statistics/teacherexams.php"> صفحتكم.</a>
        </div>
        <div class="sixteen columns alpha omega"> إذا كنت أحد طلابنا أو طالباتنا ، فهذه <a class="inline_menu" href="/sys/ertiqa/statistics/studentexams.php"> صفحتكم.</a></div>
        <hr>
        <div class="sixteen columns alpha omega"><a class="inline_menu" href="http://sys.quranzulfi.com/">للإنتقال إلى نظام الحلقات (راصد) ، اضغط هنا</a></div>
    </div>
<?php } ?>
<?php include('templates/footer.php'); ?>