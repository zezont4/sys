<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../functions.php'); ?>
<?php require_once '../secure/functions.php'; ?>
<?php sec_session_start(); ?>
<?php //include('../mobily_ws/includeSettings.php'); ?>
<?php $PageTitle = 'تحديد موعد الاختبار'; ?>
<?php if (login_check("admin,er,mktbr") == true) {
    $EdarahIDS = $_SESSION['user_id'];
    $statusName_sql = filter_array($statusName, 'جميع حالات الاختبار', 0, 'FinalExamStatus');
    $murtaqaName_sql = filter_array($murtaqaName, 'جميع المرتقيات', 0, 'ErtiqaID');
    $MarkName_Long_sql = filter_array($MarkName_Long, 'جميع التقديرات', 0, 'MarkName_Short');
    $EdarahID = isset($_GET['EdarahID']) ? $_GET['EdarahID'] : '';
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $statusName_post = isset($_GET['statusName']) ? $_GET['statusName'] : '';
    $murtaqa_name_post = isset($_GET['murtaqaName']) ? $_GET['murtaqaName'] : '';
    $degreeName = isset($_GET['degreeName']) ? $_GET['degreeName'] : '';
    $Date1 = isset($_GET['Date1']) ? $_GET['Date1'] : '';
    $Date2 = isset($_GET['Date2']) ? $_GET['Date2'] : '';
    $editFormAction = $_SERVER['PHP_SELF'];
    $Date1_Rs1 = '';
    $Date2_Rs1 = '';
    $edarah_id = '';
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
    }

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
        $Result1 = null;
        $i = 0;
        $selected_exam_ids = [];
        foreach ($_POST['AutoNo'] as $AutoNo) {
//			echo $AutoNo;
            if ($_POST["FinalExamDate$i"] !== '') {
                if ($_POST["FinalExamStatus$i"] == '0') {
                    $updateSQL = sprintf("UPDATE er_ertiqaexams SET FinalExamDate=%s,FinalExamStatus=1 WHERE AutoNo=%s",
                        GetSQLValueString(str_replace('/', '', $_POST["FinalExamDate$i"]), "int"),
                        GetSQLValueString($AutoNo, "int"));
                } else {
                    $updateSQL = sprintf("UPDATE er_ertiqaexams SET FinalExamDate=%s WHERE AutoNo=%s",
                        GetSQLValueString(str_replace('/', '', $_POST["FinalExamDate$i"]), "int"),
                        GetSQLValueString($AutoNo, "int"));
                }
            } else {
                $updateSQL = sprintf("UPDATE er_ertiqaexams SET FinalExamDate=NULL,FinalExamStatus=0 WHERE AutoNo=%s",
                    GetSQLValueString($AutoNo, "int"));
            }
            mysqli_select_db($localhost, $database_localhost);
            $Result1 = mysqli_query($localhost, $updateSQL) or die(mysqli_error($localhost));

            if ($_POST["sms$i"] == 1) {
                $selected_exam_ids[] = $AutoNo;
            }

            if ($Result1) {
                $_SESSION['u1'] = "u1";
            }

            ++$i;
        }
        $SendingAnswer = [];
        /*الرسائل النصية */
        foreach ($selected_exam_ids as $exam_id) {
//		if ($msg != '') {
            $query_RsMobile = sprintf("SELECT FatherMobileNo,edarah_sex,StName1,MarkName_Long,O_MurtaqaName,O_FinalExamDate,FinalExamDate  FROM view_er_ertiqaexams WHERE AutoNo = %s",
                GetSQLValueString($exam_id, "int"));

            $RsMobile = mysqli_query($localhost, $query_RsMobile) or die(mysqli_error($localhost));
            $row_RsMobile = mysqli_fetch_assoc($RsMobile);
            $totalRows_RsMobile = mysqli_num_rows($RsMobile);

            $sex = $row_RsMobile['edarah_sex'];
            $StName1 = $row_RsMobile['StName1'];
            $MarkName_Long = $row_RsMobile['MarkName_Long'];
            $murtaqa_name = $row_RsMobile['O_MurtaqaName'];
            $final_exam_date = $row_RsMobile['O_FinalExamDate'];
            $msg_2 = '';
            if ($sex == 1) {
                $msg_2 = implode(' ', [
                    'تم حجز موعد الإختبار النهائي لابنكم',
                    $StName1,
                    'لمرتقى',
                    $murtaqa_name,
                    'في مقر الجمعية وذلك يوم ',
                    getHijriDayName(str_ireplace('/', '', $final_exam_date)),
                    'الموافق',
                    str_ireplace('/', '-', $final_exam_date) . 'هـ',
                ]);
            } elseif ($sex == 0) {
                $msg_2 = implode(' ', [
                    'تم حجز موعد الإختبار النهائي لإبنتكم',
                    $StName1,
                    'لمرتقى',
                    $murtaqa_name,
                    'وسيكون الاختبار في يوم',
                    getHijriDayName(str_ireplace('/', '', $final_exam_date)),
                    'الموافق',
                    str_ireplace('/', '-', $final_exam_date) . 'هـ',
                    'نرجو استعدادها وحضورها حسب الموعد',
                ]);
            }
            $msg = $msg_2;

            $numbers = '966' . substr($row_RsMobile['FatherMobileNo'], 1, 9);
            $SendingAnswer[$exam_id] = sendSMS($numbers, $msg);
            if ($SendingAnswer[$exam_id] == 1) {
                $_SESSION['msgFatherSent'] = '';
            } else {
                $_SESSION['msgFatherNotSent'] = $SendingAnswer[$exam_id];
            }
        }
        $sms_ok = 0;
        $sms_error = 0;
        foreach ($SendingAnswer as $answer) {
            if ($answer == 1) {
                $sms_ok++;
            } else {
                $sms_error++;
            }
        }

        if ($sms_ok) {
            $_SESSION['sms_ok'] = count($sms_ok);
        }
        if ($sms_error) {
            $_SESSION['sms_error'] = count($sms_error);
        }

    }

    $currentPage = $_SERVER["PHP_SELF"];

    $maxRows_ReExams = 40;
    $pageNum_ReExams = 0;
    if (isset($_GET['pageNum_ReExams'])) {
        $pageNum_ReExams = $_GET['pageNum_ReExams'];
    }


    $startRow_ReExams = $pageNum_ReExams * $maxRows_ReExams;
    $sql_sex = sql_sex('edarah_sex');

