<?php


namespace App\Infrastructure\Persistence\Post;


use App\Domain\Post\Post;
use App\Domain\Post\PostRepository;

class DbPostRepository implements PostRepository
{
    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return array|null
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM post
        ');

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return Post|null
     */
    public function findPostById(int $id): ?Post
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM post
            WHERE `id`=:id
        ');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            return new Post($result['id'], $result['content']);
        }

        return null;
    }

    public function save(Post $post): bool
    {
        $id = $post->getId();
        $content = $post->getContent();

        $stmt = $this->pdo->prepare('
                INSERT INTO post 
                VALUES (:id, :content)
            ');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    public function update(int $id, Post $updatedData): bool
    {
        $newContent = $updatedData->getContent();

        $stmt = $this->pdo->prepare('
            UPDATE post
            SET `content`= :newContent
            WHERE `id` = :id
        ');
        $stmt->bindParam(':newContent',$newContent);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }
}