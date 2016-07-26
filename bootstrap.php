<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require __DIR__ . '/../../../vendor/autoload.php';
}


use Dotenv\Dotenv;
use Illuminate\Filesystem\Filesystem;

(new Dotenv(getenv('HOME'), '.workrc'))->load();

Filesystem::macro('touch', function ($filename) {
    $this->append($filename, '');
});

if ((new Filesystem)->exists('.work')) {
    (new Dotenv(getcwd(), '.work'))->load();
}

