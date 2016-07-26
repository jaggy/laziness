<?php

namespace Work\Console\Command;

use Work\Basecamp\Project;
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
        $project = new Project(['id' => getenv('PROJECT_ID')]);

        $output->writeln('<comment>Calculating your remaining hours.. [Internet speed dependent.. sorry]</comment>');
        $remaining = $project->remainingHours();


        $output->writeln("<info>You have {$remaining} hour/s left! ヽ(ﾟ〇ﾟ)ﾉ</info>");
    }
}
