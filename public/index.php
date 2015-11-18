<?php

date_default_timezone_set('Europe/Amsterdam'); // TODO: Moet in php.ini

require __DIR__ . '/../vendor/autoload.php';

$app = new Ozag\Skeleton\Application\Application();

$app->bootstrap();
