#!/usr/bin/env php
<?php

use App\Console\Command\ClearCacheCommand;
use Framework\Console;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

$cli = new Console\Application();
$cli->add($container->get(ClearCacheCommand::class));
$cli->run(new Console\Input($argv), new Console\Output());
