<?php declare(strict_types=1);

namespace Framework\Http;

interface ServerRequestInterface
{
    public function getQueryParams(): array;

    /**
     * @param array $query
     * @return static
     */
    public function withQueryParams(array $query);

    public function getParsedBody(): ?array;

    /**
     * @param $data
     * @return static
     */
    public function withParsedBody($data);
}
