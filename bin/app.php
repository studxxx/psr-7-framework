#!/usr/bin/env php
<?php

use Framework\Console;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

$cli = new Console\Application();

$commands = $container->get('config')['console']['commands'];

foreach ($commands as $command) {
    $cli->add($container->get($command));
}
$cli->run(new Console\Input($argv), new Console\Output());
