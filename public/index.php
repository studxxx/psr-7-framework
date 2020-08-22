<?php declare(strict_types=1);

use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));

require './vendor/autoload.php';

### Initialization

$request = ServerRequestFactory::fromGlobals();

### Action

$path = $request->getUri()->getPath();

if ($path === '/') {
    $response = new HtmlResponse('Hello, ' . $request->getQueryParams()['name'] ?? 'Guest' . '!');
} elseif ($path === '/about') {
    $response = new HtmlResponse('I am a simple site.');
} elseif ($path === '/blog') {
    $response = new JsonResponse(
        [
            ['id' => 2, 'title' => 'The Second Post'],
            ['id' => 1, 'title' => 'The First Post'],
        ]
    );
} elseif (preg_match('#^/blog/(?P<id>\d+)$#i', $path, $matches)) {
    $id = $matches['id'];
    if ($id > 2) {
        $response = new JsonResponse(['error' => 'Undefined page'], 404);
    } else {
        $response = new JsonResponse(['id' => $id, 'title' => "Post #$id"]);
    }
} else {
    $response = new JsonResponse(['error' => 'Undefined page'], 404);
}

### PostProcessing

$response = $response->withHeader('X-Developer', 'studxxx');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
