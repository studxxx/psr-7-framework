#!/usr/bin/env php
<?php

use App\Console\Command\ClearCacheCommand;
use Framework\Console;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var ClearCacheCommand $command */
$command = $container->get(ClearCacheCommand::class);

$command->execute(new Console\Input($argv), new Console\Output());
