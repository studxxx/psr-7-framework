<?php declare(strict_types=1);

namespace Framework\Http\Router;

class Route
{
    public string $name;
    public string $pattern;
    public $handler;
    public array $tokens;
    public array $methods;

    public function __construct($name, $pattern, $handler, $methods, $tokens = [])
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->tokens = $tokens;
        $this->methods = $methods;
    }
}