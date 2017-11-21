<?php
$query_rs_mutqin_exam = sprintf("SELECT * FROM ms_mutqin_rgstr WHERE StID = %s", GetSQLValueString($student_id, "double"));
$rs_mutqin_exam = mysqli_query($localhost, $query_rs_mutqin_exam) or die(mysqli_error($localhost));
$row_rs_mutqin_exam = mysqli_fetch_assoc($rs_mutqin_exam);
$totalRows_rs_mutqin_exam = mysqli_num_rows($rs_mutqin_exam);
?>
<div class="content CSSTableGenerator">
    <table>
        <caption>
            <h1>مشاركات <?php echo get_gender_label('st', 'ال'); ?> في برنامج متقن </h1>
        </caption>
        <tr>
            <td>العام الدراسي</td>
            <td>الفرع</td>
            <td>التاريخ</td>
            <td>آخر مرتقى</td>
            <td>السنة الدراسية</td>
            <td>المجمع</td>
            <td>الحلقة</td>
            <td>المعلم</td>
            <?php if ($logedInUser == true) { ?>
                <td>تعديل</td><?php } ?>
        </tr>
        <?php if ($totalRows_rs_mutqin_exam > 0) { ?>
            <?php do { ?>
                <?php $study_fahd_name = get_fahd_year_name($row_rs_mutqin_exam['RDate']); ?>
                <tr>
                    <td><?php echo $study_fahd_name; ?></td>
                    <td><?php echo get_array_1($mutqin_msbkh_type, $row_rs_mutqin_exam['MsbkhID']); ?></td>
                    <td><?php echo StringToDate($row_rs_mutqin_exam['RDate']); ?></td>
                    <td><?php echo get_array_1($murtaqaName, $row_rs_mutqin_exam['ErtiqaID']); ?></td>
                    <td><?php echo get_array_1($SchoolLevelNameAll, $row_rs_mutqin_exam['SchoolLevelID']); ?></td>
                    <td><?php echo get_edarah_name($row_rs_mutqin_exam['EdarahID']); ?></td>
                    <td><?php echo get_halakah_name($row_rs_mutqin_exam['HalakahID']); ?></td>
                    <td><?php echo get_teacher_name($row_rs_mutqin_exam['TeacherID']); ?></td>
                    <?php if ($logedInUser == true) { ?>
                        <td><a
                                href="../../mutqin/Register_edit.php?auto_no=<?php echo $row_rs_mutqin_exam['AutoNo']; ?>">تعديل</a>
                        </td><?php } ?>
                </tr>
            <?php } while ($row_rs_mutqin_exam = mysqli_fetch_assoc($rs_mutqin_exam)); ?>
        <?php } else { ?>
            <td colspan="9">لاتوجد مشاركات سابقة <?php echo get_gender_label('st', 'لل'); ?> في برنامج متقن
            </td>
        <?php } ?>
    </table>
</div>

<script>
    $(document).ready(function () {
        <?php if (Input::get('msg') == 'mutqin') { ?>
        alertify.success("تم التسجيل ببرنامج متقن بنجاح");
        <?php } ?>
    });
</script>