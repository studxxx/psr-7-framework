<?php declare(strict_types=1);

namespace Template;

class PhpRenderer implements TemplateRenderer
{
    private string $path;
    private array $params = [];
    private ?string $extend;
    private ?array $blocks;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function render($view, array $params = []): string
    {
        $templateFile = $this->path . '/' . $view . '.php';

        ob_start();
        extract($params, EXTR_OVERWRITE);
        $this->extend = null;

        require $templateFile;

        $content = ob_get_clean();

        if ($this->extend === null) {
            return $content;
        }

        return $this->render($this->extend, [
            'content' => $content,
        ]);
    }

    public function extend($view): void
    {
        $this->extend = $view;
    }

    public function beginBlock(): void
    {
        ob_start();
    }

    public function endBlock(string $name): void
    {
        $this->blocks[$name] = ob_get_clean();
    }

    public function renderBlock(string $name): string
    {
        return $this->blocks[$name] ?? '';
    }
}
