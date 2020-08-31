# [psr-7-framework]()
Demo framework

[![version][version-badge]][CHANGELOG]

## Run project

```shell script
composer serve
```
or
```shell script
php -S localhost:8000 -t public/
```

## Tests

```shell script
composer test
```
or
```shell script
./vendor/bin/phpunit
```

## Doc

### Container

```php
<?php

use App\Http\Action\Blog\IndexAction;
use Framework\Container\Container;

class Mailer
{
    public function __construct($username, $password)
    {
    }
}

$c = new Container();

// Parameters

$c->set('config', [
    'debug' => true,
    'users' => ['amin' => 'password'],
    'db' => [
        'dsn' => 'mysql:host=mysql;port=3306;dbname=psr7',
        'username' => 'root',
        'password' => 'secret'
    ],
    'mailer' => [
        'username' => 'root',
        'mailer' => 'secret'
    ],
]);

$c->set('mailer.address', 'mail@site.com');
$c->set('mailer.username', 'root');
$c->set('mailer.password', 'secret');
$c->set('per_page', 10);

// Services

$c->set(PDO::class, function (Container $container) {
    return new PDO(
        $container->get('config')['db']['dsn'],
        $container->get('config')['db']['username'],
        $container->get('config')['db']['password']
    );
});

$c->set(Mailer::class, function (Container $container) {
    return new Mailer(
        $container->get('config')['mailer']['username'],
        $container->get('config')['mailer']['password']
    );
});
$c->set(IndexAction::class, function (Container $container) {
    return new IndexAction($container->get(PDO::class), $container->get('per_page'));
});

############
$a = $c->get(IndexAction::class);
$response = $a($request);

```

### Auto fill of constructor

```php
<?php

require 'vendor/autoload.php';

$container = new \Framework\Container\Container();

$container->set(\Framework\Http\Router\Router::class, function () {
    return new \Framework\Http\Router\AuraRouterAdapter(new \Aura\Router\RouterContainer());
});

$class = \Framework\Http\Middleware\RouteMiddleware::class;
$reflection = new ReflectionClass($class);

$args = [];

if (($constructor = $reflection->getConstructor()) !== null) {
    foreach ($constructor->getParameters() as $param) {
        $paramClass = $param->getClass();
        $args[] = $container->get($paramClass->getName());
    }
}

$middleware = $reflection->newInstanceArgs($args);

var_dump($middleware);

echo PHP_EOL;
```

### Integrating  zendframework/zend-servicemanager. Theory

$container = new \Zend\ServiceManager\ServiceManager();

$container->get($id);
$container->has($id);

/*
$container->setService();
$container->setFactory();
$container->setInvokableClass();
$container->setAlias();
$container->addAbstractFactory();
*/
$container->setService('config', []);

#### $container->setFactory

$container->setFactory('db', function () {
    return new PDO('...', '...', '...');
});
//or
class PDOFactory
{
    //var1
    public static function create(): PDO
    {
        return new PDO('...', '...', '...');
    }
    //var 2
    public function __invoke(): PDO
    {
        return new PDO('...', '...', '...');
    }
}
//var1
$container->setFactory('db', [PDOFactory::class, 'create']);
//var2
$container->setFactory('db', PDOFactory::class);

#### $container->setInvokableClass

$container->setInvokableClass(\App\Http\Action\HelloAction::class);

#### $container->setAlias

interface AuthInterface {}
interface ProviderInterface {}
class Service implements AuthInterface, ProviderInterface {}

// tricks
$container->setFactory(AuthInterface::class, function ($container) {
    return $container->get('service');
});
$container->setFactory(ProviderInterface::class, function ($container) {
    return $container->get('service');
});
$container->setFactory('service', function ($container) {
    return new Service();
});

// How we can resolve

$container->setAlias(AuthInterface::class, Service::class);
$container->setAlias(ProviderInterface::class, Service::class);
$container->setFactory(Service::class, function ($container) {
    return new Service();
});

$container->get(AuthInterface::class);
$container->get(ProviderInterface::class);

#### container->addAbstractFactory

class SimpleAbstractFactory implements \Zend\ServiceManager\Factory\AbstractFactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (class_exists($requestedName)) {
            return new $requestedName();
        }
        return false;
    }

    public function canCreate(\Psr\Container\ContainerInterface $container, $requestedName): bool
    {
        return class_exists($requestedName);
    }
}

$container->addAbstractFactory(SimpleAbstractFactory::class);

#### For our framework

```php
$container = new \Zend\ServiceManager\ServiceManager([
    'invokables' => [
        \App\Http\Middleware\ProfilerMiddleware::class,
        \App\Http\Action\HelloAction::class,
        \App\Http\Action\Blog\IndexAction::class,
        \App\Http\Action\Blog\ShowAction::class,
    ],
    'factories' => [
        \Framework\Http\Router\Router::class => function () {
            return new \Framework\Http\Router\AuraRouterAdapter(new \Aura\Router\RouterContainer());
        }
    ],
    'abstract_factories' => [
        SimpleAbstractFactory::class
    ],
    'services' => [
        'config' => [
            'debug' => true,
            'users' => [
                'admin' => 'password'
            ]
        ]
    ],
]);
```

[CHANGELOG]: ./CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-0.0.3-blue.svg