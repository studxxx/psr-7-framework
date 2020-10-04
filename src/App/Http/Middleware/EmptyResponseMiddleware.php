<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Stream;

class EmptyResponseMiddleware implements MiddlewareInterface
{
    private TemplateRenderer $template;

    public function __construct(TemplateRenderer $template)
    {
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($response->getBody()->getSize() === 0) {
            if ($response->getStatusCode() === 403) {
                $response = $response->withBody(
                    self::createBody($this->template->render('error/403', ['request' => $request]))
                );
            }
            if ($response->getStatusCode() === 404) {
                $response = $response->withBody(
                    self::createBody($this->template->render('error/404', ['request' => $request]))
                );
            }
        }

        return $response;
    }

    private static function createBody(string $content): StreamInterface
    {
        $body = new Stream('php://temp', 'wb+');
        $body->write($content);
        return $body;
    }
}
