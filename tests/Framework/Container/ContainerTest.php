<?php declare(strict_types=1);

namespace Tests\Framework\Container;

use Framework\Container\Container;
use Framework\Container\ServiceNotFoundException;
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
    public function testCallback(): void
    {
        $container = new Container();

        $container->set($name = 'name', function () {
            return new \stdClass();
        });

        self::assertNotNull($value = $container->get($name));
        self::assertInstanceOf(\stdClass::class, $value);
    }

    /**
     * @covers
     */
    public function testSingleton(): void
    {
        $container = new Container();

        $container->set($name = 'name', function () {
            return new \stdClass();
        });

        self::assertNotNull($value1 = $container->get($name));
        self::assertNotNull($value2 = $container->get($name));
        self::assertSame($value1, $value2);
    }

    /**
     * @covers
     */
    public function testContainerPass(): void
    {
        $container = new Container();

        $container->set('param', $value = 15);
        $container->set($name = 'name', function (Container $container) {
            $object = new \stdClass();
            $object->param = $container->get('param');
            return $object;
        });

        self::assertObjectHasAttribute('param', $object = $container->get($name));
        self::assertEquals($value, $object->param);
    }

    /**
     * @covers
     */
    public function testAutoInstalling(): void
    {
        $container = new Container();

        self::assertNotNull($value1 = $container->get(\stdClass::class));
        self::assertNotNull($value2 = $container->get(\stdClass::class));

        self::assertInstanceOf(\stdClass::class, $value1);
        self::assertInstanceOf(\stdClass::class, $value2);

        self::assertSame($value1, $value2);
    }

    /**
     * @covers
     */
    public function testAutowiring(): void
    {
        $container = new Container();

        $outer = $container->get(Outer::class);

        self::assertNotNull($outer);
        self::assertInstanceOf(Outer::class, $outer);

        self::assertNotNull($middle = $outer->middle);
        self::assertInstanceOf(Middle::class, $middle);

        self::assertNotNull($inner = $middle->inner);
        self::assertInstanceOf(Inner::class, $inner);
    }

    /**
     * @covers
     */
    public function testNotFound(): void
    {
        $container = new Container();

        $this->expectException(ServiceNotFoundException::class);

        $container->get('undefined_key');
    }
}

class Outer
{
    public Middle $middle;

    public function __construct(Middle $middle)
    {
        $this->middle = $middle;
    }
}

class Middle
{
    public Inner $inner;

    public function __construct(Inner $inner)
    {
        $this->inner = $inner;
    }
}

class Inner
{
}