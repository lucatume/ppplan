<?php

namespace PPPlan;


class Units
{
    public static function getValidUnits()
    {
        return array(
            'minute' => 1/60,
            'hour' => 1,
            'pomodoro' => 60 / 30,
            'day' => 24,
            'week' => 24 * 7
        );
    }

    public static function createOutputUnitFrom($outputUnitName = null)
    {
        $validUnits = self::getValidUnits();
        $unit = (isset($outputUnitName) && array_key_exists($outputUnitName, $validUnits)) ? $outputUnitName : 'hour';
        return new Unit($validUnits[$outputUnitName], $outputUnitName);
    }
}