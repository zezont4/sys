<?php
$sql = sprintf("SELECT * FROM ms_shabab_rgstr WHERE StID = %s", GetSQLValueString($student_id, "double"));
$query = mysqli_query($localhost, $sql) or die(mysqli_error($localhost));
$data = mysqli_fetch_assoc($query);
$rows_count = mysqli_num_rows($query);
?>
<div class="content CSSTableGenerator">
    <table>
        <caption>
            <h1>مشاركات <?php echo get_gender_label('st', 'ال'); ?> في مسابقة الهيئة العامة للرياضة</h1>
        </caption>
        <tr>
            <td>العام الدراسي</td>
            <td>المسابقة</td>
            <td>التاريخ</td>
            <td>آخر مرتقى</td>
            <td>السنة الدراسية</td>
            <td>المجمع</td>
            <td>الحلقة</td>
            <td>المعلم</td>
            <?php if ($logedInUser == true) { ?>
                <td>تعديل</td>
            <?php } ?>
        </tr>
        <?php if ($rows_count > 0) { ?>
            <?php do { ?>
                <?php $study_fahd_name = get_fahd_year_name($data['RDate']); ?>
                <tr>
                    <td><?php echo $study_fahd_name; ?></td>
                    <td><?php echo get_array_1($shabab_msbkh_type, $data['MsbkhID']); ?></td>
                    <td><?php echo StringToDate($data['RDate']); ?></td>
                    <td><?php echo get_array_1($murtaqaName, $data['ErtiqaID']); ?></td>
                    <td><?php echo get_array_1($SchoolLevelNameAll, $data['SchoolLevelID']); ?></td>
                    <td><?php echo get_edarah_name($data['EdarahID']); ?></td>
                    <td><?php echo get_halakah_name($data['HalakahID']); ?></td>
                    <td><?php echo get_teacher_name($data['TeacherID']); ?></td>
                    <?php if ($logedInUser == true) { ?>
                        <td><a
                                    href="../../shabab/Register_edit.php?auto_no=<?php echo $data['AutoNo']; ?>">تعديل</a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } while ($data = mysqli_fetch_assoc($query)); ?>
        <?php } else { ?>
            <td colspan="9">لاتوجد مشاركات سابقة <?php echo get_gender_label('st', 'لل'); ?> في مسابقة رعاية
                الشباب
            </td>
        <?php } ?>
    </table>
</div>

<script>
    $(document).ready(function () {
        <?php if (Input::get('msg') == 'shabab') { ?>
        alertify.success("تم التسجيل بمسابقة الهيئة العامة للرياضة بنجاح");
        <?php } ?>
    });
</script>