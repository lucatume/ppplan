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
} 