<?php
if ( ! function_exists('is_Date')) {
    function is_Date($str, $format = 'Y-m-d')
    {
        $unixTime_1 = strtotime($str);
        if (!is_numeric($unixTime_1)) return false; //如果不是数字格式，则直接返回
        $checkDate = date($format, $unixTime_1);
        $unixTime_2 = strtotime($checkDate);
        if ($unixTime_1 == $unixTime_2) {
            return true;
        } else {
            return false;
        }
    }
}