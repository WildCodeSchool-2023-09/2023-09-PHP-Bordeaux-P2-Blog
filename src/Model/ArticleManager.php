<?php

namespace App\Model;

use PDO;

class ArticleManager extends AbstractManager
{
    public const TABLE = 'Articles';

    public function getAllArticlesWithAuthors(): array
    {
        $query = "SELECT A.*, U.NomUtilisateur
        FROM Articles A
        INNER JOIN Utilisateurs U ON A.IDAuteur = U.ID";

        $result = $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
