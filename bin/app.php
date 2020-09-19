#!/usr/bin/env php
<?php

use App\Console\Command\ClearCacheCommand;
use Framework\Console;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

$commands = [
    [
        'name' => 'cache:clear',
        'command' => ClearCacheCommand::class,
        'description' => 'Clear cache',
    ],
];

$input = new Console\Input($argv);
$output = new Console\Output();

$name = $input->getArgument(0);
if (!empty($name)) {
    foreach ($commands as ['name' => $commandName, 'command' => $commandClass]) {
        if ($commandName === $name) {
            /** @var \Framework\Console\Command $command */
            $command = $container->get($commandClass);
            $command->execute($input, $output);
            exit;
        }
    }
    throw new InvalidArgumentException("Undefined command $name");
}

$output->writeln('<comment>Available commands:</comment>');
$output->writeln('');
foreach ($commands as ['name' => $command, 'description' => $description]) {
    $output->writeln("<info>$command</info>\t$description");
}
