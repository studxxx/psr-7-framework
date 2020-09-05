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

### PhpRenderer

```php
<?php

use Framework\Http\Application;
use Framework\Http\Router\Router;
use Template\Php\PhpRenderer;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$container = require 'config/container.php';
$app = $container->get(Application::class);
require 'config/pipeline.php';
require 'config/routes.php';
/** @var Router $router */
$router = $container->get(Router::class);

class PathFunction
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __invoke($name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}

$renderer = new PhpRenderer('templates', $router, [
    'pathUrl' => $container->get(PathFunction::class),
]);

$html = $renderer->render('app/hello');

echo $html . PHP_EOL;
```

```php
<?php

use Framework\Http\Application;
use Framework\Http\Router\Router;
use Template\Php\PhpRenderer;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';
/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';
$app = $container->get(Application::class);
require 'config/pipeline.php';
require 'config/routes.php';
/** @var Router $router */
$router = $container->get(Router::class);
class Extension
{
    public function getFunctions(): array
    {
        return [];
    }

    public function getFilters(): array
    {
        return [];
    }
}

class Template\Php\Extension\RouteExtension extends Extension
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            'pathUrl' => [$this, 'generatePathUrl'],
        ];
    }

    public function getFit(): array
    {
        return [
            'pathUrl' => [$this, 'generatePathUrl'],
        ];
    }

    public function generatePathUrl($name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}

$renderer = new PhpRenderer('templates', $router);

$renderer->addFunction($container->get(Template\Php\Extension\RouteExtension::class));

$html = $renderer->render('app/hello');

echo $html . PHP_EOL;
```

### Config xdebug

#### docker-compose.yml
> set into php-fpm service
```
XDEBUG_CONFIG: "idekey=PHPSTORM remote_enable=1 remote_connect_back=1 remote_host=10.254.254.254"
```

#### php.ini
```
xdebug.remote_host=10.254.254.254
```
launch in bash
```shell script
sudo ip addr add 10.254.254.254/24 brd + dev wlp4s0 label wlp4s0:1
```
> wlp4s0 - network name from ifconfig or `enp2s0`

it looks like
```
enp2s0: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 192.168.0.85  netmask 255.255.255.0  broadcast 192.168.0.255
        inet6 fe80::2387:a0b4:efce:bc47  prefixlen 64  scopeid 0x20<link>
        ether 9c:5c:8e:97:7a:df  txqueuelen 1000  (Ethernet)
        RX packets 85555648  bytes 62443042382 (62.4 GB)
        RX errors 0  dropped 0  overruns 0  frame 0
        TX packets 107389361  bytes 129014095923 (129.0 GB)
        TX errors 0  dropped 0 overruns 0  carrier 0  collisions 0

enp2s0:1: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 10.254.254.254  netmask 255.255.255.0  broadcast 10.254.254.255
        ether 9c:5c:8e:97:7a:df  txqueuelen 1000  (Ethernet)
```
#### Articles about configuring xdebug
[How to setup Docker + PhpStorm + xdebug](https://stackoverflow.com/questions/46263043/how-to-setup-docker-phpstorm-xdebug-on-ubuntu-16-04/46265103#46265103)
[Configure Xdebug in PHP-FPM Docker container](https://stackoverflow.com/questions/48026670/configure-xdebug-in-php-fpm-docker-container)
[Can't connect PhpStorm with xdebug with Docker](https://stackoverflow.com/questions/47284905/cant-connect-phpstorm-with-xdebug-with-docker/47450391#47450391)
[xdebug-phpstorm-docker](https://stackoverflow.com/questions/tagged/xdebug+phpstorm+docker)
[Docker + php-fpm + PhpStorm + Xdebug](https://habr.com/ru/post/473184/)
[CHANGELOG]: ./CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-0.0.5-blue.svg