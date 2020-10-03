<?php

namespace Infrastructure\App\Console\Command;

use App\Console\Command\FixtureCommand;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class FixtureCommandFactory
{
    public function __invoke(ContainerInterface $container): FixtureCommand
    {
        return new FixtureCommand($container->get(EntityManagerInterface::class), 'src/Fixture');
    }
}
