<?php

declare(strict_types=1);

namespace Template\Php\Extension;

use Framework\Http\Router\Router;
use Template\Php\Extension;
use Template\Php\SimpleFunction;

class RouteExtension extends Extension
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new SimpleFunction('path', [$this, 'generatePath'])
        ];
    }

    public function generatePath($name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}
