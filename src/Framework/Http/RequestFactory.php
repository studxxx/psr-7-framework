<?php declare(strict_types=1);

namespace Framework\Http;

class RequestFactory
{
    public static function fromGlobals(array $query = null, array $body = null): Request
    {
        return (new Request())
            ->withParsedBody($query ?: $_GET)
            ->withParsedBody($body ?: $_POST);
    }
}
