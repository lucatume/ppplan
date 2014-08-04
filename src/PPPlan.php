<?php

namespace PPPlan;


class PPPlan
{
    
    protected $colors;
    protected $hourReader;
    protected $review;
    protected $lineColor = 'cyan';
    protected $listLineColor = 'white';
    protected $headPrinted;
    protected $options;

    public function __construct(Colors $colors, HourReader $hourReader, $review = false, $options)
    {
        $this->colors = $colors;
        $this->hourReader = $hourReader;
        $this->review = $review;
        $this->headPrinted = false;
        $this->options = $options;
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
        $this->maybeClear();
        if ($this->review) {
            $line = sprintf('Review the things to do to %s (y/n)? ', $objective->title);
            echo "\n" . $this->color($line);
            echo "\n" . $this->color(preg_replace('/./', '-', $line)) . "\n";
            if (!preg_match('/^(Y|y).*/', readline())) {
                exit;
            }
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
        $this->maybeClear();
        $answer = '';
        if ($task->toEstimate) {
            $this->maybeNewline();
            $line = $this->color("How long will it take to $task->title?\n(Either a fraction number or 0 for \"I do not know\")\n\n", 'cyan');
            $answer = floatval(readline($line));
        }
        $task->hours = $this->hourReader->getHoursFrom($answer);
        if ($task->hours == 0) {
            $task->toEstimate = false;
            $subTasks = $this->askDecomposeFor($task);
            foreach ($subTasks as $subTask) {
                $tasks[] = new Task($subTask);
            }
        } else {
            $task->toEstimate = false;
        }
    }
    
    protected function askDecomposeFor(Task $task)
    {
        $this->maybeClear();
        $this->maybeNewline();
        $subTasks = explode(', ', readline($this->color("Ok, what's needed to $task->title?\n(Task with a comma separated list)\n\n")));
        return $subTasks;
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
        $this->maybeClear();
        $this->maybeNewline();
        $fileList = sprintf('Things to do to %s', $objective->title);
        $fileList.= "\n";
        $objective->totalHours = 0;
        // remove the objective from the tasks
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
        $fileName = readline($this->color("\nType the name of the file to save the list in (do not include file extension):\n"));
        if (!$fileName) {
            $fileName = 'todo_' . time();
        }
        $cwd = getcwd();
        $filePath = $cwd . DIRECTORY_SEPARATOR . $fileName . '.txt';
        if (file_put_contents($filePath, $list)) {
            echo $this->color("List written to the $filePath file.");
        }
    }

    protected function maybeClear()
    {
        if ($this->options->clearMode) {
            System::clear();
        }
    }

    protected function maybeNewline()
    {
        if (!$this->options->clearMode) {
            echo "\n";
        }
    }
}
