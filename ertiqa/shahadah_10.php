<?php
require_once('../functions.php');
$editFormAction = $_SERVER ['PHP_SELF'];
if (isset ($_SERVER ['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER ['QUERY_STRING']);
}
$user_sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : 1;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$Result1 = null;
$Result2 = null;
$Result3 = null;
$Result4 = null;
if ((isset ($_POST ["MM_update"])) && ($_POST ["MM_update"] == "form1")) {

    if ($_POST ['RadioStatus'] == 2) {
        $updateSQL = sprintf("REPLACE INTO er_shahadah (
  Sora1Name, Sora1Discount, Sora2Name, Sora2Discount, Sora3Name, Sora3Discount, Sora4Name, Sora4Discount, Sora5Name, Sora5Discount,
  Sora6Name, Sora6Discount, Sora7Name, Sora7Discount, Sora8Name, Sora8Discount, Sora9Name, Sora9Discount, Sora10Name, Sora10Discount,
  Ek_slok, Ek_mwathbah, Degree, `Money`, teacher_money, edarah_money, examPoints, MarkName_Short, MarkName_Long, ExamNo, MukhtaberTeacher, MukhtaberTeacher2)
VALUES
  (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            GetSQLValueString(Input::get('Sora1Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora1Discount')), "double"),
            GetSQLValueString(Input::get('Sora2Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora2Discount')), "double"),
            GetSQLValueString(Input::get('Sora3Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora3Discount')), "double"),
            GetSQLValueString(Input::get('Sora4Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora4Discount')), "double"),
            GetSQLValueString(Input::get('Sora5Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora5Discount')), "double"),
            GetSQLValueString(Input::get('Sora6Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora6Discount')), "double"),
            GetSQLValueString(Input::get('Sora7Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora7Discount')), "double"),
            GetSQLValueString(Input::get('Sora8Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora8Discount')), "double"),
            GetSQLValueString(Input::get('Sora9Name'), "int"),
            GetSQLValueString(abs(Input::get('Sora9Discount')), "double"),
            GetSQLValueString(abs(Input::get('Sora10Name')), "int"),
            GetSQLValueString(abs(Input::get('Sora10Discount')), "double"),
            GetSQLValueString(abs(Input::get('Ek_slok')), "double"),
            GetSQLValueString(abs(Input::get('Ek_mwathbah')), "double"),
            GetSQLValueString(Input::get('Degree'), "double"),
            GetSQLValueString(Input::get('Money'), "int"),
            GetSQLValueString(Input::get('TeacherMoney'), "int"),
            GetSQLValueString(Input::get('EdarahMoney'), "int"),
            GetSQLValueString(Input::get('examPoints'), "double"),
            GetSQLValueString(Input::get('MarkName_Short'), "text"),
            GetSQLValueString(Input::get('MarkName_Long'), "text"),
            GetSQLValueString(Input::get('ExamNo'), "int"),
            GetSQLValueString(Input::get('MukhtaberTeacher'), "int"),
            GetSQLValueString(Input::get('MukhtaberTeacher2'), "int"));
        $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));
        $updateSQL2 = sprintf("UPDATE er_ertiqaexams
SET FinalExamStatus = % s WHERE AutoNo = % s",
            GetSQLValueString("2", "int"), GetSQLValueString($_POST ['ExamNo'], "int"));
        $Result2 = mysqli_query($localhost, $updateSQL2) or die(mysqli_error($localhost));
    } else {
        $updateSQL4 = sprintf("DELETE FROM er_shahadah
WHERE ExamNo = % s", GetSQLValueString($_POST ['ExamNo'], "int"));

        $Result4 = mysqli_query($localhost, $updateSQL4) or die(mysqli_error($localhost));
        $updateSQL3 = sprintf("UPDATE er_ertiqaexams
SET FinalExamStatus = % s WHERE AutoNo = % s", GetSQLValueString($_POST ['RadioStatus'], "int"), GetSQLValueString($_POST ['ExamNo'], "int"));
        $Result3 = mysqli_query($localhost, $updateSQL3) or die(mysqli_error($localhost));
    }

    updateMurajaahStatus($_POST['ExamNo'], $_POST['RadioStatus']);

    if (($Result1 && $Result2) || ($Result4 && $Result3)) {
        $msg_2 = null;
        $msg_3 = null;
        $colname_RsMobile = "-1";
        if (isset ($_GET ['ExamNo'])) {
            $colname_RsMobile = $_GET ['ExamNo'];
        }
        $query_RsMobile = sprintf("SELECT FatherMobileNo, edarah_sex,StID,EdarahID, StName1, MarkName_Long, O_MurtaqaName
                                    FROM view_er_ertiqaexams
                                    WHERE AutoNo = % s", GetSQLValueString($colname_RsMobile, "int"));
        $RsMobile = mysqli_query($localhost, $query_RsMobile) or die(mysqli_error($localhost));
        $row_RsMobile = mysqli_fetch_assoc($RsMobile);
        $totalRows_RsMobile = mysqli_num_rows($RsMobile);

        $numbers = '966' . substr($row_RsMobile['FatherMobileNo'], 1, 9);
        $msg = '';
        $sex = $row_RsMobile['edarah_sex'];
        $StName1 = $row_RsMobile['StName1'];
        $MarkName_Long = $row_RsMobile['MarkName_Long'];
        $murtaqa_name = $row_RsMobile['O_MurtaqaName'];
        if ($sex == 1) {
            $msg_2 = sprintf("نبارك لنا ولكم اجتياز ابنكم (%s) مرتقى (%s) بتقدير (%s), جعله الله قرة عين لكم.", $StName1, $murtaqa_name, $MarkName_Long);
            $msg_3 = sprintf("ابنكم (%s) لم يوفق في اجتياز مرتقى (%s),نسأل الله له التوفيق في الاختبار القادم.", $StName1, $murtaqa_name);
        } elseif ($sex == 0) {
            $msg_2 = sprintf("نبارك لنا ولكم اجتياز ابنتكم (%s) مرتقى (%s) بتقدير (%s), جعلها الله قرة عين لكم.", $StName1, $murtaqa_name, $MarkName_Long);
            $msg_3 = sprintf("ابنتكم (%s) لم توفق في اجتياز مرتقى (%s),نسأل الله لها التوفيق في الاختبار القادم.", $StName1, $murtaqa_name);
        }
        if ($_POST['RadioStatus'] == 2) {
            $msg = $msg_2;
        } else if ($_POST['RadioStatus'] == 3) {
            $msg = $msg_3;
        }
        if ($msg != '') {
            $SendingAnswer = sendSMS($numbers, $msg);
            if ($SendingAnswer == 1) {
                $_SESSION['msgFatherSent'] = '';
            } else {
                $_SESSION['msgFatherNotSent'] = $SendingAnswer;
            }
        }
        //إرسال رسال لمدير المجمع والدار
        sendMsgToModerator($row_RsMobile['StID'], $row_RsMobile['EdarahID'], $_POST['RadioStatus']);
        $_SESSION['u1'] = "";
    }

}
$colname_RsErExams = "-1";
if (isset ($_GET ['ExamNo'])) {
    $colname_RsErExams = $_GET ['ExamNo'];
}
$query_RsErExams = sprintf("SELECT * FROM view_er_ertiqaexams
WHERE AutoNo = % s", GetSQLValueString($colname_RsErExams, "int"));
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
                <td><?php echo $row_RsErExams['O_MurtaqaName']; ?></td>
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
    $TotalDiscount = $row_RsErExams ['Sora1Discount'] + $row_RsErExams ['Sora2Discount'] + $row_RsErExams ['Sora3Discount'] + $row_RsErExams ['Sora4Discount'] + $row_RsErExams ['Sora5Discount'] + $row_RsErExams ['Sora6Discount'] + $row_RsErExams ['Sora7Discount'] + $row_RsErExams ['Sora8Discount'] + $row_RsErExams ['Sora9Discount'] + $row_RsErExams ['Sora10Discount'];
    $TotalRremaining = (10 - $row_RsErExams ['Sora1Discount']) + (10 - $row_RsErExams ['Sora2Discount']) + (10 - $row_RsErExams ['Sora3Discount']) + (10 - $row_RsErExams ['Sora4Discount']) + (10 - $row_RsErExams ['Sora5Discount']) + (10 - $row_RsErExams ['Sora6Discount']) + (10 - $row_RsErExams ['Sora7Discount']) + (10 - $row_RsErExams ['Sora8Discount']) + (10 - $row_RsErExams ['Sora9Discount']) + (10 - $row_RsErExams ['Sora10Discount']);

    ?>
    <input type="hidden" name="MM_update" value="form1">
    <input
            type="hidden" name="ExamNo"
            value="<?php echo $row_RsErExams['AutoNo']; ?>">

    <div class="content">
        <div class="FieldsTitle">درجات الاختبار</div>
        <div class="eight columns alpha">
            <table class="shahadah">
                <tr>
                    <th>المقطع</th>
                    <th>اسم السورة</th>
                    <th>الدرجة المحسومة</th>
                </tr>
                <tr>
                    <th>الأول</th>
                    <td>
                        <select name="Sora1Name"
                                id="Sora1Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="1">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora1Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select>
                    </td>
                    <td>
                        <input name="Sora1Discount" id="Sora1Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora1Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="2" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الثاني</th>
                    <td><select name="Sora2Name"
                                id="Sora2Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="3">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora2Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora2Discount" id="Sora2Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora2Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="4" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الثالث</th>
                    <td><select name="Sora3Name"
                                id="Sora3Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="5">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora3Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora3Discount" id="Sora3Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora3Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="6" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الرابع</th>
                    <td><select name="Sora4Name"
                                id="Sora4Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="7">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora4Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora4Discount" id="Sora4Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora4Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="8" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الخامس</th>
                    <td><select name="Sora5Name"
                                id="Sora5Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="9">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora5Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora5Discount" id="Sora5Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora5Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="10" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>السادس</th>
                    <td><select name="Sora6Name"
                                id="Sora6Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="11">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora6Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora6Discount" id="Sora6Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora6Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="12" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>السابع</th>
                    <td><select name="Sora7Name"
                                id="Sora7Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="13">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora7Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora7Discount" id="Sora7Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora7Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="14" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الثامن</th>
                    <td><select name="Sora8Name"
                                id="Sora8Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="15">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora8Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora8Discount" id="Sora8Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora8Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="16" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>التاسع</th>
                    <td><select name="Sora9Name"
                                id="Sora9Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="17">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora9Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora9Discount" id="Sora9Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora9Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="18" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>العاشر</th>
                    <td><select name="Sora10Name"
                                id="Sora10Name" class="FullWidthCombo Enbl_Dsbl"
                                data-required="true" autofocus autocorrect="off" tabindex="19">
                            <option value>حدد السورة</option>
                            <?php $ii = 114;
                            do { ?>
                                <option value="<?php echo $ii; ?>"
                                    <?php if (!(strcmp($row_RsErExams['Sora10Name'], $ii))) {
                                        echo "selected=\"selected\"";
                                    } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php

                                if ($ii == $murtaqaEndSora [$row_RsErExams ['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0);
                            ?>
                        </select></td>
                    <td><input name="Sora10Discount" id="Sora10Discount"
                               class="soraDiscount Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Sora10Discount']; ?>" size="4"
                               maxlength="4" data-type="number" data-required="true"
                               data-max="20" tabindex="20" autocomplete="off"></td>
                </tr>
            </table>
        </div>
        <div class="eight columns omega">
            <table class="shahadah">
                <tr>
                    <th>السلــوك في الاختبار:</th>
                    <th>المواظبة في الاختبار:</th>
                </tr>
                <tr>
                    <td><input name="Ek_slok" id="Ek_slok"
                               class="Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Ek_slok']; ?>" size="4"
                               data-type="number" data-required="true" data-max="5" tabindex="21"
                               autocomplete="off"></td>
                    <td><input name="Ek_mwathbah" id="Ek_mwathbah"
                               class="Enbl_Dsbl" type="number" step="0.5"
                               value="<?php echo $row_RsErExams['Ek_mwathbah']; ?>" size="4"
                               data-type="number" data-required="true" data-max="5" tabindex="22"
                               autocomplete="off"></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <th><?php echo get_gender_label('mktbr', ''), $user_sex == 0 ? ' [1]' : ''; ?></th>
                    <td>
                        <select name="MukhtaberTeacher" class="FullWidthCombo Enbl_Dsbl" id="MukhtaberTeacher"
                                data-required="true" tabindex="23">
                            <option value>حدد المعلم المختبر</option>
                            <?php
                            if ($row_RsErExams['MukhtaberTeacher'] > 0) {
                                $selected_mktbr = $row_RsErExams['MukhtaberTeacher'];
                            } else {
                                $selected_mktbr = $user_id;
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
                <?php if ($user_sex == 0) { ?>
                    <tr>
                        <th><?php echo get_gender_label('mktbr', '') . ' [2]'; ?></th>
                        <td>
                            <select name="MukhtaberTeacher2" class="Enbl_Dsbl" id="MukhtaberTeacher2"
                                    data-required="true" tabindex="24">
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
                <?php } ?>
            </table>
        </div>
        <input type="hidden" name="Money" id="Money" value="<?php echo $row_RsErExams['Money']; ?>">
        <input type="hidden" name="TeacherMoney" id="TeacherMoney"
               value="<?php echo $row_RsErExams['teacher_money']; ?>">
        <input type="hidden" name="EdarahMoney" id="EdarahMoney" value="<?php echo $row_RsErExams['edarah_money']; ?>">
        <input type="hidden" name="TotalMwathabah" id="TotalMwathabah"
               value="<?php echo($row_RsErExams['H_MwadabahGrade'] + $row_RsErExams['Ek_mwathbah']); ?>">
        <input type="hidden" name="TotalSolok" id="TotalSolok"
               value="<?php echo($row_RsErExams['H_SolokGrade'] + $row_RsErExams['Ek_slok']); ?>">
        <input type="hidden" name="MarkName_Short" id="MarkName_Short"
               value="<?php echo $row_RsErExams['MarkName_Short']; ?>">
        <input type="hidden" name="MarkName_Long" id="MarkName_Long"
               value="<?php echo $row_RsErExams['MarkName_Long']; ?>">
        <input type="hidden" name="TotalDicount" id="TotalDicount" value="<?php echo $TotalDiscount; ?>">
        <input type="hidden" name="Degree" id="Degree" value="<?php echo $row_RsErExams['FinalExamDegree']; ?>">
        <input type="hidden" name="ResultSora1" id="ResultSora1"
               value="<?php echo 10 - $row_RsErExams['Sora1Discount'] ?>">
        <input type="hidden" name="ResultSora2" id="ResultSora2"
               value="<?php echo 10 - $row_RsErExams['Sora2Discount'] ?>">
        <input type="hidden" name="ResultSora3" id="ResultSora3"
               value="<?php echo 10 - $row_RsErExams['Sora3Discount'] ?>">
        <input type="hidden" name="ResultSora4" id="ResultSora4"
               value="<?php echo 10 - $row_RsErExams['Sora4Discount'] ?>">
        <input type="hidden" name="ResultSora5" id="ResultSora5"
               value="<?php echo 10 - $row_RsErExams['Sora5Discount'] ?>">
        <input type="hidden" name="ResultSora6" id="ResultSora6"
               value="<?php echo 10 - $row_RsErExams['Sora6Discount'] ?>">
        <input type="hidden" name="ResultSora7" id="ResultSora7"
               value="<?php echo 10 - $row_RsErExams['Sora7Discount'] ?>">
        <input type="hidden" name="ResultSora8" id="ResultSora8"
               value="<?php echo 10 - $row_RsErExams['Sora8Discount'] ?>">
        <input type="hidden" name="ResultSora9" id="ResultSora9"
               value="<?php echo 10 - $row_RsErExams['Sora9Discount'] ?>">
        <input type="hidden" name="ResultSora10" id="ResultSora10"
               value="<?php echo 10 - $row_RsErExams['Sora10Discount'] ?>">
        <input type="hidden" name="examPoints" id="examPoints" value="<?php echo $row_RsErExams['ExamPoints']; ?>">
        <br class="clear">

        <div class="six columns alpha">&nbsp;</div>
        <div class="four columns">
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

            if ($user_sex == 0) {
                $female_lbl = '_g';
            } else {
                $female_lbl = '';
            }

        ?>
        <div class="four columns"><a
                    href="reports/shahadah_10<?php echo $female_lbl; ?>.php?ExamNo=<?php echo $row_RsErExams['AutoNo']; ?>"
                    class="button-primary full-width">طباعة شهادة</a></div>
        <div class="four columns"><a
                    href="reports/shokr<?php echo $female_lbl; ?>.php?ExamNo=<?php echo $row_RsErExams['AutoNo']; ?>"
                    class="button-primary full-width">طباعة شكر وتقدير</a></div>
        <div class="four columns"><a href="reports/esh3ar.php?AutoNo=<?php echo $row_RsErExams['AutoNo']; ?>"
                                     class="button-primary full-width">طباعة اشعار</a></div>
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

if (isset ($_SESSION['msgFatherNotSent'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.set({delay: 60000});
            alertify.error('<?php echo "لم ترسل رسالة لولي الأمر للسبب التالي:" . "<br>" . $_SESSION['msgFatherNotSent'];?>');
        });
    </script>
    <?php
    $_SESSION['msgFatherNotSent'] = null;
    unset ($_SESSION['msgFatherNotSent']);
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
        if ($('#Sora1Discount').val() === '') {
            $('#Sora1Discount').val(0);
        }
        if ($('#Sora2Discount').val() === '') {
            $('#Sora2Discount').val(0);
        }
        if ($('#Sora3Discount').val() === '') {
            $('#Sora3Discount').val(0);
        }
        if ($('#Sora4Discount').val() === '') {
            $('#Sora4Discount').val(0);
        }
        if ($('#Sora5Discount').val() === '') {
            $('#Sora5Discount').val(0);
        }
        if ($('#Sora6Discount').val() === '') {
            $('#Sora6Discount').val(0);
        }
        if ($('#Sora7Discount').val() === '') {
            $('#Sora7Discount').val(0);
        }
        if ($('#Sora8Discount').val() === '') {
            $('#Sora8Discount').val(0);
        }
        if ($('#Sora9Discount').val() === '') {
            $('#Sora9Discount').val(0);
        }
        if ($('#Sora10Discount').val() === '') {
            $('#Sora10Discount').val(0);
        }

        if ($('#Ek_slok').val() === '') {
            $('#Ek_slok').val(5);
        }
        if ($('#Ek_mwathbah').val() === '') {
            $('#Ek_mwathbah').val(5);
        }

        calculate();

//$('#Ek_slok,#Ek_mwathbah').change(function(){
//});

        $('.soraDiscount,#Ek_slok,#Ek_mwathbah').change(function () {
            calculate();
        });

        function calculate() {
            $('#TotalSolok').val(parseFloat($('#Ek_slok').val()) + parseFloat("<?php echo $row_RsErExams['H_SolokGrade']; ?>"));
            $('#TotalMwathabah').val(parseFloat($('#Ek_mwathbah').val()) + parseFloat("<?php echo $row_RsErExams['H_MwadabahGrade']; ?>"));

            $('#ResultSora1').val(10 - parseFloat(Math.abs($('#Sora1Discount').val())));
            $('#ResultSora2').val(10 - parseFloat(Math.abs($('#Sora2Discount').val())));
            $('#ResultSora3').val(10 - parseFloat(Math.abs($('#Sora3Discount').val())));
            $('#ResultSora4').val(10 - parseFloat(Math.abs($('#Sora4Discount').val())));
            $('#ResultSora5').val(10 - parseFloat(Math.abs($('#Sora5Discount').val())));
            $('#ResultSora6').val(10 - parseFloat(Math.abs($('#Sora6Discount').val())));
            $('#ResultSora7').val(10 - parseFloat(Math.abs($('#Sora7Discount').val())));
            $('#ResultSora8').val(10 - parseFloat(Math.abs($('#Sora8Discount').val())));
            $('#ResultSora9').val(10 - parseFloat(Math.abs($('#Sora9Discount').val())));
            $('#ResultSora10').val(10 - parseFloat(Math.abs($('#Sora10Discount').val())));

            var totalresutl = Math.ceil(parseFloat($('#ResultSora1').val()) + parseFloat($('#ResultSora2').val()) + parseFloat($('#ResultSora3').val()) + parseFloat($('#ResultSora4').val()) + parseFloat($('#ResultSora5').val()) + parseFloat($('#ResultSora6').val()) + parseFloat($('#ResultSora7').val()) + parseFloat($('#ResultSora8').val()) + parseFloat($('#ResultSora9').val()) + parseFloat($('#ResultSora10').val()));
            $('#Degree').val(totalresutl);

            var totalDescount = parseFloat($('#Sora1Discount').val()) + parseFloat($('#Sora2Discount').val()) + parseFloat($('#Sora3Discount').val()) + parseFloat($('#Sora4Discount').val()) + parseFloat($('#Sora5Discount').val()) + parseFloat($('#Sora6Discount').val()) + parseFloat($('#Sora7Discount').val()) + parseFloat($('#Sora8Discount').val()) + parseFloat($('#Sora9Discount').val()) + parseFloat($('#Sora10Discount').val());
            $('#TotalDicount').val(totalDescount);

            $('#MarkName_Long').val(GetMarkName($('#Degree').val(), 'long'));
            $('#MarkName_Short').val(GetMarkName($('#Degree').val(), 'short'));

            var slok_Mwathabah = parseFloat($('#TotalSolok').val()) + parseFloat($('#TotalMwathabah').val());
            $('#Money').val(parseFloat(GetMarkMony($('#MarkName_Short').val(), "<?php echo $row_RsErExams['ErtiqaID']; ?>")) - (50 - slok_Mwathabah));
            $('#TeacherMoney').val(parseFloat(GetMarkMonyForTeacher($('#MarkName_Short').val(), "<?php echo $row_RsErExams['ErtiqaID']; ?>")));
            $('#EdarahMoney').val(parseFloat(GetMarkMonyForEdarah($('#MarkName_Short').val(), "<?php echo $row_RsErExams['ErtiqaID']; ?>")));
            $('#examPoints').val(ExamPoints("<?php echo $row_RsErExams['ErtiqaID']; ?>", totalresutl));

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