<?php
class Hash{

    public static function make($string,$salt=''){
        return hash('sha256',$string.$salt);
    }

    public static function salt() {
        return base64_encode(mcrypt_create_iv(32, MCRYPT_DEV_RANDOM));
    }

    public static function unique() {
        return self::make(uniqid());
    }
}
?>
