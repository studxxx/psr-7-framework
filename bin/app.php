#!/usr/bin/env php
<?php

use Framework\Http\Application;
use Template\TemplateRenderer;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

$app = $container->get(Application::class);

require 'config/pipeline.php';
require 'config/routes.php';

$renderer = $container->get(TemplateRenderer::class);
$html = $renderer->render('app/hello');
echo $html.PHP_EOL;