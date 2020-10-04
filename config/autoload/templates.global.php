<?php

use Infrastructure\App\Twig\MixExtensionFactory;
use Infrastructure\Framework\Template\TemplateRendererFactory;
use Infrastructure\Framework\Template\Twig\TwigEnvironmentFactory;
use Stormiix\Twig\Extension\MixExtension;
use Template\TemplateRenderer;

return [
    'dependencies' => [
        'factories' => [
            TemplateRenderer::class => TemplateRendererFactory::class,
            Twig\Environment::class => TwigEnvironmentFactory::class,
            MixExtension::class => MixExtensionFactory::class
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
            MixExtension::class,
        ],
    ],
    'mix' => [
        'root' => 'public/build',
        'manifest' => 'mix-manifest.json',
    ],
];
