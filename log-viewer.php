<?php require_once('functions.php');

$PageTitle = 'سجل التنبيهات والأخطاء';

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . ($_SERVER['QUERY_STRING']);
}

if (!login_check("admin")) {
    include('templates/restrict_msg.php');
    exit();
}
$selected_log_file = null;
if (Input::get('_submit') == "form1") {
    $selected_log_file = Input::get('files');
}
include_once 'templates/header1.php'; ?>
    <title><?php echo $PageTitle; ?></title>
    </head>
    <body>
<?php include_once 'templates/header2.php'; ?>
<?php include('templates/nav_menu.php'); ?>
    <div id="PageTitle"><?php echo $PageTitle; ?></div>
    <div class="content lp">
        <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">

            <div class="six columns alpha">
                <div class="LabelContainer">
                    <label for="files">التاريخ</label>
                </div>
                <?php $files = glob(__DIR__ . '\logs\*.html'); ?>
                <select name="files">
                    <?php
                    $files = glob(__DIR__ . '/logs/*.html');
                    usort($files, function ($a, $b) {
                        return filemtime($a) < filemtime($b);
                    });
                    foreach ($files as $file) {
                        $selected = $selected_log_file == basename($file) ? ' selected ' : '';
                        echo '<option ' . $selected . 'value="' . basename($file) . '">' . basename($file) . '</option>';
                    } ?>
                </select>
            </div>
            <div class="four columns left">
                <input name="submit" type="submit" class="button-primary" id="submit" value="استعراض">
            </div>
            <input type="hidden" name="_submit" value="form1">
        </form>
    </div>
<?php if (Input::get('_submit') == "form1") { ?>
    <div class="content lp">
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                #log_container {
                    direction: ltr;
                    font-size: 22px;
                }

                #log_container p {
                    line-height: 30px;
                }

                #log_container .block {
                    padding: 10px 0;
                }

                #log_container .error {
                    color: red;
                }

                #log_container .success {
                    color: green;
                }

                #log_container .log {

                }
            </style>
        </head>
        <body>
        <div id="log_container">
            <?php include(__DIR__ . '/logs/' . Input::get('files')); ?>
        </div>
        </body>
        </html>
    </div>
<?php } ?>

<?php include_once 'templates/footer.php'; ?>