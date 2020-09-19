#!/usr/bin/env php
<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

function delete ($path) {
    if (!file_exists($path)) {
        return true;
    }

    if (!is_dir($path)) {
        return unlink($path);
    }

    foreach (scandir($path, SCANDIR_SORT_ASCENDING) as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        if (!delete($path . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($path);
}

echo 'Clearing cache' . PHP_EOL;
delete('var/cache/twig');
/** @var \Psr\Log\LoggerInterface $logger */
$logger = $container->get(\Psr\Log\LoggerInterface::class);
$logger->info('Clearing cache');
echo 'Done!' . PHP_EOL;
