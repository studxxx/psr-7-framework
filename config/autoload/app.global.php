<?php

use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Psr\Container\ContainerInterface;
use Template\TemplateRenderer;
use Template\Twig\Extension\RouteExtension;
use Template\Twig\TwigRenderer;
use Twig\Loader\FilesystemLoader;
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
            TemplateRenderer::class => function (ContainerInterface $container) {
                return new TwigRenderer($container->get(Twig\Environment::class), '.html.twig');
            },
            Twig\Environment::class => function (ContainerInterface $container) {

                $templateDir = 'templates';
                $cacheDir = 'var/cache/twig';
                $debug = $container->get('config')['debug'];

                $loader = new FilesystemLoader();
                $loader->addPath($templateDir);

                $twig = new Twig\Environment($loader, [
                    'cache' => $debug ? false : $cacheDir,
                    'debug' => $debug,
                    'auto_reload' => $debug,
                    'strict_variables' => $debug,
                ]);

                if ($debug) {
                    $twig->addExtension(new Twig\Extension\DebugExtension());
                }
                $twig->addExtension($container->get(RouteExtension::class));
                return $twig;
            },
        ],
    ],
    'debug' => false,
];
