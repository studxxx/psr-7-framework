<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Middleware\ErrorHandler;

use Psr\Container\ContainerInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response;

class HtmlErrorResponseGeneratorFactory
{
    public function __invoke(ContainerInterface $container): HtmlErrorResponseGenerator
    {
        return new HtmlErrorResponseGenerator(
            $container->get(TemplateRenderer::class),
            new Response(),
            [
                '404' => 'error/404',
                '403' => 'error/403',
                'error' => 'error/error',
            ]
        );
    }
}
