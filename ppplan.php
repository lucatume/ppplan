<?php
$ppplan = new Ppplan();
$objective = new Objective;
$answers = array();

$ppplan->theHead($objective);
$answers = $ppplan->theQuestions($objective, $answers);
$list = $ppplan->theResponse($objective, $answers);
$ppplan->theSavingOption($list);

class Ppplan
{
    public function __construct()
    {
    }
    public function theHead($objective)
    {
        $objective->title = readline("What do you want to do?\n\n");
    }
    public function theQuestions($objective, $answers)
    {
        $answers[0] = new Answer($objective->title);
        while ($this->thereAreUnestimated($answers)) {
            foreach ($answers as $answer) {
                if ($answer->hours > 0 or !$answer->toEstimate) {
                    continue;
                }
                $this->askEstimationFor($answer, $answers);
            }
        }
        return $answers;
    }
    protected function askEstimationFor($answer, &$answers)
    {
        $answer->hours = floatval(readline("How long will it take to $answer->title?\n(Either a fraction number or 0 for \"I do not know\")\n\n"));
        if ($answer->hours == 0) {
            $answer->toEstimate = false;
            $subAnswers = $this->askDecomposeFor($answer);
            foreach ($subAnswers as $subAnswer) {
                $answers[] = new Answer($subAnswer);
            }
        } else {
            $answer->toEstimate = false;
        }
    }
    protected function askDecomposeFor($answer)
    {
        $subAnswers = explode(', ', readline("Ok, what's needed to $answer->title?\n(Answer with a comma separated list)\n\n"));
        return $subAnswers;
    }
    protected function thereAreUnestimated($answers)
    {
        foreach ($answers as $answer) {
            if ($answer->toEstimate) {
                return true;
            }
        }
    }
    public function theResponse($objective, $answers, $echo = true)
    {
        $list = sprintf('Things to do to %s', $objective->title);
        $list.= "\n";
        $objective->totalHours = 0;
        foreach ($answers as $answer) {
            if ($answer->hours == 0) {
                continue;
            }
            $tabs = "\t";
            $list.= sprintf('%s %s (%s hr%s)', $tabs, $answer->title, $answer->hours, $this->getPluralSuffixFor($answer->hours));
            $objective->totalHours+= $answer->hours;
        }
        $list.= "\n";
        $list.= sprintf('that\'s a total estimate of %s hour%s', $objective->totalHours, $this->getPluralSuffixFor($totalHours));
        if ($echo) {
            echo "\n" . $list . "\n";
        }
        return $list;
    }
    public function theSavingOption($list)
    {
        $answer = readline("Do you want to save the list to a file (y/n)? ");
        if (!preg_match('/^(Y|y|yes|Yes)/', $answer)) {
            return;
        }
        $cwd = getcwd();
        $filePath = $cwd . DIRECTORY_SEPARATOR . 'todo.txt';
        @file_put_contents($filePath, $list);
    }
    protected function getPluralSuffixFor($hours)
    {
        return $hours > 1 ? 's' : '';
    }
}
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
