<?php declare(strict_types=1);

use Framework\Container\Container;

$container = new Container(require __DIR__ . '/dependencies.php');
$container->set('config', require 'config/params.php');

return $container;
