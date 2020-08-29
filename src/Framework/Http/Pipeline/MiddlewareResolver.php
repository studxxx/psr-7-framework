<?php declare(strict_types=1);

namespace Framework\Http\Pipeline;

class MiddlewareResolver
{
    public function resolve($handler): callable
    {
        if (\is_string($handler)) {
            return function ($request, callable $next) use ($handler) {
                $object = new $handler();
                return $object($request, $next);
            };
        }
        return $handler;
    }
}
