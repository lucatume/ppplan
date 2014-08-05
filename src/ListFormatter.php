<?php

namespace PPPlan;


class ListFormatter
{
    protected $format = 'txt';
    protected $legitFormats = array(
        'txt',
        'taskpaper'
    );
    
    public function __construct($format = null)
    {
        $this->format = ($format and in_array($format, $this->legitFormats)) ? $format : 'txt';
    }
    public function formatLine(Task $task)
    {
        $out = '';
        $indent = "\t";
        switch ($this->format) {
            case 'taskpaper':
                $out = sprintf('%s- %s @est(%s)', $indent, $task->title, $task->hours);
                break;

            default:
                $out = sprintf('%s- %s (est. %s hr%s)', $indent, $task->title, $task->hours, Utils::getPluralSuffixFor($task->hours));
                break;
        }
        return $out;
    }
    public function createListFrom(Objective $objective, array $tasks, $format = 'txt')
    {
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
        return $fileList;
    }
    public function formatHead(Objective $objective)
    {
        $out = '';
        $newlinesAfterHead = "\n\n";
        switch ($this->format) {
            case 'taskpaper':
                $out = sprintf('%s: @est(%s)%s', ucfirst($objective->title) , $objective->totalHours, $newlinesAfterHead);
                break;

            default:
                $out = sprintf('Things to do to %s:%s', lcfirst($objective->title) , $newlinesAfterHead);
                break;
        }
        return $out;
    }
    public function formatFoot(Objective $objective)
    {
        $out = '';
        $beforeHead = "\n";
        switch ($this->format) {
            case 'taskpaper':
                $out = '';
                break;

            default:
                $out = sprintf('%sThat\'s a total estimate of %s hour%s.', $beforeHead, $objective->totalHours, Utils::getPluralSuffixFor($objective->totalHours));
                break;
        }
        return $out;
    }
}
