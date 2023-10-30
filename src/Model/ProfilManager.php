<?php

namespace App\Model;

class ProfilManager extends AbstractManager
{
    public const TABLE = 'Article';

    public function getArticlesByUserId(int $userId): array
    {
        $query = 'SELECT A.TitreArticle, A.ContenuArticle, A.DatePublication, A.Image, U.NomUtilisateur
                  FROM Articles A
                  JOIN Utilisateurs U ON A.IDAuteur= U.id
                  WHERE A.IDAuteur = :userId';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
