<?php declare(strict_types=1);

namespace Framework\Http\Router\Route;

class RouteData
{
    public string $name;
    public string $path;
    /** @var mixed */
    public $handler;
    public array $methods;
    public array $options;

    public function __construct(string $name, string $path, $handler, array $methods, array $options)
    {
        $this->name = $name;
        $this->path = $path;
        $this->handler = $handler;
        $this->methods = array_map('mb_strtoupper', $methods);
        $this->options = $options;
    }
}