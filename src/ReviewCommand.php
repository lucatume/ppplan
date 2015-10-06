<?php

namespace PPPlan;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ReviewCommand extends NewCommand
{
    protected $isReviewing = true;

    protected function configure()
    {
        $this->setName('review')
            ->setDescription('Review a project plan')
            ->addOption('clear', 'cl', InputOption::VALUE_OPTIONAL, 'Clear the terminal on each question for super-focus', false)
            ->addArgument('file', InputArgument::REQUIRED, 'The file plan to review')
            ->addArgument('format', InputArgument::OPTIONAL, 'The output file format', 'txt')
            ->addArgument('dayDuration', InputArgument::OPTIONAL, 'How many working hours in a day?', '8')
            ->addArgument('weekDuration', InputArgument::OPTIONAL, 'How many working days in a week?', 5)
            ->addArgument('pomodoroDuration', InputArgument::OPTIONAL, 'How many minutes in a pomodoro sprint?', 25)
            ->addArgument('outputUnit', InputArgument::OPTIONAL, 'The plan output format', 'hour')
            ->addArgument('estimateTag', InputArgument::OPTIONAL, 'The tag name for the estimation tag used by Taskpaper', 'est');
    }
}