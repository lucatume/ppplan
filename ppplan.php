<?php

namespace PPPlan;

include 'vendor/autoload.php';

$ppplan = new Ppplan();
$objective = new Objective;
$answers = array();

$ppplan->theHead($objective);
$answers = $ppplan->theQuestions($objective, $answers);
$list = $ppplan->theResponse($objective, $answers);
$ppplan->theSavingOptions($list);

