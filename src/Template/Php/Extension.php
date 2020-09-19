<?php

declare(strict_types=1);

namespace Template\Php;

abstract class Extension
{
    public function getFunctions(): array
    {
        return [];
    }

    public function getFilters(): array
    {
        return [];
    }
}
