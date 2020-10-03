<?php

declare(strict_types=1);

namespace App\ReadModel;

use App\Entity\Post;

class PostReadRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return Post[]|array
     */
    public function all(int $offset, int $limit): array
    {
        $stmt = $this->pdo->prepare('
            SELECT p.*, (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) as comments_count
            FROM posts p ORDER BY p.create_date DESC LIMIT :limit OFFSET :offset
        ');

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return array_map([$this, 'hydratePostList'], $stmt->fetchAll());
    }

    /**
     * @param int $id
     * @return Post|object|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT p.* FROM posts p WHERE id = ?');

        $stmt->execute([$id]);

        if (!$post = $stmt->fetch()) {
            return null;
        }

        $stmt = $this->pdo->prepare('SELECT * FROM comments c WHERE c.post_id = :post_id ORDER BY id');
        $stmt->bindValue(':post_id', (int)$post['id'], \PDO::PARAM_INT);

        $stmt->execute();
        $comments = $stmt->fetchAll();

        return $this->hydratePostDetail($post, $comments);
    }

    public function countAll(): int
    {
        return (int)$this->pdo->query('SELECT COUNT(id) FROM posts')->fetchColumn();
    }

    private function hydratePostList(array $row): array
    {
        return [
            'id' => (int)$row['id'],
            'date' => new \DateTimeImmutable($row['create_date']),
            'title' => $row['title'],
            'preview' => $row['content_short'],
            'commentsCount' => $row['comments_count'],
        ];
    }

    private function hydratePostDetail(array $row, array $comments): array
    {
        return [
            'id' => (int)$row['id'],
            'date' => new \DateTimeImmutable($row['create_date']),
            'title' => $row['title'],
            'content' => $row['content_full'],
            'meta' => [
                'title' => $row['meta_title'],
                'description' => $row['meta_description'],
            ],
            'comments' => array_map([$this, 'hydrateComment'], $comments),
        ];
    }

    private function hydrateComment(array $row): array
    {
        return [
            'id' => (int)$row['id'],
            'date' => new \DateTimeImmutable($row['date']),
            'author' => $row['author'],
            'text' => $row['text'],
        ];
    }
}
