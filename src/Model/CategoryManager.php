<?php

namespace App\Model;

use PDO;

class CategoryManager extends AbstractManager // rajouter par côme
{
    public const TABLE = 'category';

    public function getCategoryById($categoryId)
    {
        $query = 'SELECT * FROM ' . static::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $categoryId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function insert(array $category)
    {
        $query = 'INSERT INTO ' . static::TABLE . ' (name) VALUES (:name)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $category['name']);
        $statement->execute();

        // Retourne l'ID de la dernière catégorie insérée
        return $this->pdo->lastInsertId();
    }
}
