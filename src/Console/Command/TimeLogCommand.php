<?php

namespace Work\Console\Command;

use Work\Basecamp\Project;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TimeLogCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('time:log')
            ->setDescription('Track the time for basecamp.')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption('project', 'p', InputOption::VALUE_REQUIRED, 'The id of the project that will be log on to.')
            ->addOption('description', 'd', InputOption::VALUE_REQUIRED, 'The description of the task that will be logged.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getTargetProject($input, $output);

        if (! $project) {
            return $this->exit($output);
        }

        $description = $this->getLogDescription($input, $output);

        if (! $description) {
            return $this->exit($output);
        }

        // $output->writeln($text);
    }

    /**
     * Gjt the project the user wants to log hours to.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return Project
     */
    private function getTargetProject(InputInterface $input, OutputInterface $output)
    {
        $projects = (new Project)->all();

        $lines = $projects->map(function ($project, $index) {
            return ($index + 1) . ': ' . $project->name;
        });

        $lines->prepend("Yow! Which project would you like to add an entry to?");
        $lines->push("Enter the id of the project: [Leeave blank to cancel]: ");


        return $projects[$this->prompt($input, $output, $lines->implode("\n"))] ?? false;
    }

    /**
     * Set the description of the time entry.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     */
    private function getLogDescription(InputInterface $input, OutputInterface $output)
    {
        $description = $input->getOption('description');

        return $this->prompt($input, $output, "Log description [{$description}]: ", $description);
    }

    /**
     * Create a question handler.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @param  string  $question
     * @param  mixed  $default
     * @return mixed
     */
    private function prompt(InputInterface $input, OutputInterface $output, $question, $default = null)
    {
        $helper = $this->getHelper('question');

        $question = new Question($question);

        return $this->getHelper('question')->ask($input, $output, $question);
    }

    /**
     * Exit message.
     *
     * @param  OutputInterface  $output
     * @return bool
     */
    private function exit(OutputInterface $output)
    {
        $output->writeln('<comment>Goodbye!</comment>');

        return false;
    }
}
