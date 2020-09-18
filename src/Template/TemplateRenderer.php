<?php

declare(strict_types=1);

namespace Template;

interface TemplateRenderer
{
    public function render($view, array $params = []): string;
}
