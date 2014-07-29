<?php

namespace PPPlan;


class Answer
{
    public $title;
    public $hours;
    public $toEstimate;
    
    public function __construct($title, $hours = 0, $toEstimate = true)
    {
        $this->title = $title;
        $this->hours = $hours;
        $this->toEstimate = $toEstimate;
    }
}
