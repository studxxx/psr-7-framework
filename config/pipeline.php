<?php
/** @var \Framework\Http\Application $app */

use App\Http\Middleware as Middleware;
use Framework\Http\Middleware as FrameworkMiddleware;

$app->pipe(FrameworkMiddleware\ErrorHandler\ErrorHandlerMiddleware::class);
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe(FrameworkMiddleware\RouteMiddleware::class);
$app->pipe('cabinet', Middleware\BasicAuthMiddleware::class);
$app->pipe(FrameworkMiddleware\DispatchMiddleware::class);
