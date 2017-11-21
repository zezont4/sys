<?php require_once('../Connections/localhost.php');
require_once('../functions.php');
require_once '../secure/functions.php';
sec_session_start();
if (login_check("admin,edarh,t3lem") == true) {

	if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
		$deleteSQL = sprintf("update `0_employees` set `is_hidden` =0 WHERE id=%s",
			GetSQLValueString($_GET['id'], "double"));

		mysqli_select_db($localhost, $database_localhost);
		$Result1 = mysqli_query($localhost, $deleteSQL) or die(mysqli_error($localhost));

		$deleteGoTo = "search_e_result.php";
		if (isset($_SERVER['QUERY_STRING'])) {
			$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
			$deleteGoTo .= $_SERVER['QUERY_STRING'];
		}
		header(sprintf("Location: %s", $deleteGoTo));
	}
	?>
<?php } else {
	echo 'عفوا... لاتملك صلاحيات للدخول لهذه الصفحة.';
}