<?php
$sql = sprintf("SELECT O_TeacherName,O_FinalExamDate,O_Edarah,O_HName,ErtiqaID,AutoNo,StID,FinalExamDegree,FinalExamDate,MarkName_Long,Money 
FROM view_er_ertiqaexams 
WHERE StID = %s", GetSQLValueString($student_id, "double"));
$query = mysqli_query($localhost, $sql) or die('query_RSStudentExams : ' . mysqli_error($localhost));
$data = mysqli_fetch_assoc($query);
$rows_count = mysqli_num_rows($query);
?>
    <div class="content CSSTableGenerator">
        <table>
            <caption>
                <h1>اختبارات المرتقيات السابقة </h1>
            </caption>
            <tr>
                <td>المرتقى</td>
                <td>درجة الاختبار النهائي</td>
                <td>التقدير</td>
                <td>المكافأة</td>
                <td>تاريخ الاختبار النهائي</td>
                <td class="NoMobile">المجمع</td>
                <td>الحلقة</td>
                <td class="NoMobile">المعلم</td>
                <?php if ($logedInUser) { ?>
                    <td class="NoMobile">تعديل</td>
                <?php } ?>
            </tr>
            <?php if ($rows_count > 0) { // Show if recordset not empty ?>
                <?php do { ?>
                    <tr>
                        <td><?php echo get_array_1($murtaqaName, $data['ErtiqaID']); ?></td>
                        <td><?php echo $data['FinalExamDegree']; ?></td>
                        <td><?php echo $data['MarkName_Long']; ?></td>
                        <td><?php echo $data['Money']; ?></td>
                        <td><?php echo $data['O_FinalExamDate']; ?></td>
                        <td class="NoMobile"><?php echo $data['O_Edarah']; ?></td>
                        <td><?php echo $data['O_HName']; ?></td>
                        <td class="NoMobile"><?php echo $data['O_TeacherName']; ?></td>
                        <?php if ($logedInUser) { ?>
                            <td class="NoMobile">
                                <a title="تعديل الحجز"
                                   href="../examfulldetail.php?AutoNo=<?php echo $data['AutoNo']; ?>"
                                   tabindex="-1">تعديل</a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } while ($data = mysqli_fetch_assoc($query)); ?>
            <?php } else { ?>
                <td colspan="9">لا توجد اختبارات في المرتقيات <?php echo get_gender_label('st', 'ال'); ?></td>
            <?php } ?>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            <?php if (Input::get('msg') == 'ertiqa') { ?>
            alertify.success("تم حجز موعد بنجاح");
            <?php } ?>
        });
    </script>