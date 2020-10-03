<?php

declare(strict_types=1);

namespace App\ReadModel;

use App\Entity\Post;
use Doctrine\ORM\EntityRepository;

class PostReadRepository
{
    private EntityRepository $repository;

    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return Post[]|array
     */
    public function all(int $offset, int $limit): array
    {
        return $this->repository
            ->createQueryBuilder('p')
            ->select('p')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('p.createDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @return Post|object|null
     */
    public function find(int $id): ?Post
    {
        return $this->repository->find($id);
    }

    public function countAll(): int
    {
        return (int)$this->repository
            ->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
