<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Filesystem\Filesystem;

(new Dotenv(__DIR__))->load();

Filesystem::macro('touch', function ($filename) {
    $this->append($filename, '');
});
