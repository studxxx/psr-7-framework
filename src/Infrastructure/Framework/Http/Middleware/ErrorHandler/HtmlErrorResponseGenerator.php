<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Middleware\ErrorHandler;

use Framework\Http\Middleware\ErrorHandler\ErrorResponseGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Stratigility\Utils;

class HtmlErrorResponseGenerator implements ErrorResponseGenerator
{
    private TemplateRenderer $template;
    private array $views;
    private ResponseInterface $response;

    public function __construct(TemplateRenderer $template, ResponseInterface $response, array $views)
    {
        $this->template = $template;
        $this->views = $views;
        $this->response = $response;
    }

    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $code = Utils::getStatusCode($e, $this->response);

        $response = $this->response->withStatus($code);

        $response
            ->getBody()
            ->write($this->template->render($this->getView($code), [
                'request' => $request,
                'exception' => $e,
            ]));

        return $response;
    }

    private function getView(int $code): string
    {
        if (array_key_exists($code, $this->views)) {
            return $this->views[$code];
        }
        return $this->views['error'];
    }
}
