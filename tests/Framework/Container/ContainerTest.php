<?php declare(strict_types=1);

namespace Tests\Framework\Container;

use Framework\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @covers
     */
    public function testPrimitives(): void
    {
        $container = new Container();

        $container->set($name = 'name', $value = 5);
        self::assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = 'string');
        self::assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = ['array']);
        self::assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = new \stdClass());
        self::assertEquals($value, $container->get($name));
    }

    /**
     * @covers
     */
    public function testNotFound(): void
    {
        $container = new Container();

        $this->expectException(\InvalidArgumentException::class);

        $container->get('undefined_key');
    }
}
