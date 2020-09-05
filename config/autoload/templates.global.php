<?php

use Psr\Container\ContainerInterface;
use Template\TemplateRenderer;
use Template\Twig\Extension\RouteExtension;
use Template\Twig\TwigRenderer;
use Twig\Loader\FilesystemLoader;

return [
    'dependencies' => [
        'factories' => [
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
    'templates' => [
        'extension' => '.html.twig'
    ],
    'twig' => [
        'template_dir' => 'templates',
        'cache_dir' => 'var/cache/twig',
        'extensions' => [
            // Put extension here like RouteExtension::class,
        ],
    ],
];
