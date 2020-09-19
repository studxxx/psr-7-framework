<?php

declare(strict_types=1);

namespace Framework\Console;

class Output
{
    public function write(string $message): void
    {
        echo $message;
    }

    public function writeln(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
