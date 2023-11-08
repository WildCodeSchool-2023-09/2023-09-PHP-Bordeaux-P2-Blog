<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'blog_user';

    public function selectOneByEmail(string $email)
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE email = :email";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetch();
        return $user;
    }

    public function insert(array $credentials)
    {
        $query = "INSERT INTO " . self::TABLE . " (email, password, title, description, name) 
                  VALUES (:email, :password, :title, :description, :name)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':email', $credentials['email']);
        $statement->bindValue(':password', password_hash($credentials['password'], PASSWORD_DEFAULT));
        $statement->bindValue(':title', $credentials['title']);
        $statement->bindValue(':description', $credentials['description']);
        $statement->bindValue(':name', $credentials['name']);
        $statement->execute();

        return (int)$this->pdo->lastInsertId();
    }
}
