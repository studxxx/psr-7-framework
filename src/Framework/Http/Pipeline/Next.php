<?php declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Next
{
    /** @var callable */
    private $default;
    private \SplQueue $queue;
    private ResponseInterface $response;

    public function __construct(\SplQueue $queue, callable $default)
    {
        $this->default = $default;
        $this->queue = $queue;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request, $response);
        }

        $middleware = $this->queue->dequeue();

        return $middleware($request, $response, function (ServerRequestInterface $request) use ($response) {
            return $this($request, $response);
        });
    }
}
