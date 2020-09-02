<?php

namespace Template;

interface TemplateRenderer
{
    public function render($view, array $params = []): string;
}