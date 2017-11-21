<?php
class Token{

    public static function generate($formName){
        return Session::put($formName,md5(uniqid()));
    }

    public static function check($token,$formName){
        $tokenName= $formName;
        if(Session::exists($tokenName) && $token === Session::get($tokenName)){
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}