<?php require_once('../functions.php');

$usergroup = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;

include('../templates/header1.php');
$PageTitle = 'تقارير الإرتقاء'; ?>
<title><?php echo $PageTitle; ?></title>
<style>
    .top_padding {
        padding-top: 13px;
    }
</style>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"> <?php echo $PageTitle; ?> </div>
<!--PageTitle-->

<div class="content">
    <?php if (login_check("admin,edarh,er,t3lem,alaqat") == true) { ?>
    <P> * للاستعلام عن جميع الطلاب في سلم البراعم، اترك التواريخ فارغة <br>
        * يمكنك الاستعلام بالتاريخ الأول فقط أو الثاني فقط أو بالجميع </P>
</div>
    <div class="content">
        <div class="three columns alpha">
            <div class="LabelContainer">
                <label for="Date1">التاريخ الأول</label>
            </div>
            <input type="text" name="Date1" id="Date1" zezo_date="true">
        </div>
        <div class="three columns">
            <div class="LabelContainer">
                <label for="Date2">التاريخ الثاني</label>
            </div>
            <input type="text" name="Date2" id="Date2" zezo_date="true">
        </div>
        <!--        <div class="clearfix"></div>-->
        <div class="four columns top_padding">
            <a id="link1" class="button-primary full-width" target="new" href="/sys/ertiqa/reports/no_ertiqa.php" tabindex="-1">بيان بمن لم يختبر من الطلاب</a>
        </div>

        <div class="four columns omega top_padding">
            <a id="link2" class="button-primary  full-width" target="new" href="/sys/ertiqa/reports/no_ertiqa_teacher.php" tabindex="-1">بيان بمن لم يختبر لديه
                طلاب</a>
        </div>

    </div>

<?php } else {
    echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
} ?>
<!--content-->
<script>
    $(function () {
        $('#Date1,#Date2').change(function () {
            var base = '/sys/ertiqa/reports/';
            var date1 = $('#Date1').val();
            var date2 = $('#Date2').val();
            var parameters = '?Date1=' + date1 + '&Date2=' + date2;

            $('#link1').attr('href', base + 'no_ertiqa.php' + parameters);
            $('#link2').attr('href', base + 'no_ertiqa_teacher.php' + parameters);

        });
    });
</script>
<?php include('../templates/footer.php'); ?>
