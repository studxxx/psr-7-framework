<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'psr7',
            'user' => 'root',
            'pass' => 'secret',
            'port' => '33306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => '0.0.0.0',
            'name' => 'psr7',
            'user' => 'root',
            'pass' => 'secret',
            'port' => '33306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => '0.0.0.0',
            'name' => 'psr7_test',
            'user' => 'root',
            'pass' => 'secret',
            'port' => '33306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
