<?php
if (! function_exists ( "zlog" )) {
	function zlog($MSG){ ?>
		<script>
			console.log("<?php echo $MSG ;?>");
		</script>
	<?php }
}
//SECURE SESSION START FUNCTION
if (! function_exists ( "sec_session_start" )) {
	function sec_session_start() {
		if (!isset($_SESSION)) {
			if (!headers_sent()) {
				$session_name = 'sec_session_id'; // Set a custom session name
				$secure = false; // Set to true if using https.
				$httponly = true; // This stops javascript being able to access the session id.

				ini_set('session.use_only_cookies',1); // Forces sessions to only use cookies.
				$cookieParams = session_get_cookie_params(); // Gets current cookies params.
				session_set_cookie_params($cookieParams["lifetime"],$cookieParams["path"],$cookieParams["domain"],$secure,$httponly);
				session_name($session_name); // Sets the session name to the one set above.
				//$status = session_status();
				//if($status == PHP_SESSION_NONE){
				if (!isset($_SESSION)){
					session_start(); // Start the php session
					session_regenerate_id(true); // regenerated the session,delete the old one.
				}
			}
		}
	}
}
//SECURE LOGIN FUNCTION
if (! function_exists ( "login" )) {
	function login($username,$password) {
		//$_SESSION['logz']=$username.' - '.$password.'<br>';
		global $database_localhost,$localhost;
		// Using prepared Statements means that SQL injection is not possible. 
		mysqli_select_db($localhost,$database_localhost);
		$query_stmt = sprintf("SELECT id,username,password,user_group,arabic_name,sex,can_edit
								FROM 0_users WHERE hidden = 0 and username = %s LIMIT 1",
						GetSQLValueString($username,"text"));
		$stmt = mysqli_query ($localhost,$query_stmt )or die('query_stmt : '.  mysqli_error($localhost) );
		$row_stmt = mysqli_fetch_assoc ( $stmt );
		$totalRows_stmt = mysqli_num_rows ( $stmt );
		// get variables from result.
		if($totalRows_stmt > 0){
			//$_SESSION['logz']=$_SESSION['logz'].'<p>user exist</p><br>';
			$user_id=$row_stmt['id'];
			$username=$row_stmt['username'];
			$db_password=$row_stmt['password'];
			$usergroup=$row_stmt['user_group'];
			$arabic_name=$row_stmt['arabic_name'];
			$sex=$row_stmt['sex'];
			$can_edit=$row_stmt['can_edit'];
			//$_SESSION['logz']=$_SESSION['logz'].'<p>db_pass=: </p><br>'.$db_password.'<p>password=: </p><br>'.$password;
			if($db_password == $password) {
				//$_SESSION['logz']=$_SESSION['logz'].'<p>Password is correct</p><br>';
				//Check if the password in the database matches the password the user submitted.
				//Password is correct
				$ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user.
				$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

				$user_id = preg_replace("/[^0-9]+/","",$user_id); // XSS protection as we might print this value
				$_SESSION['user_id'] = $user_id;
				$username = preg_replace("/[^a-zA-Z0-9_\-]+/","",$username); // XSS protection as we might print this value
				$_SESSION['username'] = $username;
				$_SESSION['user_group'] = $usergroup;
				$_SESSION['arabic_name'] = $arabic_name;
				setcookie("arabic_name",$arabic_name,time()+60*60*24*30,'/') ;
				$_SESSION['sex'] = $sex;
				$_SESSION['can_edit'] = $can_edit;
				$_SESSION['login_string'] = $password.$ip_address.$user_browser;
				//Login successful.';
				return true;
			} else {
				//$_SESSION['logz']=$_SESSION['logz'].'<p>Password is (NOT) correct</p><br>';
				return false;
			}
		} else {
			//$_SESSION['logz']=$_SESSION['logz'].'<p>No user exists</p><br>';
			//No user exists.';
			return false;
		}
	}
}
//CREATE LOGIN CHECK FUNCTION - Logged Status
if (! function_exists ( "login_check" )) {
	function login_check($allowdgroupes) {
		// Check if all session variables are set
		if(isset($_SESSION['user_id'],$_SESSION['username'],$_SESSION['login_string'])) {
			$user_id = $_SESSION['user_id'];
			$login_string = $_SESSION['login_string'];
			$username = $_SESSION['username'];
			$ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user.
			$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

			global $database_localhost,$localhost;
			// Using prepared Statements means that SQL injection is not possible.
			mysqli_select_db($localhost,$database_localhost);
			$query_stmt = sprintf("SELECT password,user_group FROM 0_users WHERE `hidden` = 0 and id = %s LIMIT 1",GetSQLValueString($user_id,"text"));
			$stmt = mysqli_query($localhost,$query_stmt)or die('query_stmt : '. mysqli_error($localhost) );
			$row_stmt = mysqli_fetch_assoc ( $stmt );
			$totalRows_stmt = mysqli_num_rows ( $stmt );
			// get variables from result.
			if($totalRows_stmt > 0){
				// get variables from result.
				$password=$row_stmt['password'];
				$user_group=$row_stmt['user_group'];
				$login_check = $password.$ip_address.$user_browser;

				if($login_check == $login_string) {
					$arrAllowdGroups = Explode ( ",",$allowdgroupes );
					if (in_array ( $user_group,$arrAllowdGroups )) {
						// Logged In!!!!
						return true;
					}
				} else {
					// Not logged in
					return false;
				}
			} else {
				// Not logged in
				return false;
			}

		} else {
			// Not logged in
			return false;
		}
	}
}
?>