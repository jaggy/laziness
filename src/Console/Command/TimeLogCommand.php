<?php

namespace Work\Console\Command;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Question\Question;
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->isForced = $input->getOption('force');

        $project = $this->getTargetProject($input, $output);

        if (! $project) {
            return $this->exit($output);
        }

        $description = $this->getLogDescription($input, $output);

        if (! $description) {
            return $this->exit($output);
        }

        $hours = $this->getRenderedHours($input, $output, $project);

        if (! $hours) {
            return $this->exit($output);
        }

        $project->log($description, $hours);

        $output->writeln("<info>Time is not logged! You're good to go! (╯°□°）╯︵ ┻━┻</info>");
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
        if ($this->isForced && $project = $input->getOption('project')) {
            return new Project(['id' => $project]);
        }

        $projects = (new Project)->all();

        $lines = $projects->map(function ($project, $index) {
            return ($index + 1) . ': ' . $project->name;
        });

        $lines->prepend("Yow! (￣^￣)ゞ\nWhich project would you like to add an entry to?");
        $lines->push("Enter the id of the project: [Leave blank to cancel]: ");

        return $projects[
            $this->prompt($input, $output, $lines->implode("\n")) - 1
        ] ?? false;
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
        if ($this->isForced && $description = $input->getOption('description')) {
            return $description;
        }

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
