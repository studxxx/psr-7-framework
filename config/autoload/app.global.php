<?php

use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Middleware\ErrorHandler\ErrorHandlerMiddleware;
use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator;
use Framework\Http\Middleware\ErrorHandler\WhoopsErrorResponseGenerator;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Infrastructure\Framework\Http\Middleware\ErrorHandler\HtmlErrorResponseGenerator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Template\TemplateRenderer;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\RunInterface;
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
            ErrorHandlerMiddleware::class => function (ContainerInterface $container) {
                return new ErrorHandlerMiddleware(
                    $container->get(ErrorResponseGenerator::class),
                    $container->get(LoggerInterface::class)
                );
            },
            ErrorResponseGenerator::class => function (ContainerInterface $container) {
                if ($container->get('config')['debug']) {
                    return new WhoopsErrorResponseGenerator(
                        $container->get(RunInterface::class),
                        new Response()
                    );
                }
                return new HtmlErrorResponseGenerator(
                    $container->get(TemplateRenderer::class),
                    new Response(),
                    [
                        '404' => 'error/404',
                        '403' => 'error/403',
                        'error' => 'error/error',
                    ]
                );
            },
            RunInterface::class => function () {
                $whoops = new Run();
                $whoops->writeToOutput(false);
                $whoops->allowQuit(false);
                $whoops->pushHandler(new PrettyPageHandler());
                $whoops->register();
                return $whoops;
            },

            LoggerInterface::class => function (ContainerInterface $container) {
                $logger = new Logger('App');
                $logger->pushHandler(new \Monolog\Handler\StreamHandler(
                    'var/log/application.log',
                    $container->get('config')['debug'] ? Logger::DEBUG : Logger::WARNING
                ));
                return $logger;
            }
        ],
    ],
    'debug' => false,
];
