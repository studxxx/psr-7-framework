<?php declare(strict_types=1);

namespace Tests\App\Http\Action;

use App\Http\Action\HelloAction;
use Framework\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use Template\Php\PhpRenderer;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends TestCase
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
    public function testGuest(): void
    {
        $action = new HelloAction($this->renderer);

        $request = new ServerRequest();
        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertStringContainsString('Hello!', $response->getBody()->getContents());
    }
}
