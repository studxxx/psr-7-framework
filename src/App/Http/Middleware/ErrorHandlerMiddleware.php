<?php declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Middleware\ErrorHandler\ErrorResponseGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private ErrorResponseGenerator $generator;

    public function __construct(ErrorResponseGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            return $this->generator->generate($e, $request);
        }
    }
}
