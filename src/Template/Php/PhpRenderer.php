<?php declare(strict_types=1);

namespace Template\Php;

use Template\TemplateRenderer;

class PhpRenderer implements TemplateRenderer
{
    private string $path;
    private ?string $extend;
    private array $blocks = [];
    private \SplStack $blockNames;
    /** @var array|Extension[] */
    private array $extensions = [];

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
    }

    public function addExtension(Extension $extension): void
    {
        $this->extensions[] = $extension;
    }

    public function __call($name, $arguments)
    {
        foreach ($this->extensions as $extension) {
            $functions = $extension->getFunctions();
            foreach ($functions as $function) {
                /** @var SimpleFunction $function */
                if ($function->name === $name) {
                    if ($function->needRenderer) {
                        return ($function->callback)($this, ...$arguments);
                    }
                    return ($function->callback)(...$arguments);
                }
            }
        }
        throw new \InvalidArgumentException("Undefined function \"$name\"");
    }

    public function render($view, array $params = []): string
    {
        $level = ob_get_level();
        try {
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
        } catch (\Throwable|\Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }
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

    public function encode(string $name): string
    {
        return \htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE);
    }
}
