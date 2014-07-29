<?php

namespace PPPlan;

include 'vendor/autoload.php';

$colors = new Colors();
$ppplan = new Ppplan($colors);
$objective = new Objective();
$answers = array();

$ppplan->theHead($objective);
$answers = $ppplan->theQuestions($objective, $answers);
$list = $ppplan->theResponse($objective, $answers);
$ppplan->theSavingOptions($list);

