<?php require_once(__DIR__ . '/../functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}
$old_student_id = Input::get('old_student_id');
$other_student = false;
$student_id = '';
if ((isset($_POST["update"])) && ($_POST["update"] == "form1")) {

    $student_id = Input::get('student_id');
    $pdo = new DB();
    $other_student = $pdo->row('select * from 0_students where STID=:STID', [':STID' => $student_id]);
    if (!$other_student) {

//        var_dump($other_student);
//        exit('يوجد سجل مدني بنفس الرقم');

        $db_name = Config::get('db_name');
        $dsn = "mysql:dbname=$db_name;host=" . Config::get('host');
        $pdo = new PDO($dsn, Config::get('user'), Config::get('pass'), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();

            $tables = [
                ['0_students', 'StID'],
                ['er_bra3m', 'StID'],
                ['er_ertiqaexams', 'StID'],
                ['ms_etqan_rgstr', 'StID'],
                ['ms_fahd_rgstr', 'StID'],
                ['ms_fahd_rgstr_tmp', 'StID'],
                ['ms_mutqin_rgstr', 'StID'],
                ['ms_salman_rgstr', 'StID'],
                ['ms_shabab_rgstr', 'StID'],
            ];

            foreach ($tables as $table) {
                $pdo->query("update " . $table[0] .
                    " set " . $table[1] . " = " . $student_id . " where " . $table[1] . " = " . $old_student_id);
            }

            $pdo->commit();
            Session::put('update_is_done', true);
            Redirect::to('update_student_id.php?old_student_id=' . $student_id);
        } catch (Exception $e) {

            $pdo->rollback();
            echo $e->getMessage();
        }
    }
}
$update_is_done = isset($_SESSION['update_is_done']) ?  Session::get('update_is_done') : '';

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
    <?php echo get_st_info($old_student_id); ?>
</div>
<div class="content lp">
    <form name="form1" id="form1" method="POST" action="<?php echo $editFormAction; ?>" data-validate="parsley">
        <div class="sixteen columns alpha">
            <h1 style="font-size:16px">السجل المدني الحالي : <?php echo $old_student_id; ?></h1>
        </div>
        <br>
        <div class="clearfix"></div>
        <br>
        <div class="five columns alpha">
            <div class="LabelContainer">
                <label for="student_id">رقم السجل المدني الجديد</label>
            </div>
            <input name="student_id" type="text" value="<?php echo $student_id; ?>" id="student_id"
                   data-required="true" data-type="digits" data-maxlength="10" data-minlength="10">
        </div>
        <div class="four columns">
            <input type="submit" class="button-primary" id="submit" value="تعديل السجل المدني"/>
        </div>
        <input type="hidden" name="update" value="form1">
    </form>

    <?php if ($other_student) {
        echo join(' ', [
            '<div class="clearfix"></div><br>',
            '<h1 style="color:red;text-align:center;">',
            'يوجد',
            get_gender_label('st'),
            'بنفس السجل المدني الجديد ، لذا يجب تصحيح السجل المدني',
            get_gender_label('st', 'لل'),
            '(',
            $other_student->StName1,
            $other_student->StName2,
            $other_student->StName3,
            $other_student->StName4,
            ')',
            '<br>',
            '<a href="update_student_id.php?old_student_id=' . $other_student->StID . '">',
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