<?php

namespace PPPlan;


use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;

class ListFormatter
{

    /**
     * The format to use for the output, defaults to `txt`.
     *
     * @var string
     */
    protected $format = 'txt';

    protected $unit;
    /**
     * The possible && valid output formats the list formatter supports.
     *
     * @var array
     */
    protected $legitFormats = array(
        'txt',
        'taskpaper'
    );

    /**
     * @var Input
     */
    protected $input;

    public function __construct($format, Unit $unit, InputInterface $input)
    {
        $this->setFormat($format);
        $this->setUnit($unit);
        $this->input = $input;
    }

    public function formatLine(Task $task)
    {
        $out = '';
        $indent = "\t";
        switch ($this->format) {
            case 'taskpaper':
                $estimateTag = $this->input->getArgument('estimateTag');
                $out = sprintf('%s- %s @%s(%s)', $indent, $task->title, $estimateTag, ($task->hours / $this->unit->base));
                break;

            default:
                $out = sprintf('%s- %s (est. %s %s%s)', $indent, $task->title, $task->hours / $this->unit->base, $this->unit->name, Utils::getPluralSuffixFor($task->hours / $this->unit->base));
                break;
        }
        return $out;
    }

    public function formatList(Objective $objective, array $tasks)
    {
        $list = $this->formatHead($objective);
        foreach ($tasks as $task) {
            $list .= "\n" . $this->formatLine($task);
        }
        $list .= $this->formatFoot($objective);
        return $list;
    }

    public function formatHead(Objective $objective)
    {
        $out = '';
        switch ($this->format) {
            case 'taskpaper':
                $newLinesBeforeNote = "\n\n";
                $estimateTag = $this->input->getArgument('estimateTag');
                $out = sprintf('%s: @%s(%s)%sestimates in %ss', ucfirst($objective->title), $estimateTag, $objective->totalHours / $this->unit->base, $newLinesBeforeNote, $this->unit->name);
                break;

            default:
                $newlinesAfterHead = "\n";
                $out = sprintf('Things to do to %s:%s', lcfirst($objective->title), $newlinesAfterHead);
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
                $out = sprintf("%sThat's a total estimate of %s %s%s.\n", $beforeFoot, $objective->totalHours / $this->unit->base, $this->unit->name, Utils::getPluralSuffixFor($objective->totalHours / $this->unit->base));
                break;
        }
        return $out;
    }

    public function setFormat($format)
    {
        $this->format = ($format && in_array($format, $this->legitFormats)) ? $format : 'txt';
    }

    public function setUnit(Unit $unit = null)
    {
        $this->unit = $unit ? $unit : new Unit();
    }

}
