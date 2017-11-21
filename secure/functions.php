<?php
require_once __DIR__ . '/../Connections/localhost.php';
if (!function_exists("zlog")) {
    function zlog($MSG)
    { ?>
        <script>
            console.log("<?php echo $MSG;?>");
        </script>
    <?php }
}


// أكواد حفظ تسجيل الدخول
define('SECRET_KEY', 'u|n-%?k--63xmhx[+}03a6I)68|ar)KfM1,bKu7:^bS!Cb17 >tSkh?d]g00:.$ ');


/**
 * A timing safe equals comparison
 *
 * To prevent leaking length information, it is important
 * that user input is always used as the second parameter.
 *
 * @param string $safe The internal (safe) value to be checked
 * @param string $user The user submitted (unsafe) value
 *
 * @return boolean True if the two strings are identical.
 */
if (!function_exists("timingSafeCompare")) {
    function timingSafeCompare($safe, $user)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($safe, $user); // PHP 5.6
        }
        // Prevent issues if string length is 0
        $safe .= chr(0);
        $user .= chr(0);

        // mbstring.func_overload can make strlen() return invalid numbers
        // when operating on raw binary strings; force an 8bit charset here:
        if (function_exists('mb_strlen')) {
            $safeLen = mb_strlen($safe, '8bit');
            $userLen = mb_strlen($user, '8bit');
        } else {
            $safeLen = strlen($safe);
            $userLen = strlen($user);
        }

        // Set the result to the difference between the lengths
        $result = $safeLen - $userLen;

        // Note that we ALWAYS iterate over the user-supplied length
        // This is to prevent leaking length information
        for ($i = 0; $i < $userLen; $i++) {
            // Using % here is a trick to prevent notices
            // It's safe, since if the lengths are different
            // $result is already non-0
            $result |= (ord($safe[$i % $safeLen]) ^ ord($user[$i]));
        }

        // They are only identical strings if $result is exactly 0...
        return $result === 0;
    }
}

if (!function_exists("GenerateRandomToken")) {
    function GenerateRandomToken()
    {
        require_once "random_compat_lib/random.php";
        try {
            $string = random_bytes(128);
        } catch (TypeError $e) {
            // Well, it's an integer, so this IS unexpected.
            die("An unexpected error has occurred");
        } catch (Error $e) {
            // This is also unexpected because 32 is a reasonable integer.
            die("An unexpected error has occurred");
        } catch (Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            die("Could not generate a random string. Is our OS secure?");
        }
        return bin2hex($string);
    }
}


if (!function_exists("storeTokenForUser")) {
    function storeTokenForUser($user, $token)
    {
        global $database_localhost, $localhost;
        mysqli_select_db($localhost, $database_localhost);
        $query_stmt = sprintf("Update 0_users set remember_token=%s WHERE  id = %s",
            GetSQLValueString($token, "text"),
            GetSQLValueString($user, "int")
        );
        $result = mysqli_query($localhost, $query_stmt) or die('storeTokenForUser : ' . mysqli_error($localhost));
        return $result;
    }
}

if (!function_exists("storeCookie")) {
    function storeCookie($user)
    {
        $token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit

        $user_token = fetchTokenByUserId($user);
        if ($user_token) {
            $token = $user_token;
        }
        storeTokenForUser($user, $token);
        $cookie = $user . ':' . $token;
        $mac = hash_hmac('sha256', $cookie, SECRET_KEY);
        $cookie .= ':' . $mac;
//        var_dump($cookie);
        setcookie('rememberme', $cookie, time() + 60 * 60 * 24 * 30, '/');
    }
}
//storeCookie(29);
if (!function_exists("fetchTokenByUserId")) {
    function fetchTokenByUserId($user)
    {
        $token = null;
        global $database_localhost, $localhost;
        mysqli_select_db($localhost, $database_localhost);
        $query = sprintf("SELECT remember_token FROM 0_users WHERE id = %s", GetSQLValueString($user, "int"));
        $result = mysqli_query($localhost, $query) or die('$database_localhost : ' . mysqli_error($localhost));
        $user_data = mysqli_fetch_assoc($result);
        $user_data_count = mysqli_num_rows($result);
        if ($user_data_count) {
            $token = $user_data['remember_token'];
        }
        return $token;
    }
}

