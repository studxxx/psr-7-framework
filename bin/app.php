#!/usr/bin/env php
<?php

use App\Console\Command\ClearCacheCommand;
use Framework\Console\Input;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

echo 'Clearing cache' . PHP_EOL;

/** @var ClearCacheCommand $command */
$command = $container->get(ClearCacheCommand::class);

$input = new Input($argv);

$command->execute($input);
