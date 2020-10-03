<?php

declare(strict_types=1);

namespace Infrastructure\App;

use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class PDOFactory
{
    public function __invoke(ContainerInterface $container): DriverConnection
    {
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        return $em->getConnection()->getWrappedConnection();
    }
}
