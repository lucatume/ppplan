<?php

namespace PPPlan;


class OptionReader
{
    public function getOptionsFrom($args)
    {
        $options = new \stdClass();
        for ($i = 0; $i < count($args); $i++) {
            if (!preg_match('/^--/', $args[$i])) {
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
    public function getDefaults()
    {
        $defaultOptions = array();
        $defaultsFile = posix_getpwuid(posix_getuid());
        $defaultsFile = $defaultsFile['dir'] . '/.ppplan';
        if (file_exists($defaultsFile)) {
            $defaults = @file_get_contents($defaultsFile);
            $defaultOptions = explode(' ', $defaults);
            $defaultOptions = array_map('trim', $defaultOptions);
            $defaultOptions = $this->getOptionsFrom($defaultOptions);
            return $defaultOptions;
        }
        return $defaultOptions;
    }
}