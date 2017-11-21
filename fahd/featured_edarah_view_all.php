<?php require_once('../functions.php'); ?>
<?php require_once('fahd_functions.php'); ?>
<?php $PageTitle = 'المسجلون في مسابقة الإدارة المتميزة'; ?>
<?php if (login_check('admin,ms,t3lem,edarh,alaqat') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $Date1_Rs1 = 'and f.f_e_date>=' . str_replace('/', '', $_SESSION ['default_start_date']);
    if (isset($_POST['Date1'])) {
        if (Input::get('Date1') != null) {
            $Date1_Rs1 = 'and f.f_e_date>=' . str_replace('/', '', Input::get('Date1'));
        }
    }
    $Date2_Rs1 = 'and f.f_e_date<=' . str_replace('/', '', $_SESSION ['default_end_date']);
    if (isset($_POST['Date2'])) {
        if (Input::get('Date2') != null) {
            $Date2_Rs1 = 'and f.f_e_date<=' . str_replace('/', '', Input::get('Date2'));
        }
    }
    $sql_sex = sql_sex('e.sex');
    $edarh = '';
    if ($_SESSION['user_group'] == 'edarh') {
        $edarh = ' AND edarah_id=' . $_SESSION['user_id'] . ' ';
    }
    $query_ReRegistered = "SELECT f.id,f.total_e,f.f_e_date,f.edarah_id,
                            e.arabic_name ,f.is_approved
                            FROM ms_fahd_featured_edarah f
                            left join 0_users e on f.edarah_id = e.id
                            where f.id>0  $Date1_Rs1  $Date2_Rs1 $sql_sex $edarh
                            ORDER BY f.total_e DESC";


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
                * للاستعلام عن جميع الإدارات المسجلة، اترك التواريخ فارغة
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
            المسجلون في مسابقة الإدارة
            المتميزة <?php echo((Input::get('Date1') == '' && Input::get('Date2') == '') ? ' للعام الدراسي ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?> خلال
            الفترة من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
            إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </div>
        <div class="CSSTableGenerator">
            <table>
                <tr>
                    <td>م</td>
                    <td>المجمع</td>
                    <td>تاريخ التسجيل</td>
                    <td>الدرجة الكبرى</td>
                    <td>الاعتماد</td>
                    <td>تعديل</td>

                </tr>
                <?php
                $i = 1;
                if ($totalRows_ReRegistered) {
                    do { ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row_ReRegistered['arabic_name']; ?></td>
                            <td><?php echo StringToDate($row_ReRegistered['f_e_date']); ?></td>
                            <td><?php echo $row_ReRegistered['total_e']; ?></td>
                            <td <?php if ((int)$row_ReRegistered['is_approved'] == 1) echo 'class="bg_green"' ?>>
                                <?php echo ((int)$row_ReRegistered['is_approved'] == 1) ? 'معتمد' : 'غير معتمد'; ?>
                            </td>
                            <td><?php if (login_check('admin,ms')) { ?>
                                <a href="/sys/fahd/featured_edarah_edit.php?id=<?php echo $row_ReRegistered['id']; ?>">تعديل</a></td>
                            <?php } ?>
                        </tr>
                        <?php $i++;
                    } while ($row_ReRegistered = mysqli_fetch_assoc($ReRegistered));
                } else {
                    ?>
                    <tr>
                        <td colspan="6"><h1>لا يوجد بيانات حسب التاريخ المحدد</h1></td>
                    </tr>
                <?php }
                ?>
            </table>
        </div>
        <div id="printButton">
            <input class="button-primary" type="button" value="طباعة" onclick="window.print()">
        </div>

    </div><!--content-->
    <?php include('../templates/footer.php'); ?>
    <?php
    mysqli_free_result($ReRegistered);
    ?>
<?php } else {
    include('../templates/restrict_msg.php');
} ?>