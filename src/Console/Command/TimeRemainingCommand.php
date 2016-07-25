<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TimeRemainingCommand extends Command
{
    protected function configure()
    {
        $this->setName('time:remaining')
             ->setDescription('Check how much hours are still needed to be filled.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
