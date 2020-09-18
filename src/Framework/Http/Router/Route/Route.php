<?php

declare(strict_types=1);

namespace Framework\Http\Router\Route;

use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;

interface Route
{
    public function match(ServerRequestInterface $request): ?Result;

    public function generate(string $name, array $params = []): ?string;
}
