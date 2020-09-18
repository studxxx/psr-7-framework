<?php

declare(strict_types=1);

namespace Template\Twig;

use Template\TemplateRenderer;
use Twig\Environment;

class TwigRenderer implements TemplateRenderer
{
    private Environment $twig;
    private string $extension;

    public function __construct(Environment $twig, string $extension)
    {
        $this->twig = $twig;
        $this->extension = $extension;
    }

    public function render($name, array $params = []): string
    {
        return $this->twig->render($name . $this->extension, $params);
    }
}
