<?php require_once('../functions.php');
$PageTitle = 'طلب نقل ' . get_gender_label('st');
if (login_check("edarh") == true) {

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}
$user_id = $_SESSION['user_id'];

$maxRows_rs_transfer = 40;
$pageNum_rs_transfer = 0;
$queryString_rs_transfer = null;
$currentPage = null;
if (isset($_GET['pageNum_rs_transfer'])) {
    $pageNum_rs_transfer = $_GET['pageNum_rs_transfer'];
}
$startRow_rs_transfer = $pageNum_rs_transfer * $maxRows_rs_transfer;

$query_rs_transfer = sprintf("SELECT s.register_date,s.id, s.StName1,s.StName2,s.StName3,s.StName4,s.note,s.is_transferred,u_from.`arabic_name` transfer_from,u_to.`arabic_name` transfer_to FROM 0_student_transfer s 
LEFT JOIN  0_users u_from ON u_from.id = s.transfer_from
LEFT JOIN  0_users u_to ON u_to.id = s.StEdarah
where s.StEdarah=%s or s.transfer_from=%s ORDER BY s.id DESC", $user_id, $user_id);
//    echo $query_rs_transfer;
$query_limit_rs_transfer = sprintf("%s LIMIT %d,%d", $query_rs_transfer, $startRow_rs_transfer, $maxRows_rs_transfer);

$rs_transfer = mysqli_query($localhost, $query_limit_rs_transfer) or die('$query_rs_transfer 1' . mysqli_error($localhost));
$row_rs_transfer = mysqli_fetch_assoc($rs_transfer);
if (isset($_GET['totalRows_rs_transfer'])) {
    $totalRows_rs_transfer = $_GET['totalRows_rs_transfer'];
} else {
    $all_rs_transfer = mysqli_query($localhost, $query_rs_transfer) or die('$query_rs_transfer 2' . mysqli_error($localhost));
    $totalRows_rs_transfer = mysqli_num_rows($all_rs_transfer);
}
$totalPages_rs_transfer = ceil($totalRows_rs_transfer / $maxRows_rs_transfer) - 1;

?>
<?php include('../templates/header1.php'); ?>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->

<div class="content lp">
    <div class="FieldsTitle">السجل المدني <?php echo get_gender_label('st', 'لل'); ?></div>
    <form method="get" name="form1" id="form1" action="transfer_st_add.php" data-validate="parsley">
        <div class="four columns alpha">
            <div class="LabelContainer">
                <label for="StID">السجل المدني</label>
            </div>
            <input type="text" name="StID" id="StID" value="" data-required="true">
        </div>
        <div id="duplicate_student" style="display: none">
            <br class="clear"/>
            <div class="sixteen columns alpha omega"
                 style="color: darkgreen; padding: 10px 5px;border-bottom: 1px solid #ddd">
                <p id="msg"></p>
            </div>
            <br class="clear"/>
        </div>

        <br class="clear">
        <div class="four columns omega left">
            <input type="submit" class="button-primary" value="تقديم طلب النقل">
        </div>
        <input type="hidden" name="MM_insert" value="form1">
    </form>

</div>
<div class="content CSSTableGenerator">
    <table>
        <caption>عمليات النقل السابقة (من وإلى) <?php echo get_gender_label('e', 'ال'); ?></caption>
        <tr>
            <td>تاريخ الطلب</td>
            <td>الاسم</td>
            <td>من</td>
            <td>إلى</td>
            <td>حالة النقل</td>
        </tr>
        <?php do {
            $canceled_style = $row_rs_transfer['is_transferred'] == 2 ? 'style="background-color: #fffed0"' : '';
            ?>
            <tr <?php echo $canceled_style;?>>
                <td><?php echo StringToDate($row_rs_transfer['register_date']); ?></td>
                <td><?php echo $row_rs_transfer['StName1'] . ' ' . $row_rs_transfer['StName2'] . ' ' . $row_rs_transfer['StName3'] . ' ' . $row_rs_transfer['StName4']; ?></td>
                <td><?php echo $row_rs_transfer['transfer_from']; ?></td>
                <td><?php echo $row_rs_transfer['transfer_to']; ?></td>
                <td style="padding: 4px 0 0 0px"><img src="/sys/_images/transfer_<?php echo $row_rs_transfer['is_transferred']; ?>.png" width="24" height="24"></td>
            </tr>
            <?php
            if ($row_rs_transfer['is_transferred'] == 2) {
                ?>
                <tr <?php echo $canceled_style;?>>
                    <td colspan="5" style="color: red">سبب رفض النقل : <br><?php echo nl2br($row_rs_transfer['note']);?></td>
                </tr>
            <?php }
        } while ($row_rs_transfer = mysqli_fetch_assoc($rs_transfer)); ?>
    </table>
    <br/>
    <div class="button-group">

        <?php if ($pageNum_rs_transfer > 0) { // Show if not first page ?>
            <a title="الصفحة الأولى" class="button-primary"
               href="<?php printf("%s?pageNum_rs_transfer=%d%s", $currentPage, 0, $queryString_rs_transfer); ?>"
               tabindex="-1"> << </a>
        <?php } else { // Show if not first page ?>
            <a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
        <?php } ?>

        <?php if ($pageNum_rs_transfer > 0) { // Show if not first page ?>
            <a title="السابق" class="button-primary"
               href="<?php printf("%s?pageNum_rs_transfer=%d%s", $currentPage, max(0, $pageNum_rs_transfer - 1), $queryString_rs_transfer); ?>"
               tabindex="-1"> < </a>
        <?php } else { // Show if not first page ?>
            <a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
        <?php } // Show if not first page ?>

        <?php if ($pageNum_rs_transfer < $totalPages_rs_transfer) { // Show if not last page ?>
            <a title="التالي" class="button-primary"
               href="<?php printf("%s?pageNum_rs_transfer=%d%s", $currentPage, min($totalPages_rs_transfer, $pageNum_rs_transfer + 1), $queryString_rs_transfer); ?>"
               tabindex="-1"> > </a>
        <?php } else { // Show if not first page ?>
            <a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
        <?php } // Show if not last page ?>

        <?php if ($pageNum_rs_transfer < $totalPages_rs_transfer) { // Show if not last page ?>
            <a title="الصفحة الأخيرة" class="button-primary"
               href="<?php printf("%s?pageNum_rs_transfer=%d%s", $currentPage, $totalPages_rs_transfer, $queryString_rs_transfer); ?>"
               tabindex="-1"> >> </a>
        <?php } else { // Show if not first page ?>
            <a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
        <?php } // Show if not last page ?>
    </div>
    <br>
    السجلات <?php echo($startRow_rs_transfer + 1) ?>
    إلى <?php echo min($startRow_rs_transfer + $maxRows_rs_transfer, $totalRows_rs_transfer) ?>
    من <?php echo $totalRows_rs_transfer ?>
    <br/>

</div><!--content-->
<?php include('../templates/footer.php'); ?>

<?php } else {
    include('../templates/restrict_msg.php');
} ?>
<script>
    var st_id = $('#StID');
    st_id.on('keyup blur change', function (e) {
        var st_id_char_count = st_id.val().length;

        if (st_id_char_count == 10) {
            $.get("/sys/basic/ajax_student_exists.php", {
                StID: st_id.val()
            })
                .done(function (data) {
                    if (data) {
                        var msg = [
                            'هذا السجل المدني تابع لـ',
                            '(',
                            data.StName1,
                            data.StName2,
                            data.StName3,
                            data.StName4,
                            ')'
                        ].join(' ');
                        console.log(data.StID);
                        $('#msg').html(msg);
                        $('#duplicate_student').show();
                    } else {
                        $('#duplicate_student').hide();
                    }
                });
        } else {
            $('#duplicate_student').hide();
        }
    });
</script>
