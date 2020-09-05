<?php declare(strict_types=1);

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\ShowAction;
use App\Http\Middleware\NotFoundHandler;
use App\ReadModel\PostReadRepository;
use Framework\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use Template\Php\PhpRenderer;
use Zend\Diactoros\ServerRequest;

class ShowActionTest extends TestCase
{
    private PhpRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        $router = $this->createMock(Router::class);
        $this->renderer = new PhpRenderer('templates', $router);
    }

    /**
     * @covers
     */
    public function testSuccess(): void
    {
        $action = new ShowAction(new PostReadRepository(), $this->renderer);

        $request = (new ServerRequest())
            ->withAttribute('id', $id = 2);

        $response = $action($request, new NotFoundHandler());

        self::assertEquals(200, $response->getStatusCode());
//        self::assertJsonStringEqualsJsonString(
//            json_encode(['id' => $id, 'title' => 'Post #' . $id]),
//            $response->getBody()->getContents()
//        );
    }

    /**
     * @covers
     */
    public function testNotFound(): void
    {
        $action = new ShowAction(new PostReadRepository(), $this->renderer);

        $request = (new ServerRequest())
            ->withAttribute('id', $id = 10);

        $response = $action($request, new NotFoundHandler());

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('Undefined page', $response->getBody()->getContents());
    }
}