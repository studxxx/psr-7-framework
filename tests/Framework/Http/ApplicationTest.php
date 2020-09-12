<?php

namespace Tests\Framework\Http;

use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class ApplicationTest extends TestCase
{
    private MiddlewareResolver $resolver;
    private Router $router;

    public function setUp(): void
    {
        parent::setUp();
        $this->resolver = new MiddlewareResolver(new DummyContainer(), new Response());
        $this->router = $this->createMock(Router::class);
    }

    /**
     * @covers
     */
    public function testPipe(): void
    {
        $app = new Application($this->resolver, $this->router, new DefaultHandler(), new Response());

        $app->pipe(new MiddleWare1($this->resolver));
        $app->pipe(new MiddleWare2($this->resolver));

        /** @var ResponseInterface $response */
        $response = $app->handle(new ServerRequest());

        $this->assertJsonStringEqualsJsonString(
            json_encode(['middleware-1' => 1, 'middleware-2' => 2]),
            $response->getBody()->getContents()
        );
    }
}

class MiddleWare1 implements MiddlewareInterface
{
    private MiddlewareResolver $resolver;

    public function __construct(MiddlewareResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request->withAttribute('middleware-1', 1));
    }
}

class MiddleWare2 implements MiddlewareInterface
{
    private MiddlewareResolver $resolver;

    public function __construct(MiddlewareResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request->withAttribute('middleware-2', 2));
    }
}

class DefaultHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse($request->getAttributes());
    }
}
