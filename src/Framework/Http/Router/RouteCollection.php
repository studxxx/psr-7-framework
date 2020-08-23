<?php declare(strict_types=1);

namespace Framework\Http\Router;

class RouteCollection
{
    private array $routes = [];

    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function add($name, $pattern, $handler, array $methods, array $tokens = []): void
    {
        $this->addRoute(new Route($name, $pattern, $handler, $methods, $tokens));
    }

    public function any(string $name, string $pattern, $handler, array $tokens = []): void
    {
        $this->addRoute(new Route($name, $pattern, $handler, [], $tokens));
    }

    public function get(string $name, string $pattern, $handler, array $tokens = []): void
    {
        $this->addRoute(new Route($name, $pattern, $handler, ['GET'], $tokens));
    }

    public function post(string $name, string $pattern, $handler, array $tokens = []): void
    {
        $this->addRoute(new Route($name, $pattern, $handler, ['POST'], $tokens));
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
