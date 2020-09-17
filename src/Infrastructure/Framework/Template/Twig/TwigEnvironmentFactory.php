<?php declare(strict_types=1);

namespace Infrastructure\Framework\Template\Twig;

use Psr\Container\ContainerInterface;
use Template\Twig\Extension\RouteExtension;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigEnvironmentFactory
{
    public function __invoke(ContainerInterface $container): Environment
    {
        $debug = $container->get('config')['debug'];

        $loader = new FilesystemLoader();
        $loader->addPath($container->get('config')['twig']['template_dir']);

        $twig = new Environment($loader, [
            'cache' => $debug ? false : $container->get('config')['twig']['cache_dir'],
            'debug' => $debug,
            'auto_reload' => $debug,
            'strict_variables' => $debug,
        ]);

        if ($debug) {
            $twig->addExtension(new DebugExtension());
        }
        $twig->addExtension($container->get(RouteExtension::class));

        foreach ($container->get('config')['twig']['extensions'] as $extension) {
            $twig->addExtension($container->get($extension));
        }
        return $twig;
    }
}