//	if (isset($_GET['MM_show'])) {
    $Date1_Rs1 = '';
    $Date2_Rs1 = '';
    $edarah_id = '';
    if (isset($_GET['statusName'])) {
        if ($_GET['statusName'] == ' and FinalExamStatus =0 ' || $_GET['statusName'] == null) {
            $Date1_Rs1 = '';
            $Date2_Rs1 = '';
        } else {
            $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', $_SESSION ['default_start_date']);
            if (isset($_GET['Date1'])) {
                if ($_GET['Date1'] != null) {
                    $Date1_Rs1 = 'and FinalExamDate>=' . str_replace('/', '', $_GET['Date1']);
                }
            }
            $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', $_SESSION ['default_end_date']);
            if (isset($_GET['Date2'])) {
                if ($_GET['Date2'] != null) {
                    $Date2_Rs1 = 'and FinalExamDate<=' . str_replace('/', '', $_GET['Date2']);
                }
            }
        }
    }
    if (isset($_GET['EdarahID'])) {
        if ($_GET['EdarahID'] != null) {
            $edarah_id = 'and EdarahID=' . str_replace('/', '', $_GET['EdarahID']);
        }
    }
    mysqli_select_db($localhost, $database_localhost);


    $query_ReExams = sprintf("SELECT RegesterTime,StID,StName1,StName2,StName3,StName4,O_StudentName3,O_TeacherName3,O_FinalExamDate,
O_Edarah,O_HName,O_MurtaqaName,ErtiqaID,AutoNo,FinalExamStatus
FROM view_er_ertiqaexams
where StID>0 %s %s %s %s %s %s %s
ORDER BY AutoNo DESC",
        $sql_sex,
        $statusName_post,
        $murtaqa_name_post,
        $degreeName,
        $Date1_Rs1,
        $Date2_Rs1,
        $edarah_id
    );
