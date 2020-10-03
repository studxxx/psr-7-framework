<?php

declare(strict_types=1);

namespace Infrastructure\App\ReadModel;

use App\Entity\Post;
use App\ReadModel\PostReadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;

class PostReadRepositoryFactory
{
    public function __invoke(ContainerInterface $container): PostReadRepository
    {
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        /** @var EntityRepository $repository */
        $repository = $em->getRepository(Post::class);

        return new PostReadRepository($repository);
    }
}
