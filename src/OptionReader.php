<?php

namespace PPPlan;


class OptionReader
{
    public function getOptionsFrom($args)
    {
        $options = new \stdClass();
        $catchValue = false;
        for ($i = 0; $i < count($args); $i++) {
            if (!preg_match('/^--/', $args[$i]) and !$catchValue) {
                continue;
            } else {
                // it's an option key
                $optionName = str_replace('--', '', $args[$i]);
                // the option value is either the following arg or true if the
                // following arg is another option, also increase the counter (eventually)
                $optionValue = true;
                if (isset($args[$i + 1]) and !preg_match('/^--/', $args[$i + 1])) {
                    $optionValue = $args[++$i];
                }
                $options->$optionName = $optionValue;
            }
        }
        return $options;
    }
}