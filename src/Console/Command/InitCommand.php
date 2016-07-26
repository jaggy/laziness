<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected function configure()
    {
        $this->setName('init')
             ->setDescription('Initialize the current directory as the project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Append to .work to gitignore
        // Create a .work file
        // Fetch all the projects from basecamp
        // Append PROJECT_ID to the .work file
        // Ask if a prefix is needed for the logging [ Dev > Frontend > Vue.js ]
    }
}
