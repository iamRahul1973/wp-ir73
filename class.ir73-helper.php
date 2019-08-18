<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:13 PM
 */

/**
 * Class IR73
 */
Class IR73_Helper
{
    public function __construct() { }

    static function error_flash($object, $message)
    {
        // DO STUFF HERE
        return false;
    }

    /**
     * @param $key
     * @return string
     */
    static function key_to_label($key)
    {
        return ucwords(str_replace(['_', '-'], ' ', $key));
    }
}

// Don’t cry because it’s over, smile because it happened.
 