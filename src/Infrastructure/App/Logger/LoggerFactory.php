<?php

declare(strict_types=1);

namespace Infrastructure\App\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container): Logger
    {
        $logger = new Logger('App');
        $logger->pushHandler(new StreamHandler(
            'var/log/application.log',
            $container->get('config')['debug'] ? Logger::DEBUG : Logger::WARNING
        ));
        return $logger;
    }
}
