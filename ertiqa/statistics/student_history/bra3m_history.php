<?php
$sql = sprintf("SELECT AutoNo,SchoolLevelID,DarajahID,Money,O_StFullName,O_TFullName,O_arabic_name,O_HName,O_DDate FROM view_er_bra3m WHERE StID = %s ORDER BY DDate ASC", GetSQLValueString($student_id, "double"));
$query = mysqli_query($localhost, $sql) or die(mysqli_error($localhost));
$data = mysqli_fetch_assoc($query);
$rows_count = mysqli_num_rows($query);
?>
<div class="content CSSTableGenerator">
    <table>
        <caption>
            <h1>سلم البراعم</h1>
        </caption>
        <tr>
            <td>الدرجة</td>
            <td>المكافأة</td>
            <td>الصف الدراسي</td>
            <td>تاريخ الدرجة</td>
            <td class="NoMobile">المجمع</td>
            <td>الحلقة</td>
            <td class="NoMobile">المعلم</td>
            <?php if ($logedInUser == true) { ?>
                <td class="NoMobile">تعديل</td><?php } ?>
        </tr>
        <?php if ($rows_count > 0) { ?>
            <?php do { ?>
                <tr>
                    <td><?php echo $bra3mName[$data['DarajahID']]; ?></td>
                    <td><?php echo $data['Money']; ?></td>
                    <td><?php echo $SchoolLevelName[$data['SchoolLevelID']]; ?></td>
                    <td><?php echo $data['O_DDate']; ?></td>
                    <td class="NoMobile"><?php echo $data['O_arabic_name']; ?></td>
                    <td><?php echo $data['O_HName']; ?></td>
                    <td class="NoMobile"><?php echo $data['O_TFullName']; ?></td>
                    <?php if ($logedInUser == true) { ?>
                        <td class="NoMobile"><a
                                    href="../../ertiqa/bra3m_edit.php?AutoNo=<?php echo $data['AutoNo']; ?>">تعديل</a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } while ($data = mysqli_fetch_assoc($query)); ?>
        <?php } else { ?>
            <td colspan="8">لايوجد درجات <?php echo get_gender_label('st', 'لل'); ?> في سلم البراعم</td>
        <?php } ?>
    </table>

</div>

<script>
    $(document).ready(function () {
        <?php if (Input::get('msg') == 'bra3m') { ?>
        alertify.success("تم التسجيل بالبراعم بنجاح");
        <?php } ?>
    });
</script>