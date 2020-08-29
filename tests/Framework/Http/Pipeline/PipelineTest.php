<?php

namespace Tests\Framework\Http\Pipeline;

use Framework\Http\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class PipelineTest extends TestCase
{
    /**
     * @covers
     */
    public function testPipe(): void
    {
        $pipeline = new Pipeline();

        $pipeline->pipe(new MiddleWare1());
        $pipeline->pipe(new MiddleWare2());

        /** @var ResponseInterface $response */
        $response = $pipeline(new ServerRequest(), new Last());

        $this->assertJsonStringEqualsJsonString(
            json_encode(['middleware-1' => 1, 'middleware-2' => 2]),
            $response->getBody()->getContents()
        );
    }
}

class MiddleWare1
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-1', 1));
    }
}

class MiddleWare2
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-2', 2));
    }
}

class Last
{
    public function __invoke(ServerRequestInterface $request)
    {
        return new JsonResponse($request->getAttributes());
    }
}
