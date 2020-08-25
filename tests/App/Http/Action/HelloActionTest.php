<?php declare(strict_types=1);

namespace Tests\App\Http\Action;

use App\Http\Action\HelloAction;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends TestCase
{
    /**
     * @covers
     */
    public function testGuest(): void
    {
        $action = new HelloAction();

        $request = new ServerRequest();
        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('Hello, Guest!', $response->getBody()->getContents());
    }

    /**
     * @covers
     */
    public function testJohn(): void
    {
        $action = new HelloAction();

        $request = (new ServerRequest())
            ->withQueryParams(['name' => 'John']);

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('Hello, John!', $response->getBody()->getContents());
    }
}
