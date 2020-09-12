<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private bool $debug;
    private TemplateRenderer $template;

    public function __construct(bool $debug = false, TemplateRenderer $template)
    {
        $this->debug = $debug;
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            if ($this->debug) {
                return new HtmlResponse($this->template->render('error/error-debug', [
                    'error' => 'Server error',
                    'e' => $e,
                ]), 500);
            }
            return new HtmlResponse($this->template->render('error/error', [
                'request' => $request
            ]), 500);
        }
    }
}
