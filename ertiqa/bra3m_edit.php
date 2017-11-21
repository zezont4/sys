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
$auto_no = "-1";
if (isset($_GET['AutoNo'])) {
    $auto_no = $_GET['AutoNo'];
}

//$editFormAction ="Register_edit.php?AutoNo=".$auto_no;
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

mysqli_select_db($localhost,$database_localhost);
$query_RSFahdExam = sprintf("SELECT * FROM er_bra3m WHERE AutoNo=%s",GetSQLValueString($auto_no,"int"));
$RSFahdExam = mysqli_query($localhost,$query_RSFahdExam)or die(mysqli_error($localhost));
$row_RSFahdExam = mysqli_fetch_assoc($RSFahdExam);
$totalRows_RSFahdExam = mysqli_num_rows($RSFahdExam);

$StID = $row_RSFahdExam['StID'];
$DarajahID = $row_RSFahdExam['DarajahID'];
$ErtiqaID = $row_RSFahdExam['ErtiqaID'];
$SchoolLevelID = $row_RSFahdExam['SchoolLevelID'];
$DDate = $row_RSFahdExam['DDate'];
//zlog($StID);
//zlog($MsbkhID);
//zlog($ErtiqaID);
//zlog($SchoolLevelID);
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    // search for dublicate musabakah ##############
    //if ($OldMsbkhID!=$dublicate_RsMsbkhID){
    //zlog("dublicate found");
    mysqli_select_db($localhost,$database_localhost);
    $query_Rsdublicate = sprintf("SELECT AutoNo FROM er_bra3m WHERE StID=%s and DarajahID>=%s and AutoNo<>%s",
                                 GetSQLValueString($StID,"double"),
                                 GetSQLValueString($_POST['DarajahID'],"int"),
                                 GetSQLValueString($auto_no,"int"));
    //zlog($query_Rsdublicate);
    $Rsdublicate = mysqli_query($localhost,$query_Rsdublicate)or die(mysqli_error($localhost));
    $row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
    $totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);
    if ($totalRows_Rsdublicate>0){
        $insertSQL = sprintf("update  er_bra3m set SchoolLevelID=%s,DDate=%s where AutoNo=%s",
                             GetSQLValueString($_POST['SchoolLevelID'],"int"),
                             GetSQLValueString(str_replace('/','',$_POST['DDate']),"int"),
                             GetSQLValueString($auto_no,"int")
                            );
        mysqli_select_db($localhost,$database_localhost);
        $Result1 = mysqli_query($localhost,$insertSQL)or die(mysqli_error($localhost));
        if ($Result1){
            $_SESSION['u5']="u5";
            //header("Location: ".$editFormAction);
            //exit;
        }
        header("Location: ".$editFormAction);
        exit;
?>

<?php include('../templates/header1.php'); ?>
<br><br><br>
<div style="direction:rtl;text-align:center;font-size:22px;">
    <p>
        لقد تم تسجيل هذه الدرجة  أو أعلى منها سابقا <?php echo get_gender_label('st','لل'); ?>!
    </p>
</div>
<?php	exit;
    }
    //}


    $insertSQL = sprintf("update  er_bra3m set DarajahID=%s,SchoolLevelID=%s,Money=%s,DDate=%s where AutoNo=%s",
                         GetSQLValueString($_POST['DarajahID'],"int"),
                         GetSQLValueString($_POST['SchoolLevelID'],"int"),
                         GetSQLValueString($_POST['Money'],"int"),
                         GetSQLValueString(str_replace('/','',$_POST['DDate']),"int"),
                         GetSQLValueString($auto_no,"int")
                        );
    mysqli_select_db($localhost,$database_localhost);
    $Result1 = mysqli_query($localhost,$insertSQL)or die(mysqli_error($localhost));
    if ($Result1){
        $_SESSION['u1']="u1";
        //header("Location: ".$editFormAction);
        //exit;
    }
    header("Location: ".$editFormAction);
    exit;

}

