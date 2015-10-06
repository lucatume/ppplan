<?php

namespace PPPlan;


use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;

class HourReader
{
    /**
     * @var int The duration of a pomodoro in minutes including the pause.
     */
    protected $pomodoroDuration;
    protected $dayDuration;
    protected $weekDuration;

    public function __construct(InputInterface $input)
    {
        $this->dayDuration = floatval($input->getArgument('dayDuration'));
        $this->weekDuration = floatval($input->getArgument('weekDuration'));
        $this->pomodoroDuration = floatval($input->getArgument('pomodoroDuration'));
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
        } else if (preg_match('/^[0-9]*\.?[0-9]+\s*w/', $answer)) {
            $base = 1 / $this->dayDuration / $this->weekDuration;
        }
        $answer = floatval($answer);
        return round($answer / $base, 2);
    }
}