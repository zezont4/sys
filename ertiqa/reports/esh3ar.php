<?php require_once('../../Connections/localhost.php');

$colname_RsEsh3ar = "-1";
if (isset($_GET['AutoNo'])) {
    $colname_RsEsh3ar = $_GET['AutoNo'];
}
$query_RsEsh3ar = sprintf("SELECT O_StudentName,O_TeacherName,O_FinalExamDate,O_Edarah,O_HName,O_MurtaqaName,ErtiqaID,FinalExamStatus,FinalExamDegree,MarkName_Short,Money FROM view_er_ertiqaexams WHERE AutoNo = %s", GetSQLValueString($colname_RsEsh3ar, "int"));
$RsEsh3ar = mysqli_query($localhost, $query_RsEsh3ar) or die(mysqli_error($localhost));
$row_RsEsh3ar = mysqli_fetch_assoc($RsEsh3ar);
$totalRows_RsEsh3ar = mysqli_num_rows($RsEsh3ar);

$pageTitle = "اشعار بالنتيجة";
$deptName = "الشؤون التعليمية / برنامج الإرتقاء";
$secondLogo = true;
$secondLogoURL = '<img class="ertiqafLogo" src="/sys/_images/ertiqa_160.png" width="140">';

require_once("../../functions.php");
require_once("../../templates/report_header1.php");
require_once("../../templates/report_header2.php"); ?>
<div class="reportContent">
    <p class="reportTitle">إشعار بالنتيجة</p>

    المكرم ولي أمر <?php echo get_gender_label('st', 'ال'); ?> :
    <?php echo $row_RsEsh3ar['O_StudentName']; ?>
    <br>
    السلام عليكم ورحمة الله وبركاته وبعد : نفيدكم بنتيجة ابنكم في اختبار المرتقيات حسب البيانات التالية:
    <br>

    <div class="tableWrapper">
        <table>
            <tr>
                <th scope="col">اسم <?php echo get_gender_label('st', 'ال'); ?></th>
                <th scope="col">اسم <?php echo get_gender_label('t', 'ال'); ?></th>
                <th scope="col">الحلقة</th>
                <th scope="col"><?php echo get_gender_label('e', 'ال'); ?></th>
            </tr>
            <tr>
                <td><?php echo $row_RsEsh3ar['O_StudentName']; ?></td>
                <td><?php echo $row_RsEsh3ar['O_TeacherName']; ?></td>
                <td><?php echo $row_RsEsh3ar['O_HName']; ?></td>
                <td><?php echo $row_RsEsh3ar['O_Edarah']; ?></td>
            </tr>
        </table>
        <table>
            <tr>
                <th scope="col">المرتقى</th>
                <th scope="col">تاريخ الاختبار</th>
                <th scope="col">الحالة</th>
                <th scope="col">النتيجة</th>
                <th scope="col">الدرجة</th>
                <th scope="col">المكافأة</th>
                <th scope="col">المرتقى التالي</th>
            </tr>
            <tr>
                <td><?php echo $row_RsEsh3ar['O_MurtaqaName']; ?></td>
                <td><?php echo $row_RsEsh3ar['O_FinalExamDate']; ?></td>
                <td><?php echo get_array_1($statusName, $row_RsEsh3ar['FinalExamStatus']); ?></td>
                <td><?php echo $row_RsEsh3ar['MarkName_Short']; ?></td>
                <td><?php echo $row_RsEsh3ar['FinalExamDegree']; ?></td>
                <td><?php echo $row_RsEsh3ar['Money']; ?></td>
                <td><?php if ($row_RsEsh3ar['FinalExamDegree'] > 79) {
                        echo get_array_1($murtaqaName, $row_RsEsh3ar['ErtiqaID'] + 1);
                    } ?></td>
            </tr>
        </table>
    </div>

    <!-- اجتاز -->
    <?php if ($row_RsEsh3ar['FinalExamStatus'] == 2) { ?>
        نسأل الله أن يجعله قرة عين لكم,وأن يوفقه لإتمام حفظ كتابه إنه ولي ذلك والقادر عليه .
        <br/>
        <!-- لم يجتز -->
    <?php } else if ($row_RsEsh3ar['FinalExamStatus'] == 3) { ?>
        نفيدكم بعدم اجتياز ابنكم اختبار مرتقى(<?php echo $row_RsEsh3ar['O_MurtaqaName']; ?>) وذلك بسبب :
        <br>
        <span style=" font-size:10px; font-family:Arial,Helvetica,sans-serif;">
................................................................................................................................................................................................................................
<br>................................................................................................................................................................................................................................
</span>
        <br/>
        نتمنى مساعدة <?php echo get_gender_label('st', 'ال'); ?> في الاستعداد لإعادة الاختبار، وتلافي الأسباب السابقة,وذلك بعمل التالي:
        <br/>
        <ul>
            <li>
                * عمل منهجية للمراجعة والتأكد من الاستعداد المناسب.
            </li>
            <li>
                * تجاوز الاختبار التجريبي في المجمع.
            </li>
            <li>
                * حجز موعد آخر بعد مدة لا تقل عن (<strong>اسبوعين</strong>) .
            </li>
            مع تمنياتنا له بالتوفيق في اختباره القادم.
        </ul>
    <?php } ?>
    <center>
        <h2>
            والسلام عليكم ورحمة الله وبركاته
        </h2>
    </center>

