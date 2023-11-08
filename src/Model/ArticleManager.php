<?php

namespace App\Model;

use PDO;

class ArticleManager extends AbstractManager
{
    public const TABLE = 'article';

    public function getAllArticlesWithAuthors(): array
    {
        $query = "SELECT A.*, BU.name AS author_name
                  FROM article A
                  INNER JOIN blog_user BU ON A.blog_user_id = BU.id";

        $result = $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function insert(array $data): int
    {
        $query = "INSERT INTO " . self::TABLE . " (title, content, image, blog_user_id) 
                  VALUES (:title, :content, :image, :blog_user_id)";
        $statement = $this->pdo->prepare($query);
        $statement->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(array $data): void
    {
        $query = "UPDATE " . self::TABLE . " SET title = :title, content = :content, image = :image WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function getLastInsertedId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }
}
