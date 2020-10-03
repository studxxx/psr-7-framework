<?php

declare(strict_types=1);

use App\ReadModel\PostReadRepository;
use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Infrastructure\App\ReadModel\PostReadRepositoryFactory;

return [
    'dependencies' => [
        'factories' => [
            EntityManagerInterface::class => EntityManagerFactory::class,
            PostReadRepository::class => PostReadRepositoryFactory::class,
//            PDO::class => \Infrastructure\App\PDOFactory::class,
        ],
    ],
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'result_cache' => 'filesystem',
                'metadata_cache' => 'filesystem',
                'query_cache' => 'filesystem',
                'hydration_cache' => 'filesystem',
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driver_class' => Driver::class,
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'App\Entity' => 'entities'
                ],
            ],
            'entities' => [
                'class' => AnnotationDriver::class,
                'cache' => 'filesystem',
                'paths' => ['src/App/Entity'],
            ],
        ],
        'cache' => [
            'filesystem' => [
                'class' => FilesystemCache::class,
                'directory' => 'var/cache/doctrine'
            ],
        ],
    ],
];
