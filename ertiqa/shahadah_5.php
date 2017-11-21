<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    mysqli_select_db($localhost, $database_localhost);

    if ($_POST['RadioStatus'] == 2) {
        $updateSQL = sprintf("REPLACE INTO er_shahadah (Sora1Name,Sora1Discount,Sora2Name,Sora2Discount,Sora3Name,Sora3Discount,Sora4Name,Sora4Discount,Sora5Name,Sora5Discount,Ek_slok,Ek_mwathbah,Degree,`Money`,teacher_money,edarah_money,ExamPoints,MarkName_Short,MarkName_Long,ExamNo,MukhtaberTeacher,MukhtaberTeacher2)
  VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['Sora1Name'], "int"),
            GetSQLValueString(abs($_POST['Sora1Discount']), "double"),
            GetSQLValueString($_POST['Sora2Name'], "int"),
            GetSQLValueString(abs($_POST['Sora2Discount']), "double"),
            GetSQLValueString($_POST['Sora3Name'], "int"),
            GetSQLValueString(abs($_POST['Sora3Discount']), "double"),
            GetSQLValueString($_POST['Sora4Name'], "int"),
            GetSQLValueString(abs($_POST['Sora4Discount']), "double"),
            GetSQLValueString($_POST['Sora5Name'], "int"),
            GetSQLValueString(abs($_POST['Sora5Discount']), "double"),
            GetSQLValueString(abs($_POST['Ek_slok']), "double"),
            GetSQLValueString(abs($_POST['Ek_mwathbah']), "double"),
            GetSQLValueString($_POST['Degree'], "double"),
            GetSQLValueString($_POST['Money'], "int"),
            GetSQLValueString($_POST['TeacherMoney'], "int"),
            GetSQLValueString($_POST['EdarahMoney'], "int"),
            GetSQLValueString($_POST['examPoints'], "double"),
            GetSQLValueString($_POST['MarkName_Short'], "text"),
            GetSQLValueString($_POST['MarkName_Long'], "text"),
            GetSQLValueString($_POST['ExamNo'], "int"),
            GetSQLValueString($_POST['MukhtaberTeacher'], "int"),
            GetSQLValueString($_POST['MukhtaberTeacher2'], "int")
        );
        $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));

        $updateSQL2 = sprintf("UPDATE er_ertiqaexams SET FinalExamStatus=%s WHERE AutoNo=%s",
            GetSQLValueString("2", "int"),
            GetSQLValueString($_POST['ExamNo'], "int"));
        $Result2 = mysqli_query($localhost, $updateSQL2) or die(mysqli_error($localhost));

    } else {

        $updateSQL4 = sprintf("DELETE FROM er_shahadah WHERE ExamNo=%s", GetSQLValueString($_POST['ExamNo'], "int"));
        $Result4 = mysqli_query($localhost, $updateSQL4) or die(mysqli_error($localhost));
        $updateSQL3 = sprintf("UPDATE er_ertiqaexams SET FinalExamStatus=%s WHERE AutoNo=%s",
            GetSQLValueString($_POST['RadioStatus'], "int"),
            GetSQLValueString($_POST['ExamNo'], "int"));
        $Result3 = mysqli_query($localhost, $updateSQL3) or die(mysqli_error($localhost));
    }
    if (($Result1 && $Result2) || ($Result4 && $Result3)) {
        $colname_RsMobile = "-1";
        if (isset($_GET['ExamNo'])) {
            $colname_RsMobile = $_GET['ExamNo'];
        }
        $query_RsMobile = sprintf("SELECT FatherMobileNo,edarah_sex,StName1,MarkName_Long,O_MurtaqaName  FROM view_er_ertiqaexams WHERE AutoNo = %s", GetSQLValueString($colname_RsMobile, "int"));
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
            $SendingAnswer = sendSMS($numbers,  $msg);
            if ($SendingAnswer == 1) {
                $_SESSION['msgFatherSent'] = '';
            } else {
                $_SESSION['msgFatherNotSent'] = $SendingAnswer;
            }
        }
        $_SESSION['u1'] = "";
    }
}

