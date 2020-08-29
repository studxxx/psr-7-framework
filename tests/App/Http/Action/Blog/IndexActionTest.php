<?php declare(strict_types=1);

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\IndexAction;
use PHPUnit\Framework\TestCase;

class IndexActionTest extends TestCase
{
    /**
     * @covers
     */
    public function testSuccess(): void
    {
        $action = new IndexAction();
        $response = $action();

        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString(
            json_encode([
                ['id' => 2, 'title' => 'The Second Post'],
                ['id' => 1, 'title' => 'The First Post'],
            ]),
            $response->getBody()->getContents()
        );
    }
}
