<?php declare(strict_types=1);

namespace Framework\Container;

class Container
{
    private array $definitions = [];

    public function get($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ServiceNotFoundException("Undefined parameter \"$id\"");
        }
        $definition = $this->definitions[$id];

        if ($definition instanceof \Closure) {
            return $definition();
        }
        return $definition;
    }

    public function set($id, $value): void
    {
        $this->definitions[$id] = $value;
    }
}
