<?php declare(strict_types=1);

namespace Template;

class PhpRenderer implements TemplateRenderer
{
    private string $path;
    private ?string $extend;
    private array $blocks = [];
    private \SplStack $blockNames;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
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

        return $this->render($this->extend);
    }

    public function extend($view): void
    {
        $this->extend = $view;
    }

    public function block(string $name, $content): void
    {
        if ($this->hasBlock($name)) {
            return;
        }
        $this->blocks[$name] = $content;
    }

    public function ensureBlock(string $name): bool
    {
        if ($this->hasBlock($name)) {
            return false;
        }
        $this->beginBlock($name);
        return true;
    }

    public function beginBlock(string $name): void
    {
        $this->blockNames->push($name);
        ob_start();
    }

    public function endBlock(): void
    {
        $content = ob_get_clean();
        $name = $this->blockNames->pop();

        if ($this->hasBlock($name)) {
            return;
        }
        $this->blocks[$name] = $content;
    }

    public function renderBlock(string $name): string
    {
        $block = $this->blocks[$name] ?? null;
        if ($block instanceof \Closure) {
            return $block();
        }
        return $block ?? '';
    }

    public function hasBlock(string $name): bool
    {
        return array_key_exists($name, $this->blocks);
    }
}
