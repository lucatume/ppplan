#!/usr/bin/php
<?php

namespace PPPlan;

use Symfony\Component\Console\Application;

include 'vendor/autoload.php';

$application = new Application();
$application->add(new NewCommand());
$application->add(new ReviewCommand());
$application->run();
