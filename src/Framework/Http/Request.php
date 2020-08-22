<?php declare(strict_types=1);

namespace Framework\Http;

class Request
{
    private array $queryParams = [];
    private ?array $parsedBody;

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): self
    {
        $this->queryParams = $query;
        return $this;
    }

    public function getParsedBody(): ?array
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): self
    {
        $this->parsedBody = $data;
        return $this;
    }
}
