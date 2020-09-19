<?php

declare(strict_types=1);

namespace Framework\Console;

class Input
{
    private array $args;

    public function __construct(array $argv)
    {
        $this->args = array_slice($argv, 1);;
    }

    public function getArgument(int $index): string
    {
        return $this->args[$index] ?? '';
    }
}
