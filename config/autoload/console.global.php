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
        'cachePaths' => [
            'twig' => 'var/cache/twig',
        ]
    ],
];
