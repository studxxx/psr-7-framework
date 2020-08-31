<?php

use App\Http\Middleware as Middleware;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response;

return [
    'dependencies' => [
        'factories' => [
            Middleware\BasicAuthMiddleware::class => function (ContainerInterface $container) {
                return new Middleware\BasicAuthMiddleware($container->get('config')['auth']['users'], new Response());
            },
        ],
    ],

    'auth' => [
        'users' => []
    ],
];
