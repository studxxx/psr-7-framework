<?php declare(strict_types=1);

namespace Infrastructure\Framework\Http\Pipeline;

use Framework\Http\Pipeline\MiddlewareResolver;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response;

class MiddlewareResolverFactory
{
    public function __invoke(ContainerInterface $container): MiddlewareResolver
    {
        return new MiddlewareResolver($container, new Response());
    }
}
