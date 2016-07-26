<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Messaging\Skype;

class TimeInCommand extends Command
{
    protected function configure()
    {
        $this->setName('time:in')
             ->setDescription('Message the office feel your presence. 〜(￣▽￣〜)   (〜￣▽￣)〜');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $greeting = $this->generateGreeting();

        (new Skype)->send($greeting);
    }

    /**
     * Generate the greeting for everyone!
     *
     * @return string
     */
    private function generateGreeting()
    {
        $currentHour = (int) date('H');

        if ($currentHour < 12) {
            return array_random(['gam', "Mornin'", 'Good morning', 'Morning. :)', 'Morning! :D']);
        }

        return array_random(['gpm', "Afternoon!", 'Good afternoon.', 'Afternoon~', 'Good afternoon. :D']);
    }
}
