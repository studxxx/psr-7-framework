<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Middleware\ErrorHandler;

use Framework\Http\Middleware\ErrorHandler\WhoopsErrorResponseGenerator;
use Psr\Container\ContainerInterface;
use Whoops\RunInterface;
use Zend\Diactoros\Response;

class WhoopsErrorResponseGeneratorFactory
{
    public function __invoke(ContainerInterface $container): WhoopsErrorResponseGenerator
    {
        return new WhoopsErrorResponseGenerator(
            $container->get(RunInterface::class),
            new Response()
        );
    }
}
