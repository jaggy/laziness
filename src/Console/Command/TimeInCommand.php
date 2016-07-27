<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Exceptions\Tantrum;
use Work\Messaging\Skype;
use Work\Cache\Cache;

class TimeInCommand extends Command
{
    protected function configure()
    {
        $this->setName('time:in')
             ->setDescription('Make the office feel your presence. 〜(￣▽￣〜)   (〜￣▽￣)〜');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $greeting = $this->generateGreeting();

        if ($this->hasLogged()) {
            $output->writeln("<info>Sir! You have already timed in today, sir! (￣^￣)ゞ</info>");
            exit;
        }

        if (! $this->inTheOffice()) {
            throw Tantrum::overtime();
        }

        (new Skype)->send($greeting);

        $this->cacheTimeIn();
    }

    /**
     * Check if the user has already logged today.
     *
     * @return bool
     */
    private function hasLogged()
    {
        return Cache::has("time:in");
    }

    /**
     * Cache the greeting log for today.
     *
     * @return void
     */
    private function cacheTimeIn()
    {
        Cache::put("time:in", true);
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
