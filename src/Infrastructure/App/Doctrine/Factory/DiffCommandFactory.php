<?php

declare(strict_types=1);

namespace Infrastructure\App\Doctrine\Factory;

use Doctrine\Migrations\Provider\OrmSchemaProvider;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class DiffCommandFactory
{
    public function __invole(ContainerInterface $container): DiffCommand
    {
        return new DiffCommand(new OrmSchemaProvider($container->get(EntityManagerInterface::class)));
    }
}