if (!function_exists("fetch_user_id_from_cookie")) {
    function fetch_user_id_from_cookie()
    {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
//        var_dump($cookie);
        if ($cookie) {
            list ($user, $token, $mac) = explode(':', $cookie);
            if (!timingSafeCompare(hash_hmac('sha256', $user . ':' . $token, SECRET_KEY), $mac)) {
                return false;
            }
            $user_token = fetchTokenByUserId($user);
            if (timingSafeCompare($user_token, $token)) {
//                logUserIn($user);
                return $user;
            }
        }
        return false;
    }
}

if (!function_exists("rememberMe")) {
    function rememberMe()
    {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
//        var_dump($cookie);
        if ($cookie) {
            list ($user, $token, $mac) = explode(':', $cookie);
            if (!timingSafeCompare(hash_hmac('sha256', $user . ':' . $token, SECRET_KEY), $mac)) {
                return false;
            }
            $user_token = fetchTokenByUserId($user);
            if (timingSafeCompare($user_token, $token)) {
//            var_dump($token);
//                logUserIn($user);
                return true;
            }
        }
        return false;
    }
}


#نهاية اكواد تذكر تسجيل الدخول

//SECURE SESSION START FUNCTION

if (!function_exists("sec_session_start")) {
    function sec_session_start()
    {
        if (!isset($_SESSION)) {
            if (!headers_sent()) {
                $session_name = 'sec_session_id'; // Set a custom session name
                $secure = false; // Set to true if using https.
                $httponly = true; // This stops javascript being able to access the session id.

                ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies.
                $cookieParams = session_get_cookie_params(); // Gets current cookies params.
                session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
                session_name($session_name); // Sets the session name to the one set above.
                //$status = session_status();
                //if($status == PHP_SESSION_NONE){
                if (!isset($_SESSION)) {
                    session_start(); // Start the php session
                    session_regenerate_id(true); // regenerated the session,delete the old one.
                }
            }
        }
    }
}

if (!function_exists("store_user_data_to_sessions")) {
    function store_user_data_to_sessions($user_data, $password)
    {
        $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

        $user_id = preg_replace("/[^0-9]+/", "", $user_data['id']); // XSS protection as we might print this value
        $_SESSION['user_id'] = $user_id;
        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $user_data['username']); // XSS protection as we might print this value
        $_SESSION['username'] = $username;
        $_SESSION['user_group'] = $user_data['user_group'];
        $_SESSION['arabic_name'] = $user_data['arabic_name'];
//        setcookie("arabic_name", $user_data['arabic_name'], time() + 60 * 60 * 24 * 30, '/');
        $_SESSION['sex'] = $user_data['sex'];
        $_SESSION['can_edit'] = $user_data['can_edit'];
        $_SESSION['login_string'] = $password . $ip_address . $user_browser;

        // get default year
        global  $localhost;
//        mysqli_select_db($localhost, $database_localhost);
        $query_default_y = "SELECT * FROM `0_years` WHERE default_y=1";
        $default_y = mysqli_query($localhost, $query_default_y) or die(mysqli_error($localhost));
        $row_default_y = mysqli_fetch_assoc($default_y);
        $totalRows_default_y = mysqli_num_rows($default_y);
        $_SESSION ['default_year_id'] = $row_default_y['y_id'];
        $_SESSION ['default_year_name'] = $row_default_y['year_name'];
        $_SESSION ['default_start_date'] = $row_default_y['y_start_date'];
        $_SESSION ['default_end_date'] = $row_default_y['y_end_date'];
    }
}

