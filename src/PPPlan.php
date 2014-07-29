<?php

namespace PPPlan;


class PPPlan
{
    
    protected $colors;
    protected $review;
    protected $lineColor = 'green';
    protected $listLineColor = 'white';
    
    public function __construct(Colors $colors, $review = false)
    {
        $this->colors = $colors;
        $this->review = $review;
    }
    
    protected function color($string)
    {
        return $this->colors->getColoredString($string, $this->lineColor);
    }
    
    protected function listColor($string)
    {
        return $this->colors->getColoredString($string, $this->listLineColor);
    }
    
    public function theHead(Objective $objective)
    {
        if ($objective->title != '') {
            $line = sprintf('Reviewing the things to do to %s', $objective->title);
            echo $this->color($line);
        } else {
            $objective->title = readline($this->color("What do you want to do?\n\n"));
        }
    }
    
    public function theQuestions(Objective $objective, array $tasks, $review = false)
    {
        $tasks = array_merge(array(
            new Task($objective->title, $objective->totalHours, !$review)
        ) , $tasks);
        while ($this->thereAreUnestimated($tasks)) {
            foreach ($tasks as $task) {
                if (!$task->toEstimate and $task->hours == 0) {
                    continue;
                }
                $this->askEstimationFor($task, $tasks);
            }
        }
        
        return $tasks;
    }
    
    protected function askEstimationFor(Task $task, array & $tasks)
    {
        if ($this->review) {
            echo $this->color("\nPrevious estimate to $task->title is $task->hours hours.", 'cyan');
        }
        if ($task->toEstimate) {
            $line = $this->color("\nHow long will it take to $task->title?\n(Either a fraction number or 0 for \"I do not know\")\n\n", 'cyan');
            $task->hours = floatval(readline($line));
        }
        if ($task->hours == 0) {
            $task->toEstimate = false;
            $subAnswers = $this->askDecomposeFor($task);
            foreach ($subAnswers as $subAnswer) {
                $tasks[] = new Task($subAnswer);
            }
        } else {
            $task->toEstimate = false;
        }
    }
    
    protected function askDecomposeFor(Task $task)
    {
        $subAnswers = explode(', ', readline($this->color("\nOk, what's needed to $task->title?\n(Task with a comma separated list)\n\n")));
        
        return $subAnswers;
    }
    
    protected function thereAreUnestimated(array $tasks)
    {
        foreach ($tasks as $task) {
            if ($task->toEstimate) {
                return true;
            }
        }
    }
    
    public function theResponse(Objective $objective, array $tasks, $echo = true)
    {
        $fileList = sprintf('Things to do to %s', $objective->title);
        $fileList.= "\n";
        $objective->totalHours = 0;
        
        // remove the objective from the answers
        unset($tasks[0]);
        $tasks = array_values($tasks);
        foreach ($tasks as $task) {
            if ($task->hours == 0) {
                continue;
            }
            $tabs = "\n\t";
            $fileList.= sprintf('%s- %s (est. %s hr%s)', $tabs, $task->title, $task->hours, Utils::getPluralSuffixFor($task->hours));
            $objective->totalHours+= $task->hours;
        }
        $fileList.= "\n\n";
        $fileList.= sprintf('that\'s a total estimate of %s hour%s', $objective->totalHours, Utils::getPluralSuffixFor($objective->totalHours));
        if ($echo) {
            echo "\n" . $this->listColor($fileList) . "\n\n";
        }
        
        return $fileList;
    }
    
    public function theSavingOptions($list)
    {
        $task = readline($this->color("Do you want to save the list to a file (y/n)? "));
        if (!preg_match('/^(Y|y|yes|Yes)/', $task)) {
            return;
        }
        $cwd = getcwd();
        $filePath = $cwd . DIRECTORY_SEPARATOR . 'todo_' . time() . '.txt';
        if (file_put_contents($filePath, $list)) {
            echo $this->color("List written to the $filePath file.");
        }
    }
}
