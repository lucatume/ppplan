<?php

namespace PPPlan;


class PPPlan
{
    
    protected $hourReader;
    protected $listFormatter;
    protected $review;
    protected $lineColor = 'cyan';
    protected $listLineColor = 'white';
    protected $headPrinted;
    protected $options;
    
    public function __construct(HourReader $hourReader, ListFormatter $listFormatter, $review = false, $options)
    {
        $this->hourReader = $hourReader;
        $this->listFormatter = $listFormatter;
        $this->review = $review;
        $this->headPrinted = false;
        $this->options = $options;
    }
    
    public function theHead(Objective $objective)
    {
        $this->maybeClear();
        if ($this->review) {
            $line = sprintf('Review the things to do to %s (y/n)? ', $objective->title);
            echo "\n" . $line;
            echo "\n" . preg_replace('/./', '-', $line) . "\n";
            if (!preg_match('/^(Y|y).*/', readline())) {
                exit;
            }
        } else {
            $objective->title = readline("What do you want to do?\n\n");
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
            $line = "How long will it take to $task->title?\n(Either a fraction number or 0 for \"I do not know\")\n\n";
            $answer = readline($line);
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
        $subTasks = explode(', ', readline("Ok, what's needed to $task->title?\n(Tasks in a comma separated list)\n\n"));
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
        list($tasks, $totalHours) = $this->setObjective($tasks);
        $objective->totalHours = $totalHours;
        $fileList = $this->listFormatter->formatList($objective, $tasks);
        if ($echo) {
            echo "\n" . $fileList . "\n\n";
        }
        
        return $fileList;
    }

    public function theSavingOptions(Objective $objective, array $tasks)
    {
        $answer = readline("Do you want to save the list to a file (y/n)? ");
        if (Answer::isYes($answer)) {
            return;
        }
        $shouldWrite = false;
        while (!$shouldWrite) {
            $fileName = readline("\nType the name of the file to save the list in (do not include file extension):\n");
            $filePath = $this->getFilePathFor($fileName);
            if (file_exists($filePath)) {
                $answer = readline("File $filePath already created: do you want to overwrite it (y/n)?");
                if(Answer::isYes($answer)){
                    $shouldWrite = true;
                }
            }
        }
        $fileName = readline("\nType the name of the file to save the list in (do not include file extension):\n");
        $filePath = $this->getFilePathFor($fileName);
        $format = $this->getFileFormat();
        $this->listFormatter->setFormat($format);
        list($tasks, $totalHours) = $this->setObjective($tasks);
        $list = $this->listFormatter->formatList($objective, $tasks);
        // check for file existence
        if (file_exists($filePath)) {
            if (file_put_contents($filePath, $list, FILE_APPEND)) {
                echo "List appended to the $filePath file.";
            }
        } else {
            if (file_put_contents($filePath, $list)) {
                echo "List written to the $filePath file.";
            }
        }
    }
    
    protected function maybeClear()
    {
        if (isset($this->options->clear) and $this->options->clear) {
            System::clear();
        }
    }
    
    protected function maybeNewline()
    {
        if (!isset($this->options->clear) or !$this->options->clear) {
            echo "\n";
        }
    }

    protected function setObjective(array $tasks)
    {
        array_shift($tasks);
        $totalHours = 0;
        array_map(function ($task) use (&$totalHours) {
                $totalHours += $task->hours;
            }
            , $tasks);
        return array($tasks, $totalHours);
    }

    /**
     * @param $fileName
     * @return array
     */
    protected function getFilePathFor($fileName)
    {
        $fileName = is_string($fileName) ? $fileName : 'todo_' . time();
        $cwd = getcwd();
        $format = $this->getFileFormat();
        $filePath = $cwd . DIRECTORY_SEPARATOR . $fileName . '.' . $format;
        return $filePath;
    }

    /**
     * @return string
     */
    protected function getFileFormat()
    {
        $format = '';
        if (!isset($this->options->format)) {
            $line = "File format (taskpaper/txt)?\n";
            $format = readline($line);
        } else {
            $format = $this->options->format;
        }
        $format = Formats::getValidFormatFor($format);
        return $format;
    }
}
