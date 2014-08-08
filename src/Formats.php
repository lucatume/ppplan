<?php

namespace PPPlan;


class Formats
{
    protected static $validFormats = array('txt', 'taskpaper');

    public static function getValidFormatFor($format)
    {
        return (isset($format) && in_array($format, self::$validFormats)) ? $format : self::$validFormats[0];
    }
}