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
}
