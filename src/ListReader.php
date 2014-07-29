<?php

namespace PPPlan;


class ListReader
{
    protected $fileContents;
    protected $lines;

    public function __construct($file)
    {
        if (is_null($file) or !file_exists($file)) {
            throw new \ErrorException('$file does not exist!');
        }
        $this->fileContents = @file_get_contents($file);
        $this->lines = explode("\n", $this->fileContents);
    }

    public function getObjective()
    {
        $lines = $this->lines;
        $matches = array();
        preg_match("/Things to do to\\s+(.*)/uim", $lines[0], $matches);
        $objectiveTitle = trim($matches[1]);
        $lastLine = array_pop($lines);
//        that's a total estimate of 3 hours
        preg_match("/\\s([\\d\\.]+)\\shours/uim", $lastLine, $matches);
        $totalHours = $matches[1];
        return new Objective($objectiveTitle, $totalHours);
    }

    public function getTodos()
    {
        $lines = $this->lines;
        unset($lines[0]);
        unset($lines[1]);
        $lines = array_values($lines);
        array_pop($lines);
        array_pop($lines);
        $answers = array();
        foreach ($lines as $line) {
            $matches = array();
            preg_match("/\\t+-\\s+(.*)\\s\\(est.\\s+([\\d\\.]+)/uim", $line, $matches);
            $action = $matches[1];
            $estimate = floatval($matches[2]);
            $answer = new Answer($action, $estimate, true);
            $answers[] = $answer;
        }
        return $answers;
    }
}
