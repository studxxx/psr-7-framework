<?php declare(strict_types=1);

namespace Framework\Http\Router\Route;

use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;

class RegexpRoute implements Route
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

    public function match(ServerRequestInterface $request): ?Result
    {
        if ($this->methods && !\in_array($request->getMethod(), $this->methods, true)) {
            return null;
        }

        $pattern = \preg_replace_callback('~\{([^\}]+)\}~', function ($matches) {
            $argument = $matches[1];
            $replace = $this->tokens[$argument] ?? '[^}]+';
            return "(?P<$argument>$replace)";
        }, $this->pattern);

        if (preg_match("~^$pattern$~i", $request->getUri()->getPath(), $matches)) {
            return new Result(
                $this->name,
                $this->handler,
                array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
            );
        }
        return null;
    }

    public function generate(string $name, array $params = []): ?string
    {
        $arguments = array_filter($params);

        if ($name !== $this->name) {
            return null;
        }

        $url = \preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use (&$arguments) {
            $argument = $matches[1];
            if (!array_key_exists($argument, $arguments)) {
                throw new \InvalidArgumentException("Missing parameter \"$argument\"");
            }
            return $arguments[$argument];
        }, $this->pattern);

        if ($url !== null) {
            return $url;
        }

        return null;
    }
}
