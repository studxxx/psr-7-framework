<?php

declare(strict_types=1);

namespace Template\Twig\Extension;

use Framework\Http\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'generatePath']),
        ];
    }

    public function generatePath($name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}
