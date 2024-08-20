<?php

namespace App\Helpers;



class ConvertNumber
{
    public static function convert_numbers($input)
    {
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $string = str_replace($persian, $english, $input);
        return $string;
    }
}
