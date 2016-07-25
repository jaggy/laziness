<?php

namespace Work\Console\Command;

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
}
