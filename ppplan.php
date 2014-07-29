#!/usr/bin/php
<?php
namespace PPPlan;

include 'vendor/autoload.php';

$objective = new Objective();
$answers = array();
$review = false;
if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'review':
            if (isset($argv[2])) {
                $review = true;
                $file = $argv[2];
                $listReader = new ListReader($file);
                $objective = $listReader->getObjective();
                $answers = $listReader->getTodos();
            } else {
                echo "Please provide a file to review.";
                exit;
            }
            break;

        default:
            break;
    }
}

$colors = new Colors();
$ppplan = new Ppplan($colors, $review);

$ppplan->theHead($objective);
$answers = $ppplan->theQuestions($objective, $answers, $review);
$list = $ppplan->theResponse($objective, $answers);
$ppplan->theSavingOptions($list);
?>