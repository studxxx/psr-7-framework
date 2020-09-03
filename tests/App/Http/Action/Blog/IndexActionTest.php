<?php declare(strict_types=1);

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\IndexAction;
use App\ReadModel\PostReadRepository;
use Framework\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use Template\PhpRenderer;

class IndexActionTest extends TestCase
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
        $action = new IndexAction(new PostReadRepository(), $this->renderer);
        $response = $action();

        self::assertEquals(200, $response->getStatusCode());
//        self::assertJsonStringEqualsJsonString(
//            json_encode([
//                ['id' => 2, 'title' => 'The Second Post'],
//                ['id' => 1, 'title' => 'The First Post'],
//            ]),
//            $response->getBody()->getContents()
//        );
    }
}
