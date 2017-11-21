<?php require("../../functions.php");
$colname_Recordset1 = "-1";
if (isset($_GET['ExamNo'])) {
    $colname_Recordset1 = $_GET['ExamNo'];
}
$query_Recordset1 = sprintf("SELECT AutoNo,O_StudentName,O_TeacherName,O_Edarah,O_HName,O_MurtaqaName,FinalExamDate,H_SolokGrade,H_MwadabahGrade,Sora1Name,Sora1Discount,Sora2Name,Sora2Discount,Sora3Name,Sora3Discount,Sora4Name,Sora4Discount,Sora5Name,Sora5Discount,Ek_mwathbah,Ek_slok,MarkName_Short,MarkName_Long,Money,FinalExamDegree FROM view_er_ertiqaexams WHERE AutoNo = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysqli_query($localhost, $query_Recordset1) or die(mysqli_error($localhost));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$ExamNo1_RsMktberTeacher = "-1";
if (isset($_GET['ExamNo'])) {
    $ExamNo1_RsMktberTeacher = $_GET['ExamNo'];
}
$query_RsMktberTeacher = sprintf("SELECT 0_users.arabic_name FROM  0_users,er_shahadah WHERE er_shahadah.MukhtaberTeacher=0_users.id and er_shahadah.ExamNo=%s", GetSQLValueString($ExamNo1_RsMktberTeacher, "int"));
$RsMktberTeacher = mysqli_query($localhost, $query_RsMktberTeacher) or die(mysqli_error($localhost));
$row_RsMktberTeacher = mysqli_fetch_assoc($RsMktberTeacher);
$totalRows_RsMktberTeacher = mysqli_num_rows($RsMktberTeacher);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .red_color {
            color: #94332a
        }

        .ar {
            font-family: 'al_jass_zq', sans-serif;
        }

        div, span, p {
            margin: 0;
            padding: 0;
            text-align: center
        }

        @media print {
            #HPageWrapper {
                background: none !important
            }

            #printButton {
                display: none !important
            }

            div, span, p {
                margin: 0;
                padding: 0;
                text-align: center
            }
        }

        #printButton {
            text-align: center;
            margin: 15px 0;
            border-bottom: 2px dashed #2b9fbb;
            padding-bottom: 5px
        }

        .button-primary {
            background-color: #2c9fbb;
            padding: 5px 20px;
            color: #fff;
            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
            border: 1px solid #46b3d3;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: inset 0 0 2px rgba(256, 256, 256, 0.75);
            -moz-box-shadow: inset 0 0 2px rgba(256, 256, 256, 0.75);
            -webkit-box-shadow: inset 0 0 2px rgba(256, 256, 256, 0.75);
            font-size: 18px
        }

        .button-primary:hover {
            background: #2b7791;
            border: 1px solid rgba(256, 256, 256, 0.75);
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
            -moz-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5)
        }

        @font-face {
            font-family: 'al_jass_zq';
            src: url('../../_fonts/jasscoozq.eot');
            src: url('../../_fonts/jasscoozq.eot?#iefix') format('embedded-opentype'), url('../../_fonts/jasscoozq.woff') format('woff'), url('../../_fonts/jasscoozq.ttf') format('truetype'), url('../../_fonts/jasscoozq.svg#al_jass_zqregular') format('svg');
            font-weight: normal;
            font-style: normal
        }

        body {
            direction: rtl;
            text-align: center;
            font-family: 'Droid Arabic Kufi', sans-serif;
            font-size: 20px;
            white-space: nowrap
        }

        .dateDiv {
            font-size: 16px;
            text-align: right;
            vertical-align: top
        }

        #HPageWrapper {
            z-index: 0;
            position: relative;
            width: 29.5cm;
            height: 19cm;
            border: 0;
            margin: 0 auto;
            padding: 0;
        }

        #containerDiv1 {
            width: 18.785cm;
            height: 3.44cm;
            position: absolute;
            left: 246px;
            top: 72px
        }

        .FieldDiv {
            vertical-align: middle
        }

        #containerDiv2 .FieldDiv {
            font-size: 120%;
            padding-top: 2px
        }

        #apDiv1 {
            position: absolute;
            left: 100px;
            top: 347px;
            width: 900px;
            height: 29px;
            z-index: 1
        }

        #apDiv2 {
            position: absolute;
            left: 375px;
            top: 51px;
            width: 262px;
            height: 30px;
            z-index: 1
        }

        #apDiv3 {
            position: absolute;
            left: 15px;
            top: 362px;
            width: 263px;
            height: 28px;
            z-index: 1
        }

        #apDiv4 {
            position: absolute;
            left: 100px;
            top: 389px;
            width: 900px;
            height: 28px;
            z-index: 1
        }

        #apDiv5 {
            position: absolute;
            left: 217px;
            top: 473px;
            width: 831px;
            height: 28px;
            z-index: 1
        }

        #apDiv6 {
            position: absolute;
            left: 6px;
            top: 9px;
            width: 339px;
            height: 41px;
            z-index: 1
        }

        #apDiv7 {
            position: absolute;
            left: 873px;
            top: -9px;
            width: 133px;
            height: 180px;
            z-index: 1
        }

        #apDiv8 {
            position: absolute;
            left: 12px;
            top: 236px;
            width: 286px;
            height: 30px;
            z-index: 1
        }

        #apDiv9 {
            position: absolute;
            left: 422px;
            top: -8px;
            width: 93px;
            height: 28px;
            z-index: 1
        }

        #apDiv10 {
            position: absolute;
            left: 238px;
            top: 53px;
            width: 470px;
            height: 28px;
            z-index: 1
        }

        #apDiv11 {
            position: absolute;
            left: 190px;
            top: 5px;
            width: 52px;
            height: 28px;
            z-index: 1
        }

        #apDiv12 {
            position: absolute;
            left: 729px;
            top: 161px;
            width: 131px;
            height: 223px;
            z-index: 1
        }

        #containerDiv2 {
            width: 9.499cm;
            height: 1.561cm;
            position: absolute;
            left: 59px;
            top: 224px
        }

        #apDiv13 {
            position: absolute;
            left: 72px;
            top: 20px;
            width: 357px;
            height: 31px;
            z-index: 1
        }

        #apDiv14 {
            position: absolute;
            left: -35px;
            top: 1px;
            width: 58px;
            height: 31px;
            z-index: 1
        }

        #apDiv15 {
            position: absolute;
            left: 185px;
            top: 84px;
            width: 54px;
            height: 31px;
            z-index: 1
        }

        #apDiv16 {
            position: absolute;
            left: 37px;
            top: 560px;
            width: 311px;
            height: 25px;
            z-index: 1;
            text-align: right;
            font-size: 90%
        }

        #apDiv16i {
            position: absolute;
            left: 796px;
            top: 560px;
            width: 257px;
            height: 25px;
            z-index: 1;
            text-align: right;
            font-size: 90%
        }

        #apDiv17 {
            position: absolute;
            left: -41px;
            top: 87px;
            width: 102px;
            height: 32px;
            z-index: 1
        }

        #apDiv18 {
            position: absolute;
            left: 156px;
            top: 16px;
            width: 132px;
            height: 226px;
            z-index: 1
        }

        #apDiv19 {
            position: absolute;
            left: 356px;
            top: 94px;
            width: 276px;
            height: 28px;
            z-index: 1
        }

        #apDiv20 {
            position: absolute;
            left: 66px;
            top: 555px;
            width: 748px;
            height: 128px;
            z-index: 1
        }

        #apDiv21 {
            position: absolute;
            left: 299px;
            top: 251px;
            width: 126px;
            height: 34px;
            z-index: 1
        }

        #apDiv22 {
            position: absolute;
            left: 421px;
            top: 4px;
            width: 93px;
            height: 28px;
            z-index: 1
        }

        #apDiv23 {
            position: absolute;
            left: 422px;
            top: 43px;
            width: 93px;
            height: 28px;
            z-index: 1
        }

        #apDiv24 {
            position: absolute;
            left: 422px;
            top: 43px;
            width: 93px;
            height: 28px;
            z-index: 1
        }

        #apDiv25 {
            position: absolute;
            left: 256px;
            top: 45px;
            width: 51px;
            height: 31px;
            z-index: 1
        }

        #apDiv26 {
            position: absolute;
            left: 256px;
            top: 45px;
            width: 51px;
            height: 31px;
            z-index: 1
        }

        #apDiv27 {
            position: absolute;
            left: 114px;
            top: 44px;
            width: 54px;
            height: 31px;
            z-index: 1
        }

        #apDiv28 {
            position: absolute;
            left: 864px;
            top: 624px;
            width: 18px;
            height: 18px;
            z-index: 1
        }

        #apDiv29 {
            position: absolute;
            left: 700px;
            top: 657px;
            width: 228px;
            height: 22px;
            z-index: 1;
            font-family: 'al_jass_zq', sans-serif;
        }

        #apDiv30 {
            position: absolute;
            left: 410px;
            top: 684px;
            width: 195px;
            height: 22px;
            z-index: 1;
            font-family: 'al_jass_zq', sans-serif;
        }

        #apDiv31 {
            position: absolute;
            left: 620px;
            top: 666px;
            width: 212px;
            height: 18px;
            z-index: 2;
            font-family: 'al_jass_zq', sans-serif;
        }

        #apDiv32 {
            position: absolute;
            left: 114px;
            top: 44px;
            width: 54px;
            height: 31px;
            z-index: 1
        }

        #apDiv33 {
            position: absolute;
            left: 61px;
            top: 680px;
            width: 142px;
            height: 78px;
            z-index: 1
        }

        #apDiv34 {
            position: absolute;
            left: 457px;
            top: 710px;
            width: 228px;
            height: 26px;
            z-index: 1;
            font-size: 90%;
            text-align: right
        }

        #apDiv35 {
            position: absolute;
            left: 100px;
            top: 348px;
            width: 900px;
            height: 31px;
            z-index: 1
        }

        #apDiv36 {
            position: absolute;
            left: 100px;
            top: 429px;
            width: 900px;
            height: 38px;
            z-index: 2
        }

        #apDiv37 {
            position: absolute;
            left: 100px;
            top: 523px;
            width: 900px;
            height: 41px;
            z-index: 1
        }

        #apDiv38 {
            position: absolute;
            left: 780px;
            top: 656px;
            width: 197px;
            height: 25px;
            z-index: 1;
            text-align: right;
            font-size: 70%;
        }

        #apDiv39 {
            position: absolute;
            left: 100px;
            top: 305px;
            width: 900px;
            height: 39px;
            z-index: 1;
            text-align: center;
        }

        #apDiv40 {
            position: absolute;
            left: 100px;
            top: 469px;
            width: 901px;
            height: 30px;
            z-index: 2;
        }

        #apDiv41 {
            position: absolute;
            left: 986px;
            top: 560px;
            width: 81px;
            height: 28px;
            z-index: 3;
        }

        #apDiv42 {
            position: absolute;
            left: 268px;
            top: 560px;
            width: 72px;
            height: 26px;
            z-index: 4;
        }

        #apDiv43 {
            position: absolute;
            left: 983px;
            top: 611px;
            width: 105px;
            height: 24px;
            z-index: 5;
        }

        #apDiv44 {
            position: absolute;
            left: 279px;
            top: 611px;
            width: 74px;
            height: 24px;
            z-index: 6;
        }

        #apDiv45 {
            position: absolute;
            left: 966px;
            top: 654px;
            width: 128px;
            height: 21px;
            z-index: 7;
            font-size: 70%;
        }

        #apDiv46 {
            position: absolute;
            left: 796px;
            top: 518px;
            width: 254px;
            height: 23px;
            z-index: 3;
            font-size: 110%;
            color: #0A7314;
        }

        #apDiv47 {
            position: absolute;
            left: 38px;
            top: 518px;
            width: 308px;
            height: 22px;
            z-index: 4;
            font-size: 110%;
            color: #0A7314;
        }

        #apDiv48 {
            position: absolute;
            left: 114px;
            top: 583px;
            width: 165px;
            height: 70px;
            z-index: 1;
        }

        #apDiv49 {
            position: absolute;
            left: 835px;
            top: 580px;
            width: 181px;
            height: 86px;
            z-index: 1;
        }

        #apDiv50 {
            position: absolute;
            left: 451px;
            top: 546px;
            width: 197px;
            height: 126px;
            z-index: 1;
        }
    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="/sys/_js/zezo_1_get_selected_year.js"></script>
    <script src="/sys/_js/zezo_2_date_functions.js"></script>
    <script src="/sys/_js/zezo_3_h_calender.js"></script>
    <title>شهادة شكر</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/earlyaccess/droidarabickufi.css">
