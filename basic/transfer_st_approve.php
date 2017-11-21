<?php require_once('../Connections/localhost.php');
require_once('../functions.php');
require_once '../secure/functions.php';
sec_session_start();
$PageTitle = 'طلبات نقل ' . get_gender_label('sts', 'ال');
$_token = isset($_GET['_token']) ? $_GET['_token'] : '';
if (login_check("admin,t3lem") == true || $_token == 'STW6gPk8QrrZdF1gW2tO') {

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}


mysqli_select_db($localhost, $database_localhost);
if ((isset($_POST["mm_update"])) && ($_POST["mm_update"] == "form1")) {
    $query_rs_approve = 'select * from 0_student_transfer where is_transferred=0';
    $rs_approve = mysqli_query($localhost, $query_rs_approve) or die('$query_rs_approve 1' . mysqli_error($localhost));
    $row_rs_approve = mysqli_fetch_assoc($rs_approve);
    $total_rows_approve = mysqli_num_rows($rs_approve);
    if ($total_rows_approve) {
        $i = 0;
        $transfer_result = [];

        do {
            $update_student = sprintf("UPDATE `0_students` SET
            StName1=%s,StName2=%s,StName3=%s,StName4=%s,
            StBurthDate=%s,StMobileNo=%s,FatherMobileNo=%s,guardian_name=%s,
            StEdarah=%s,StHalaqah=%s,`hide`=0,school_level=%s,nationality=%s WHERE StID=%s",
                GetSQLValueString($row_rs_approve['StName1'], 'text'),
                GetSQLValueString($row_rs_approve['StName2'], 'text'),
                GetSQLValueString($row_rs_approve['StName3'], 'text'),
                GetSQLValueString($row_rs_approve['StName4'], 'text'),
                GetSQLValueString(str_replace('/', '', $row_rs_approve['StBurthDate']), 'int'),
                GetSQLValueString($row_rs_approve['StMobileNo'], 'text'),
                GetSQLValueString($row_rs_approve['FatherMobileNo'], 'text'),
                GetSQLValueString($row_rs_approve['guardian_name'], 'text'),
                GetSQLValueString($row_rs_approve['StEdarah'], 'int'),
                GetSQLValueString($row_rs_approve['StHalaqah'], 'int'),
                GetSQLValueString($row_rs_approve['school_level'], 'int'),
                GetSQLValueString($row_rs_approve['nationality'], 'int'),
                GetSQLValueString($row_rs_approve['StID'], 'double'));

            $result_update_student = mysqli_query($localhost, $update_student) or die(mysqli_error('$result_update_student : ' . $localhost));
            $update_transfer = sprintf("UPDATE `0_student_transfer` SET is_transferred=1 where StID=%s", $row_rs_approve['StID']);
            $result_update_transfer = mysqli_query($localhost, $update_transfer) or die(mysqli_error('$result_update_transfer : ' . $localhost));

//        echo $update_student;
//        exit;
            $transfer_result[$i][0] = $row_rs_approve['StName1'] . ' ' . $row_rs_approve['StName2'] . ' ' . $row_rs_approve['StName3'] . ' ' . $row_rs_approve['StName4'];

            if ($result_update_student) {
                $transfer_result[$i][1] = 'تم النقل';
            } else {
                $transfer_result[$i][1] = 'لم يتم النقل';
            }
            if ($result_update_transfer) {
                $transfer_result[$i][2] = 'تم حذف الطلب واعتماده';
            } else {
                $transfer_result[$i][2] = 'لم يتم حذف الطلب واعتماده';
            }
            $i++;
        } while ($row_rs_approve = mysqli_fetch_assoc($rs_approve));
    }
}


$query_rs_transfer = 'SELECT tr.register_date,tr.id, 
concat_ws(" ",tr.StName1,tr.StName2,tr.StName3,tr.StName4) new_name,
tr.is_transferred,
u_from.arabic_name transfer_from,
u_to.arabic_name transfer_to ,
concat_ws(" ",s.StName1,s.StName2,s.StName3,s.StName4) old_name
FROM 0_student_transfer tr 
LEFT JOIN  0_users u_from ON u_from.id = tr.transfer_from
LEFT JOIN  0_users u_to ON u_to.id = tr.StEdarah
LEFT JOIN 0_students s ON s.StID = tr.StID
where tr.is_transferred=0 ORDER BY tr.id DESC';
//    echo $query_rs_transfer;

$rs_transfer = mysqli_query($localhost, $query_rs_transfer) or die('$query_rs_transfer 1' . mysqli_error($localhost));
$row_rs_transfer = mysqli_fetch_assoc($rs_transfer);
$total_rows_transfer = mysqli_num_rows($rs_transfer);

?>
<?php include('../templates/header1.php'); ?>
<style>
    .different_name td {
        background-color: #f6f9de;
    }
</style>
<title><?php echo $PageTitle; ?></title>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->


<div class="content lp">
    <form name="form1" method="post" action="<?php echo $editFormAction; ?>">
        <div class="FieldsTitle">السجل المدني <?php echo get_gender_label('st', 'لل'); ?></div>
        <div class="five columns alpah">&nbsp</div>
        <div class="six columns">
            <input type="submit" class="button-primary" value="اعتماد جميع طلبات النقل">
        </div>
        <div class="three columns">&nbsp</div>
        <div class="one columns omega">
            <a class="button-primary" style="margin-top: 31px; background: #274ce8; "
               href="<?php echo $editFormAction; ?>">تحديث</a>
        </div>
        <input type="hidden" name="mm_update" value="form1">
        <input type="hidden" name="_token" value="<?php echo $_token; ?>">
    </form>
</div>
<div class="content CSSTableGenerator">
    <?php if ($total_rows_transfer) { ?>
        <table>
            <caption>طلبات النقل</caption>
            <tr>
                <td>تاريخ الطلب</td>
                <td>الاسم السابق</td>
                <td>الاسم الحالي</td>
                <td>من</td>
                <td>إلى</td>
            </tr>
            <?php do { ?>
                <tr <?php echo $row_rs_transfer['old_name'] <> $row_rs_transfer['new_name'] ? 'class="different_name"' : ''; ?>>
                    <td><?php echo StringToDate($row_rs_transfer['register_date']); ?></td>
                    <td><?php echo $row_rs_transfer['old_name']; ?></td>
                    <td><?php echo $row_rs_transfer['new_name']; ?></td>
                    <td><?php echo $row_rs_transfer['transfer_from']; ?></td>
                    <td><?php echo $row_rs_transfer['transfer_to']; ?></td>
                </tr>
            <?php } while ($row_rs_transfer = mysqli_fetch_assoc($rs_transfer)); ?>
        </table>
    <?php } else { ?>
        <p>لا توجد طلبات نقل</p>
    <?php } ?>
</div><!--content-->

<?php include('../templates/footer.php'); ?>

<?php } else {
    include('../templates/restrict_msg.php');
} ?>

