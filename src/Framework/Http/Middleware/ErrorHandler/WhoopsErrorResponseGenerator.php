<?php

declare(strict_types=1);

namespace Framework\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\RunInterface;
use Zend\Stratigility\Utils;

class WhoopsErrorResponseGenerator implements ErrorResponseGenerator
{
    private RunInterface $whoops;
    private ResponseInterface $response;

    public function __construct(RunInterface $whoops, ResponseInterface $response)
    {
        $this->whoops = $whoops;
        $this->response = $response;
    }

    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->whoops->getHandlers() as $handler) {
            if ($handler instanceof PrettyPageHandler) {
                $this->prepareWhoopsHandler($request, $handler);
            }
        }

        $response = $this->response->withStatus(Utils::getStatusCode($e, $this->response));

        $response->getBody()->write($this->whoops->handleException($e));

        return $response;
    }

    private function prepareWhoopsHandler(ServerRequestInterface $request, PrettyPageHandler $handler): void
    {
        $handler->addDataTable('ApplicationRequest', [
            'HTTP Method' => $request->getMethod(),
            'URI' => $request->getMethod(),
            'Script' => $request->getServerParams()['SCRIPT_NAME'],
            'Headers' => $request->getHeaders(),
            'Cookies' => $request->getCookieParams(),
            'Attributes' => $request->getAttributes(),
            'Query String Arguments' => $request->getQueryParams(),
            'Body Params' => $request->getParsedBody(),
        ]);
    }
}
