<?php

namespace PPPlan;


use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PPPlan
{

    protected $hourReader;
    protected $listFormatter;
    protected $review;
    protected $lineColor = 'cyan';
    protected $listLineColor = 'white';
    protected $headPrinted;
    protected $input;
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(HourReader $hourReader, ListFormatter $listFormatter, $review = false, InputInterface $input, OutputInterface $output)
    {
        $this->hourReader = $hourReader;
        $this->listFormatter = $listFormatter;
        $this->review = $review;
        $this->headPrinted = false;
        $this->input = $input;
        $this->output = $output;
    }

    public function theHead(Objective $objective)
    {
        $this->maybeClear();
        if ($this->review) {
            $line = sprintf('Review the things to do to %s (y/n)? ', $objective->title);
            $this->output->writeln($line);
            $this->output->writeln(preg_replace('/./', '-', $line));
            if (!preg_match('/^(Y|y).*/', readline())) {
                exit();
            }
        } else {
            $objective->title = readline("What do you want to do?\n\n");
        }
    }

    public function theQuestions(Objective $objective, array $tasks, $review = false)
    {
        $tasks = array_merge(array(
            new Task($objective->title, $objective->totalHours, !$review)
        ), $tasks);
        while ($this->thereAreUnestimated($tasks)) {
            foreach ($tasks as $task) {
                if (!$task->toEstimate && $task->hours == 0) {
                    continue;
                }
                $this->askEstimationFor($task, $tasks);
            }
        }

        return $tasks;
    }

    protected function askEstimationFor(Task $task, array & $tasks)
    {
        if ($task->hours > 0 && !$task->toEstimate) {
            return;
        }
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

            // remove the task from the tasks
            $key = array_search($task, $tasks);
            unset($tasks[$key]);
            $tasks = array_values($tasks);
            foreach ($subTasks as $subTask) {

                // 0 hours, to estimate
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
        return false;
    }

    public function theResponse(Objective $objective, array $tasks, $echo = true)
    {
        $this->maybeClear();
        $this->maybeNewline();
        $totalHours = $this->getTotalHoursFrom($tasks);
        $objective->totalHours = $totalHours;
        $fileList = $this->listFormatter->formatList($objective, $tasks);
        if ($echo) {
            $this->output->writeln($fileList . "\n\n");
        }

        return $fileList;
    }

    public function theSavingOptions(Objective $objective, array $tasks)
    {
        $answer = readline("Do you want to save the list to a file (y/n)? ");
        if (Answer::isNo($answer)) {
            return;
        }
        $shouldWrite = false;
        $filePath = '';
        $format = '';
        $baseName = '';
        do {
            $fileName = readline("\nType the name of the file to save the list in (do not include file extension):\n");
            list($filePath, $format) = $this->getFilePathFor($fileName);
            $baseName = baseName($filePath);
            if (file_exists($filePath)) {
                $answer = readline("File $baseName already created: do you want to append the list to it (y/n)?");
                if (Answer::isYes($answer)) {
                    $shouldWrite = true;
                }
            } else {
                $shouldWrite = true;
            }
        } while (!$shouldWrite);

        $this->listFormatter->setFormat($format);
        $list = $this->listFormatter->formatList($objective, $tasks);

        // check for file existence
        if (file_exists($filePath)) {

            // prepend the new list with newlines
            $list = "\n\n" . $list;
            if (file_put_contents($filePath, $list, FILE_APPEND)) {
                echo "List appended to the $baseName file.";
            }
        } else {
            if (file_put_contents($filePath, $list)) {
                $this->output->writeln("List written to the $baseName file.");
            }
        }
    }

    protected function maybeClear()
    {
        if ($this->input->getOption('clear')) {
            System::clear();
        }
    }

    protected function maybeNewline()
    {
        if ($this->input->getOption('clear')) {
            echo "\n";
        }
    }

    protected function getTotalHoursFrom(array $tasks)
    {
        $totalHours = 0;
        array_map(function (Task $task) use (&$totalHours) {
            $totalHours += $task->hours;
        }
            , $tasks);
        return $totalHours;
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
        return array(
            $filePath,
            $format
        );
    }

    /**
     * @return string
     */
    protected function getFileFormat()
    {
        $format = Formats::getValidFormatFor($this->input->getArgument('format'));
        return $format;
    }
}