$colname_RsErExams = "-1";
if (isset($_GET['ExamNo'])) {
    $colname_RsErExams = $_GET['ExamNo'];
}
mysqli_select_db($localhost, $database_localhost);
$query_RsErExams = sprintf("SELECT AutoNo,O_StudentName,FatherMobileNo,O_TeacherName,O_Edarah,O_HName,O_MurtaqaName,ErtiqaID,FinalExamStatus,Sora1Name,Sora1Discount,Sora2Name,Sora2Discount,Sora3Name,Sora3Discount,Sora4Name,Sora4Discount,Sora5Name,Sora5Discount,H_SolokGrade,H_MwadabahGrade,Ek_mwathbah,Ek_slok,MarkName_Short,MarkName_Long,Money,teacher_money,edarah_money,ExamPoints,FinalExamDegree,MukhtaberTeacher,MukhtaberTeacher2 FROM view_er_ertiqaexams WHERE AutoNo = %s", GetSQLValueString($colname_RsErExams, "int"));
$RsErExams = mysqli_query($localhost, $query_RsErExams) or die(mysqli_error($localhost));
$row_RsErExams = mysqli_fetch_assoc($RsErExams);
$totalRows_RsErExams = mysqli_num_rows($RsErExams);

$sql_sex = sql_sex('sex');
mysqli_select_db($localhost, $database_localhost);
$query_RsMukhtaber = "SELECT id,arabic_name FROM 0_users WHERE hidden = 0 {$sql_sex} AND user_group='mktbr' ORDER BY arabic_name ASC";
$RsMukhtaber = mysqli_query($localhost, $query_RsMukhtaber) or die(mysqli_error($localhost));
$row_RsMukhtaber = mysqli_fetch_assoc($RsMukhtaber);
$totalRows_RsMukhtaber = mysqli_num_rows($RsMukhtaber);
?>
<?php include('../templates/header1.php'); ?>
<?php $PageTitle = 'بيانات الاختبار'; ?>
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
        /*font-size: 16px;*/
        width: 100%;
        margin-top: 10px;
    }

    .shahadah th, .shahadah td {
        border: 1px solid #999;
        padding: 2px;
        text-align: center;
        vertical-align: middle;
    }

    .shahadah th {
        padding: 5px 2px;
    }
</style>
</head>
<body>
<?php include('../templates/header2.php'); ?>
<?php include('../templates/nav_menu.php'); ?>
<div id="PageTitle"><?php echo $PageTitle; ?></div>

