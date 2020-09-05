<?php

use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Psr\Container\ContainerInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'dependencies' => [
        'abstract_factories' => [
            ReflectionBasedAbstractFactory::class,
        ],
        'factories' => [
            Application::class => function (ContainerInterface $container) {
                return new Application(
                    $container->get(MiddlewareResolver::class),
                    $container->get(Router::class),
                    new Middleware\NotFoundHandler($container->get(TemplateRenderer::class)),
                    new Response()
                );
            },
            Router::class => function () {
                return new AuraRouterAdapter(new RouterContainer());
            },
            MiddlewareResolver::class => function (ContainerInterface $container) {
                return new MiddlewareResolver($container);
            },
            Middleware\ErrorHandlerMiddleware::class => function (ContainerInterface $container) {
                return new Middleware\ErrorHandlerMiddleware(
                    $container->get('config')['debug'],
                    $container->get(TemplateRenderer::class)
                );
            },

        ],
    ],
    'debug' => false,
];
