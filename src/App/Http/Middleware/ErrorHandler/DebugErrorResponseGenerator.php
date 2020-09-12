<?php declare(strict_types=1);

namespace App\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response;
use Zend\Stratigility\Utils;

class DebugErrorResponseGenerator implements ErrorResponseGenerator
{
    private TemplateRenderer $template;
    private string $view;

    public function __construct(TemplateRenderer $template, string $view)
    {
        $this->template = $template;
        $this->view = $view;
    }

    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        return new Response\HtmlResponse($this->template->render($this->view, [
            'request' => $request,
            'exception' => $e,
        ]), Utils::getStatusCode($e, new Response()));
    }
}
