<?php

use Carbon\Carbon;

if (!function_exists('generateFileName')) {
    function generateFileName($name)
    {
        $year = now()->year;
        $month = now()->month;
        $day = now()->day;
        $hour = now()->hour;
        $minute = now()->minute;
        $second = now()->second;
        $microsecond = now()->microsecond;

        return $year . '-' . $month . '-' . $day . '-' . $hour . '-' . $minute . '-' . $second . '-' . $microsecond . '-' . $name;
    }
}

if (!function_exists('generateProductImageLink')) {
    function generateProductImageLink($imageName)
    {
        return url(env('PRODUCT_IMAGES_UPLOAD_PATH') . $imageName);
    }
}

if (!function_exists('convertShamsiToGregorianDate')) {
    function convertShamsiToGregorianDate($date)
    {
        if (!is_null($date)) {
            
            $pattern = "/[-\s]/";

            $shamsiDateSplit = preg_split($pattern, $date);

            $arrayGregorianDate = verta()->jalaliToGregorian($shamsiDateSplit[0], $shamsiDateSplit[1], $shamsiDateSplit[2]);

            return implode("-", $arrayGregorianDate) . " " . $shamsiDateSplit[3];
        }

        return null;
    }
}
