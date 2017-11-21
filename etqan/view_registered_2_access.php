<?php require_once('../functions.php');
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

$Date1_Rs1 = Input::get('Date1') ? 'and f.RDate>=' . str_replace('/', '', Input::get('Date1')) : '';
$Date2_Rs1 = Input::get('Date2') ? 'and f.RDate<=' . str_replace('/', '', Input::get('Date2')) : '';

$sql_sex = sql_sex('e.sex');
$query_ReRegistered = "SELECT f.ErtiqaID,f.MsbkhID,f.SchoolLevelID,f.HalakahID,s.StBurthDate,concat_ws(' ',t.TName1,t.TName2,t.TName3,t.TName4) as t_name,s.StName1,s.StName2,s.StName3,s.StName4,e.arabic_name,h.HName
FROM ms_etqan_rgstr f
left join 0_students s on f.StID = s.StID
left join 0_users e on f.EdarahID = e.id
left join 0_halakat h on f.HalakahID = h.AutoNo
left join 0_teachers t on f.TeacherID = t.TID
where f.AutoNo>0 $sql_sex $Date1_Rs1  $Date2_Rs1
ORDER BY f.MsbkhID,f.EdarahID,f.HalakahID";
$ReRegistered = mysqli_query($localhost, $query_ReRegistered) or die(mysqli_error($localhost));
$row_ReRegistered = mysqli_fetch_assoc($ReRegistered);
$totalRows_ReRegistered = mysqli_num_rows($ReRegistered);

?>
<?php include('../templates/header1.php'); ?>
    <style>
        #printButton {
            text-align: center;
            margin: 15px 0px;
            border-top: 2px dashed #2B9FBB;
            padding-top: 5px
        }
    </style>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/cupertino/jquery-ui.css"/>
<?php $PageTitle = 'نسخ المتسابقين إلى قاعدة بيانات Access'; ?>
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
                    <input name="submit" type="submit" class="button-primary" id="submit" value="استعلام"/>
                </div>
                <input type="hidden" name="MM_show" value="form1">
            </form>
        </div>
    </div>
    <div class="content CSSTableGenerator">
        <?php if (isset($Date1_Rs1)) { ?>
            <div class="FieldsTitle">المسجلون في مسابقة الفهد خلال الفترة
                من <?php echo((Input::get('Date1') != '') ? Input::get('Date1') : '(بداية النظام الإلكتروني في 1434/08/29 هـ)'); ?>
                إلى <?php echo((Input::get('Date2') != '') ? Input::get('Date2') : '(تاريخ اليوم)'); ?> </div>
        <?php } ?>
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
                    <td><?php echo get_array_1($etqan_msbkh_type, $row_ReRegistered['MsbkhID']); ?></td>
                    <td><?php echo $row_ReRegistered['MsbkhID']; ?></td>
                    <td><?php echo StringToDate($row_ReRegistered['StBurthDate']); ?></td>
                </tr>
            <?php } while ($row_ReRegistered = mysqli_fetch_assoc($ReRegistered)); ?>
        </table>
    </div>
    <div id="printButton">
        <input class="button-primary" type="button" value="طباعة" onclick="window.print()">
    </div>

<?php include('../templates/footer.php'); ?>