#!/usr/bin/env php
<?php

use App\Console\Command\ClearCacheCommand;
use Framework\Console;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Framework\Console\Command[] $commands */
$commands = [
    $container->get(ClearCacheCommand::class),
];

$input = new Console\Input($argv);
$output = new Console\Output();
$name = $input->getArgument(0);

if (!empty($name)) {
    foreach ($commands as $command) {
        if ($command->getName() === $name) {
            $command->execute($input, $output);
            exit;
        }
    }
    throw new InvalidArgumentException("Undefined command $name");
}

$output->writeln('<comment>Available commands:</comment>');
$output->writeln('');
foreach ($commands as $command) {
    $output->writeln("<info>{$command->getName()}</info>\t{$command->getDescription()}");
}
