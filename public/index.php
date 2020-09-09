<?php declare(strict_types=1);

use Framework\Http\Application;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$container = require 'config/container.php';

/** @var Application $app */
$app = $container->get(Application::class);

require 'config/pipeline.php';
require 'config/routes.php';

$request = ServerRequestFactory::fromGlobals();
$response = $app->handle($request, new Response());

$emitter = new SapiEmitter();
$emitter->emit($response);
