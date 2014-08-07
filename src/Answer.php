<?php

namespace PPPlan;


class Answer
{
    public static function isYes($answer)
    {
        if (!preg_match('/^(Y|y|yes|Yes)/', $answer)) {
            return false;
        }
        return true;
    }

    public static function isNo($answer)
    {
        return !self::isYes($answer);
    }
}