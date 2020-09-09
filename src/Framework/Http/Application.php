<?php declare(strict_types=1);

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Route\RouteData;
use Framework\Http\Router\Router;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

class Application implements MiddlewareInterface, RequestHandlerInterface
{
    /** @var callable */
    private $default;
    private MiddlewareResolver $resolver;
    private Router $router;
    private MiddlewarePipe $pipeline;
    private ResponseInterface $responsePrototype;

    public function __construct(
        MiddlewareResolver $resolver,
        Router $router,
        callable $default,
        ResponseInterface $responsePrototype
    ) {
        $this->resolver = $resolver;
        $this->default = $default;

        $this->pipeline = new MiddlewarePipe();
        $this->pipeline->setResponsePrototype($responsePrototype);

        $this->router = $router;
        $this->responsePrototype = $responsePrototype;
    }

    public function pipe($path, $middleware = null): MiddlewarePipe
    {
        if ($middleware === null) {
            return $this->pipeline->pipe($this->resolver->resolve($path, $this->responsePrototype));
        }
        return $this->pipeline->pipe($path, $this->resolver->resolve($middleware, $this->responsePrototype));
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        return ($this->pipeline)($request, $response, $next);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->process($request, $handler);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this($request, $this->responsePrototype, $this->default);
    }

    public function route($name, $path, $handler, array $methods, array $options = []): void
    {
        $this->router->addRoute(new RouteData($name, $path, $handler, $methods, $options));
    }

    public function any($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, [], $options);
    }

    public function get($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['GET'], $options);
    }

    public function post($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['POST'], $options);
    }
}
