<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\Exception;

use function class_exists;

final class SinglePassMiddlewareDecorator implements MiddlewareInterface
{
    /** @var callable */
    private $middleware;

    public function __construct(callable $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * {@inheritDoc}
     * @throws Exception\MissingResponseException if the decorated middleware
     *     fails to produce a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = ($this->middleware)(
            $request,
            $this->decorateHandler($handler)
        );

        if (! $response instanceof ResponseInterface) {
            throw Exception\MissingResponseException::forCallableMiddleware($this->middleware);
        }

        return $response;
    }

    private function decorateHandler(RequestHandlerInterface $handler): callable
    {
        return function ($request) use ($handler) {
            return $handler->handle($request);
        };
    }
}
