#!/usr/bin/env php
<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

echo 'Clearing cache' . PHP_EOL;

$command = $container->get(\App\Console\Command\ClearCacheCommand::class);

$command->execute();
