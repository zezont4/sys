<?php
require_once('../functions.php');
$editFormAction = $_SERVER['PHP_SELF'];
if (isset ($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}
$Result1 = null;
$Result2 = null;
$Result3 = null;
$Result4 = null;
if ((isset ($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

    if ($_POST['RadioStatus'] == 2) {
        $updateSQL = sprintf("REPLACE INTO er_shahadah (
  Sora1Name, Sora1Discount, Sora2Name, Sora2Discount, Sora3Name, Sora3Discount, Sora4Name, Sora4Discount, Sora5Name, Sora5Discount,
  Sora6Name, Sora6Discount, Sora7Name, Sora7Discount, Sora8Name, Sora8Discount, Sora9Name, Sora9Discount, Sora10Name, Sora10Discount,
	Sora11Name, Sora11Discount, Sora12Name, Sora12Discount, Sora13Name, Sora13Discount, Sora14Name, Sora14Discount, Sora15Name, Sora15Discount,
	Sora16Name, Sora16Discount, Sora17Name, Sora17Discount, Sora18Name, Sora18Discount, Sora19Name, Sora19Discount, Sora20Name, Sora20Discount
	, Degree, `Money`, teacher_money, edarah_money, examPoints, MarkName_Short, MarkName_Long, ExamNo, MukhtaberTeacher, MukhtaberTeacher2)
VALUES
  (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            GetSQLValueString(Input::get('Sora1Name'), "int"), GetSQLValueString(abs(Input::get('Sora1Discount')), "double"),
            GetSQLValueString(Input::get('Sora2Name'), "int"), GetSQLValueString(abs(Input::get('Sora2Discount')), "double"),
            GetSQLValueString(Input::get('Sora3Name'), "int"), GetSQLValueString(abs(Input::get('Sora3Discount')), "double"),
            GetSQLValueString(Input::get('Sora4Name'), "int"), GetSQLValueString(abs(Input::get('Sora4Discount')), "double"),
            GetSQLValueString(Input::get('Sora5Name'), "int"), GetSQLValueString(abs(Input::get('Sora5Discount')), "double"),
            GetSQLValueString(Input::get('Sora6Name'), "int"), GetSQLValueString(abs(Input::get('Sora6Discount')), "double"),
            GetSQLValueString(Input::get('Sora7Name'), "int"), GetSQLValueString(abs(Input::get('Sora7Discount')), "double"),
            GetSQLValueString(Input::get('Sora8Name'), "int"), GetSQLValueString(abs(Input::get('Sora8Discount')), "double"),
            GetSQLValueString(Input::get('Sora9Name'), "int"), GetSQLValueString(abs(Input::get('Sora9Discount')), "double"),
            GetSQLValueString(Input::get('Sora10Name'), 'int'), GetSQLValueString(abs(Input::get('Sora10Discount')), 'double'),
            GetSQLValueString(Input::get('Sora11Name'), 'int'), GetSQLValueString(abs(Input::get('Sora11Discount')), 'double'),
            GetSQLValueString(Input::get('Sora12Name'), 'int'), GetSQLValueString(abs(Input::get('Sora12Discount')), 'double'),
            GetSQLValueString(Input::get('Sora13Name'), 'int'), GetSQLValueString(abs(Input::get('Sora13Discount')), 'double'),
            GetSQLValueString(Input::get('Sora14Name'), 'int'), GetSQLValueString(abs(Input::get('Sora14Discount')), 'double'),
            GetSQLValueString(Input::get('Sora15Name'), 'int'), GetSQLValueString(abs(Input::get('Sora15Discount')), 'double'),
            GetSQLValueString(Input::get('Sora16Name'), 'int'), GetSQLValueString(abs(Input::get('Sora16Discount')), 'double'),
            GetSQLValueString(Input::get('Sora17Name'), 'int'), GetSQLValueString(abs(Input::get('Sora17Discount')), 'double'),
            GetSQLValueString(Input::get('Sora18Name'), 'int'), GetSQLValueString(abs(Input::get('Sora18Discount')), 'double'),
            GetSQLValueString(Input::get('Sora19Name'), 'int'), GetSQLValueString(abs(Input::get('Sora19Discount')), 'double'),
            GetSQLValueString(Input::get('Sora20Name'), 'int'), GetSQLValueString(abs(Input::get('Sora20Discount')), 'double'),
            GetSQLValueString(Input::get('Degree_percentage'), "double"),
            GetSQLValueString(Input::get('Money'), "int"),
            GetSQLValueString(Input::get('TeacherMoney'), "int"),
            GetSQLValueString(Input::get('EdarahMoney'), "int"),
            GetSQLValueString(Input::get('examPoints'), "double"),
            GetSQLValueString(Input::get('MarkName_Short'), "text"),
            GetSQLValueString(Input::get('MarkName_Long'), "text"),
            GetSQLValueString(Input::get('ExamNo'), "int"),
            GetSQLValueString(Input::get('MukhtaberTeacher'), "int"),
            GetSQLValueString(Input::get('MukhtaberTeacher2'), "int"));
//	exit('sdf');
//	exit($updateSQL);

        $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
        $updateSQL2 = sprintf("UPDATE er_ertiqaexams SET FinalExamStatus = %s WHERE AutoNo = %s",
            GetSQLValueString("2", "int"), GetSQLValueString($_POST['ExamNo'], "int"));
        $Result2 = mysqli_query($localhost, $updateSQL2) or die(mysqli_error($localhost));
    } else {
        $updateSQL4 = sprintf("DELETE FROM er_shahadah WHERE ExamNo = %s", GetSQLValueString($_POST['ExamNo'], "int"));

        $Result4 = mysqli_query($localhost, $updateSQL4) or die(mysqli_error($localhost));
        $updateSQL3 = sprintf("UPDATE er_ertiqaexams
SET FinalExamStatus = %s WHERE AutoNo = %s", GetSQLValueString($_POST['RadioStatus'], "int"), GetSQLValueString($_POST['ExamNo'], "int"));
        $Result3 = mysqli_query($localhost, $updateSQL3) or die(mysqli_error($localhost));
    }
    updateMurajaahStatus($_POST['ExamNo'], $_POST['RadioStatus']);
    if ($Result1 && $Result2) {
        $st_msg_2 = null;
        $st_msg_3 = null;
        $father_msg_2 = null;
        $father_msg_3 = null;
        $colname_RsMobile = "-1";
        if (isset ($_GET['ExamNo'])) {
            $colname_RsMobile = $_GET['ExamNo'];
        }
        $query_RsMobile = sprintf("SELECT FatherMobileNo,StMobileNo,StID,EdarahID, edarah_sex, StName1, MarkName_Long, O_MurtaqaName
FROM view_er_ertiqaexams
WHERE AutoNo = %s", GetSQLValueString($colname_RsMobile, "int"));
        $RsMobile = mysqli_query($localhost, $query_RsMobile) or die(mysqli_error($localhost));
        $row_RsMobile = mysqli_fetch_assoc($RsMobile);
        $totalRows_RsMobile = mysqli_num_rows($RsMobile);

        $father_mobile_number = '966' . substr($row_RsMobile['FatherMobileNo'], 1, 9);
        $student_mobile_number = $row_RsMobile['StMobileNo'] != '' ? '966' . substr($row_RsMobile['StMobileNo'], 1, 9) : null;
        $msg = '';
        $sex = $row_RsMobile['edarah_sex'];
        $StName1 = $row_RsMobile['StName1'];
        $MarkName_Long = $row_RsMobile['MarkName_Long'];
        $murtaqa_name = $row_RsMobile['O_MurtaqaName'];

        if ($sex == 1) {
            $st_msg_2 = "نهنئك باجتيازك الاختبار النهائي في حفظ القرآن الكريم. وفقك والله ونفع بك.";
            $st_msg_3 = "لم توفق في اجتياز الاختبار النهائي في حفظ القرآن الكريم، ونسأل الله لك التوفيق في الاختبار القادم.";

            $father_msg_2 = sprintf("نبارك لنا ولكم اجتياز الابن (%s) الاختبار النهائي في حفظ القرآن الكريم بتقدير (%s), جعله الله قرة عين لكم.", $StName1, $MarkName_Long);
            $father_msg_3 = sprintf("الابن (%s) لم يوفق في اجتيازالاختبار النهائي في حفظ القرآن الكريم,نسأل الله له التوفيق في الاختبار القادم.", $StName1);

        } elseif ($sex == 0) {
            $st_msg_2 = "نهنئكِ باجتيازكِ الاختبار النهائي في حفظ القرآن الكريم. وفقكِ والله ونفع بكِ.";
            $st_msg_3 = "لم توفقِ في اجتياز الاختبار النهائي في حفظ القرآن الكريم، ونسأل الله لكِ التوفيق في الاختبار القادم.";

            $father_msg_2 = sprintf("نبارك لنا ولكم اجتياز الابنة (%s) الاختبار النهائي في حفظ القرآن الكريم بتقدير (%s), جعلها الله قرة عين لكم.", $StName1, $MarkName_Long);
            $father_msg_3 = sprintf("الابنة (%s) لم توفق في اجتيازالاختبار النهائي في حفظ القرآن الكريم,نسأل الله لها التوفيق في الاختبار القادم.", $StName1);
        }
        $student_msg = '';
        $father_msg = '';
        if ($_POST['RadioStatus'] == 2) {
            $student_msg = $st_msg_2;
            $father_msg = $father_msg_2;
        } else if ($_POST['RadioStatus'] == 3) {
            $student_msg = $st_msg_3;
            $father_msg = $father_msg_3;
        }

//        $msg = "نهنئكم باجتيازكم الاختبار النهائي في حفظ القرآن الكريم. وفقكم والله ونفع بكم.";

        if ($student_msg != '') {
            $SendingAnswerForStudent = sendSMS($student_mobile_number, $student_msg);
            if ($SendingAnswerForStudent == 1) {
                $_SESSION['msgStudentSent'] = '';
            } else {
                $_SESSION['msgStudentNotSent'] = $SendingAnswerForStudent;
            }
        }

        if ($father_msg != '') {
            $SendingAnswerForFather = sendSMS($father_mobile_number, $father_msg);
            if ($SendingAnswerForFather == 1) {
                $_SESSION['msgFatherSent'] = '';
            } else {
                $_SESSION['msgFatherNotSent'] = $SendingAnswerForFather;
            }
        }

        //إرسال رسال لمدير المجمع والدار
        sendMsgToModerator($row_RsMobile['StID'], $row_RsMobile['EdarahID'], $_POST['RadioStatus']);
        $_SESSION['u1'] = "";
    }


}
$colname_RsErExams = "-1";
if (isset ($_GET['ExamNo'])) {
    $colname_RsErExams = $_GET['ExamNo'];
}
$query_RsErExams = sprintf("SELECT * FROM view_er_ertiqaexams
WHERE AutoNo = %s", GetSQLValueString($colname_RsErExams, "int"));
$RsErExams = mysqli_query($localhost, $query_RsErExams) or die(mysqli_error($localhost));
$row_RsErExams = mysqli_fetch_assoc($RsErExams);
$totalRows_RsErExams = mysqli_num_rows($RsErExams);

$sql_sex = sql_sex('sex');
$query_RsMukhtaber = "SELECT id,arabic_name FROM 0_users WHERE hidden = 0 {$sql_sex} and user_group='mktbr' ORDER BY arabic_name ASC";
$RsMukhtaber = mysqli_query($localhost, $query_RsMukhtaber) or die(mysqli_error($localhost));
$row_RsMukhtaber = mysqli_fetch_assoc($RsMukhtaber);
$totalRows_RsMukhtaber = mysqli_num_rows($RsMukhtaber);

$query_RsQuran = "SELECT * FROM `0_quran` ORDER BY `number` DESC";
$RsQuran = mysqli_query($localhost, $query_RsQuran) or die(mysqli_error($localhost));
$row_RsQuran = mysqli_fetch_assoc($RsQuran);
$totalRows_RsQuran = mysqli_num_rows($RsQuran);

include('../templates/header1.php');
$PageTitle = 'بيانات الاختبار'; ?>
<title><?php echo $PageTitle; ?></title>

<script src="../_js/jquery.select-to-autocomplete.min.js"></script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('select').selectToAutocomplete();
    });
</script>
<style type="text/css" media="screen">
    .ui-autocomplete {
        padding: 0;
        list-style: none;
        background-color: #fff;
        width: 218px;
        border: 1px solid #B0BECA;
        max-height: 350px;
        overflow-y: scroll;
    }

    .ui-autocomplete .ui-menu-item a {
        border-top: 1px solid #B0BECA;
        display: block;
        padding: 4px 6px;
        color: #353D44;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .ui-autocomplete .ui-menu-item:first-child a {
        border-top: none;
    }

    .ui-autocomplete .ui-menu-item a.ui-state-hover {
        background-color: #D5E5F4;
        color: #161A1C;
    }

    .readonly {
        background-color: #F3F3F3;
    }

    .shahadah {
        margin-top: 10px;
        width: 100%;
    }

    .shahadah th, .shahadah td {
        border: 1px solid #999;
        padding: 2px;
        text-align: center;
        vertical-align: middle;
    }

    .note {
        color: #E70005;
    }

    .shahadah th {
        padding: 5px 2px;
    }
</style>
</head>
<body>
<?php include('../templates/header2.php');
include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>
<!--PageTitle-->
<?php
// إذا قام المستخدم بتغيير رقم الاختبار بشريط العنوان إلى رقم آخر غير موجود
if (!$totalRows_RsErExams) {
    exit('<br><br><h1 style="font-size:22px;text-align:center;">عفوا... قد يكون هذا الإختبار محذوف<br><br></h1>');
}
?>
<form method="post" name="form1" id="form1" data-validate="parsley" action="<?php echo $editFormAction; ?>">
    <div class="content CSSTableGenerator">
        <table>
            <caption>
                بيانات <?php echo get_gender_label('st', 'ال'); ?>
            </caption>
            <tr>
                <td><?php echo get_gender_label('st', 'ال'); ?></td>
                <td>المرتقى</td>
                <td><?php echo get_gender_label('e', 'ال'); ?></td>
                <td>الحلقة</td>
            </tr>
            <tr>
                <td><?php echo $row_RsErExams['O_StudentName']; ?></td>
                <td><?php echo get_array_1($murtaqaName, $row_RsErExams['ErtiqaID']); ?></td>
                <td><?php echo $row_RsErExams['O_Edarah']; ?></td>
                <td><?php echo $row_RsErExams['O_HName']; ?></td>
            </tr>
        </table>
    </div>
    <div class="content FieldsButton">
        <div class="FieldsTitle">حالة الاختبار</div>
        <?php
        $count_statusName = count($statusName);
        for ($i = 0; $i < $count_statusName; $i++) {
            if ($statusName[$i][0] == '0') {
                echo '<div class="three columns alpha">';
            } else if ($statusName[$i][0] == '3' || $statusName[$i][0] == '4') {
                echo '<div class="two columns alpha omega">';
            } else if ($statusName[$i][0] == '5') {
                echo '<div class="three columns">';
            } else if ($statusName[$i][0] == '6') {
                echo '<div class="three columns omega">';
            } else {
                echo '<div class="two columns alpha">';
            } ?>
            <label>
                <input <?php if ($row_RsErExams['FinalExamStatus'] == $statusName[$i][0]) {
                    echo "checked='checked'";
                } ?>
                        type="radio" data-required="true" name="RadioStatus" value="<?php echo $statusName[$i][0]; ?>"
                        id="RadioStatus_<?php echo $statusName[$i][0]; ?>">
                <?php echo $statusName[$i][1]; ?> </label>
            <?php echo '</div>'; ?>
        <?php }
        unset($StatusN); ?>
    </div>
    <?php
    $TotalDiscount = $row_RsErExams['Sora1Discount'] + $row_RsErExams['Sora2Discount'] + $row_RsErExams['Sora3Discount'] + $row_RsErExams['Sora4Discount'] + $row_RsErExams['Sora5Discount'] + $row_RsErExams['Sora6Discount'] + $row_RsErExams['Sora7Discount'] + $row_RsErExams['Sora8Discount'] + $row_RsErExams['Sora9Discount'] + $row_RsErExams['Sora10Discount'];
    $TotalRremaining = (10 - $row_RsErExams['Sora1Discount']) + (10 - $row_RsErExams['Sora2Discount']) + (10 - $row_RsErExams['Sora3Discount']) + (10 - $row_RsErExams['Sora4Discount']) + (10 - $row_RsErExams['Sora5Discount']) + (10 - $row_RsErExams['Sora6Discount']) + (10 - $row_RsErExams['Sora7Discount']) + (10 - $row_RsErExams['Sora8Discount']) + (10 - $row_RsErExams['Sora9Discount']) + (10 - $row_RsErExams['Sora10Discount']);

    ?>
    <input type="hidden" name="MM_update" value="form1">
    <input
            type="hidden" name="ExamNo"
            value="<?php echo $row_RsErExams['AutoNo']; ?>">

    <?php
    function sora_and_discount($no)
    {
        global $soraName, $row_RsErExams, $murtaqaEndSora;
        $orders = [
            '',
            'الأول',
            'الثاني',
            'الثالث',
            'الرابع',
            'الخامس',
            'السادس',
            'السابع',
            'الثامن',
            'التاسع',
            'العاشر',
            'الحادي عشر',
            'الثاني عشر',
            'الثالث عشر',
            'الرابع عشر',
            'الخامس عشر',
            'السادس عشر',
            'السابع عشر',
            'الثامن عشر',
            'التاسع عشر',
            'العشرون',
        ]
        ?>
        <tr>
            <th><?php echo $orders[$no]; ?></th>
            <td>
                <select name="Sora<?php echo $no; ?>Name" id="Sora<?php echo $no; ?>Name"
                        class="FullWidthCombo Enbl_Dsbl" data-required="true" autofocus
                        autocorrect="off" tabindex="<?php echo ($no * 2) - 1; ?>">
                    <option value>حدد السورة</option>
                    <?php $ii = 114;
                    do { ?>
                        <option value="<?php echo $ii; ?>"
                            <?php if (!(strcmp($row_RsErExams["Sora{$no}Name"], $ii))) {
                                echo "selected='selected'";
                            } ?>> <?php echo $soraName[$ii] ?></option>
                        <?php

                        if ($ii == $murtaqaEndSora[$row_RsErExams['ErtiqaID']]) {
                            break;
                        }
                        $ii--;
                    } while ($ii > 0);
                    ?>
                </select>
            </td>
            <td>
                <input name="Sora<?php echo $no; ?>Discount" id="Sora<?php echo $no; ?>Discount"
                       class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                       value="<?php echo $row_RsErExams["Sora{$no}Discount"]; ?>" size="4"
                       maxlength="4" data-type="number" data-required="true" data-max="20"
                       tabindex="<?php echo($no * 2); ?>" autocomplete="off">
            </td>
            <td><p id="sora_degree_<?php echo $no; ?>"></p></td>
        </tr>
        <?php
    }

    ?>
    <div class="content">
        <div class="FieldsTitle">درجات الاختبار</div>
        <div class="eight columns alpha">
            <table class="shahadah">
                <tr>
                    <th>المقطع</th>
                    <th>اسم السورة</th>
                    <th>الدرجة المحسومة</th>
                    <th>الدرجة</th>
                </tr>
                <?php
                for ($i = 1; $i < 21; $i++) {
                    echo sora_and_discount($i);
                }
                ?>

            </table>
        </div>
        <div class="eight columns omega">
            <table class="shahadah">
                <tr>
                    <th><?php echo get_gender_label('mktbr', ''), $_SESSION['sex'] == 0 ? '[1]' : ''; ?></th>
                    <td>
                        <select name="MukhtaberTeacher" class="FullWidthCombo Enbl_Dsbl" id="MukhtaberTeacher"
                                data-required="true" tabindex="43">
                            <option value>حدد المعلم المختبر</option>
                            <?php
                            if ($row_RsErExams['MukhtaberTeacher'] > 0) {
                                $selected_mktbr = $row_RsErExams['MukhtaberTeacher'];
                            } else {
                                $selected_mktbr = $_SESSION['user_id'];
                            }
                            do {
                                ?>
                                <option value="<?php echo $row_RsMukhtaber['id'] ?>"
                                    <?php if ($row_RsMukhtaber['id'] == $selected_mktbr) {
                                        echo "selected='selected'";
                                    } ?>><?php echo $row_RsMukhtaber['arabic_name'] ?></option>
                                <?php
                            } while ($row_RsMukhtaber = mysqli_fetch_assoc($RsMukhtaber));
                            $rows = mysqli_num_rows($RsMukhtaber);
                            if ($rows > 0) {
                                mysqli_data_seek($RsMukhtaber, 0);
                                $row_RsMukhtaber = mysqli_fetch_assoc($RsMukhtaber);
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php echo get_gender_label('mktbr', '') . '[2]'; ?></th>
                    <td>
                        <select name="MukhtaberTeacher2" class="Enbl_Dsbl" id="MukhtaberTeacher2" data-required="true"
                                tabindex="44">
                            <option value>حدد المعلم المختبر</option>
                            <?php
                            do { ?>
                                <option
                                        value="<?php echo $row_RsMukhtaber['id'] ?>" <?php if ($row_RsMukhtaber['id'] == $row_RsErExams['MukhtaberTeacher2']) {
                                    echo 'selected="selected"';
                                } ?>><?php echo $row_RsMukhtaber['arabic_name'] ?></option>
                                <?php
                            } while ($row_RsMukhtaber = mysqli_fetch_assoc($RsMukhtaber));
                            $rows = mysqli_num_rows($RsMukhtaber);
                            if ($rows > 0) {
                                mysqli_data_seek($RsMukhtaber, 0);
                                $row_RsMukhtaber = mysqli_fetch_assoc($RsMukhtaber);
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <p>مجموع الدرجات</p>
                    </td>
                    <td>
                        <p id="Degree_P"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>النسبة</p>
                    </td>
                    <td>
                        <p id="Degree_percentage_P"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>التقدير</p>
                    </td>
                    <td>
                        <p id="MarkName_Long_p"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>مكافأة الطالب</p>
                    </td>
                    <td>
                        <p id="Money_p"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>مكافأة المعلم</p>
                    </td>
                    <td>
                        <p id="TeacherMoney_p"></p>
                    </td>
                </tr>
            </table>
        </div>
        <input type="hidden" name="Money" id="Money" value="<?php echo $row_RsErExams['Money']; ?>">
        <input type="hidden" name="TeacherMoney" id="TeacherMoney"
               value="<?php echo $row_RsErExams['teacher_money']; ?>">
        <input type="hidden" name="EdarahMoney" id="EdarahMoney" value="<?php echo $row_RsErExams['edarah_money']; ?>">
        <input type="hidden" name="MarkName_Short" id="MarkName_Short"
               value="<?php echo $row_RsErExams['MarkName_Short']; ?>">
        <input type="hidden" name="MarkName_Long" id="MarkName_Long"
               value="<?php echo $row_RsErExams['MarkName_Long']; ?>">
        <input type="hidden" name="TotalDiscount" id="TotalDiscount" value="<?php echo $TotalDiscount; ?>">
        <input type="hidden" name="Degree" id="Degree">
        <input type="hidden" name="Degree_percentage" id="Degree_percentage"
               value="<?php echo $row_RsErExams['FinalExamDegree']; ?>">

        <?php
        for ($i = 1; $i < 21; $i++) {
            ?>
            <input type="hidden" name="ResultSora<?php echo $i; ?>" id="ResultSora<?php echo $i; ?>"
                   value="<?php echo 10 - $row_RsErExams["Sora{$i}Discount"] ?>">

            <?php
        }
        ?>


        <input type="hidden" name="examPoints" id="examPoints" value="<?php echo $row_RsErExams['ExamPoints']; ?>">
        <br class="clear">


        <div class="four columns offset-by-six">
            <input type="submit" name="update" id="update" class="button-primary" value="حفظ">

            <div class="six columns omega">&nbsp;</div>
        </div>
    </div>
    <div class="content">
        <div class="FieldsTitle">طباعة</div>
        <div class="sixteen columns">
            <p class="note">(يجب حفظ البيانات قبل الطباعة)</p>
        </div>
        <div class="two columns alpha">&nbsp;</div>
        <?php
        if (isset($_SESSION['sex'])) {
            if ($_SESSION['sex'] == 0) {
                $female_lbl = '_g';
            } else {
                $female_lbl = '';
            }
        }
        ?>
        <br class="clear">
        <div class="four columns offset-by-six">
            <a href="reports/shahadah_20<?php echo $female_lbl; ?>.php?ExamNo=<?php echo $row_RsErExams['AutoNo']; ?>"
               class="button-primary full-width">طباعة وثيقة تخرج</a></div>
        <!--		<div class="four columns"><a href="reports/shokr--><?php //echo $female_lbl; ?><!--.php?ExamNo=-->
        <?php //echo $row_RsErExams['AutoNo']; ?><!--" class="button-primary full-width">طباعة شكر وتقدير</a></div>-->
        <!--		<div class="four columns"><a href="reports/esh3ar.php?AutoNo=-->
        <?php //echo $row_RsErExams['AutoNo']; ?><!--" class="button-primary full-width">طباعة اشعار</a></div>-->
        <div class="two columns omega">&nbsp;</div>
    </div>
</form>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php

if (isset ($_SESSION['msgFatherSent'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.set({delay: 5000});
            alertify.success("تم ارسال رسالة لولي الأمر");
        });
    </script>
    <?php
    $_SESSION['msgFatherSent'] = null;
    unset ($_SESSION['msgFatherSent']);
}

if (isset ($_SESSION['msgStudentSent'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.set({delay: 5000});
            alertify.success("تم ارسال رسالة للطالب");
        });
    </script>
    <?php
    $_SESSION['msgStudentSent'] = null;
    unset ($_SESSION['msgStudentSent']);
}

if (isset ($_SESSION['msgStudentNotSent'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.set({delay: 60000});
            alertify.error('<?php echo "لم ترسل رسالة للطالب للسبب التالي: " . "<br>" . $_SESSION['msgStudentNotSent'];?>');
        });
    </script>
    <?php
    $_SESSION['msgStudentNotSent'] = null;
    unset ($_SESSION['msgStudentNotSent']);
}

if (isset ($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.success("تم حفظ بيانات الشهادة");
        });
    </script>
    <?php
    $_SESSION['u1'] = null;
    unset ($_SESSION['u1']);
}
?>
<script type="text/javascript">
    showError();
</script>
<script>
    $(document).ready(function () {
        for (var i = 1; i < 21; i++) {
            if ($('#Sora' + i + 'Discount').val() === '') {
                $('#Sora' + i + 'Discount').val(0);
            }

        }


        calculate();


        $('.soraDiscount').keyup(function () {
            calculate();
        });

        function calculate() {
            var total_resultl = 0;
            var totalDiscount = 0;
            for (var i = 1; i < 21; i++) {
                $('#ResultSora' + i).val(10 - parseFloat(Math.abs($('#Sora' + i + 'Discount').val())));
                total_resultl += parseFloat($('#ResultSora' + i).val());
                totalDiscount += parseFloat($('#Sora' + i + 'Discount').val());
                $('#sora_degree_' + i).html(10 - Math.abs($('#Sora' + i + 'Discount').val()));
            }
//			total_resultl = Math.ceil(parseFloat($('#ResultSora1').val()) + parseFloat($('#ResultSora2').val()) + parseFloat($('#ResultSora3').val()) +
//				parseFloat($('#ResultSora4').val()) + parseFloat($('#ResultSora5').val()) + parseFloat($('#ResultSora6').val()) +
//				parseFloat($('#ResultSora7').val()) + parseFloat($('#ResultSora8').val()) + parseFloat($('#ResultSora9').val()) +
//				parseFloat($('#ResultSora10').val()));
            $('#Degree').val(total_resultl);

            $('#Degree_P').html(total_resultl);
            var degree_percentage = total_resultl * 100 / 200;
            $('#Degree_percentage_P').html(degree_percentage);
            $('#Degree_percentage').val(degree_percentage);
//			totalDiscount = parseFloat($('#Sora1Discount').val()) + parseFloat($('#Sora2Discount').val()) + parseFloat($('#Sora3Discount').val()) +
//				parseFloat($('#Sora4Discount').val()) + parseFloat($('#Sora5Discount').val()) + parseFloat($('#Sora6Discount').val()) +
//				parseFloat($('#Sora7Discount').val()) + parseFloat($('#Sora8Discount').val()) + parseFloat($('#Sora9Discount').val()) +
//				parseFloat($('#Sora10Discount').val());
            $('#TotalDiscount').val(totalDiscount);

            $('#MarkName_Long').val(GetMarkName20(degree_percentage, 'long'));
            $('#MarkName_Long_p').html(GetMarkName20(degree_percentage, 'long'));
            $('#MarkName_Short').val(GetMarkName20(degree_percentage, 'short'));

            $('#Money').val(parseFloat(GetMarkMony20($('#MarkName_Short').val())));
            $('#Money_p').html(parseFloat(GetMarkMony20($('#MarkName_Short').val())));
            $('#TeacherMoney').val(parseFloat(GetMarkMonyForTeacher20($('#MarkName_Short').val(), "<?php echo $row_RsErExams['ErtiqaID']; ?>")));
            $('#TeacherMoney_p').html(parseFloat(GetMarkMonyForTeacher20($('#MarkName_Short').val(), "<?php echo $row_RsErExams['ErtiqaID']; ?>")));
            $('#EdarahMoney').val(0);
            $('#examPoints').val(ExamPoints("<?php echo $row_RsErExams['ErtiqaID']; ?>", total_resultl));

        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("input[checked=checked]").parent().addClass('RadioSelected');

//تغيير لون المحدد
        $(':radio').change(function () {
            $(':radio[name="RadioStatus"]').parent().removeClass('RadioSelected');
            $(this).parent().addClass('RadioSelected');

            var ss = parseInt($(this).val(), 10);
            switch (ss) {
                case 2:
                    $(".Enbl_Dsbl").prop("disabled", false);
                    $(".Enbl_Dsbl").removeClass('disabledInput');
                    $('#form1').parsley('destroy');
                    $('#form1').parsley();
                    break;
                default:
                    $(".Enbl_Dsbl").prop("disabled", true);
                    $(".Enbl_Dsbl").addClass('disabledInput');
                    $('#form1').parsley('destroy');
                    break;
            }
        });
//formatter:off

//@formatter:on

        var status1 = parseInt('<?php echo $row_RsErExams["FinalExamStatus"]; ?>', 10);
//alert (status1);
        switch (status1) {
            case 2:
                $(".Enbl_Dsbl").prop("disabled", false);
                $(".Enbl_Dsbl").removeClass('disabledInput');
                $('#form1').parsley('destroy');
                $('#form1').parsley();
                break;
            default:
                $(".Enbl_Dsbl").prop("disabled", true);
                $(".Enbl_Dsbl").addClass('disabledInput');
                $('#form1').parsley('destroy');
                break;
        }
    });
</script>