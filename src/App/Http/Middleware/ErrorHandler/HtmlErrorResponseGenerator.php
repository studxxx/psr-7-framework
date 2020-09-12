<?php declare(strict_types=1);

namespace App\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class HtmlErrorResponseGenerator implements ErrorResponseGenerator
{
    private TemplateRenderer $template;
    private bool $debug;

    public function __construct(bool $debug, TemplateRenderer $template)
    {
        $this->debug = $debug;
        $this->template = $template;
    }

    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $view = $this->debug ? 'error/error-debug' : 'error/error';

        return new HtmlResponse($this->template->render($view, [
            'request' => $request,
            'exception' => $e,
        ]), self::getStatusCode($e));
    }

    public static function getStatusCode(\Throwable $e): int
    {
        $code = $e->getCode();
        if ($code >= 400 && $code < 600) {
            return $code;
        }
        return 500;
    }
}
