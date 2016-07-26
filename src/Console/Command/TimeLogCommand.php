<?php

namespace Work\Console\Command;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Work\Basecamp\Project;
use Work\Basecamp\TimeEntry;

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
            ->addOption('hours', null, InputOption::VALUE_REQUIRED, 'The amount of hours rendered on the given task.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Remove the interaction for preset data')
        ;
    }

    /** @todo  Clean up all these damned ifs **/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->isForced = $input->getOption('force');

        $output->writeln('Yow! (￣^￣)ゞ');
        $output->writeln('Which project would you like to add an entry to?');

        if (! $project = $this->getForcedOption($input, 'project')) {
            $project = $this->getTargetProject($input, $output);
        }

        if (! $project instanceof Project) {
            $project = new Project(['id' => $project]);
        }

        if (! $project) {
            return $this->exit($output);
        }

        if (! $description = $this->getForcedOption($input, 'description')) {
            $description = $this->getLogDescription($input, $output);
        }

        if (! $description) {
            return $this->exit($output);
        }

        if (! $hours = $this->getForcedOption($input, 'hours')) {
            $hours = $this->getRenderedHours($input, $output);
        }

        if (! $hours) {
            return $this->exit($output);
        }

        $project->log($description, $hours);

        $output->writeln("<info>Time is not logged! You're good to go! (╯°□°）╯︵ ┻━┻</info>");
    }

    /**
     * Set the description of the time entry.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return string
     */
    private function getLogDescription(InputInterface $input, OutputInterface $output)
    {
        return $this->prompt($input, $output, "Log description [{$description}]: ", $description);
    }

    /**
     * Get the rendered hours for the given task.
     *
     * @todo  The current limitation for this one is that the rendered hours ,
     *        for the selected project is calculated, not all throughout
     *        the basecamp api.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @param  Project  $project
     * @return float
     */
    private function getRenderedHours(InputInterface $input, OutputInterface $output, Project $project)
    {
        if ($this->isForced && $hours= $input->getOption('hours')) {
            return $hours;
        }

        $remaining = $this->calculateRemainingHours($project->entries());

        return $this->prompt(
            $input,
            $output,
            "How many hours did this task take? [<comment>{$remaining} hours remaining</comment>]]: ",
            false
        );
    }

    /**
     * Fetch the forced value if it exists.
     *
     * @param  InputInterface  $input
     * @param  string  $option
     * @return mixed
     */
    private function getForcedOption(InputInterface $input, $option)
    {
        if ($this->isForced && $option = $input->getOption($option)) {
            return $option;
        }

        return null;
    }

    /**
     * Calculate the remaining renderable hours.
     *
     * @param  Collection  $entries
     * @return float
     */
    private function calculateRemainingHours(Collection $entries)
    {
        return TimeEntry::RENDERABLE_HOURS - $entries->pluck('hours')->sum();
    }
}
