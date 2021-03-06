<?php require_once("../../functions.php"); ?>
<?php

$EdarahID = Input::get('EdarahID') ? "and EdarahID = " . Input::get('EdarahID') : '';
$HalaqatID = Input::get('HalaqatID') ? "and AutoNo = " . Input::get('HalaqatID') : '';
$h_date = Input::get('kashf_date') ? Input::get('kashf_date') : '';

$query_halakat = sprintf("SELECT AutoNo,HName from 0_halakat where hide=0 %s %s order by HName", $EdarahID, $HalaqatID);
$halakat = mysqli_query($localhost, $query_halakat) or die('absent.php 1 - ' . mysqli_error($localhost));
$row_halakat = mysqli_fetch_assoc($halakat);
$totalRows_halakat = mysqli_num_rows($halakat);

?>
<?php $pageTitle = "كشف تحضير"; ?>
<?php require_once("../../templates/report_header1.php"); ?>
    <style>
        .reportContent {
            font-size: 14px
        }
    </style>
<?php do {
    $query_teachers = sprintf("SELECT TID FROM 0_teachers where THalaqah=%s and hide=0",
        $row_halakat['AutoNo']);
    $teachers = mysqli_query($localhost, $query_teachers) or die('absent.php teacher - ' . mysqli_error($localhost));
    $row_teachers = mysqli_fetch_assoc($teachers);
    $totalRows_teachers = mysqli_num_rows($teachers);


    $query_st_count = sprintf("SELECT StID,StEdarah,FatherMobileNo FROM 0_students where StHalaqah=%s and hide=0",
        $row_halakat['AutoNo']);
    $st_count = mysqli_query($localhost, $query_st_count) or die('absent.php students - ' . mysqli_error($localhost));
    $row_st_count = mysqli_fetch_assoc($st_count);
    $totalRows_st_count = mysqli_num_rows($st_count);
    if ($totalRows_st_count > 0) {
        ?>
        <div class="reportWrapper">
            <div class="reportContent">
                <p class="report_description">بيانات <?php echo get_gender_label('sts', 'ال'); ?></p>
                <table width="100%">
                    <tr>
                        <th><?php echo get_gender_label('e', 'ال'); ?></th>
                        <td><?php echo get_edarah_name($row_st_count['StEdarah']); ?></td>
                        <th>الحلقة</th>
                        <td><?php echo $row_halakat['HName']; ?></td>
                        <th><?php echo get_gender_label('t', 'ال'); ?></th>
                        <td><?php echo get_teacher_name($row_teachers['TID']); ?></td>
                    </tr>
                </table>
                <br>
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <th><p>م</p></th>
                        <th><p><?php echo get_gender_label('st', 'ال'); ?></p></th>
                        <th><p>السجل المدني</p></th>
                        <th><p>جوال ولي الأمر</p></th>
                        <th><p>المرحلة الدراسية</p></th>
                        <th class="printButton"><p>تعديل</p></th>
                    </tr>
                    <?php
                    $query_st_data = sprintf("SELECT * FROM view_0_students WHERE StHalaqah = %s and hide=0",
                        $row_halakat['AutoNo']);

                    $st_data = mysqli_query($localhost, $query_st_data) or die('absent.php 3 - ' . mysqli_error($localhost));
                    $row_st_data = mysqli_fetch_assoc($st_data);
                    $totalRows_st_data = mysqli_num_rows($st_data);

                    $i = 1;
                    do {
                        ?>
                        <tr>
                            <th><?php echo $i; ?></th>
                            <td><?php echo $row_st_data['Stfullname']; ?></td>
                            <td><?php echo $row_st_data['StID']; ?></td>
                            <td><?php echo $row_st_data['FatherMobileNo']; ?></td>
                            <td><?php echo get_array_1($SchoolLevelNameAll, $row_st_data['school_level']); ?></td>
                            <td class="printButton"><a href="../student_edit.php?StID=<?php echo $row_st_data['StID']; ?>"
                                                       target="_blank">تعديل</a></td>
                        </tr>
                        <?php $i++;
                    } while ($row_st_data = mysqli_fetch_assoc($st_data)); ?>

                </table>
            </div>
        </div>
        <div class="page-break"></div>
    <?php }
} while ($row_halakat = mysqli_fetch_assoc($halakat)); ?>
    </body>
    </html>
<?php
mysqli_free_result($halakat);
mysqli_free_result($st_count);
?>