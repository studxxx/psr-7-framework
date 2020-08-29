<?php declare(strict_types=1);

use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use App\Http\Action as Action;
use Framework\Http\Router\Router;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));

require './vendor/autoload.php';

### Configuration

$container = new Container();

$container->set('config', [
    'debug' => true,
    'users' => ['admin' => 'password'],
]);

$container->set(Application::class, function (Container $container) {
    return new Application(
        $container->get(MiddlewareResolver::class),
        new Middleware\NotFoundHandler(),
        new Response()
    );
});

$container->set(Middleware\ErrorHandlerMiddleware::class, function (Container $container) {
    return new Middleware\ErrorHandlerMiddleware($container->get('config')['debug']);
});

$container->set(Middleware\BasicAuthMiddleware::class, function (Container $container) {
    return new Middleware\BasicAuthMiddleware($container->get('config')['users'], new Response());
});

$container->set(MiddlewareResolver::class, function () {
    return new MiddlewareResolver();
});

$container->set(RouteMiddleware::class, function (Container $container) {
    return new RouteMiddleware($container->get(Router::class));
});

$container->set(DispatchMiddleware::class, function (Container $container) {
    return new DispatchMiddleware($container->get(MiddlewareResolver::class));
});

$container->set(Router::class, function () {
    $aura = new RouterContainer();
    $routes = $aura->getMap();

    $routes->get('home', '/', Action\HelloAction::class);
    $routes->get('about', '/about', Action\AboutAction::class);
    $routes->get('blog', '/blog', Action\Blog\IndexAction::class);
    $routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);
    $routes->get('cabinet', '/cabinet', Action\CabinetAction::class);

    return new AuraRouterAdapter($aura);
});

### Initialization

/** @var Application $app */
$app = $container->get(Application::class);

$app->pipe($container->get(Middleware\ErrorHandlerMiddleware::class));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe($container->get(RouteMiddleware::class));
$app->pipe('cabinet', $container->get(Middleware\BasicAuthMiddleware::class));
$app->pipe($container->get(DispatchMiddleware::class));

### Running

$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
