<?php

namespace Work\Console\Command;

use Work\Basecamp\Project;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand
{
    /**
     * Create a question handler.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @param  string  $question
     * @param  mixed  $default
     * @return mixed
     */
    protected function prompt(InputInterface $input, OutputInterface $output, $question, $default = null)
    {
        return $this
            ->getHelper('question')
            ->ask($input, $output, new Question($question, $default));
    }

    /**
     * Exit message.
     *
     * @param  OutputInterface  $output
     * @return bool
     */
    protected function exit(OutputInterface $output)
    {
        $output->writeln("<comment>Oh.. okay. I'll see you later then. (￣﹃￣)ゞ</comment>");

        return false;
    }

    /**
     * Gjt the project the user wants to log hours to.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return Project
     */
    protected function getTargetProject(InputInterface $input, OutputInterface $output)
    {
        $projects = (new Project)->all();

        $lines = $projects->map(function ($project, $index) {
            return ($index + 1) . ': ' . $project->name;
        });

        $lines->push("Enter the id of the project: [Leave blank to cancel]: ");

        return $projects[
            $this->prompt($input, $output, $lines->implode("\n")) - 1
        ] ?? false;
    }
}
