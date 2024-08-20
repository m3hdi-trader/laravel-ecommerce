<?php

namespace App\Helpers;

use Hekmatinasser\Verta\Verta;

class DateConversion
{
    public static function ConvertSolarDateToGregorian($data)
    {
        if ($data == null) {
            return null;
        }
        $pattern = "/[-\s]/";
        $shamsiDateSplit = preg_split($pattern, $data);

        $numberOfEnglish = ConvertNumber::convert_numbers($shamsiDateSplit);
        $year = (int)$numberOfEnglish[0];
        $month = (int)$numberOfEnglish[1];
        $day = (int)$numberOfEnglish[2];

        $gregorianData = Verta::jalaliToGregorian($year, $month, $day);
        return implode("-", $gregorianData) . " " . ConvertNumber::convert_numbers($shamsiDateSplit[3]);
    }
}
