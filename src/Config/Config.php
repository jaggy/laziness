<?php

namespace Work\Config;

use Illuminate\Filesystem\Filesystem;
use Work\Basecamp\Project;

class Config
{
    const FILENAME = '.work';

    /**
     * Create a new config.
     *
     * @param  Filesystem  $filesystem
     * @return Config
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Add the config file to the gitignore.
     *
     * @return void
     */
    public function addToVcsIgnores()
    {
        $this->filesystem->append('.gitignore', static::FILENAME . "\n");
    }

    /**
     * Create the project config file.
     *
     * @return void
     */
    public function createProjectConfig()
    {
        $this->filesystem->touch(static::FILENAME); // macro @ bootstrap.php
    }

    /**
     * Select the project and write the project id to the config file.
     *
     * @param  Project  $project
     * @return void
     */
    public function registerDefaultProject(Project $project)
    {
        $this->filesystem->append(
            static::FILENAME,
            "PROJECT_ID={$project->id}\n"
        );
    }

    /**
     * Register the prefix for your logging needs.
     *
     * @param  string  $prefix
     * @return void
     */
    public function registerLoggingPrefix($prefix)
    {
        $this->filesystem->append(
            static::FILENAME,
            "LOG_PREFIX=\"{$prefix}\"\n"
        );
    }

    /**
     * Register the post-commit hook.
     *
     * @return void
     */
    public function registerPostCommitHook()
    {
        $hooks = getcwd() . '/.git/hooks';

        if (! $this->filesystem->exists($hooks)) {
            $this->filesystem->makeDirectory($hooks);
        }

        $this->filesystem->copy(
            __DIR__ . '/../../githooks/post-commit',
            $postCommitHook = '.git/hooks/post-commit'
        );

        chmod($postCommitHook, 0755); // I know, I hate this too.
    }
}
