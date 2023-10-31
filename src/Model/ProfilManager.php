<?php

namespace App\Model;

class ProfilManager extends AbstractManager
{
    public const TABLE = 'article';

    public function getArticlesByUserId(int $userId): array
    {
        $query = 'SELECT A.title, A.content, A.date, A.image, BU.name AS author_name
                  FROM article A
                  JOIN blog_user BU ON A.blog_user_id = BU.id
                  WHERE A.blog_user_id = :userId';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
