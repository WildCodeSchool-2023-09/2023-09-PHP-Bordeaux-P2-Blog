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
        // TODO RETURN LAST ARTICLE ID
        return $this->pdo->lastInsertId();// rajouter par côme
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
        $query = "SELECT A.*, BU.name AS author_name, COUNT(C.id) AS comment_count
                FROM article A
                INNER JOIN blog_user BU ON A.blog_user_id = BU.id
                LEFT JOIN commentary C ON A.id = C.article_id
                GROUP BY A.id, BU.name";

        return $this->pdo->query($query)->fetchAll();
    }

    public function getArticleById(int $articleId)
    {
        $query = "SELECT A.*, BU.name AS author_name
    FROM " . static::TABLE . " AS A 
    LEFT JOIN blog_user BU ON A.blog_user_id = BU.id 
    WHERE A.id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $articleId, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAllArticlesWithComments()
    {
        // D'abord, récupérez tous les articles
        $articles = $this->getAllArticles();

        // Ensuite, pour chaque article, récupérez les commentaires
        foreach ($articles as $key => $article) {
            $articleId = $article['id'];
            $commentQuery = "SELECT * FROM commentary WHERE article_id = :articleId";
            $statement = $this->pdo->prepare($commentQuery);
            $statement->bindValue(':articleId', $articleId, \PDO::PARAM_INT);
            $statement->execute();
            $comments = $statement->fetchAll();

            // Ajoutez les commentaires au tableau de l'article
            $articles[$key]['comments'] = $comments;
        }

        return $articles;
    }

    public function getCategoriesByArticleId($articleId)// rajouter par côme
    {
        $query = 'SELECT C.name FROM category C
                JOIN article_category AC ON C.id = AC.category_id
                WHERE AC.article_id = :articleId';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':articleId', $articleId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addCategory(int $articleId, int $categoryId)// rajouter par côme
    {
        $query = "INSERT INTO article_category (article_id, category_id) VALUES (:articleId, :categoryId)";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':articleId', $articleId, \PDO::PARAM_INT);
        $statement->bindValue(':categoryId', $categoryId, \PDO::PARAM_INT);
        $statement->execute();
    }
}
