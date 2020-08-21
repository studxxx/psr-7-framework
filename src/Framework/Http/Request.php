<?php declare(strict_types=1);

namespace Framework\Http;

class Request
{
    private array $queryParams;
    private ?array $parsedBody;

    public function __construct(array $queryParams = [], $parsedBody = null)
    {
        $this->queryParams = $queryParams;
        $this->parsedBody = $parsedBody;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParsedBody(): ?array
    {
        return $this->parsedBody;
    }
}
