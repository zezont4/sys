<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php';?>
<?php sec_session_start();  ?>
<?php
$userType=0;
if (isset($_SESSION['user_group'])){
    $userType=$_SESSION['user_group'];
}
?>
<?php
$usergroup = "-1";
if (isset($_SESSION['user_group'])) {
    $usergroup = $_SESSION['user_group'];
}
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'الكشوفات المالية'; ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include ('../templates/nav_menu.php'); ?>
<div id="PageTitle"> <?php echo $PageTitle; ?> </div>
<!--PageTitle-->

<div class="content">
    <?php if(login_check("admin,edarh,er,t3lem") == true) { ?>
    <P> * للاستعلام عن  جميع الطلاب في سلم البراعم، اترك التواريخ فارغة <br>
        * يمكنك الاستعلام بالتاريخ الأول فقط أو الثاني فقط أو بالجميع </P>
</div>
    <div class="content">
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="Date1">التاريخ الأول</label>
            </div>
            <input type="text" name="Date1" id="Date1" zezo_date="true">
        </div>
        <div class="four columns omega">
            <div class="LabelContainer">
                <label for="Date2">التاريخ الثاني</label>
            </div>
            <input type="text" name="Date2" id="Date2" zezo_date="true">
        </div>
        <br class="clear">
        <div class="three columns alpha">
            <a id="link1" class="button-primary full-width" target="new" href="/sys/ertiqa/reports/maliah.php" tabindex="-1">أمر صرف للمالية</a>
        </div>
        <div class="three columns">
            <a id="link2" class="button-primary full-width" target="new" href="/sys/ertiqa/reports/awards_students.php" tabindex="-1">جوائز <?php echo get_gender_label('sts','ال') ?></a>
        </div>
        <div class="four columns">
            <a id="link4" class="button-primary full-width" target="new" href="/sys/ertiqa/reports/awards_bra3m.php" tabindex="-1">مكافآت البراعم</a>
        </div>
        <div class="three columns">
            <a id="link3" class="button-primary full-width" target="new" href="/sys/ertiqa/reports/awards_teachers.php" tabindex="-1">حوافز  <?php echo get_gender_label('ts','ال') ?></a>
        </div>
        <div class="three columns omega">
            <a id="link5" class="button-primary full-width" target="new" href="/sys/ertiqa/reports/awards_edarah.php" tabindex="-1">حوافز الدار</a>
        </div>
    </div>
<?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>
</div>
<!--content-->
<script>
    $(function () {
        $('#Date1,#Date2').change(function () {
            var base = '/sys/ertiqa/reports/';
            var date1 = $('#Date1').val();
            var date2 = $('#Date2').val();
            var parameters = '?Date1=' + date1 + '&Date2=' + date2;
            $('#link1').attr('href',base + 'maliah.php' + parameters);
            $('#link2').attr('href',base + 'awards_students.php' + parameters);
            $('#link3').attr('href',base + 'awards_teachers.php' + parameters);
            $('#link4').attr('href',base + 'awards_bra3m.php' + parameters);
            $('#link5').attr('href',base + 'awards_edarah.php' + parameters);
        });
    });
</script>
<?php include('../templates/footer.php'); ?>
