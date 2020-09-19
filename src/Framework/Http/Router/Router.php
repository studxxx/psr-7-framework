<?php

declare(strict_types=1);

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Framework\Http\Router\Route\RouteData;
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
     * @param RouteData $data
     */
    public function addRoute(RouteData $data): void;
}
