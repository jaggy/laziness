<?php

namespace Work\Console\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Basecamp\Project;
use Work\Network\Network;

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
     * Ask a yes or no question.
     *
     * @return bool
     */
    protected function confirm(InputInterface $input, OutputInterface $output, $question)
    {
        return $this
            ->getHelper('question')
            ->ask($input, $output, new ConfirmationQuestion($question, false));
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

        exit;
    }

    /**
     * Gjt the project the user wants to log hours to.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return Project
     */
    protected function promptTargetProject(InputInterface $input, OutputInterface $output)
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

    /**
     * Check where you're working. You better not work on office projects outside!
     *
     * @return bool
     */
    protected function inTheOffice()
    {
        return getenv('OFFICE_WIFI') == Network::ssid();
    }
}
