<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Exceptions\Tantrum;
use Work\Messaging\Skype;
use Work\Network\Network;
use Work\Cache\Cache;

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

        if ($this->hasLogged()) {
            $output->writeln("<info>Sir! You have already timed in today, sir! (￣^￣)ゞ</info>");
            exit;
        }

        if (! $this->inTheOffice()) {
            $this->throwTantrum();
        }

        (new Skype)->send($greeting);

        $this->cacheTimeIn($greeting);
    }

    /**
     * Check if the user has already logged today.
     *
     * @return bool
     */
    private function hasLogged()
    {
        $today = strtotime(date('Ymd'));

        return Cache::has("{$today}.time:in");
    }

    /**
     * Cache the greeting log for today.
     *
     * @return void
     */
    private function cacheTimeIn($greeting)
    {
        $today = strtotime(date('Ymd'));

        Cache::put("{$today}.time:in", true, $day = 1440);
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

    /**
     * Check where you're working. You better not work on office projects outside!
     *
     * @return bool
     */
    private function inTheOffice()
    {
        return getenv('OFFICE_WIFI') == Network::ssid();
    }

    /**
     * Throw a tantrum.
     *
     * @return void
     */
    public function throwTantrum()
    {
        throw Tantrum::overtime();
    }
}
