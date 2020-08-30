<?php declare(strict_types=1);

use Framework\Container\Container;

$container = new Container();
$container->set('config', require 'config/params.php');
require 'config/dependencies.php';

return $container;
