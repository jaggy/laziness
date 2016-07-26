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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->isForced = $input->getOption('force');

        $output->writeln('Yow! (￣^￣)ゞ');
        $output->writeln('Which project would you like to add an entry to? [It might take a while to fetch the project list]');

        $project     = $this->getTargetProject($input, $output);
        $description = $this->getLogDescription($input, $output);
        $hours       = $this->getRenderedHours($input, $output, $project);

        // Confirm if user is sure about the data (when it's not forced)
        if (! $this->isForced && ! $this->confirmSubmission($input, $output, $project, $description, $hours)) {
            $this->exit($output);
        }

        $output->writeln('Sending the data to basecamp...');
        $project->log($description, $hours);

        $output->writeln("<info>Time is now logged! You're good to go! (╯°□°）╯︵ ┻━┻</info>");
    }

    /**
     * Confirm the submission of the data.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @param  Project  $project
     * @param  string  $description
     * @param  float  $hours
     * @return bool
     */
    private function confirmSubmission(InputInterface $input, OutputInterface $output, Project $project, $description, $hours)
    {
        $lines = [
            "Project ID: {$project->id}",
            "Log Description: {$description}",
            "Rendered Hours: {$hours}",
            "Is the provided data correct? [n]",
        ];

        $question = '<comment>' . implode("\n", $lines) . '</comment>';

        return $this->confirm($input, $output, $question);
    }

    /**
     * Get the project.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return Project
     */
    public function getTargetProject(InputInterface $input, OutputInterface $output)
    {
        if ($this->isForced && $project = $input->getOption('project')) {
            return new Project(['id' => $project]);
        }

        if ($this->isForced && $project = getenv('PROJECT_ID')) {
            $output->writeln("<comment>Using project id #{$project}!</comment>");

            return new Project(['id' => $project]);
        }

        $project = $this->promptTargetProject($input, $output);

        if (! $project) {
            $this->exit($output);
        }

        return $project;
    }

    /**
     * Get the description for the log.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return string
     */
    public function getLogDescription(InputInterface $input, OutputInterface $output)
    {
        if ($prefix = getenv('LOG_PREFIX')) {
            $prefix .= ' ';
        }

        if ($this->isForced && $description = $input->getOption('description')) {
            $output->writeln("<comment>Using the description '{$prefix}{$description}'</comment>");

            return $prefix . $description;
        }

        $description = $this->promptLogDescription($input, $output);

        if (! $description) {
            $this->exit($output);
        }

        return $prefix . $description;
    }

    /**
     * Get the rendered hours.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @param  Project  $project
     * @return float
     */
    public function getRenderedHours(InputInterface $input, OutputInterface $output, Project $project)
    {
        $remaining = $this->calculateRemainingHours($project->entries());

        if ($this->isForced && $hours = $input->getOption('hours')) {
            return $hours;
        }

        $hours = (float) $this->promptRenderedHours($input, $output, $remaining);

        if (! $hours) {
            $this->exit($output);
        }

        if ($hours > $remaining) {
            $output->writeln('<error>Rendered hours input is not valid.</error>');

            $this->exit($output);
        }

        return $hours;
    }

    /**
     * Set the description of the time entry.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return string
     */
    private function promptLogDescription(InputInterface $input, OutputInterface $output)
    {
        if ($prefix = getenv('LOG_PREFIX')) {
            $prefix .= ' ';
        }

        $description = $prefix . $input->getOption('description');

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
     * @param  float  $remainingHours
     * @return float
     */
    private function promptRenderedHours(InputInterface $input, OutputInterface $output, $remainingHours)
    {
        return $this->prompt(
            $input,
            $output,
            "How many hours did this task take? [<comment>{$remainingHours} hours remaining</comment>]]: ",
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
