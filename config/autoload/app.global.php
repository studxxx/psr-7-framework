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
                    $container->get(Middleware\NotFoundHandler::class),
                    new Response()
                );
            },
            Router::class => function () {
                return new AuraRouterAdapter(new RouterContainer());
            },
            MiddlewareResolver::class => function (ContainerInterface $container) {
                return new MiddlewareResolver($container, new Response());
            },
            Middleware\ErrorHandlerMiddleware::class => function (ContainerInterface $container) {
                return new Middleware\ErrorHandlerMiddleware(
                    $container->get(Middleware\ErrorHandler\ErrorResponseGenerator::class)
                );
            },
            Middleware\ErrorHandler\ErrorResponseGenerator::class => function (ContainerInterface $container) {
                if ($container->get('config')['debug']) {
                    return new Middleware\ErrorHandler\DebugErrorResponseGenerator(
                        $container->get(TemplateRenderer::class),
                        'error/error-debug'
                    );
                }
                return new Middleware\ErrorHandler\HtmlErrorResponseGenerator(
                    $container->get(TemplateRenderer::class),
                    [
                        '404' => 'error/404',
                        '403' => 'error/403',
                        'error' => 'error/error',
                    ]
                );
            },
        ],
    ],
    'debug' => false,
];
