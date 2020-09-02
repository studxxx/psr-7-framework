<?php declare(strict_types=1);

namespace Template;

class PhpRenderer implements TemplateRenderer
{
    private string $path;
    private array $params = [];
    private ?string $extend;

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
}
