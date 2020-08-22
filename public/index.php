<?php declare(strict_types=1);

use Framework\Http\RequestFactory;
use Framework\Http\Response;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

### Initialization

$request = RequestFactory::fromGlobals();

### Action

$name = $request->getQueryParams()['name'] ?? 'Guest';

$response = (new Response('Hello, ' . $name . '!'))
    ->withHeader('X-Developer', 'studxxx');

### Sending

header('HTTP/1.0' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
echo $response->getBody();
