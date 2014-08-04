#!/usr/bin/php
<?php

namespace PPPlan;


include 'vendor/autoload.php';

$objective = new Objective();
$tasks = array();
$review = false;
if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'review':
            if (isset($argv[2])) {
                $review = true;
                $file = $argv[2];
                $listReader = new ListReader($file);
                $objective = $listReader->getObjective();
                $tasks = $listReader->getTodos();
            } else {
                echo "Please provide a file to review.";
                exit;
            }
            break;

        default:
            break;
    }
}
$options = new \stdClass();
$options->clearMode = in_array('--clear', $argv) ? true : false;

$colors = new Colors();
$hourReader = new HourReader($options);
$ppplan = new PPPlan($colors, $hourReader, $review, $options);

$ppplan->theHead($objective);
$tasks = $ppplan->theQuestions($objective, $tasks, $review);
$list = $ppplan->theResponse($objective, $tasks);
$ppplan->theSavingOptions($list);
?>
