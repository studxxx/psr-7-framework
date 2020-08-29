<?php declare(strict_types=1);

use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use App\Http\Action as Action;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));

require './vendor/autoload.php';

### Initialization

$params = [
    'debug' => true,
    'users' => ['admin' => 'password']
];

$aura = new RouterContainer();
$map = $aura->getMap();

$map->get('home', '/', Action\HelloAction::class);
$map->get('about', '/about', Action\AboutAction::class);
$map->get('blog', '/blog', Action\Blog\IndexAction::class);
$map->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);
$map->get('cabinet', '/cabinet', [
    new Middleware\BasicAuthMiddleware($params['users']),
    Action\CabinetAction::class,
]);

$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();
$app = new Application($resolver, new Middleware\NotFoundHandler());

$app->pipe(new Middleware\ErrorHandlerMiddleware($params['debug']));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe(new RouteMiddleware($router));
$app->pipe(new DispatchMiddleware($resolver));

### Running

$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request);

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