<!--PageTitle-->
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
            ?>
            <?php if ($statusName[$i][0] == '0') {
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
                    type="radio" data-required="true" name="RadioStatus" value="<?php echo $statusName[$i][0]; ?>" id="RadioStatus_<?php echo $statusName[$i][0]; ?>">
                <?php echo $statusName[$i][1]; ?> </label>
            <?php echo '</div>'; ?>
        <?php }
        unset($StatusN); ?>
    </div>
    <?php
    $TotalDiscount = $row_RsErExams['Sora1Discount'] + $row_RsErExams['Sora2Discount'] + $row_RsErExams['Sora3Discount'] + $row_RsErExams['Sora4Discount'] + $row_RsErExams['Sora5Discount'];
    $TotalRremaining = (20 - $row_RsErExams['Sora1Discount']) + (20 - $row_RsErExams['Sora2Discount']) + (20 - $row_RsErExams['Sora3Discount']) + (20 - $row_RsErExams['Sora4Discount']) + (20 - $row_RsErExams['Sora5Discount']);

    ?>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="ExamNo" value="<?php echo $row_RsErExams['AutoNo']; ?>">

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
                    <td><select placeholder="اختر السورة..." name="Sora1Name" id="Sora1Name" class="Enbl_Dsbl" data-required="true" autofocus autocorrect="off" tabindex="1">
                            <option value>حدد السورة</option>
                            <?php $ii = 116;
                            do { ?>
                                <option value="<?php echo $ii; ?>"<?php if ($row_RsErExams['Sora1Name'] == $ii) {
                                    echo 'selected="selected"';
                                } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php if ($ii == $murtaqaEndSora[$row_RsErExams['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0); ?>
                        </select></td>
                    <td><input name="Sora1Discount" id="Sora1Discount" class="soraDiscount Enbl_Dsbl" type="number" step="0.5" value="<?php echo $row_RsErExams['Sora1Discount']; ?>" size="4" maxlength="4"
                               data-type="number" data-required="true" data-max="20" tabindex="2" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الثاني</th>
                    <td><select placeholder="اختر السورة..." name="Sora2Name" id="Sora2Name" class="Enbl_Dsbl" data-required="true" autofocus autocorrect="off" tabindex="3">
                            <option value>حدد السورة</option>
                            <?php $ii = 116;
                            do { ?>
                                <option value="<?php echo $ii; ?>"<?php if ($row_RsErExams['Sora2Name'] == $ii) {
                                    echo 'selected="selected"';
                                } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php if ($ii == $murtaqaEndSora[$row_RsErExams['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0); ?>
                        </select></td>
                    <td><input name="Sora2Discount" id="Sora2Discount" class="soraDiscount Enbl_Dsbl" type="number" step="0.5" value="<?php echo $row_RsErExams['Sora2Discount']; ?>" size="4" maxlength="4"
                               data-type="number" data-required="true" data-max="20" tabindex="4" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الثالث</th>
                    <td><select placeholder="اختر السورة..." name="Sora3Name" id="Sora3Name" class="Enbl_Dsbl" data-required="true" autofocus autocorrect="off" tabindex="5">
                            <option value>حدد السورة</option>
                            <?php $ii = 116;
                            do { ?>
                                <option value="<?php echo $ii; ?>"<?php if ($row_RsErExams['Sora3Name'] == $ii) {
                                    echo 'selected="selected"';
                                } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php if ($ii == $murtaqaEndSora[$row_RsErExams['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0); ?>
                        </select></td>
                    <td><input name="Sora3Discount" id="Sora3Discount" class="soraDiscount Enbl_Dsbl" type="number" step="0.5" value="<?php echo $row_RsErExams['Sora3Discount']; ?>" size="4" maxlength="4"
                               data-type="number" data-required="true" data-max="20" tabindex="6" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الرابع</th>
                    <td><select placeholder="اختر السورة..." name="Sora4Name" id="Sora4Name" class="Enbl_Dsbl" data-required="true" autofocus autocorrect="off" tabindex="7">
                            <option value>حدد السورة</option>
                            <?php $ii = 116;
                            do { ?>
                                <option value="<?php echo $ii; ?>"<?php if ($row_RsErExams['Sora4Name'] == $ii) {
                                    echo 'selected="selected"';
                                } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php if ($ii == $murtaqaEndSora[$row_RsErExams['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0); ?>
                        </select></td>
                    <td><input name="Sora4Discount" id="Sora4Discount" class="soraDiscount Enbl_Dsbl" type="number" step="0.5" value="<?php echo $row_RsErExams['Sora4Discount']; ?>" size="4" maxlength="4"
                               data-type="number" data-required="true" data-max="20" tabindex="8" autocomplete="off"></td>
                </tr>
                <tr>
                    <th>الخامس</th>
                    <td><select placeholder="اختر السورة..." name="Sora5Name" id="Sora5Name" class="Enbl_Dsbl" data-required="true" autofocus autocorrect="off" tabindex="9">
                            <option value>حدد السورة</option>
                            <?php $ii = 116;
                            do { ?>
                                <option value="<?php echo $ii; ?>"<?php if ($row_RsErExams['Sora5Name'] == $ii) {
                                    echo 'selected="selected"';
                                } ?>> <?php echo $soraName[$ii] ?></option>
                                <?php if ($ii == $murtaqaEndSora[$row_RsErExams['ErtiqaID']]) {
                                    break;
                                }
                                $ii--;
                            } while ($ii > 0); ?>
                        </select></td>
                    <td><input name="Sora5Discount" id="Sora5Discount" class="soraDiscount Enbl_Dsbl" type="number" step="0.5" value="<?php echo $row_RsErExams['Sora5Discount']; ?>" size="4" maxlength="4"
                               data-type="number" data-required="true" data-max="20" tabindex="10" autocomplete="off"></td>
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
                    <td><input name="Ek_slok" id="Ek_slok" type="number" class="Enbl_Dsbl" value="<?php echo $row_RsErExams['Ek_slok']; ?>" size="4" data-type="number" step="0.5" data-required="true" data-max="5"
                               tabindex="11" autocomplete="off"></td>
                    <td><input name="Ek_mwathbah" id="Ek_mwathbah" type="number" class="Enbl_Dsbl" value="<?php echo $row_RsErExams['Ek_mwathbah']; ?>" size="4" data-type="number" step="0.5" data-required="true"
                               data-max="5" tabindex="12" autocomplete="off"></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;<br></td>
                </tr>
                <tr>
                    <th><?php echo get_gender_label('mktbr', ''), $_SESSION['sex'] == 0 ? ' [1]' : ''; ?></th>
                    <td><select name="MukhtaberTeacher" class="Enbl_Dsbl" id="MukhtaberTeacher" data-required="true" tabindex="13">
                            <option value>حدد المعلم المختبر</option>
                            <?php
                            if ($row_RsErExams['MukhtaberTeacher'] > 0) {
                                $selected_mktbr = $row_RsErExams['MukhtaberTeacher'];
                            } else {
                                $selected_mktbr = $_SESSION['user_id'];
                            }
                            do {

                                ?>
                                <option value="<?php echo $row_RsMukhtaber['id'] ?>"<?php if ($row_RsMukhtaber['id'] == $selected_mktbr) {
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
                        </select></td>
                </tr>
                <?php if ($_SESSION['sex'] == 0) { ?>
                    <tr>
                        <th><?php echo get_gender_label('mktbr', '') . ' [2]'; ?></th>
                        <td><select name="MukhtaberTeacher2" class="Enbl_Dsbl" id="MukhtaberTeacher2" data-required="true" tabindex="14">
                                <option value>حدد المعلم المختبر</option>
                                <?php
                                do {

                                    ?>
                                    <option value="<?php echo $row_RsMukhtaber['id'] ?>" <?php if ($row_RsMukhtaber['id'] == $row_RsErExams['MukhtaberTeacher2']) {
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
                            </select></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <input type="hidden" name="Money" id="Money" value="<?php echo $row_RsErExams['Money']; ?>">
        <input type="hidden" name="TeacherMoney" id="TeacherMoney" value="<?php echo $row_RsErExams['teacher_money']; ?>">
        <input type="hidden" name="EdarahMoney" id="EdarahMoney" value="<?php echo $row_RsErExams['edarah_money']; ?>">
        <input type="hidden" name="TotalMwathabah" id="TotalMwathabah" value="<?php echo($row_RsErExams['H_MwadabahGrade'] + $row_RsErExams['Ek_mwathbah']); ?>">
        <input type="hidden" name="TotalSolok" id="TotalSolok" value="<?php echo($row_RsErExams['H_SolokGrade'] + $row_RsErExams['Ek_slok']); ?>">
        <input type="hidden" name="MarkName_Short" id="MarkName_Short" value="<?php echo $row_RsErExams['MarkName_Short']; ?>">
        <input type="hidden" name="MarkName_Long" id="MarkName_Long" value="<?php echo $row_RsErExams['MarkName_Long']; ?>">
        <input type="hidden" name="TotalDicount" id="TotalDicount" value="<?php echo $TotalDiscount; ?>">
        <input type="hidden" name="Degree" id="Degree" value="<?php echo $row_RsErExams['FinalExamDegree']; ?>">
        <input type="hidden" name="ResultSora5" id="ResultSora5" value="<?php echo 20 - $row_RsErExams['Sora5Discount']; ?>">
        <input type="hidden" name="ResultSora4" id="ResultSora4" value="<?php echo 20 - $row_RsErExams['Sora4Discount']; ?>">
        <input type="hidden" name="ResultSora3" id="ResultSora3" value="<?php echo 20 - $row_RsErExams['Sora3Discount']; ?>">
        <input type="hidden" name="ResultSora2" id="ResultSora2" value="<?php echo 20 - $row_RsErExams['Sora2Discount']; ?>">
        <input type="hidden" name="ResultSora1" id="ResultSora1" value="<?php echo 20 - $row_RsErExams['Sora1Discount']; ?>">
        <input type="hidden" name="FatherMobileNo" id="FatherMobileNo" value="<?php echo $row_RsErExams['FatherMobileNo']; ?>">
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
        <?php
        if (isset($_SESSION['sex'])) {
            if ($_SESSION['sex'] == 0) {
                $female_lbl = '_g';
            } else {
                $female_lbl = '';
            }
        }
        ?>
        <div class="two columns alpha">&nbsp;</div>
        <div class="four columns"><a href="reports/shahadah_5<?php echo $female_lbl; ?>.php?ExamNo=<?php echo $row_RsErExams['AutoNo']; ?>" class="button-primary full-width">طباعة شهادة</a></div>
        <div class="four columns"><a href="reports/shokr<?php echo $female_lbl; ?>.php?ExamNo=<?php echo $row_RsErExams['AutoNo']; ?>" class="button-primary full-width">طباعة شكر وتقدير</a></div>
        <div class="four columns"><a href="reports/esh3ar.php?AutoNo=<?php echo $row_RsErExams['AutoNo']; ?>" class="button-primary full-width">طباعة اشعار</a></div>
        <div class="two columns omega">&nbsp;</div>
    </div>
</form>
<!--content-->
<?php include('../templates/footer.php'); ?>
<?php
if (isset($_SESSION['msgFatherSent'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.set({delay: 5000});
            alertify.success("تم ارسال رسالة لولي الأمر");
        });

    </script>
    <?php
    $_SESSION['msgFatherSent'] = NULL;
    unset($_SESSION['msgFatherSent']);
}

if (isset($_SESSION['msgFatherNotSent'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.set({delay: 60000});
            alertify.error('<?php echo "لم ترسل رسالة لولي الأمر للسبب التالي:"."<br>".iconv("Windows-1256","UTF-8",$arraySendMsg[$_SESSION['msgFatherNotSent']]);?>');
        });
    </script>
    <?php
    $_SESSION['msgFatherNotSent'] = NULL;
    unset($_SESSION['msgFatherNotSent']);
}

if (isset($_SESSION['u1'])) {
    ?>
    <script>
        $(document).ready(function () {
            alertify.set({delay: 5000});
            alertify.success("تم حفظ بيانات الشهادة");
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

            $('#ResultSora1').val(20 - parseFloat(Math.abs($('#Sora1Discount').val())));
            $('#ResultSora2').val(20 - parseFloat(Math.abs($('#Sora2Discount').val())));
            $('#ResultSora3').val(20 - parseFloat(Math.abs($('#Sora3Discount').val())));
            $('#ResultSora4').val(20 - parseFloat(Math.abs($('#Sora4Discount').val())));
            $('#ResultSora5').val(20 - parseFloat(Math.abs($('#Sora5Discount').val())));

            var totalresutl = Math.ceil(parseFloat($('#ResultSora1').val()) + parseFloat($('#ResultSora2').val()) + parseFloat($('#ResultSora3').val()) + parseFloat($('#ResultSora4').val()) + parseFloat($('#ResultSora5').val()));
            $('#Degree').val(totalresutl);

            var totalDescount = parseFloat($('#Sora1Discount').val()) + parseFloat($('#Sora2Discount').val()) + parseFloat($('#Sora3Discount').val()) + parseFloat($('#Sora4Discount').val()) + parseFloat($('#Sora5Discount').val());
            $('#TotalDicount').val(totalDescount);

            $('#MarkName_Long').val(GetMarkName($('#Degree').val(), 'long'));
            $('#MarkName_Short').val(GetMarkName($('#Degree').val(), 'short'));

            var slok_Mwathabah = parseFloat($('#TotalSolok').val()) + parseFloat($('#TotalMwathabah').val());
            $('#Money').val(parseFloat(GetMarkMony($('#MarkName_Short').val(), "<?php echo $row_RsErExams['ErtiqaID']; ?>")) - (50 - slok_Mwathabah));
            $('#TeacherMoney').val(parseFloat(GetMarkMonyForTeacher($('#MarkName_Short').val(), "<?php echo $row_RsErExams['ErtiqaID']; ?>")));
            $('#EdarahMoney').val(parseFloat(GetMarkMonyForEdarah($('#MarkName_Short').val(), "<?php echo $row_RsErExams['ErtiqaID']; ?>")));
            //alert(slok_Mwathabah+'--'+$('#Money').val());
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
