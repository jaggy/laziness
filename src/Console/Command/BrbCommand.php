<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Exceptions\Tantrum;
use Work\Messaging\Skype;
use DateTime;

class BrbCommand extends Command
{
    protected function configure()
    {
        $this->setName('brb')
             ->setDescription('Have a kitkat');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->inTheOffice()) {
            throw Tantrum::overtime();
        }

        $start = new DateTime;

        $output->writeln("<comment>Break time!</comment>");
        (new Skype)->send('brb');

        $this->prompt($input, $output, "Press enter when you're back! ");
        (new Skype)->send('back!');

        $end = new DateTime;


        $minutes = $end->diff($start)->format('%i');

        $output->writeln("You took a break for {$minutes} minutes");
    }
}
