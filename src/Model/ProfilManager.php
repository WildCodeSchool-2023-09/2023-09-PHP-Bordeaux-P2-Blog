<?php

namespace App\Model;

class ProfilManager extends AbstractManager
{
    public const TABLE = 'blog_user';

    public function getUserById(int $userId)
    {
        $query = 'SELECT * FROM ' . static::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function getUserByEmail(string $email)
    {
        $query = 'SELECT * FROM ' . static::TABLE . ' WHERE email = :email';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();

        return $statement->fetch();
    }

    public function addUser(array $data)
    {
        $query = 'INSERT INTO ' . static::TABLE .
            ' (name, password, email, image, title, description) 
        VALUES (:name, :password, :email, :image, :title, :description)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $data['name']);
        $statement->bindValue(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $statement->bindValue(':email', $data['email']);
        $statement->bindValue(':image', $data['image']);
        $statement->bindValue(':title', $data['title']);
        $statement->bindValue(':description', $data['description']);
        $statement->execute();
    }
}
