<?php declare(strict_types=1);

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Result;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DispatchMiddleware
{
    private MiddlewareResolver $resolver;

    public function __construct(MiddlewareResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        /** @var Result $result */
        if (!$result = $request->getAttribute(Result::class)) {
            return $next($request);
        }
        $middleware = $this->resolver->resolve($result->getHandler());
        return $middleware($request, $next);
    }
}
