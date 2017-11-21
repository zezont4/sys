<?php
class Input
{
    /**
     * [[Description]]
     * @param   [[Type]] [$type='post'] [[Description]]
     * @returns Boolean  [[Description]]
     */
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;

            case 'get';
                return (!empty($_GET)) ? true : false;
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * [[Description]]
     * @param   [[Type]] $item [[Description]]
     * @returns [[Type]] [[Description]]
     */
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return escape($_POST[$item]);
        } else if (isset($_GET[$item])) {
            return escape($_GET[$item]);
        }
        return '';
    }
}