//SECURE LOGIN FUNCTION
if (!function_exists("login")) {
    function login($user_name, $password)
    {
        //$_SESSION['logz']=$user_name.' - '.$password.'<br>';
        global $database_localhost, $localhost;
        // Using prepared Statements means that SQL injection is not possible.
        mysqli_select_db($localhost, $database_localhost);
        $remember_me = rememberMe();
        $user_id_from_cookie = fetch_user_id_from_cookie();

//        var_dump($remember_me);
//        var_dump($user_id_from_cookie);
        $query_stmt = sprintf("SELECT * FROM 0_users WHERE hidden = 0 and username = %s LIMIT 1",
            GetSQLValueString($user_name, "text"));
        if ($remember_me) {
            $query_stmt = sprintf("SELECT * FROM 0_users WHERE id = %s LIMIT 1",
                GetSQLValueString($user_id_from_cookie, "int"));
        }
        $stmt = mysqli_query($localhost, $query_stmt) or die('query_stmt : ' . mysqli_error($localhost));
        $row_stmt = mysqli_fetch_assoc($stmt);
//        var_dump($row_stmt);
        $totalRows_stmt = mysqli_num_rows($stmt);
        // get variables from result.

        if ($totalRows_stmt > 0) {
            //$_SESSION['logz']=$_SESSION['logz'].'<p>user exist</p><br>';
//            $user_id = $row_stmt['id'];
//            $username = $row_stmt['username'];
//            $db_password = $row_stmt['password'];
//            $usergroup = $row_stmt['user_group'];
//            $arabic_name = $row_stmt['arabic_name'];
//            $sex = $row_stmt['sex'];
//            $can_edit = $row_stmt['can_edit'];
            //$_SESSION['logz']=$_SESSION['logz'].'<p>db_pass=: </p><br>'.$db_password.'<p>password=: </p><br>'.$password;
            if ($row_stmt['password'] == $password) {
//                var_dump('asdf');
                //$_SESSION['logz']=$_SESSION['logz'].'<p>Password is correct</p><br>';
                //Check if the password in the database matches the password the user submitted.
                //Password is correct
//                $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user.
//                $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
//
//                $user_id = preg_replace("/[^0-9]+/", "", $row_stmt['id']); // XSS protection as we might print this value
//                $_SESSION['user_id'] = $user_id;
//                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $row_stmt['username']); // XSS protection as we might print this value
//                $_SESSION['username'] = $username;
//                $_SESSION['user_group'] = $row_stmt['user_group'];
//                $_SESSION['arabic_name'] = $row_stmt['arabic_name'];
//                setcookie("arabic_name", $row_stmt['arabic_name'], time() + 60 * 60 * 24 * 30, '/');
//                $_SESSION['sex'] = $row_stmt['sex'];
//                $_SESSION['can_edit'] = $row_stmt['can_edit'];
//                $_SESSION['login_string'] = $password . $ip_address . $user_browser;
                store_user_data_to_sessions($row_stmt, $password);
                //Login successful.';
                storeCookie($row_stmt['id']);
//                exit('sdf');
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
if (!function_exists("login_check")) {
    function login_check($allowed_groups)
    {

        if (rememberMe()) {
            global $database_localhost, $localhost;
            mysqli_select_db($localhost, $database_localhost);
            $user_id_from_cookie = fetch_user_id_from_cookie();
            $query_stmt = sprintf("SELECT * FROM 0_users WHERE id = %s LIMIT 1",
                GetSQLValueString($user_id_from_cookie, "int"));
            $stmt = mysqli_query($localhost, $query_stmt) or die('login_check : ' . mysqli_error($localhost));
            $row_stmt = mysqli_fetch_assoc($stmt);
            store_user_data_to_sessions($row_stmt, $row_stmt['password']);
        }


//        $remember_me = rememberMe();
        // Check if all session variables are set
        if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
            login(1, 1);
//            var_dump($remember_me);
            $user_id = $_SESSION['user_id'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['username'];
            $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user.
            $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

            global $database_localhost, $localhost;
            // Using prepared Statements means that SQL injection is not possible.
            mysqli_select_db($localhost, $database_localhost);
            $query_stmt = sprintf("SELECT password,user_group FROM 0_users WHERE `hidden` = 0 and id = %s LIMIT 1", GetSQLValueString($user_id, "text"));
            $stmt = mysqli_query($localhost, $query_stmt) or die('query_stmt : ' . mysqli_error($localhost));
            $row_stmt = mysqli_fetch_assoc($stmt);
            $totalRows_stmt = mysqli_num_rows($stmt);
            // get variables from result.
            if ($totalRows_stmt > 0) {
                // get variables from result.
                $password = $row_stmt['password'];
                $user_group = $row_stmt['user_group'];
                $login_check = $password . $ip_address . $user_browser;

                if ($login_check == $login_string) {
                    $arrAllowdGroups = Explode(",", $allowed_groups);
                    if (in_array($user_group, $arrAllowdGroups)) {
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
