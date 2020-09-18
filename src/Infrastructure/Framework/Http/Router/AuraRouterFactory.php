<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Router;

use Aura\Router\RouterContainer;
use Framework\Http\Router\AuraRouterAdapter;

class AuraRouterFactory
{
    public function __invoke()
    {
        return new AuraRouterAdapter(new RouterContainer());
    }
}
