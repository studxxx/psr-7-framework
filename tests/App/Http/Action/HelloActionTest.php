<?php declare(strict_types=1);

namespace Tests\App\Http\Action;

use App\Http\Action\HelloAction;
use PHPUnit\Framework\TestCase;
use Template\PhpRenderer;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends TestCase
{
    private PhpRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->renderer = new PhpRenderer('templates');
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
