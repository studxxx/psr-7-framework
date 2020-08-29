<?php declare(strict_types=1);

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\ShowAction;
use App\Http\Middleware\NotFoundHandler;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class ShowActionTest extends TestCase
{
    /**
     * @covers
     */
    public function testSuccess(): void
    {
        $action = new ShowAction();

        $request = (new ServerRequest())
            ->withAttribute('id', $id = 2);

        $response = $action($request, new NotFoundHandler());

        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString(
            json_encode(['id' => $id, 'title' => 'Post #' . $id]),
            $response->getBody()->getContents()
        );
    }

    /**
     * @covers
     */
    public function testNotFound(): void
    {
        $action = new ShowAction();

        $request = (new ServerRequest())
            ->withAttribute('id', $id = 10);

        $response = $action($request, new NotFoundHandler());

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('Undefined page', $response->getBody()->getContents());
    }
}