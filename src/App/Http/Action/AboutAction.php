<?php declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class AboutAction
{
    private TemplateRenderer $template;

    public function __construct(TemplateRenderer $template)
    {
        $this->template = $template;
    }

    public function __invoke(): ResponseInterface
    {
        return new HtmlResponse($this->template->render('about', [
            'content' => 'I am a simple site.',
        ]));
    }
}
