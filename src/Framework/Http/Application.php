<?php declare(strict_types=1);

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

class Application extends MiddlewarePipe
{
    /** @var callable */
    private $default;
    private MiddlewareResolver $resolver;

    public function __construct(MiddlewareResolver $resolver, callable $default, ResponseInterface $response)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->default = $default;
        $this->setResponsePrototype($response);
    }

    public function pipe($path, $middleware = null): MiddlewarePipe
    {
        if ($middleware === null) {
            return parent::pipe($this->resolver->resolve($path, $this->responsePrototype));
        }
        parent::pipe($path, $this->resolver->resolve($middleware, $this->responsePrototype));
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this($request, $response, $this->default);
    }
}
