<?php declare(strict_types=1);

namespace Framework\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private ErrorResponseGenerator $generator;
    private LoggerInterface $logger;

    public function __construct(ErrorResponseGenerator $generator, LoggerInterface $logger)
    {
        $this->generator = $generator;
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
                'request' => self::extractRequest($request),
            ]);
            return $this->generator->generate($e, $request);
        }
    }

    private static function extractRequest(ServerRequestInterface $request): array
    {
        return [
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
            'server' => $request->getServerParams(),
            'cookies' => $request->getCookieParams(),
            'body' => $request->getParsedBody(),
        ];
    }
}
