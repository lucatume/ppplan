<?php

namespace PPPlan;


class Unit
{
    protected $base;
    protected $name;

    public function __construct($base = 1, $name = 'hour')
    {
        $this->setBase($base);
        $this->setName($name);
    }

    public function setBase($base)
    {
        $this->base = (is_float($base) and $base > 0) ? $base : 1;
    }

    public function setName($name)
    {
        $this->name = is_string($name) ? $name : 'hour';
    }

    public function __get($name)
    {
        if (isset($this->name)) {

            return $this->$name;
        }
        return null;
    }
}
