<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundHandler implements RequestHandlerInterface
{
    private TemplateRenderer $template;

    public function __construct(TemplateRenderer $template)
    {
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->template->render('error/404', [
            'request' => $request
        ]), 404);
    }
}
