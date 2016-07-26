<?php

namespace Work\Console\Command;

use DateTime;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Basecamp\Project;

class ScrumCommand extends Command
{
    protected function configure()
    {
        $this->setName('scrum')
             ->setDescription('Enter a scrum session.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $session = $this->scrumSession($input, $output);

        $output->writeln('Scrum ended after ' . $session->format('%i minutes and %s seconds'));

        $output->writeln('Sending SCRUM log to basecamp...');

        $project = new Project(['id' => getenv('PROJECT_ID')]);

        $basecampHours = $session->format('%i') / $minutes = 60;

        $project->log('SCRUM', $basecampHours);
    }

    /**
     * Start the scrum watcher.
     *
     * @return void
     */
    private function scrumSession(InputInterface $input, OutputInterface $output)
    {
        $start = new DateTime;

        $this->prompt($input, $output, "Press enter to finish SCRUM.");

        $end = new DateTime;

        return $end->diff($start);
    }
}
