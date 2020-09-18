<?php

declare(strict_types=1);

namespace Framework\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private ErrorResponseGenerator $generator;
    /** @var callable[] */
    private array $listeners = [];

    public function __construct(ErrorResponseGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            foreach ($this->listeners as $listener) {
                $listener($e, $request);
            }
            return $this->generator->generate($e, $request);
        }
    }

    public function addListener(callable $listener): void
    {
        $this->listeners[] = $listener;
    }
}
