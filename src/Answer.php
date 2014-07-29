<?php

namespace PPPlan;


class Answer
{
    public $title;
    public $hours;
    public $toEstimate;
    
    public function __construct($title)
    {
        $this->title = $title;
        $this->hours = 0;
        $this->toEstimate = true;
    }
}
