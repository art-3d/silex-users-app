<?php

return array(
    'twig.options' => array('cache' => __DIR__.'/../var/cache/twig'),

    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'silex',
        'user' => 'root',
        'password' => 'root',
    )
);
