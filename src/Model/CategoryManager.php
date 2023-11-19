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

    public function updateArticleCategories($articleId, array $selectedCategories)
    {
        // Supprimer les anciennes associations
        $deleteQuery = "DELETE FROM article_category WHERE article_id = :articleId";
        $deleteStmt = $this->pdo->prepare($deleteQuery);
        $deleteStmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
        $deleteStmt->execute();
        // Ajouter les nouvelles associations
        $insertQuery = "INSERT INTO article_category (article_id, category_id) VALUES (:articleId, :categoryId)";
        $insertStmt = $this->pdo->prepare($insertQuery);
        foreach ($selectedCategories as $categoryId) {
            $insertStmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
            $insertStmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
            $insertStmt->execute();
        }
    }

    public function doesCategoryExist(string $categoryName): bool {
        $query = 'SELECT COUNT(*) FROM ' . static::TABLE . ' WHERE name = :name';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $categoryName);
        $statement->execute();
    
        return $statement->fetchColumn() > 0;
    }
    
    public function deleteAllCategoriesFromArticle($articleId) {
        $query = "DELETE FROM article_category WHERE article_id = :articleId";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':articleId', $articleId, PDO::PARAM_INT);
        $statement->execute();
    }

    public function getCategoryIdByName(string $categoryName): ?int {
        $query = 'SELECT id FROM category WHERE name = :name';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $categoryName, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['id'] : null;
    }
    
}
