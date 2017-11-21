<?php require_once('../functions.php'); ?>
<?php require_once('fahd_functions.php'); ?>
<?php $PageTitle = 'المسجلون في مسابقة المعلم المتميز'; ?>
<?php if (login_check('admin,ms,t3lem') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $Date1_Rs1 = 'and f.f_t_date>=' . str_replace('/', '', $_SESSION ['default_start_date']);
    if (isset($_POST['Date1'])) {
        if (Input::get('Date1') != null) {
            $Date1_Rs1 = 'and f.f_t_date>=' . str_replace('/', '', Input::get('Date1'));
        }
    }
    $Date2_Rs1 = 'and f.f_t_date<=' . str_replace('/', '', $_SESSION ['default_end_date']);
    if (isset($_POST['Date2'])) {
        if (Input::get('Date2') != null) {
            $Date2_Rs1 = 'and f.f_t_date<=' . str_replace('/', '', Input::get('Date2'));
        }
    }
    $sql_sex = sql_sex('e.sex');
    $query_ReRegistered = "SELECT f.*,concat_ws(' ',t.TName1,t.TName2,t.TName3,t.TName4) as t_name,e.arabic_name
FROM `ms_fahd_featured_teacher` f
left join 0_teachers t on f.`teacher_id` = t.`TID`
left join 0_users e on f.`t_edarah` = e.`id`
where f.auto_no>0  $Date1_Rs1  $Date2_Rs1 $sql_sex
ORDER BY f.full_degree DESC";
    $ReRegistered = mysqli_query($localhost, $query_ReRegistered) or die(mysqli_error($localhost));
    $row_ReRegistered = mysqli_fetch_assoc($ReRegistered);
    $totalRows_ReRegistered = mysqli_num_rows($ReRegistered);

    ?>
    <?php include('../templates/header1.php'); ?>
    <style>
        @media print {
            .FieldsTitle {
                text-align: center;
            }

            #content {
                direction: rtl;
            }

            #WrapperFull, #content {
                background: none !important;
            }

            #printButton, #header, #PageTitle, #footer, #hideInPrint, #header0 {
                display: none !important;
            }

            body {
                direction: rtl;
                text-align: center;
                font-family: 'al_jass_zq', arial, tahoma;
                font-size: 16px;
                white-space: nowrap;
            }
        }

        #printButton {
            text-align: center;
            margin: 15px 0px;
            border-top: 2px dashed #2B9FBB;
            padding-top: 5px;
        }

        .container {
            width: 100%;
        }
    </style>
    <title><?php echo $PageTitle; ?></title>
    </head>
    <body>
    <?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle">
        <?php echo $PageTitle; ?>
    </div><!--PageTitle-->

    <div id="hideInPrint">
        <div class="content">
            <P>
                * للاستعلام عن جميع المعلمين المسجلين، اترك التواريخ فارغة
                <br>
                * يمكنك الاستعلام بالتاريخ الأول فقط أو الثاني فقط أو بالجميع
            </P>
            <form name="form1" method="post" action="<?php echo $editFormAction; ?>">
                <div class="four columns alpha">
                    <div class="LabelContainer">
                        <label for="Date1">التاريخ الأول</label>
                    </div>
                    <input type="text" name="Date1" id="Date1" zezo_date="true">
                </div>
                <div class="four columns">
                    <div class="LabelContainer">
                        <label for="Date2">التاريخ الثاني</label>
                    </div>
                    <input type="text" name="Date2" id="Date2" zezo_date="true">
                </div>
                <div class="four columns">
                    <input name="submit" type="submit" class="full-width button-primary" id="submit" value="استعلام"/>
                </div>
                <input type="hidden" name="MM_show" value="form1">
            </form>
        </div>
    </div>
    <div class="content">
        <div class="FieldsTitle">
            المسجلون في مسابقة المعلم المتميز <?php echo((Input::get('Date1') == '' && Input::get('Date2') == '') ? ' للعام الدراسي ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?>
            خلال الفترة من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
            إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </div>
        <div class="CSSTableGenerator">
            <?php
            $i = 1;
            $f_t_array = array('1a', '2a', '2b', '3a', '3b', '4a', '4b', '5a', '5b', '5c', '6a', '7a', '8a', '9a', '10a', '11a', '12a', '13a', '14a');
            $f_t_label_array = array('تقييم', 'ارتقاء', 'براعم', 'أفراد', 'حلق', 'متأهل', 'فائز', 'جزرية', 'تحفة', 'تطويرية', 'اجتماعات', 'منهجية', 'المرتقى', 'برنامج', 'خارجية', 'فكرة', 'اجازة', 'شاطبية', 'خارجية');
            $ci = count($f_t_array);
            ?>
            <table>
                <tr>
                    <td>م</td>
                    <td>الاسم</td>
                    <td>المجمع</td>
                    <td>تاريخ التسجيل</td>
                    <?php
                    for ($z = 0; $z < $ci; $z++) {
                        echo '<td>' . $f_t_label_array[$z] . '</td>';
                    }
                    ?>
                    <td>الاجمالي</td>
                    <td>المزيد</td>
                </tr>
                <?php do { ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row_ReRegistered['t_name']; ?></td>
                        <td><?php echo $row_ReRegistered['arabic_name']; ?></td>
                        <td><?php echo StringToDate($row_ReRegistered['f_t_date']); ?></td>
                        <?php
                        for ($z = 0; $z < $ci; $z++) {
                            echo '<td>', $row_ReRegistered['f_' . $f_t_array[$z] . '_d'], '</td>';
                        }
                        ?>
                        <td><?php echo $row_ReRegistered['full_degree']; ?></td>
                        <td><a href="/sys/fahd/featured_teacher_edit.php?auto_no=<?php echo $row_ReRegistered['auto_no']; ?>">المزيد</a></td>
                    </tr>
                    <?php $i++;
                } while ($row_ReRegistered = mysqli_fetch_assoc($ReRegistered)); ?>
            </table>
        </div>
        <div id="printButton">
            <input class="button-primary" type="button" value="طباعة" onclick="window.print()">
        </div>
    </div><!--content-->
    <?php include('../templates/footer.php');

} else {
    include('../templates/restrict_msg.php');
}