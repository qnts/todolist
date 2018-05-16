<?php

require __DIR__.'/../vendor/autoload.php';

$app = new App\Core\Application(realpath(__DIR__ . '/../'));

$app->run();
