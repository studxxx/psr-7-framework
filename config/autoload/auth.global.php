<?php

use App\Http\Middleware\BasicAuthMiddleware;
use Infrastructure\App\Http\Middleware\BasicAuthMiddlewareFactory;

return [
    'dependencies' => [
        'factories' => [
            BasicAuthMiddleware::class => BasicAuthMiddlewareFactory::class,
        ],
    ],

    'auth' => [
        'users' => []
    ],
];
