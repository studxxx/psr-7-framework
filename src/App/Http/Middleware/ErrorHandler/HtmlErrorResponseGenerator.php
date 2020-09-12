<?php declare(strict_types=1);

namespace App\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Stratigility\Utils;

class HtmlErrorResponseGenerator implements ErrorResponseGenerator
{
    private TemplateRenderer $template;
    private array $views;

    public function __construct(TemplateRenderer $template, array $views)
    {
        $this->template = $template;
        $this->views = $views;
    }

    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $code = Utils::getStatusCode($e, new Response());

        return new HtmlResponse($this->template->render($this->getView($code), [
            'request' => $request,
            'exception' => $e,
        ]), $code);
    }

    private function getView(int $code): string
    {
        if (array_key_exists($code, $this->views)) {
            return $this->views[$code];
        }
        return $this->views['error'];
    }
}
