<?php declare(strict_types=1);

namespace Tests\Framework\Http;

use Framework\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @covers
     */
    public function testEmpty(): void
    {
        $response = new Response($body = 'Body');

        self::assertEquals($body, $response->getBody());
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('OK', $response->getReasonPhrase());
    }

    /**
     * @covers
     */
    public function test404(): void
    {
        $response = new Response($body = 'Empty', $status = 404);

        self::assertEquals($body, $response->getBody()->getContents());
        self::assertEquals($status, $response->getStatusCode());
        self::assertEquals('Not Found', $response->getReasonPhrase());
    }

    /**
     * @covers
     */
    public function testHeaders(): void
    {
        $response = (new Response('', 204))
            ->withHeader($name1 = 'X-Header-1', $value1 = 'value_1')
            ->withHeader($name2 = 'X-Header-2', $value2 = 'value_2');

        self::assertEquals([$name1 => $value1, $name2 => $value2], $response->getHeaders());
    }
}
