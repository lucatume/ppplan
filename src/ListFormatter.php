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
    public function formatList(Objective $objective, array $tasks)
    {
        $list = $this->formatHead($objective);
        foreach ($tasks as $task) {
            $list.= "\n" . $this->formatLine($task);
        }
        $list.= $this->formatFoot($objective);
        return $list;
    }
    public function formatHead(Objective $objective)
    {
        $out = '';
        $newlinesAfterHead = "\n";
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
        $beforeFoot = "\n\n";
        switch ($this->format) {
            case 'taskpaper':
                $out = '';
                break;

            default:
                $out = sprintf('%sThat\'s a total estimate of %s hour%s.', $beforeFoot, $objective->totalHours, Utils::getPluralSuffixFor($objective->totalHours));
                break;
        }
        return $out;
    }
}
