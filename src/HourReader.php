<?php

namespace PPPlan;


class HourReader
{
    protected $pomodoroDuration = 25;
    protected $dayDuration = 24;
    protected $options = null;

    public function __construct($options = null)
    {
        $this->options = is_null($options) ? new \stdClass() : $options;
        $this->options->dayDuration = isset($this->options->dayDuration) ? $this->options->dayDuration : 24;
        $this->options->pomodoroDuration= isset($this->options->pomodoroDuration) ? $this->options->pomodoroDuration : 25;
    }

    public function getHoursFrom($answer)
    {
        $base = 1;
        if (preg_match('/^[0-9]*\.?[0-9]+\s*m/', $answer)) {
            $base = 60;
        } else if (preg_match('/^\d+\s*h/', $answer)) {
            $base = 1;
        } else if (preg_match('/^[0-9]*\.?[0-9]+\s*d/', $answer)) {
            $base = 1 / $this->dayDuration;
        } else if (preg_match('/^[0-9]*\.?[0-9]+\s*p/', $answer)) {
            $base = 60 / $this->pomodoroDuration;
        }
        $answer = floatval($answer);
        return round($answer / $base, 2);
    }

    public function setPomodoroDuration($duration = 25)
    {
        $this->pomodoroDuration = intval($duration);
    }

    public function setDayDuration($duration = 24)
    {
        $this->dayDuration = floatval($duration);
    }
} 