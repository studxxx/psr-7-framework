<?php declare(strict_types=1);

namespace Tests\Framework\Http;

use Framework\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @covers
     */
    public function testEmpty(): void
    {
        $_GET = [];
        $_POST = [];

        $request = new Request();

        self::assertEquals([], $request->getQueryParams());
        self::assertNull($request->getParsedBody());
    }

    /**
     * @covers
     */
    public function testQueryParams(): void
    {
        $_GET = $data = [
            'name' => 'John',
            'age' => 25
        ];

        $_POST = [];

        $request = new Request();

        self::assertEquals($data, $request->getQueryParams());
        self::assertNull($request->getParsedBody());
    }

    /**
     * @covers
     */
    public function testParsedBody(): void
    {
        $_GET = [];

        $_POST = $data = ['title' => 'Title'];

        $request = new Request();

        self::assertEquals([], $request->getQueryParams());
        self::assertEquals($data, $request->getParsedBody());
    }
}
