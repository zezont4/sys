<style>
    .CSSTableGenerator td .positive_0, .CSSTableGenerator td .is_not_memorized_1 {
        color: red;
    }

    .CSSTableGenerator tr.attend_2 td {
        background-color: #ffd1d1;
    }

    .CSSTableGenerator td .positive_1 {
        color: darkgreen;
    }

    .CSSTableGenerator td .degree_a {
        color: #2fb52f;
    }

    .CSSTableGenerator td .grey {
        color: gray;
    }

    h2 {
        line-height: 20px;
        text-align: center;
    }
</style>
<?php
$limit = 10;
$more_link_url = "full_memorize_history.php?student_id=$student_id&limit=100";
if (isset($from_memorize_page)) {
    $limit = Input::get('limit');
    $more_link_url = "full_memorize_history.php?student_id=$student_id&limit=$limit";
}
$pdo = new DB();
$st_no = $pdo->single("SELECT st_no FROM 0_students WHERE StID=:StID", [':StID' => $student_id]);

$dailies = $pdo->query("SELECT 
                                a.name AS absence_label ,
                                d.*,
                                e.arabic_name AS edarah_name,
                                h.HName AS halakah_name,
                                g_h.day_no as day_no ,
                                concat_ws(' ',t.TName1,t.TName2,t.TName4) as teacher_name
                            FROM 
                                ( SELECT * FROM daily  WHERE st_id=:st_no ORDER BY h_date DESC LIMIT :limit ) d
                                 LEFT JOIN daily_absence_types a ON a.id =  d.attendance_status
                                 LEFT JOIN 0_users e ON e.id = d.edarah_id
                                 LEFT JOIN 0_halakat h ON h.AutoNo = d.halakah_id
                                 LEFT JOIN g_h_dates g_h ON g_h.h_date = d.h_date
                                 LEFT JOIN 0_teachers t ON t.t_no = d.teacher_id
                                 ORDER BY d.h_date asc", [':st_no' => $st_no, ':limit' => $limit]);

$rows_count = count($dailies);
?>
<div class="content CSSTableGenerator">
    <table>
        <caption>
            <h1>سجل الحفظ والسلوك اليومي <?php echo get_gender_label('st', 'لل'); ?>
                <?php if (!isset($from_memorize_page)) {
                    echo "<a href=\"$more_link_url\">وللمزيد اضغط هنا</a>";
                }
                ?>
            </h1>
        </caption>
        <tr>
            <td>التاريخ</td>
            <td><?php echo get_gender_label('e', 'ال'); ?><br>الحلقة<br><?php echo get_gender_label('t', 'ال'); ?></td>
            <td>الحفظ<br>نقاط الحفظ</td>
            <td>التثبيت<br>نقاط التثبيت</td>
            <td>المراجعة<br>نقاط المراجعة</td>
            <td>السلوك</td>
            <td>اجمالي النقاط</td>
        </tr>
        <?php if ($rows_count > 0) {
//            عدد الأحرف
            $memorize_char_count[1] = 0;
            $memorize_char_count[2] = 0;
            $memorize_char_count[3] = 0;

            foreach ($dailies as $daily) {
                $attend_class = 'attend_' . $daily->attendance_status;
                $points_count = 0;
                $daily_id = $daily->id;
                $daily_behaviors = $pdo->query("SELECT b.*,t.name as behavior_label,t.is_positive FROM daily_behavior b
                     left join daily_behavior_types t on b.behavior_type_id=t.id
                     WHERE  b.daily_id=:daily_id", [':daily_id' => $daily_id]);
                ?>
                <tr class="<?php echo $attend_class; ?>">
                    <td><?php echo $day_name[$daily->day_no] . '<br>' . StringToDate($daily->h_date); ?></td>
                    <td><?php echo $daily->edarah_name; ?><br><?php echo $daily->halakah_name; ?><br><?php echo $daily->teacher_name; ?></td>
                    <?php if ($daily->attendance_status > 1) { ?>
                        <td colspan="3"><?php echo $daily->absence_label; ?></td>
                    <?php } else {
                        $daily_memorize[1] = $pdo->row("SELECT * FROM daily_memorize WHERE memorize_type_id=1 AND daily_id=:daily_id", [':daily_id' => $daily_id]);
                        $daily_memorize[2] = $pdo->row("SELECT * FROM daily_memorize WHERE memorize_type_id=2 AND daily_id=:daily_id", [':daily_id' => $daily_id]);
                        $daily_memorize[3] = $pdo->row("SELECT * FROM daily_memorize WHERE memorize_type_id=3 AND daily_id=:daily_id", [':daily_id' => $daily_id]);
                        for ($i = 1; $i <= 3; $i++) { ?>
                            <td>
                                <?php if ($daily_memorize[$i]) {
                                $memorize_char_count[$i] += $daily_memorize[$i]->char_count;
                                $points_count += $daily_memorize[$i]->points;

                                $start_sora = $daily_memorize[$i]->start_sora;
                                $start_aya = $daily_memorize[$i]->start_aya;

                                $end_sora = $daily_memorize[$i]->end_sora;
                                $end_aya = $daily_memorize[$i]->end_aya;
                                ?>
                                <span class="is_not_memorized_<?php echo $daily_memorize[$i]->is_not_memorized; ?>">
                                    <?php if ($start_sora == $end_sora) {
                                        echo $soraName[$start_sora] . '[' . $start_aya . ' : ' . $end_aya . ']';
                                    } else {
                                        echo $soraName[$start_sora] . '[' . $start_aya . '] - ';
                                        echo $soraName[$end_sora] . '[' . $end_aya . ']';
                                    }

                                    echo '<br><span class="grey">';
                                    echo 'خطأ:' . $daily_memorize[$i]->errors_count;
                                    echo ' ';
                                    echo 'تردد:' . $daily_memorize[$i]->hesitations_count;
                                    echo '</span>';
                                    if ($daily_memorize[$i]->is_not_memorized) {
                                        echo '<br>لم يحفظ';
                                    } else {
                                        echo "<br>نقاط:" . $daily_memorize[$i]->points;
                                    } ?>
                                    <?php } else {
                                        echo 'ـــ';
                                    } ?>
                                </span>
                            </td>
                        <?php } ?>
                    <?php } ?>
                    <td>
                        <?php if ($daily_behaviors) {
                            foreach ($daily_behaviors as $daily_behavior) {
                                $points_count += $daily_behavior->points;
                                ?>
                                <span class="<?php echo 'positive_' . $daily_behavior->is_positive; ?>"><?php echo $daily_behavior->behavior_label . '<br>' . $daily_behavior->points; ?></span>
                            <?php }
                        } ?>
                    </td>
                    <td>
                        <?php
                        $points_class = '';
                        if ($points_count >= 9) {
                            $points_class = 'a';
                        } else if ($points_count >= 3 && $points_count <= 6) {
                            $points_class = 'b';
                        } else if ($points_count <= 3) {
                            $points_class = 'c';
                        }
                        ?>
                        <span class="degree_<?php echo $points_class; ?>"><?php echo $points_count; ?></span>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <td colspan="9">لا يتوفر سجل الحفظ اليومي <?php echo get_gender_label('st', 'لل'); ?>
            </td>
        <?php } ?>
    </table>
    <?php if ($rows_count > 0) { ?>
        <h2>ملخص التسميع اليومي لما سبق</h2>
        <h2>الحفظ :
            <?php echo $memorize_char_count[1]; ?>
            حرف / أحرف
            (
            <?php echo round($memorize_char_count[1] / 550, 1); ?>
            وجه / أوجه تقريبا
            )
        </h2>

        <h2>التثبيت :
            <?php echo $memorize_char_count[2]; ?>
            حرف / أحرف
            (
            <?php echo round($memorize_char_count[2] / 550, 1); ?>
            وجه / أوجه تقريبا
            )
        </h2>

        <h2>المراجعة :
            <?php echo $memorize_char_count[3]; ?>
            حرف / أحرف
            (
            <?php echo round($memorize_char_count[3] / 550, 1); ?>
            وجه / أوجه تقريبا
            )
        </h2>
        <br>
        <?php if (!isset($from_memorize_page))  { ?>
            <h1 style="text-align: center"><a style="font-size:18px" href="<?php echo $more_link_url; ?>">لعرض سجل أيام أكثر ، اضغط هنا</a></h1>

        <?php } ?>
    <?php } ?>
</div>