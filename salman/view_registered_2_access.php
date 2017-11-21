<?php require_once('../functions.php'); ?>
<?php $PageTitle = 'نسخ المتسابقين إلى قاعدة بيانات Access'; ?>
<?php if (login_check('admin,ms') == true) { ?>
    <?php
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }
    $default_start_date = isset($_SESSION['default_start_date']) ? $_SESSION['default_start_date'] : '';
    $default_end_date = isset($_SESSION['default_end_date']) ? $_SESSION['default_end_date'] : '';

    $Date1_Rs1 = Input::get('Date1')
        ? 'and RDate>=' . str_replace('/', '', Input::get('Date1'))
        : 'and RDate>=' . $default_start_date;

    $Date2_Rs1 = Input::get('Date2')
        ? 'and RDate<=' . str_replace('/', '', Input::get('Date2'))
        : 'and RDate<=' . $default_end_date;

    $sql_sex = sql_sex('e.sex');
    $query_ReRegistered = sprintf("SELECT fhd.ErtiqaID,fhd.MsbkhID,fhd.SchoolLevelID,fhd.HalakahID,
concat_ws(' ',t.TName1,t.TName2,t.TName3,t.TName4) AS t_name,
s.StName1,s.StName2,s.StName3,s.StName4,s.StMobileNo,s.FatherMobileNo,
concat_ws('/',substr(s.StBurthDate ,1,4),substr(s.StBurthDate ,5,2),substr(s.StBurthDate ,7,2)) AS O_BurthDate,
e.arabic_name,
h.HName
FROM ms_salman_rgstr AS fhd
LEFT JOIN 0_students AS s ON fhd.StID = s.StID
LEFT JOIN 0_users e ON fhd.EdarahID = e.id
LEFT JOIN 0_halakat AS h ON fhd.HalakahID = h.AutoNo
LEFT JOIN 0_teachers AS t ON fhd.TeacherID = t.TID
WHERE fhd.AutoNo>0 %s %s %s
ORDER BY fhd.MsbkhID,fhd.EdarahID,fhd.HalakahID", $sql_sex, $Date1_Rs1, $Date2_Rs1);

    $ReRegistered = mysqli_query($localhost, $query_ReRegistered) or die(mysqli_error($localhost));
    $row_ReRegistered = mysqli_fetch_assoc($ReRegistered);
    $totalRows_ReRegistered = mysqli_num_rows($ReRegistered);

    ?>
    <?php include('../templates/header1.php'); ?>
    <style type="text/css">
        .container {
            width: 100%;
        }

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
            margin: 1.5em 0;
            border-top: 0.2em dashed #2B9FBB;
            padding-top: 0.5em;
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
                * للاستعلام عن جميع الطلاب المسجلين، اترك التواريخ فارغة
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
            المسجلون في مسابقة الملك
            سلمان <?php echo((Input::get('Date1') == '' && Input::get('Date2') == '') ? ' للعام الدراسي ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?>
            خلال الفترة من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
            إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?></div>
        <div class="CSSTableGenerator">
            <table>
                <tr>
                    <td>الاسم</td>
                    <td>اسم الأب</td>
                    <td>اسم الجد</td>
                    <td>العائلة</td>
                    <td>المجمع</td>
                    <td>الحلقة</td>
                    <td>رقم الحلقة</td>
                    <td>المعلم</td>
                    <td>السنة الدراسية</td>
                    <td>آخر مرتقى</td>
                    <td>نوع المسابقة</td>
                    <td>رمز المسابقة</td>
                    <td>جوال الطالب</td>
                    <td>جوال الولي</td>
                    <td>تاريخ الميلاد</td>
                </tr>
                <?php do { ?>
                    <tr>
                        <td><?php echo $row_ReRegistered['StName1']; ?></td>
                        <td><?php echo $row_ReRegistered['StName2']; ?></td>
                        <td><?php echo $row_ReRegistered['StName3']; ?></td>
                        <td><?php echo $row_ReRegistered['StName4']; ?></td>
                        <td><?php echo $row_ReRegistered['arabic_name']; ?></td>
                        <td><?php echo $row_ReRegistered['HName']; ?></td>
                        <td><?php echo $row_ReRegistered['HalakahID']; ?></td>
                        <td><?php echo $row_ReRegistered['t_name']; ?></td>
                        <td><?php echo get_array_1($SchoolLevelNameAll, $row_ReRegistered['SchoolLevelID']); ?></td>
                        <td><?php echo get_array_1($murtaqaName, $row_ReRegistered['ErtiqaID']); ?></td>
                        <td><?php echo get_array_1($salman_msbkh_type, $row_ReRegistered['MsbkhID']); ?></td>
                        <td><?php echo $row_ReRegistered['MsbkhID']; ?></td>
                        <td><?php echo $row_ReRegistered['StMobileNo']; ?></td>
                        <td><?php echo $row_ReRegistered['FatherMobileNo']; ?></td>
                        <td><?php echo $row_ReRegistered['O_BurthDate']; ?></td>
                    </tr>
                <?php } while ($row_ReRegistered = mysqli_fetch_assoc($ReRegistered)); ?>
            </table>
        </div>
        <div id="printButton">
            <input class="button-primary" type="button" value="طباعة" onClick="window.print()">
        </div>

    </div><!--content-->
    <?php include('../templates/footer.php'); ?>
    <?php
    mysqli_free_result($ReRegistered);
    ?>
<?php } else {
    include('../templates/restrict_msg.php');
} ?>