?>
<?php
if (isset($_SESSION['user_id'])) {
    $colname_RSEdarat = $_SESSION['user_id'];
}
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle ='تعديل درجة البراعم'; ?>
<title><?php echo $PageTitle; ?></title>
<style type="text/css">
    .FieldsButton .note {
        color: #4FA64B;
    }
</style>
<script>
    $(document).ready(function() {
        $('#DarajahID').change(function () {
            $('#Money').val(GetBra3mMoney(this.value));
        });
        //$('#DarajahID').trigger("change");
    });
</script>

</head>
<body>
    <?php include('../templates/header2.php'); ?>
    <?php include ('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->

    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
        <div class="CSSTableGenerator content lp">
            <?php if(login_check("admin,edarh") == true) { ?>
            <table>
                <caption>بيانات ال<?php echo get_gender_label('st'); ?> الأساسية  (وقت التسجيل بالمرتقى)</caption>
                <tr>
                    <td>اسم <?php echo get_gender_label('st','ال'); ?></td>
                    <td>المجمع</td>
                    <td>الحلقة</td>
                    <td>المعلم</td>
                </tr>
                <tr>
                    <td><?php echo get_student_name( $row_RSFahdExam['StID']);?></td>
                    <td><?php echo get_edarah_name( $row_RSFahdExam['EdarahID']);?></td>
                    <td><?php echo get_halakah_name( $row_RSFahdExam['HalakahID']);?></td>
                    <td><?php echo get_teacher_name( $row_RSFahdExam['TeacherID']);?></td>
                </tr>
            </table>
        </div>
        <div class="content">
            <div class="FieldsTitle">بيانات الدرجة</div>
            <input name="MM_insert" type="hidden" value="form1">
            <div class="four columns alpha">
                <div class="LabelContainer">المرحلة الدراسية</div>
                <select class="FullWidthCombo" name="SchoolLevelID" id="SchoolLevelID"  data-required="true">
                    <option VALUE>اختر المرحلة الدراسي</option>
                    <?php $ii=0; do { ?>
                    <option value="<?php echo $ii?>" <?php if($ii==$SchoolLevelID){echo selected;} ?>><?php echo $SchoolLevelName[$ii]?></option>
                    <?php $ii++; }  while ($ii<count($SchoolLevelName));?>
                </select>
            </div>
            <div class="four columns">
                <div class="LabelContainer">الدرجة</div>
                <select class="FullWidthCombo" name="DarajahID" id="DarajahID"  data-required="true">
                    <option VALUE>حدد الدرجة</option>
                    <?php $ii=1; do { ?>
                    <option value="<?php echo $ii?>" <?php if($ii==$DarajahID){echo selected;} ?>><?php echo $bra3mName[$ii]?></option>
                    <?php $ii++; }  while ($ii<6);?>
                </select>
            </div>
            <div class="four columns">
                <div class="LabelContainer">تاريخ الدرجة</div>
                <input name="DDate" type="text" id="DDate" value="<?php echo StringToDate($DDate);?>" data-required="true" zezo_date="true">
            </div>
            <div class="four columns omega">
                <div class="LabelContainer">الجائزة</div>
                <input name="Money" type="text" id="Money" value="<?php echo $row_RSFahdExam['Money'];?>" READONLY  data-required="true">
            </div>

            <br class="clear"/>
            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
            </div>
            <?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';}?>
        </div>
    </form>


    <?php
if (isset($_SESSION['u1'])){
    ?>
    <script>
        $(document).ready(function() {
            alertify.success("تم تعديل كافة البيانات");
        });
    </script>
    <?php
    $_SESSION['u1'] = NULL;
    unset($_SESSION['u1']);
}
if (isset($_SESSION['u5'])){
    ?>
    <script>
        $(document).ready(function() {
            alertify.success("تم تحديث البيانات");
            alertify.error("ملحوظة : الدرجة لم تعدل لمنع التعارض");
        });
    </script>
    <?php
    $_SESSION['u5'] = NULL;
    unset($_SESSION['u5']);
}
    ?>
    <script type="text/javascript">
        showError();
    </script>
    </div><!--content-->
<?php include('../templates/footer.php'); ?>
