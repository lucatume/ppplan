<?php

namespace PPPlan;


class Objective
{
    public $title;
    public $totalHours;
    public function __construct()
    {
        $this->title = '';
        $this->totalHours = 0;
    }
}
