<?php require_once('../functions.php');
$PageTitle = 'بحث';
if (login_check($all_groups) == true) {
    include('../templates/header1.php'); ?>
    <title><?php echo $PageTitle; ?></title>
    </head>
    <body>
    <?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
    <!--PageTitle-->
    <div class="content lp">

        <P>
            * لاستعراض جميع <?php echo get_gender_label('sts', 'ال'); ?> أو <?php echo get_gender_label('ts', 'ال'); ?>، اترك الحقول فارغة
            <br>
            * يمكنك البحث بإدخال حقل واحد أو أكثر
        </P>
    </div>
    <div class="content">
        <form name="form1" method="POST" action="search_st_resault.php">
            <div class="FieldsTitle">بحث عن <?php echo get_gender_label('st'); ?></div>
            <div class="three columns alpha">
                <div class="LabelContainer">
                    <label for="st_name1">الاسم</label>
                </div>
                <input type="text" name="st_name1" id="st_name1">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="st_name2">الأب</label>
                </div>
                <input type="text" name="st_name2" id="st_name2">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="st_name3">الجد</label>
                </div>
                <input type="text" name="st_name3" id="st_name3">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="st_name4">العائلة</label>
                </div>
                <input type="text" name="st_name4" id="st_name4">
            </div>
            <div class="four columns omega">
                <input name="submit" type="submit" class="button-primary" id="submit" value="بحث عن <?php echo get_gender_label('st', ''); ?>"/>
            </div>
        </form>
    </div>
    <div class="content">
        <form name="form2" method="post" action="search_t_resault.php">
            <div class="FieldsTitle">بحث عن <?php echo get_gender_label('t', ''); ?></div>
            <div class="three columns alpha">
                <div class="LabelContainer">
                    <label for="t_name1">الاسم</label>
                </div>
                <input type="text" name="t_name1" id="t_name1">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="t_name2">الأب</label>
                </div>
                <input type="text" name="t_name2" id="t_name2">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="t_name3">الجد</label>
                </div>
                <input type="text" name="t_name3" id="t_name3">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="t_name4">العائلة</label>
                </div>
                <input type="text" name="t_name4" id="t_name4">
            </div>
            <div class="four columns omega">
                <input name="submit" type="submit" class="button-primary" id="submit" value="بحث عن <?php echo get_gender_label('t', ''); ?>"/>
            </div>
        </form>
    </div>

    <div class="content">
        <form name="form3" method="post" action="search_e_result.php">
            <div class="FieldsTitle">بحث عن <?php echo get_gender_label('emp', ''); ?></div>
            <div class="three columns alpha">
                <div class="LabelContainer">
                    <label for="e_name1">الاسم</label>
                </div>
                <input type="text" name="e_name1" id="e_name1">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="e_name2">الأب</label>
                </div>
                <input type="text" name="e_name2" id="e_name2">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="e_name3">الجد</label>
                </div>
                <input type="text" name="e_name3" id="e_name3">
            </div>
            <div class="three columns">
                <div class="LabelContainer">
                    <label for="e_name4">العائلة</label>
                </div>
                <input type="text" name="e_name4" id="e_name4">
            </div>
            <div class="four columns omega">
                <input name="submit" type="submit" class="button-primary" id="submit" value="بحث عن <?php echo get_gender_label('emp', ''); ?>"/>
            </div>
        </form>
    </div>
    <!--content-->
    <?php include('../templates/footer.php');
} else {
    include('../templates/restrict_msg.php');
}