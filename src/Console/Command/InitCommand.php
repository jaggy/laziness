<?php

namespace Work\Console\Command;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Config\Config;

class InitCommand extends Command
{
    protected function configure()
    {
        $this->setName('init')
             ->setDescription('Initialize the current directory as the project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->newConfig();

        // Append to .work to gitignore
        $config->addToVcsIgnores();
        $this->print($output, 'Adding .work to .gitignore.');

        // Create a .work file
        $config->createProjectConfig();
        $this->print($output, 'Creating project configuration.');

        // Fetch all the projects from basecamp
        // Append PROJECT_ID to the .work file
        $project = $this->getTargetProject($input, $output);
        $config->registerDefaultProject($project);

        // Ask if a prefix is needed for the logging [ Dev > Frontend > Vue.js ]
        $prefix = $this->prompt($input, $output, 'Add your log prefix [Leave blank for none]: ');
        $config->registerLoggingPrefix($prefix);

        $this->print($output, "You're all set! ヽ(ﾟ〇ﾟ)ﾉ");
    }

    /**
     * Create a new config handler.
     *
     * @return Config
     */
    private function newConfig()
    {
        return new Config(new Filesystem);
    }

    /**
     * Print the line with colorization.
     *
     * @param  OutputInterface  $output
     * @param  string  $message
     * @return void
     */
    private function print(OutputInterface $output, $message)
    {
        $output->writeln("<comment>{$message}</comment>");
    }
}
