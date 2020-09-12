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
    private ResponseInterface $response;

    public function __construct(TemplateRenderer $template, ResponseInterface $response, string $view)
    {
        $this->template = $template;
        $this->view = $view;
        $this->response = $response;
    }

    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->response->withStatus(Utils::getStatusCode($e, $this->response));
        $response
            ->getBody()
            ->write($this->template->render($this->view, [
                'request' => $request,
                'exception' => $e,
            ]));

        return $response;
    }
}
