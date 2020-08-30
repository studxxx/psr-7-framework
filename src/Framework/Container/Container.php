<?php declare(strict_types=1);

namespace Framework\Container;

class Container
{
    private array $definitions = [];
    private array $results = [];

    public function get($id)
    {
        if (array_key_exists($id, $this->results)) {
            return $this->results[$id];
        }

        if (!array_key_exists($id, $this->definitions)) {
            if (class_exists($id)) {
                $reflection = new \ReflectionClass($id);
                $args = [];

                if (($constructor = $reflection->getConstructor()) !== null) {
                    foreach ($constructor->getParameters() as $param) {
                        if ($paramClass = $param->getClass()) {
                            $args[] = $this->get($paramClass->getName());
                        } elseif ($param->isArray()) {
                            $args[] = [];
                        } else {
                            if (!$param->isDefaultValueAvailable()) {
                                throw new ServiceNotFoundException("Unable to resolve \"{$param->getName()}\" in service \"$id\"");
                            }
                            $args[] = $param->getDefaultValue();
                        }
                    }
                }
                return $this->results[$id] = $reflection->newInstanceArgs($args);
            }
            throw new ServiceNotFoundException("Undefined parameter \"$id\"");
        }
        $definition = $this->definitions[$id];

        if ($definition instanceof \Closure) {
            $this->results[$id] = $definition($this);
        } else {
            $this->results[$id] = $definition;
        }
        return $this->results[$id];
    }

    public function set($id, $value): void
    {
        if (array_key_exists($id, $this->results)) {
            unset($this->results[$id]);
        }
        $this->definitions[$id] = $value;
    }

    public function has($id): bool
    {
        return class_exists($id);
    }
}
