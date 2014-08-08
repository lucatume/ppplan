<?php

namespace PPPlan;


class System
{
    protected static function isWin()
    {
        if (defined('PHP_OS') && preg_match('/^W(in|IN).*/', PHP_OS)) {
            return true;
        }
        return false;
    }

    public static function clear()
    {
        if (self::isWin()) {
            passthru('cls');
        } else {
            passthru('clear');
        }
    }
} 