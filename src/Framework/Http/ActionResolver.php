<?php declare(strict_types=1);

namespace Framework\Http;

class ActionResolver
{
    public function resolve($handler): callable
    {
        return \is_string($handler) ? new $handler() : $handler;
    }
}
