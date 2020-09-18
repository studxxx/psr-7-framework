<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Template;

use Psr\Container\ContainerInterface;
use Template\Twig\TwigRenderer;
use Twig\Environment;

class TemplateRendererFactory
{
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        return new TwigRenderer(
            $container->get(Environment::class),
            $container->get('config')['templates']['extension']
        );
    }
}
