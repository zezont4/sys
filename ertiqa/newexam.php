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
$EdarahIDS=$_SESSION['user_id'];
?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

    // search for dublicate musabakah ##############
    mysqli_select_db($localhost,$database_localhost);
    $query_Rsdublicate = sprintf("SELECT AutoNo FROM er_ertiqaexams WHERE StID=%s and ErtiqaID=%s and (FinalExamStatus=0 or FinalExamStatus=1 or FinalExamStatus=2)",
                                 GetSQLValueString($_POST['StID'],"double"),
                                 GetSQLValueString($_POST['MurtaqaID'],"int"));
    //zlog($query_Rsdublicate);
    $Rsdublicate = mysqli_query($localhost,$query_Rsdublicate)or die(mysqli_error($localhost));
    $row_Rsdublicate = mysqli_fetch_assoc($Rsdublicate);
    $totalRows_Rsdublicate = mysqli_num_rows($Rsdublicate);
    if ($totalRows_Rsdublicate>0){ ?>
<?php include('../templates/header1.php'); ?>
<br><br><br>
<div style="direction:rtl;text-align:center;font-size:22px;">
    <p>
        لقد تم حجز موعد <?php echo get_gender_label('st','لل'); ?> سابقا ولنفس المرتقى، والنتيجة المسجلة للموعد السابق هي :
        <?php echo " ".get_array_1($statusName,$row_Rsdublicate["FinalExamStatus"]);?>
    </p>
</div>
<?php	exit;
                                 }

    $insertSQL = sprintf("INSERT INTO er_ertiqaexams (EdarahID,HalakahID,TeacherID,StID,ErtiqaID,TestExamDegree,H_SolokGrade,H_MwadabahGrade,TestExamDate) VALUES ( %s,%s,%s,%s,%s,%s,%s,%s,%s)",
                         GetSQLValueString($_POST['EdarahID'],"int"),
                         GetSQLValueString($_POST['HalakahID'],"int"),
                         GetSQLValueString($_POST['TeacherID'],"double"),
                         GetSQLValueString($_POST['StID'],"double"),
                         GetSQLValueString($_POST['MurtaqaID'],"int"),
                         GetSQLValueString($_POST['TestExamDegree'],"int"),
                         GetSQLValueString($_POST['H_SolokGrade'],"int"),
                         GetSQLValueString($_POST['H_MwadabahGrade'],"int"),
                         GetSQLValueString(str_replace('/','',$_POST['TestExamDate']),"int")
                        );
    mysqli_select_db($localhost,$database_localhost);
    $Result1 = mysqli_query($localhost,$insertSQL)or die(mysqli_error($localhost));


    if ($Result1){
        $msg="ertiqa";
        header("Location: statistics/studentexams.php?msg=".$msg."&StudentID=".$_POST['StID']);
    }

}


?>

<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'حجز موعد جديد'; ?>
<title><?php echo $PageTitle; ?></title>
<style type="text/css">
    .FieldsButton .note {
        color: #4FA64B;
    }
</style>
<script>
    $(document).ready(function() {
        $('#TestExamDegree').change(function(){
            $('#TestExamResault').val(GetMarkName($('#TestExamDegree').val(),'long'));
        });
    });
</script>

</head>
<body>
    <?php include('../templates/header2.php'); ?>
    <?php include ('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div><!--PageTitle-->
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" data-validate="parsley">
        <div class="content lp CSSTableGenerator">
            <?php if(login_check("admin,edarh") == true) { ?>
            <?php echo get_st_info($_GET['StID']); ?>
        </div>
        <div class="content">

            <input name="MM_insert" type="hidden" value="form1">
            <div class="FieldsTitle">الاختبار التجريبي</div>

            <div class="four columns alpha">
                <div class="LabelContainer">المرتقى </div>
                <?php echo create_combo("MurtaqaID",$murtaqaName,0,0,'data-required="true"'); ?>
            </div>

            <div class="four columns">
                <div class="LabelContainer">درجة السلوك</div>
                <input name="H_SolokGrade" type="text" value="20"  data-required="true" data-type="digits" data-max="20"  autocomplete="off">
            </div>
            <div class="four columns">
                <div class="LabelContainer">درجة المواظبة</div>
                <input name="H_MwadabahGrade" type="text" value="20"  data-required="true"  data-type="digits" data-max="20"  autocomplete="off">
            </div>
            <div class="four columns omega">
                <div class="LabelContainer">درجة الاختبار التجريبي</div>
                <input name="TestExamDegree" id="TestExamDegree" type="text" value=""  data-required="true"  data-type="digits" data-range="[85,100]" onChange="validateForm('form1',this.name,'TestExamResault')" autocomplete="off">
            </div>
            <div class="four columns alpha">
                <div class="LabelContainer">التقدير</div>
                <input name="TestExamResault" id="TestExamResault" type="text" value="" READONLY  tabindex="-1"/>
            </div>
            <div class="four columns">
                <div class="LabelContainer">تاريخ الاختبار التجريبي</div>
                <!-- <input type="text" name="TestExamDate" value="" onBlur="document.getElementsByName('TestExamDate1').item(0).value=dateToDB(this.name)"/>-->
                <input name="TestExamDate" type="text" id="TestExamDate" data-required="true" value="" zezo_date="true">
            </div>
            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="موافق"/>
            </div>
            <div class="sixteen columns">
                <p class="note">ملحوظة: أقل درجة مقبولة في الاختبار التجريبي هي 85 درجة.</p>
            </div>
        </div><!--content-->
    </form>
    <?php }else{echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.'.'</div>';}?>
    <?php include('../templates/footer.php'); ?>

    <?php
if (isset($_SESSION['u1'])){
    ?>
    <script>
        $(document).ready(function() {
            alertify.success("تم حجز الموعد بنجاح");
        });
    </script>
    <?php
    $_SESSION['u1'] = NULL;
    unset($_SESSION['u1']);
}
    ?>

    <script type="text/javascript">
        showError();
    </script>
