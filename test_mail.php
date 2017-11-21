<?php
require "functions.php";
define('SECRET_KEY', 'u|n-%?k--63xmhx[+}03a6I)68|ar)KfM1,bKu7:^bS!Cb17 >tSkh?d]g00:.$ ');
// أكواد حفظ تسجيل الدخول

if (!function_exists('hash_equals')) {
    function hash_equals($str1, $str2)
    {
        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
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
        storeTokenForUser($user, $token);
        $cookie = $user . ':' . $token;
        $mac = hash_hmac('sha256', $cookie, SECRET_KEY);
        $cookie .= ':' . $mac;
        setcookie('rememberme', $cookie);
    }
}

if (!function_exists("fetchTokenByUserName")) {
    function fetchTokenByUserName($user)
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

if (!function_exists("rememberMe")) {
    function rememberMe()
    {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
//        var_dump($cookie);
        if ($cookie) {
            list ($user, $token, $mac) = explode(':', $cookie);
            if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, SECRET_KEY), $mac)) {
                return false;
            }
            $user_token = fetchTokenByUserName($user);
            if (hash_equals($user_token, $token)) {
//                logUserIn($user);
                return true;
            }
        }
        return false;
    }
}
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

//var_dump(GenerateRandomToken());
//var_dump(GenerateRandomToken());
//var_dump(rememberMe());