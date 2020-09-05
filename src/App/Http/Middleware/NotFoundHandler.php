<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundHandler
{
    private TemplateRenderer $template;

    public function __construct(TemplateRenderer $template)
    {
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return new HtmlResponse($this->template->render('error/404', [
            'request' => $request
        ]), 404);
    }
}
