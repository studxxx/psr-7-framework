<?php declare(strict_types=1);

use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\ActionResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use App\Http\Action as Action;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
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
$map->get('cabinet', '/cabinet', function (ServerRequestInterface $request) use ($params) {
    $profiler = new Middleware\ProfilerMiddleware();
    $auth = new Middleware\BasicAuthActionMiddleware($params['users']);
    $cabinet = new Action\CabinetAction();

    return $profiler($request, function (ServerRequestInterface $request) use ($auth, $cabinet) {
        return $auth($request, function (ServerRequestInterface $request) use ($cabinet) {
            return $cabinet($request);
        });
    });
});

$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();

### Running

$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    /** @var callable $action */
    $handler = $result->getHandler();
    $action = $resolver->resolve($handler);
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Undefined page'], 404);
}

### PostProcessing

$response = $response->withHeader('X-Developer', 'studxxx');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
