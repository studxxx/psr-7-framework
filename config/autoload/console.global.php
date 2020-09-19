<?php

use App\Console\Command;
use Infrastructure\App\Console\Command\ClearCacheCommandFactory;

return [
    'dependencies' => [
        'factories' => [
            Command\ClearCacheCommand::class => ClearCacheCommandFactory::class,
        ],
    ],

    'console' => [
        'commands' => [
            Command\ClearCacheCommand::class,
        ],
        'cachePaths' => [
            'twig' => 'var/cache/twig',
        ]
    ],
];
