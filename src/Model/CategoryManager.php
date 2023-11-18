<?php

namespace App\Model;

use PDO;

class CategoryManager extends AbstractManager
{
    public const TABLE = 'category';

    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        return $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCategoryToArticle(int $articleId, int $categoryId): void
    {
        $query = 'INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->execute();
    }

    public function addCategory(string $categoryName): int
    {
        $query = 'INSERT INTO ' . static::TABLE . ' (name) VALUES (:name)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $categoryName);
        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function getCategoriesByArticleId(int $articleId): array
    {
        $query = "SELECT C.* FROM category C
                  JOIN article_category AC ON C.id = AC.category_id
                  WHERE AC.article_id = :articleId";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':articleId', $articleId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
