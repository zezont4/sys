<?php require_once('../functions.php'); ?>
<?php $PageTitle = 'الطلاب المسجلون في مسابقة الفهد'; ?>
<?php if (login_check('admin,ms,edarh,alaqat') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    $Date1_Rs1 = 'and RDate>=' . $_SESSION ['default_start_date'];
    if (isset($_GET['Date1'])) {
        if (Input::get('Date1') != null) {
            $Date1_Rs1 = 'and RDate>=' . str_replace('/', '', Input::get('Date1'));
        }
    }
    $Date2_Rs1 = $Date2_Rs1 = 'and RDate<=' . $_SESSION ['default_end_date'];
    if (isset($_GET['Date2'])) {
        if (Input::get('Date2') != null) {
            $Date2_Rs1 = 'and RDate<=' . str_replace('/', '', Input::get('Date2'));
        }
    }
    $sql_sex = sql_sex('u.sex');
    if ($_SESSION['user_group'] != 'edarh') {
        $query_ReRegistered = sprintf("SELECT *
FROM ms_fahd_rgstr f
LEFT JOIN 0_users u on f.EdarahID = u.id
where f.AutoNo>0 %s %s %s
ORDER BY f.EdarahID,f.MsbkhID,f.HalakahID",
            $Date1_Rs1,
            $Date2_Rs1,
            $sql_sex);
    } else {
        $query_ReRegistered = sprintf("SELECT *
FROM ms_fahd_rgstr
WHERE EdarahID = %s %s %s
ORDER BY EdarahID,MsbkhID,HalakahID",
            $_SESSION['user_id'],
            $Date1_Rs1,
            $Date2_Rs1);
    }

    $ReRegistered = mysqli_query($localhost, $query_ReRegistered) or die('ReRegistered : ' . mysqli_error($localhost));
    $row_ReRegistered = mysqli_fetch_assoc($ReRegistered);
    $totalRows_ReRegistered = mysqli_num_rows($ReRegistered);

    include('../templates/header1.php'); ?>
    <script type="text/javascript">
        function confirm_delete() {
            return confirm('هل تريد حذف الاختبار؟');
        }
    </script>
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

    <div class="content">
        <div id="hideInPrint">
            <P>
                * للاستعلام عن السنة الدراسية الحالية، اترك التواريخ فارغة
                <br>
                * يمكنك الاستعلام بالتاريخ الأول فقط أو الثاني فقط أو بالجميع
            </P>
            <form name="form1" method="get" action="<?php echo $editFormAction; ?>">
                <div class="LabelAndFieldContainer">
                    <div class="LabelContainer">
                        <label for="Date1">التاريخ الأول</label>
                    </div>
                    <input type="text" name="Date1" id="Date1" zezo_date="true" value="<?php echo Input::get('Date1'); ?>">
                </div>
                <div class="LabelAndFieldContainer">
                    <div class="LabelContainer">
                        <label for="Date2">التاريخ الثاني</label>
                    </div>
                    <input type="text" name="Date2" id="Date2" zezo_date="true" value="<?php echo Input::get('Date2'); ?>">
                </div>
                <div class="LabelAndFieldContainer">
                    <div class="LabelContainer"></div>
                    <div class="FieldsButton">
                        <input name="submit" type="submit" class="button-primary" id="submit" value="استعلام"/>
                    </div>
                </div>
                <input type="hidden" name="MM_show" value="form1">
            </form>
            <br>
            <hr>
        </div>
        <?php if (isset($Date1_Rs1)) { ?>
            <div class="FieldsTitle">المسجلون في مسابقة
                الفهد <?php echo((Input::get('Date1') == '' && Input::get('Date2') == '') ? ' ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?>
                خلال الفترة من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
                إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </div>
        <?php } ?>
        <hr>
        <div class="CSSTableGenerator">
            <table border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td>م</td>
                    <td>السجل المدني</td>
                    <td>اسم <?php echo get_gender_label('st', 'ال'); ?></td>
                    <td>االحلقة</td>
                    <td>المجمع</td>
                    <td>نوع المسابقة</td>
                    <td>نوع المتسابق</td>
                    <td>السنة الدراسية</td>
                    <td>حذف</td>
                </tr>
                <?php if ($totalRows_ReRegistered > 0) { // Show if recordset not empty ?>
                    <?php $i = 1;
                    do { ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row_ReRegistered['StID']; ?></td>
                            <td><?php echo get_student_name($row_ReRegistered['StID']); ?></td>
                            <td><?php echo get_halakah_name($row_ReRegistered['HalakahID']); ?></td>
                            <td><?php echo get_edarah_name($row_ReRegistered['EdarahID']); ?></td>
                            <td><?php echo get_array_1($fahd_MsbkhType, $row_ReRegistered['MsbkhID']); ?></td>
                            <td><?php echo get_array_1($MtsabikType, $row_ReRegistered['st_type']); ?></td>
                            <td><?php echo get_array_1($SchoolLevelNameAll, $row_ReRegistered['SchoolLevelID']); ?></td>
                            <td><?php if (login_check('admin,ms')) { ?>
                                    <a onclick="return confirm_delete()" href="delete.php?AutoNo=<?php echo $row_ReRegistered['AutoNo']; ?>">X</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $i++;
                    } while ($row_ReRegistered = mysqli_fetch_assoc($ReRegistered)); ?>
                <?php } else { ?>
                    <td colspan="8">لايوجد طلاب مسجلون في مسابقة الفهد حسب التاريخ المحدد</td>
                <?php } ?>
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