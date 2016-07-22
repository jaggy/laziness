<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TimeLogCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('time:log')
            ->setDescription('Track the time for basecamp.')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption('description', 'd', InputOption::VALUE_NONE, 'Set the description immediately. Very useful when tied to githooks.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        dd((new \Work\Basecamp\Person)->me());

        // $name = $input->getArgument('name');
        //
        // if ($input->getOption('yell')) {
        //     $text = strtoupper($text);
        // }
        //
        // $output->writeln($text);
    }
}
