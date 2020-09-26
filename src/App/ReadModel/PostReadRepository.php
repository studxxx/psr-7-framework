<?php

declare(strict_types=1);

namespace App\ReadModel;

use App\ReadModel\Views\PostView;

class PostReadRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(int $offset, int $limit): array
    {
        $stmt = $this->pdo->prepare('select * from posts order by id desc limit :limit offset :offset');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'hydratePost'], $rows);
    }

    public function find(int $id): ?PostView
    {
        $stmt = $this->pdo->prepare('select * from posts where id = :id');
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        if ($post = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return $this->hydratePost($post);
        }
        return null;
    }

    private function hydratePost(array $row): PostView
    {
        $post = new PostView();
        $post->id = (int) $row['id'];
        $post->date = new \DateTimeImmutable($row['date']);
        $post->title = $row['title'];
        $post->content = $row['content'];
        return $post;
    }

    public function countAll(): int
    {
        $stmt = $this->pdo->query('select count(id) from posts');
        return (int) $stmt->fetchColumn();
    }
}