//    die($query_ReExams);
//	echo $query_ReExams;
    $query_limit_ReExams = sprintf("%s LIMIT %d,%d", $query_ReExams, $startRow_ReExams, $maxRows_ReExams);
    $ReExams = mysqli_query($localhost, $query_limit_ReExams) or die(mysqli_error($localhost));
    $row_ReExams = mysqli_fetch_assoc($ReExams);

    $all_ReExams = mysqli_query($localhost, $query_ReExams);
    $totalRows_ReExams = mysqli_num_rows($all_ReExams);

    $totalPages_ReExams = ceil($totalRows_ReExams / $maxRows_ReExams) - 1;

    $queryString_ReExams = "";
    if (!empty($_SERVER['QUERY_STRING'])) {
        $params = explode("&", $_SERVER['QUERY_STRING']);
        $newParams = [];
        foreach ($params as $param) {
            if (stristr($param, "pageNum_ReExams") == false &&
                stristr($param, "totalRows_ReExams") == false
            ) {
                array_push($newParams, $param);
            }
        }
        if (count($newParams) != 0) {
            $queryString_ReExams = "&" . htmlentities(implode("&", $newParams));
        }
    }
    $queryString_ReExams = sprintf("&totalRows_ReExams=%d%s", $totalRows_ReExams, $queryString_ReExams);
    ?>
    <?php include('../templates/header1.php'); ?>
    <title><?php echo $PageTitle; ?></title>
    <script>
        var fullDate = '';
        var split_date = [];
        var day_no = '';
    </script>
    </head>
    <body>
    <?php include('../templates/header2.php'); ?>
    <?php include('../templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
    <!--PageTitle-->

    <div class="content no_print">
        <div class="FieldsTitle">تاريخ الاحصائية</div>
        <form name="form2" method="get" action="<?php echo $editFormAction; ?>">
            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="Date1">التاريخ الأول</label>
                </div>
                <input type="text" name="Date1" value="<?php echo isset($_GET['Date1']) ? $_GET['Date1'] : '' ?>" id="Date1" zezo_date="true">
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="Date2">التاريخ الثاني</label>
                </div>
                <input type="text" name="Date2" value="<?php echo isset($_GET['Date2']) ? $_GET['Date2'] : '' ?>" id="Date2" zezo_date="true">
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="Date2">نتيجة الاختبار</label>
                </div>
                <?php echo create_combo('statusName', $statusName_sql, 0, $statusName_post); ?>
            </div>
            <div class="four columns omega">
                <div class="LabelContainer">
                    <label for="Date2">المجمع/الدار</label>
                </div>
                <?php
                if ($_SESSION['user_group'] == 'edarh') {
                    create_edarah_combo($_SESSION['user_id']);
                } else {
                    create_edarah_combo($EdarahID);
                }
                ?>
            </div>
            <br class="clear">
            <div class="four columns alpha">
                <div class="LabelContainer">
                    <label for="Date2">المرتقى</label>
                </div>
                <?php echo create_combo('murtaqaName', $murtaqaName_sql, 0, $murtaqa_name_post); ?>
            </div>
            <div class="four columns">
                <div class="LabelContainer">
                    <label for="Date2">التقدير</label>
                </div>
                <?php echo create_combo('degreeName', $MarkName_Long_sql, 0, $degreeName); ?>
            </div>
            <div class="four columns omega left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="استعلام"/>
            </div>
            <input type="hidden" name="MM_show" value="form1">
        </form>
        <?php if (isset($Date1_Rs1)) { ?>
            <br class="clear">
            <div class="sixteen">
                <p>احصائية <?php echo(($Date1 == '' && $Date2 == '') ? ' للعام الدراسي ( ' . $_SESSION ['default_year_name'] . ' ) ' : ''); ?>
                    خلال الفترة
                    من <?php echo(($Date1 != '') ? $Date1 : StringToDate($_SESSION ['default_start_date']) . ' هـ '); ?>
                    إلى <?php echo(($Date2 != '') ? $Date2 : StringToDate($_SESSION ['default_end_date']) . ' هـ '); ?> </p></div>
        <?php } ?>
        <?php if ($totalRows_ReExams > 0) { // Show if recordset not empty ?>
    </div>

    <div class="CSSTableGenerator content lp">
        <form name="form1" id="form1" method="post" data-validate="parsley" action="<?php echo $editFormAction; ?>">
            <table border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td>تاريخ<br>التسجيل</td>
                    <td><?php echo get_gender_label('st', 'ال'); ?></td>
                    <!--				<td class="NoMobile">--><?php //echo get_gender_label('t', 'ال'); ?><!--</td>-->
                    <td class="NoMobile"><?php echo get_gender_label('e', 'ال'); ?></td>
                    <td>المرتقى</td>
                    <td class="NoMobile">اليوم</td>
                    <td>التاريخ</td>
                    <td class="NoMobile">الحالة</td>
                    <td>تفاصيل<br>
                        الاختبار
                    </td>
                    <!--				<td class="NoMobile">اختبارات<br> سابقة</td>-->
                    <!--				<td class="NoMobile">تعديل<br> الحجز</td>-->
                    <td class="NoMobile">بحث<br>وتعديل</td>
                    <td class="NoMobile">رسالة<br>نصية</td>
                </tr>
                <?php
                $array1 = 0;
                do { ?>
                <input name="AutoNo[<?php echo $array1; ?>]" type="hidden"
                       value="<?php echo $row_ReExams['AutoNo']; ?>">
                <tr class="Status_<?php echo $row_ReExams['FinalExamStatus']; ?>">

                    <td id="h_date<?php echo $array1; ?>">
                        <img height="12px" src="/sys/_images/loader.gif" alt="انتظر...">

                    </td>

                    <td><?php echo $row_ReExams['O_StudentName3']; ?>
                        <input type="hidden" id="g_date<?php echo $array1; ?>"
                               value="<?php echo trim(str_replace('-', '', substr($row_ReExams['RegesterTime'], 0, 11))); ?>">
                    </td>

                    <!--				<td class="NoMobile">-->
                    <?php //echo $row_ReExams['O_TeacherName3']; ?><!--</td>-->

                    <td class="NoMobile"><?php echo str_replace("مجمع ", "", $row_ReExams['O_Edarah']); ?></td>

                    <td><?php echo get_array_1($murtaqaName, $row_ReExams['ErtiqaID']) ; ?>
                        <input name="FinalExamStatus<?php echo $array1; ?>" type="hidden"
                               id="FinalExamStatus<?php echo $array1; ?>"
                               value="<?php echo $row_ReExams['FinalExamStatus']; ?>" size="3"></td>

                    <td class="NoMobile"><span id="hday<?php echo $array1; ?>">
                        <?php if ($row_ReExams['O_FinalExamDate'] != null) {
                            echo '<img height="12px" src="/sys/_images/loader.gif" alt="انتظر...">';
                        }; ?>
                        </span></td>

                    <td><input name="FinalExamDate<?php echo $array1; ?>" type="text"
                               id="FinalExamDate<?php echo $array1; ?>"
                               value="<?php echo $row_ReExams['O_FinalExamDate']; ?>" size="11" zezo_date="true"></td>

                    <td class="NoMobile"><?php echo get_array_1($statusName, $row_ReExams['FinalExamStatus']); ?></td>

                    <td>
                        <a href="<?php
                        if ($row_ReExams['ErtiqaID'] <= 4) {
                            ?>shahadah_5.php<?php
                        } elseif ($row_ReExams['ErtiqaID'] >= 5 && $row_ReExams['ErtiqaID'] <= 8) {
                            ?>shahadah_10.php<?php
                        } elseif ($row_ReExams['ErtiqaID'] == 9) {
                            ?>shahadah_20.php<?php
                        } elseif ($row_ReExams['ErtiqaID'] == 10) {
                            ?>shahadah_20_no_tajweed.php<?php
                        } ?>?ExamNo=<?php echo $row_ReExams['AutoNo']; ?>"
                           tabindex="-1">النتيجة</a>
                    </td>

                    <td class="NoMobile">
                        <a title="بحث عن الاختبارات السابقة"
                           href="search_duplicate.php?StID=<?php echo $row_ReExams['StID']; ?>&Name1=<?php echo $row_ReExams['StName1']; ?>&Name2=<?php echo $row_ReExams['StName2']; ?>&Name3=<?php echo $row_ReExams['StName3']; ?>&Name4=<?php echo $row_ReExams['StName4']; ?>&O_MurtaqaName=<?php echo $row_ReExams['O_MurtaqaName']; ?>"
                           target="new"><img src="../_images/find.png" width="20" height="20" alt="بحث"></a>

                        <a title="تعديل الحجز" href="examfulldetail.php?AutoNo=<?php echo $row_ReExams['AutoNo']; ?>"
                           tabindex="-1"><img src="../_images/view_detail.png" width="16" height="16"
                                              alt="تفاصيل الحجز"></a>
                    </td>

                    <td class="NoMobile"><input type="checkbox" name="sms<?php echo $array1; ?>"
                                                id="sms<?php echo $array1; ?>" value="1"></td>
                    <!--				<td class="NoMobile"><a href="examfulldetail.php?AutoNo=-->
                    <?php //echo $row_ReExams['AutoNo']; ?><!--" tabindex="-1"><img src="../_images/view_detail.png" width="16" height="16" alt="تفاصيل الحجز"></a></td>-->
                    <?php

                    ++$array1;
                    }
                    while ($row_ReExams = mysqli_fetch_assoc($ReExams)); ?>
                </tr>

            </table>
            <script>
                $(function () {
                    var fullDate = '';
                    var split_date = [];
                    var day_no = '';
                    for (var y = 0; y < 40; y++) {
                        fullDate = $('#FinalExamDate' + y).val();
                        if (fullDate != '') {
                            split_date = fullDate.split('/');
                            yyyy = parseInt(split_date[0], 10);
                            mm = ((parseInt(split_date[1], 10) < 10) ? '0' + parseInt(split_date[1], 10) : parseInt(split_date[1], 10));
                            dd = ((parseInt(split_date[2], 10) < 10) ? '0' + parseInt(split_date[2], 10) : parseInt(split_date[2], 10));
                            yyyymmdd = String(yyyy) + String(mm) + String(dd);
                            day_no = (yyyymmdd > 0) ? weekeay_name[get_g_date(yyyymmdd, 'yes')] : '';
                            $('#hday' + y).html(day_no);
                        }

                        var g_date = $('#g_date' + y).val();
                        if (g_date != '') {
//							var h_date = rawToFormattedDate(zezo_get_hijri_date(g_date));
                            var h_date = get_formated_hijri_date(zezo_get_hijri_date(g_date));
                            $('#h_date' + y).html(h_date);
                        }

                    }
                });
            </script>
            <br>
            <center>
                <div class="button-group">
                    <?php if ($pageNum_ReExams > 0) { // Show if not first page ?>
                        <a title="الصفحة الأولى" class="button-primary"
                           href="<?php printf("%s?pageNum_ReExams=%d%s", $currentPage, 0, $queryString_ReExams); ?>"
                           tabindex="-1"> << </a>
                    <?php } else { // Show if not first page ?>
                        <a title="الصفحة الأولى" class="button-primary is-disabled" href="#" tabindex="-1"> << </a>
                    <?php } ?>
                    <?php if ($pageNum_ReExams > 0) { // Show if not first page ?>
                        <a title="السابق" class="button-primary"
                           href="<?php printf("%s?pageNum_ReExams=%d%s", $currentPage, max(0, $pageNum_ReExams - 1), $queryString_ReExams); ?>"
                           tabindex="-1"> < </a>
                    <?php } else { // Show if not first page ?>
                        <a title="السابق" class="button-primary is-disabled" href="#" tabindex="-1"> < </a>
                    <?php } // Show if not first page ?>
                    <?php if ($pageNum_ReExams < $totalPages_ReExams) { // Show if not last page ?>
                        <a title="التالي" class="button-primary"
                           href="<?php printf("%s?pageNum_ReExams=%d%s", $currentPage, min($totalPages_ReExams, $pageNum_ReExams + 1), $queryString_ReExams); ?>"
                           tabindex="-1"> > </a>
                    <?php } else { // Show if not first page ?>
                        <a title="التالي" class="button-primary is-disabled" href="#" tabindex="-1"> > </a>
                    <?php } // Show if not last page ?>
                    <?php if ($pageNum_ReExams < $totalPages_ReExams) { // Show if not last page ?>
                        <a title="الصفحة الأخيرة" class="button-primary"
                           href="<?php printf("%s?pageNum_ReExams=%d%s", $currentPage, $totalPages_ReExams, $queryString_ReExams); ?>"
                           tabindex="-1"> >> </a>
                    <?php } else { // Show if not first page ?>
                        <a title="الصفحة الأخيرة" class="button-primary is-disabled" href="#" tabindex="-1"> >> </a>
                    <?php } // Show if not last page ?>
                </div>
                <br>
                السجلات <?php echo($startRow_ReExams + 1) ?>
                إلى <?php echo min($startRow_ReExams + $maxRows_ReExams, $totalRows_ReExams) ?>
                من <?php echo $totalRows_ReExams ?>
            </center>
            <br/>
            <?php if (login_check("admin,er") == true) { ?>
                <div class="FieldsButton">
                    <input name="MM_insert" type="hidden" value="form1">
                    <input name="submit" type="submit" class="button-primary" id="submit" value="حــفــظ">
                </div>
            <?php } ?>
        </form>
        <?php } ?>
        <?php include('../templates/footer.php'); ?>

    </div>
    <?php if (isset($_SESSION['u1'])) {
        $_SESSION['u1'] = null;
        unset($_SESSION['u1']);
        ?>
        <script>
            $(document).ready(function () {
                alertify.success("تم تحديث البيانات بنجاح");
                showError();
            });
        </script>
    <?php } ?>

    <?php if (isset($_SESSION['sms_ok'])) {
        $_SESSION['sms_ok'] = null;
        unset($_SESSION['sms_ok']);
        ?>
        <script>
            $(document).ready(function () {
                alertify.success("تم الإرسال لعدد  (<?php echo $sms_ok;?>) من أولياء الأمور ");
                showError();
            });
        </script>
    <?php } ?>

    <?php if (isset($_SESSION['sms_error'])) {
        $_SESSION['sms_error'] = null;
        unset($_SESSION['sms_error']);
        ?>
        <script>
            $(document).ready(function () {
                alertify.error("فشل إرسال (<?php echo $sms_error;?>) من الرسائل");
                showError();
            });
        </script>
    <?php } ?>

<?php } else {
    include('../templates/restrict_msg.php');
} ?>