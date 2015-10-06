<?php

namespace PPPlan;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    protected $isReviewing = false;

    protected function configure()
    {
        $this
            ->setName('new')
            ->setDescription('Plan a new project')
            ->addOption('clear', 'cl', InputOption::VALUE_OPTIONAL, 'Clear the terminal on each question for super-focus', false)
            ->addArgument('format', InputArgument::OPTIONAL, 'The output file format', 'txt')
            ->addArgument('dayDuration', InputArgument::OPTIONAL, 'How many working hours in a day?', '8')
            ->addArgument('weekDuration', InputArgument::OPTIONAL, 'How many working days in a week?', 5)
            ->addArgument('pomodoroDuration', InputArgument::OPTIONAL, 'How many minutes in a pomodoro sprint?', 25)
            ->addArgument('outputUnit', InputArgument::OPTIONAL, 'The plan output format', 'hour')
            ->addArgument('estimateTag', InputArgument::OPTIONAL, 'The tag name for the estimation tag used by Taskpaper', 'est');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $objective = new Objective();
        $tasks = array();

        $hourReader = new HourReader($input);
        $format = $input->getArgument('format');

        // setup the unit to use for the output
        $outputUnit = $input->getArgument('outputUnit');
        $outputUnit = Units::createOutputUnitFrom($outputUnit);
        $listFormatter = new ListFormatter($format, $outputUnit, $input);
        $ppplan = new PPPlan($hourReader, $listFormatter, $this->isReviewing, $input, $output);

        $ppplan->theHead($objective);
        $tasks = $ppplan->theQuestions($objective, $tasks, $this->isReviewing);
        $list = $ppplan->theResponse($objective, $tasks);
        $ppplan->theSavingOptions($objective, $tasks);
    }

}