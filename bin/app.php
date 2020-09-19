#!/usr/bin/env php
<?php

use Symfony\Component\Console;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

$cli = new Console\Application('Application console');

$commands = $container->get('config')['console']['commands'];

foreach ($commands as $command) {
    $cli->add($container->get($command));
}
$cli->run();
