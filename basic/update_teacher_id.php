<?php require_once(__DIR__ . '/../functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}
$old_teacher_id = Input::get('old_teacher_id');
$other_teacher = false;
$pdo1 = new DB();
$teacher_info = $pdo1->row('select t.*,e.arabic_name,h.HName from 0_teachers t 
left join 0_users e on t.TEdarah=e.id 
left join 0_halakat h on t.THalaqah=h.AutoNo and h.hide=0
where t.TID=:TID ', [':TID' => $old_teacher_id]);
//var_dump($teacher_info);
$teacher_id = null;
if ((isset($_POST["update"])) && ($_POST["update"] == "form1")) {

    $teacher_id = Input::get('student_id');
    $pdo = new DB();
    $other_teacher = $pdo->row('select * from 0_teachers where TID=:TID', [':TID' => $teacher_id]);
    if (!$other_teacher) {

//        var_dump($other_teacher);
//        exit('يوجد سجل مدني بنفس الرقم');

        $db_name = Config::get('db_name');
        $dsn = "mysql:dbname=$db_name;host=" . Config::get('host');
        $pdo = new PDO($dsn, Config::get('user'), Config::get('pass'), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();

            $tables = [
                ['0_teachers', 'TID'],
                ['er_bra3m', 'TeacherID'],
                ['er_ertiqaexams', 'TeacherID'],
                ['ms_fahd_featured_teacher', 'teacher_id'],
                ['ms_fahd_rgstr', 'TeacherID'],
                ['ms_etqan_rgstr', 'TeacherID'],
                ['ms_fahd_rgstr_tmp', 'TeacherID'],
                ['ms_mutqin_rgstr', 'TeacherID'],
                ['ms_salman_rgstr', 'TeacherID'],
                ['ms_shabab_rgstr', 'TeacherID'],
            ];

            foreach ($tables as $table) {
                $pdo->query("update " . $table[0] .
                    " set " . $table[1] . " = " . $teacher_id . " where " . $table[1] . " = " . $old_teacher_id);
            }

            $pdo->commit();
            Session::put('update_is_done', true);
            Redirect::to('update_teacher_id.php?old_teacher_id=' . $teacher_id);
        } catch (Exception $e) {
            $pdo->rollback();
            echo $e->getMessage();
        }
    }
}
$update_is_done = isset($_SESSION['update_is_done']) ? Session::get('update_is_done') : '';
include(__DIR__ . '/../templates/header1.php');
$PageTitle = 'تعديل السجل المدني'; ?>
    <title><?php echo $PageTitle; ?></title>
    </head>
    <body>

<?php include(__DIR__ . '/../templates/header2.php'); ?>

<?php include(__DIR__ . '/../templates/nav_menu.php'); ?>

    <div id="PageTitle"> <?php echo $PageTitle; ?> </div>
    <!--PageTitle-->
<div class="content CSSTableGenerator">
<?php if (login_check($all_groups) == true) { ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <caption>
            بيانات <?php echo get_gender_label('t', 'ال'); ?> الأساسية
        </caption>
        <tr>
            <td>اسم <?php echo get_gender_label('t', 'ال'); ?></td>
            <td>رقم الجوال</td>
            <td><?php echo get_gender_label('e', 'ال'); ?></td>
            <td>الحلقة</td>
        </tr>
        <tr>
            <td><?php echo join(' ', [$teacher_info->TName1, $teacher_info->TName2, $teacher_info->TName3, $teacher_info->TName4]); ?></td>
            <td><?php echo $teacher_info->TMobileNo; ?></td>
            <td><?php echo $teacher_info->arabic_name; ?></td>
            <td><?php echo $teacher_info->HName; ?></td>
        </tr>
    </table>
</div>
<div class="content lp">
    <form name="form1" id="form1" method="POST" action="<?php echo $editFormAction; ?>" data-validate="parsley">
        <div class="sixteen columns alpha">
            <h1 style="font-size:16px">السجل المدني الحالي : <?php echo $old_teacher_id; ?></h1>
        </div>
        <br>
        <div class="clearfix"></div>
        <br>
        <div class="five columns alpha">
            <div class="LabelContainer">
                <label for="student_id">رقم السجل المدني الجديد</label>
            </div>
            <input name="student_id" type="text" value="<?php echo $teacher_id; ?>" id="student_id"
                   data-required="true" data-type="digits" data-maxlength="10" data-minlength="10">
        </div>
        <div class="four columns">
            <input type="submit" class="button-primary" id="submit" value="تعديل السجل المدني"/>
        </div>
        <input type="hidden" name="update" value="form1">
    </form>

    <?php if ($other_teacher) {
        echo join(' ', [
            '<div class="clearfix"></div><br>',
            '<h1 style="color:red;text-align:center;">',
            'يوجد',
            get_gender_label('t'),
            'بنفس السجل المدني الجديد ، لذا يجب تصحيح السجل المدني',
            get_gender_label('t', 'لل'),
            '(',
            $other_teacher->TName1,
            $other_teacher->TName2,
            $other_teacher->TName3,
            $other_teacher->TName4,
            ')',
            '<br>',
            '<a href="update_teacher_id.php?old_teacher_id=' . $other_teacher->TID . '">',
            'لتعديل السجل الآن ، اضغط هنا',
            '</a>',
            '</h1>',
        ]);
    }
    if ($update_is_done) {
        echo '<div class="clearfix"></div><br>';
        echo "<br><br><h1 style='color:green;text-align:center;font-size:22px'>تم التعديل بنجاح</h1>";
    }
    Session::delete('update_is_done');
    ?>
<?php } else {
    echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
} ?>
</div>

<?php include(__DIR__ . '/../templates/footer.php');