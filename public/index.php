<?php declare(strict_types=1);

use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use App\Http\Action as Action;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));

require './vendor/autoload.php';

### Initialization

$params = [
    'users' => ['admin' => 'password']
];

$aura = new RouterContainer();
$map = $aura->getMap();

$map->get('home', '/', Action\HelloAction::class);
$map->get('about', '/about', Action\AboutAction::class);
$map->get('blog', '/blog', Action\Blog\IndexAction::class);
$map->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);
$map->get('cabinet', '/cabinet', [
    Middleware\ProfilerMiddleware::class,
    new Middleware\BasicAuthMiddleware($params['users']),
    Action\CabinetAction::class,
]);

$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();

### Running

$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }

    $handlers = $result->getHandler();
    $pipeline = new Pipeline();

    foreach (is_array($handlers) ? $handlers : [$handlers] as $handler) {
        $pipeline->pipe($resolver->resolve($handler));
    }
    $response = $pipeline($request, new Middleware\NotFoundHandler());
} catch (RequestNotMatchedException $e) {
    $response = (new Middleware\NotFoundHandler())($request);
}

### PostProcessing

$response = $response->withHeader('X-Developer', 'studxxx');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
