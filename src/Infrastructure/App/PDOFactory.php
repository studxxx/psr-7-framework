<?php

declare(strict_types=1);

namespace Infrastructure\App;

use Psr\Container\ContainerInterface;

class PDOFactory
{
    public function __invoke(ContainerInterface $container): \PDO
    {
        ['dsn' => $dsn, 'username' => $username, 'password' => $password, 'options' => $options] =
            $container->get('config')['pdo'];
        return new \PDO($dsn, $username, $password, $options);
    }
}