</head>
<body>
<div id="printButton">
    <input class="button-primary" type="button" value="طباعة" onClick="window.print()">
</div>
<div id="HPageWrapper">
    <div id="apDiv38"><?php echo $row_Recordset1['FinalExamDate']; ?></div>
    <div id="apDiv36">والجمعية إذ تخصها بهذا ترجو لها استمرار التفوق والتميز بما يعود عليها وعلى مجتمعها بالنفع العام.</div>
    <div id="apDiv45">حررت في يوم :</div>
    <div id="apDiv46">مشرفة البرنامج</div>
    <div id="apDiv47">مديرة مكتب الإشراف النسائي</div>
    <div id="apDiv50"><img src="../../_images/stamp.png" width="150" height="123"></div>
    <div id="apDiv48"><img src="../../_images/sign3.png" width="120" height="68"></div>
    <div id="apDiv49"><img src="../../_images/sign4.png" width="120" height="68"></div>
    <div id="apDiv39"> يسر الجمعية الخيرية لتحفيظ القرآن الكريم بمحافظة الزلفي أن تخص بالشكر والتقدير الطالبة</div>
    <div id="apDiv40">والله ولي التوفيق</div>
    <div class="FieldDiv"
         id="apDiv1"><?php echo get_gender_label('st', 'ال') . '<span class="red_color">' . $row_Recordset1['O_StudentName'] . '</span>' . ' من ' . '<span class="red_color">' . $row_Recordset1['O_Edarah'] . '</span>' . ' في حلقة  ' . '<span class="red_color">' . $row_Recordset1['O_HName'] . '</span>'; ?></div>
    <div class="FieldDiv"
         id="apDiv4"><?php echo 'لتفوقها في اختبار مرتقى  (  ' . '<span class="red_color">' . $row_Recordset1['O_MurtaqaName'] . '</span>' . ' ) ' . ' وحصولها على درجة   ( ' . '<span class="red_color ar">' . $row_Recordset1['FinalExamDegree'] . '</span> )  ' . ' وتقدير  ( ' . '<span class="red_color">' . $row_Recordset1['MarkName_Short'] . '</span>' . ' ) ' . ' في عام   ' . '<span class="red_color ar">' . substr($row_Recordset1['FinalExamDate'], 0, 4) . ' هـ ' . '</span>'; ?></div>
    <div id="apDiv16" style="text-align: center;"><?php echo $mudeerEshraf; ?></div>
    <div id="apDiv16i" style="text-align: center;"><?php echo $mushrefErtiqa; ?></div>
    <div class="dateDiv" id="apDiv29"><?php echo StringToDate($row_Recordset1['FinalExamDate']); ?> هـ</div>
</div>
</body>
<script>
    $(function () {
        fullDate = no_to_date($('#apDiv38').html());
        if (fullDate != '') {
            spliteddate = fullDate.split('/');
            yyyy = parseInt(spliteddate[0], 10);
            mm = ((parseInt(spliteddate[1], 10) < 10) ? '0' + parseInt(spliteddate[1], 10) : parseInt(spliteddate[1], 10));
            dd = ((parseInt(spliteddate[2], 10) < 10) ? '0' + parseInt(spliteddate[2], 10) : parseInt(spliteddate[2], 10));
            yyyymmdd = String(yyyy) + String(mm) + String(dd);
            day_no = (yyyymmdd > 0) ? weekeay_name[get_g_date(yyyymmdd, 'yes')] : '';
            $('#apDiv38').html(day_no);
        }
    });
</script>
</html>
