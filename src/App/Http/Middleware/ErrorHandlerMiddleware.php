<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandlerMiddleware
{
    private bool $debug;
    private TemplateRenderer $template;

    public function __construct(bool $debug = false, TemplateRenderer $template)
    {
        $this->debug = $debug;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        try {
            return $next($request);
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
