<?php declare(strict_types=1);

namespace Tests\App\Http\Action;

use App\Http\Action\HelloAction;
use PHPUnit\Framework\TestCase;
use Template\TemplateRenderer;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends TestCase
{
    private TemplateRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->renderer = new TemplateRenderer('templates');
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
        self::assertStringContainsString('Hello, Guest!', $response->getBody()->getContents());
    }

    /**
     * @covers
     */
    public function testJohn(): void
    {
        $action = new HelloAction($this->renderer);

        $request = (new ServerRequest())
            ->withQueryParams(['name' => 'John']);

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertStringContainsString('Hello, John!', $response->getBody()->getContents());
    }
}
