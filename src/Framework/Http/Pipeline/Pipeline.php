<?php declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Pipeline
{
    private array $middleware = [];

    public function pipe(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function __invoke(ServerRequestInterface $request, callable $default): ResponseInterface
    {
        return $this->next($request, $default);
    }

    private function next(ServerRequestInterface $request, callable $default): ResponseInterface
    {
        if (!$current = array_shift($this->middleware)) {
            return $default($request);
        }

        return $current($request, function (ServerRequestInterface $request) use ($default) {
            return $this->next($request, $default);
        });
    }
}
