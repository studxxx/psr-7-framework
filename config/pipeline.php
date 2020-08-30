<?php
/** @var \Framework\Http\Application $app */
/** @var \Framework\Container\Container $container */

use App\Http\Middleware as Middleware;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;

$app->pipe($container->get(Middleware\ErrorHandlerMiddleware::class));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe($container->get(RouteMiddleware::class));
$app->pipe('cabinet', $container->get(Middleware\BasicAuthMiddleware::class));
$app->pipe($container->get(DispatchMiddleware::class));
