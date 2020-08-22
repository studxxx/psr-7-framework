<?php declare(strict_types=1);

namespace Framework\Http;

interface ResponseInterface
{
    public function getBody();

    /**
     * @param $body
     * @return static
     */
    public function withBody($body): self;

    public function getStatusCode(): int;

    public function getReasonPhrase();

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return static
     */
    public function withStatus(int $code, $reasonPhrase = ''): self;

    public function getHeaders(): array;

    public function hasHeader($header): bool;

    public function getHeader($header);

    /**
     * @param $header
     * @param $value
     * @return static
     */
    public function withHeader($header, $value): self;
}
