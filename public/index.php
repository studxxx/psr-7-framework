<?php declare(strict_types=1);

use App\Http\Middleware as Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use App\Http\Action as Action;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));

require './vendor/autoload.php';

### Initialization

class Container
{
    private array $definitions = [];

    public function get($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new InvalidArgumentException("Undefined parameter \"$id\"");
        }
        return $this->definitions[$id];
    }

    public function set($id, $value): void
    {
        $this->definitions[$id] = $value;
    }
}

$container = new Container();

$container->set('debug', true);
$container->set('users', ['admin' => 'password']);
$container->set('db', new PDO('mysql:host=mysql;port=3306;dbname=psr7', 'root', 'secret'));

$db = $container->get('db');

##################

$definitions = [
    'debug' => true,
    'users' => ['admin' => 'password'],
    'db' => new PDO('mysql:host=mysql;port=3306;dbname=psr7', 'root', 'secret')
];

$aura = new RouterContainer();
$map = $aura->getMap();

$map->get('home', '/', Action\HelloAction::class);
$map->get('about', '/about', Action\AboutAction::class);
$map->get('blog', '/blog', Action\Blog\IndexAction::class);
$map->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);
$map->get('cabinet', '/cabinet', Action\CabinetAction::class);

$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();
$app = new Application($resolver, new Middleware\NotFoundHandler(), new Response());

$app->pipe(new Middleware\ErrorHandlerMiddleware($container->get('debug')));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe('cabinet', new Middleware\BasicAuthMiddleware($container->get('users'), new Response()));
$app->pipe(new RouteMiddleware($router));
$app->pipe(new DispatchMiddleware($resolver));

### Running

$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
