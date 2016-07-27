<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Basecamp\Project;
use Work\Exceptions\Tantrum;
use Work\Exceptions\Table;
use Work\Messaging\Skype;
use Work\Network\Network;
use Work\Cache\Cache;

class TimeOutCommand extends Command
{
    protected function configure()
    {
        $this->setName('time:out')
             ->setDescription('Goodbye~');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = new Project(['id' => getenv('PROJECT_ID')]);

        if (($hours = $project->remainingHours()) > 0) {
            throw Table::out("You have {$hours} hour/s remaining dammit! LOG IT! (╯°□°）╯︵ ┻━┻");
        }

        if ($this->hasLogged()) {
            $output->writeln("<info>You're already out! (￣﹃￣)</info>");
            exit;
        }

        if (! $this->inTheOffice()) {
            throw Tantrum::overtime();
        }

        $output->writeln("<info>Peace out! (╯°□°）╯︵ ┻━┻</info>");
        (new Skype)->send('out');

        $this->cacheTimeOut();
    }

    /**
     * Check if the user has already logged today.
     *
     * @return bool
     */
    private function hasLogged()
    {
        return Cache::has('time:out');
    }

    /**
     * Cache the farewell log for today.
     *
     * @return void
     */
    private function cacheTimeOut()
    {
        Cache::put('time:out', true);
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
}
