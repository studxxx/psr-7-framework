<?php

declare(strict_types=1);

namespace Infrastructure\App\Console\Command;

use App\Console\Command\ClearCacheCommand;
use App\Service\FileManager;
use Psr\Container\ContainerInterface;

class ClearCacheCommandFactory
{
    public function __invoke(ContainerInterface $container): ClearCacheCommand
    {
        return new ClearCacheCommand(
            $container->get('config')['console']['cachePaths'],
            $container->get(FileManager::class)
        );
    }
}