</div>
<div class="reportFotter">
    <table class="Signature">
        <tr>
            <td width="13%" nowrap="nowrap">المعلم المختبر :</td>
            <td class="col2">&nbsp;</td>
            <td width="9%" nowrap="nowrap">ولي الأمر :</td>
            <td class="col4">&nbsp;</td>
            <td width="10%" nowrap="nowrap">معلم الحلقة :</td>
            <td class="col6">&nbsp;</td>
        </tr>
        <tr>
            <td nowrap="nowrap">تـــوقيعــــة :</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">توقيعـه :</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">تـــوقيعـــة :</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td nowrap="nowrap">التــــاريـــخ :</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">التـاريـخ :</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">التــــاريـــخ :</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <br>
    <hr>
    <?php if ($row_RsEsh3ar['FinalExamStatus'] == 2) { ?>
        <center>
            <table class="Marks">
                <tr>
                    <td rowspan="2" scope="col"><strong>رموز<br>التقادير</strong></td>
                    <th scope="col">ممتاز مرتفع</th>
                    <th scope="col">ممتاز</th>
                    <th scope="col">جيد جدا مرتفع</th>
                    <th scope="col">جيد جدا</th>
                    <th scope="col">جيد مرتفع</th>
                    <th scope="col">جيد</th>
                    <th scope="col">لم يجتز</th>
                </tr>
                <tr>
                    <td>أ+</td>
                    <td>أ</td>
                    <td>ب+</td>
                    <td>ب</td>
                    <td>ج+</td>
                    <td>ج</td>
                    <td>د</td>
                </tr>
            </table>
        </center>
    <?php } ?>
    <br/>
    * ملحوظة هامة : تحفظ هذه الورقة بعد توقيعها من ولي أمر <?php echo get_gender_label('st', 'ال'); ?> ومعلم الحلقة في ملف خاص بنتائج الطلاب في مجمع الحلقة.
    <br>
    * ملحوظة خاصة : لا يقبل اختبار <?php echo get_gender_label('st', 'ال'); ?> في مرتقى (يونس وما بعده) إلا بعد حضور برنامج (تحفة الأطفال)، وإحضار شهادة حضوره.
    <br>
    * بإمكان <?php echo get_gender_label('st', 'ال'); ?> وولي الأمر الاطلاع على تفاصيل الاختبارات السابقة على الرابط التالي : www.qz.org.sa/sys
</div>
</div>
</body>
</html>
