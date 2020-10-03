<?php

use App\Console\Command;
use Doctrine\Migrations\Tools\Console\Command as MigrationCommand;
use Infrastructure\App\Console\Command\ClearCacheCommandFactory;
use Infrastructure\App\Console\Command\FixtureCommandFactory;
use Infrastructure\App\Doctrine\Factory\DiffCommandFactory;

return [
    'dependencies' => [
        'factories' => [
            Command\ClearCacheCommand::class => ClearCacheCommandFactory::class,
            Command\FixtureCommand::class => FixtureCommandFactory::class,
            MigrationCommand\DiffCommand::class => DiffCommandFactory::class,
        ],
    ],

    'console' => [
        'commands' => [
            Command\ClearCacheCommand::class,
            MigrationCommand\DiffCommand::class,
            MigrationCommand\DumpSchemaCommand::class,
            MigrationCommand\ExecuteCommand::class,
            MigrationCommand\GenerateCommand::class,
            MigrationCommand\LatestCommand::class,
            MigrationCommand\MigrateCommand::class,
            MigrationCommand\RollupCommand::class,
            MigrationCommand\StatusCommand::class,
            MigrationCommand\UpToDateCommand::class,
            MigrationCommand\VersionCommand::class,
            Command\FixtureCommand::class,
        ],
        'cachePaths' => [
            'twig' => 'var/cache/twig',
        ]
    ],
];
