<?php

declare(strict_types=1);

namespace Infrastructure\App\Twig;

use Psr\Container\ContainerInterface;
use Stormiix\Twig\Extension\MixExtension;

class MixExtensionFactory
{
    public function __invoke(ContainerInterface $container): MixExtension
    {
        ['root' => $root, 'manifest' => $manifest] = $container->get('config')['mix'];

        return new MixExtension($root, $manifest);
    }
}
