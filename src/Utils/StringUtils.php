<?php


namespace YOYOW\Utils;


class StringUtils
{
    public static function String2Hex($string) {
        return bin2hex($string);
    }


    public static function Hex2String($hex) {
        return hex2bin($hex);
    }
}