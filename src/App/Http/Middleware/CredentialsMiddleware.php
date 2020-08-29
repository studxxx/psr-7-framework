<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CredentialsMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $response = $next($request);
        return $response->withHeader('X-Developer', 'studxxx');
    }
}
