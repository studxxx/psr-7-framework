<?php

declare(strict_types=1);

use Infrastructure\App\PDOFactory;

return [
    'dependencies' => [
        'factories' => [
            PDO::class => PDOFactory::class,
        ],
    ],
    'pdo' => [
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ],
];
