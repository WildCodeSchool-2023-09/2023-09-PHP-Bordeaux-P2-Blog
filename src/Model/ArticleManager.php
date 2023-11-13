<?php

namespace App\Model;

use PDO;

class ArticleManager extends AbstractManager
{
    public const TABLE = 'article';

    public function addArticle(array $data)
    {
        $query = 'INSERT INTO ' .
        static::TABLE .
        ' (title, content, image, blog_user_id) 
        VALUES (:title, :content, :image, :blog_user_id)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $data['title']);
        $statement->bindValue(':content', $data['content']);
        $statement->bindValue(':image', $data['image']);
        $statement->bindValue(':blog_user_id', $data['blog_user_id'], \PDO::PARAM_INT);
        $statement->execute();
    }

    public function editArticle(int $articleId, array $data)
    {
        $query = 'UPDATE ' . static::TABLE . ' SET title = :title, content = :content, image = :image WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $data['title']);
        $statement->bindValue(':content', $data['content']);
        $statement->bindValue(':image', $data['image']);
        $statement->bindValue(':id', $articleId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function deleteArticle(int $articleId)
    {
        $query = 'DELETE FROM ' . static::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $articleId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function getArticlesByUserId(int $userId)
    {
        $query = 'SELECT * FROM ' . static::TABLE . ' WHERE blog_user_id = :user_id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getAllArticles()
    {
        $query = "SELECT A.*, BU.name AS author_name
        FROM article A
        INNER JOIN blog_user BU ON A.blog_user_id = BU.id";
        return $this->pdo->query($query)->fetchAll();
    }

    public function getArticleById(int $articleId)
    {
        $query = 'SELECT * FROM ' . static::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $articleId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

}
