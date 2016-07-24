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
            ->addOption('description', 'd', InputOption::VALUE_NONE, 'Set the description immediately. Very useful when tied to githooks.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getTargetProject($input, $output);

        dd($project);

        // $name = $input->getArgument('name');
        //
        // if ($input->getOption('yell')) {
        //     $text = strtoupper($text);
        // }
        //
        // $output->writeln($text);
    }

    /**
     * Get the project the user wants to log hours to.
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

        return $projects[
            $this->prompt($input, $output, $lines->implode("\n"))
        ];
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
}
