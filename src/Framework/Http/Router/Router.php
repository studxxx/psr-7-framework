<?php declare(strict_types=1);

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

interface Router
{
    /**
     * @param ServerRequestInterface $request
     * @return Result
     * @throws RequestNotMatchedException
     */
    public function match(ServerRequestInterface $request): Result;

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @throws RouteNotFoundException
     */
    public function generate(string $name, array $params = []): string;

    /**
     * @param string $name
     * @param string $path
     * @param $handler
     * @param array $methods
     * @param array $options
     */
    public function addRoute(string $name, string $path, $handler, array $methods, array $options = []): void;
}
