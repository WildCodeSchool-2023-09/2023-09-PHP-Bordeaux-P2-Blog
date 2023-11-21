<?php

namespace App\Model;

use PDO;

class CommentManager extends AbstractManager
{
    public const TABLE = 'commentary';

    public function addComment(array $data)
    {
        $query = 'INSERT INTO ' .
        self::TABLE .
        ' (content, blog_user_id, article_id) VALUES (:content, :blog_user_id, :article_id)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':content', $data['content'], PDO::PARAM_STR);
        $statement->bindValue(':blog_user_id', $data['blog_user_id'], PDO::PARAM_INT);
        $statement->bindValue(':article_id', $data['article_id'], PDO::PARAM_INT);

        if ($statement->execute()) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function deleteComment(int $commentId)
    {
        $query = 'DELETE FROM ' . self::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $commentId, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function getCommentsByArticleId(int $articleId)
    {
        $query = 'SELECT c.*, u.name AS user_name FROM ' . self::TABLE . ' c 
                  JOIN blog_user u ON c.blog_user_id = u.id 
                  WHERE c.article_id = :article_id ORDER BY c.date DESC';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function editComment(int $commentId, array $data)
    {
        $query = 'UPDATE ' . self::TABLE . ' SET content = :content WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':content', $data['content'], PDO::PARAM_STR);
        $statement->bindValue(':id', $commentId, PDO::PARAM_INT);
        return $statement->execute();
    }
}
