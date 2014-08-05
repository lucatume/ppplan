<?php

namespace PPPlan;


class ListFormatter
{
    public function createListFrom(Objective $objective, array $tasks, $format = 'txt')
    {
        $fileList = sprintf('Things to do to %s', $objective->title);
        $fileList .= "\n";
        $objective->totalHours = 0;
        // remove the objective from the tasks
        unset($tasks[0]);
        $tasks = array_values($tasks);
        foreach ($tasks as $task) {
            if ($task->hours == 0) {
                continue;
            }
            $tabs = "\n\t";
            $fileList .= sprintf('%s- %s (est. %s hr%s)', $tabs, $task->title, $task->hours, Utils::getPluralSuffixFor($task->hours));
            $objective->totalHours += $task->hours;
        }
        $fileList .= "\n\n";
        $fileList .= sprintf('that\'s a total estimate of %s hour%s', $objective->totalHours, Utils::getPluralSuffixFor($objective->totalHours));
        return $fileList;
    }
}