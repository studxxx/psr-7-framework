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
        $request = new Request();

        self::assertEquals([], $request->getQueryParams());
        self::assertNull($request->getParsedBody());
    }

    /**
     * @covers
     */
    public function testQueryParams(): void
    {
        $data = [
            'name' => 'John',
            'age' => 25
        ];
        $request = new Request($data);

        self::assertEquals($data, $request->getQueryParams());
        self::assertNull($request->getParsedBody());
    }

    /**
     * @covers
     */
    public function testParsedBody(): void
    {
        $request = new Request([], $data = ['title' => 'Title']);

        self::assertEquals([], $request->getQueryParams());
        self::assertEquals($data, $request->getParsedBody());
    }
}
