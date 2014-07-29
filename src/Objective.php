<?php

namespace PPPlan;


class Objective
{
    public $title;
    public $totalHours;

    public function __construct($title = '', $totalHours = 0)
    {
        $this->title = $title;
        $this->totalHours = $totalHours;
    }
}
