<?php

/** @var Container $container */

use App\Http\Action as Action;
use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Zend\Diactoros\Response;

$container->set(Application::class, function (Container $container) {
    return new Application(
        $container->get(MiddlewareResolver::class),
        $container->get(Router::class),
        new Middleware\NotFoundHandler(),
        new Response()
    );
});

$container->set(Middleware\ErrorHandlerMiddleware::class, function (Container $container) {
    return new Middleware\ErrorHandlerMiddleware($container->get('config')['debug']);
});

$container->set(Middleware\BasicAuthMiddleware::class, function (Container $container) {
    return new Middleware\BasicAuthMiddleware($container->get('config')['users'], new Response());
});

$container->set(MiddlewareResolver::class, function (Container $container) {
    return new MiddlewareResolver($container);
});

$container->set(RouteMiddleware::class, function (Container $container) {
    return new RouteMiddleware($container->get(Router::class));
});

$container->set(DispatchMiddleware::class, function (Container $container) {
    return new DispatchMiddleware($container->get(MiddlewareResolver::class));
});

$container->set(Router::class, function () {
    return new AuraRouterAdapter(new RouterContainer());
});
