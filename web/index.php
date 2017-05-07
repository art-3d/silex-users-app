<?php

require_once __DIR__.'/../vendor/autoload.php';

$config = require __DIR__.'/../config/prod.php';

$app = new App\Application($config);
$app->run();
