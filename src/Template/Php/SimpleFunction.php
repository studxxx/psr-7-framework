<?php

declare(strict_types=1);

namespace Template\Php;

class SimpleFunction
{
    public string $name;
    /** @var callable */
    public $callback;
    public bool $needRenderer;

    public function __construct(string $name, callable $callback, bool $needRenderer = false)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->needRenderer = $needRenderer;
    }
}
