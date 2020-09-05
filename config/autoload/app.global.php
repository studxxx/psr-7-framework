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
                return new TwigRenderer(
                    $container->get(Twig\Environment::class),
                    $container->get('config')['templates']['extension']
                );
            },
            Twig\Environment::class => function (ContainerInterface $container) {

                $debug = $container->get('config')['debug'];

                $loader = new FilesystemLoader();
                $loader->addPath($container->get('config')['twig']['template_dir']);

                $twig = new Twig\Environment($loader, [
                    'cache' => $debug ? false : $container->get('config')['twig']['cache_dir'],
                    'debug' => $debug,
                    'auto_reload' => $debug,
                    'strict_variables' => $debug,
                ]);

                if ($debug) {
                    $twig->addExtension(new Twig\Extension\DebugExtension());
                }
                $twig->addExtension($container->get(RouteExtension::class));

                foreach ($container->get('config')['twig']['extensions'] as $extension) {
                    $twig->addExtension($container->get($extension));
                }
                return $twig;
            },
        ],
    ],
    'debug' => false,
    'templates' => [
        'extension' => '.html.twig'
    ],
    'twig' => [
        'template_dir' => 'templates',
        'cache_dir' => 'var/cache/twig',
        'extensions' => [
            // Put extension here like RouteExtension::class,
        ],
    ]
];
