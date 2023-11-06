<?php

namespace App\Controller;

use App\Model\ArticleManager;

class ArticleController extends AbstractController
{
    public function showAllArticlesWithAuthors(): string
    {
        // session_start();
        // $this->checkSessionUser();
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticlesWithAuthors();
        return $this->twig->render('Article/index.html.twig', ['articles' => $articles]);
    }

    public function showArticle(int $id): string
    {
        session_start();
        $this->checkSessionUser();
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($id);
        return $this->twig->render('Article/showArticle.html.twig', ['article' => $article]);
    }

    public function addArticle(): string
    {
        session_start();
        $this->checkSessionUser();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $image = $_POST['image'];
            $userId = $_SESSION['user_id'];

            $articleManager = new ArticleManager();
            $articleManager->insert([
                'title' => $title,
                'content' => $content,
                'image' => $image,
                'blog_user_id' => $userId
            ]);

            // Redirige vers la page de l'article nouvellement créé
            header('Location: /show?id=' . $articleManager->getLastInsertedId());
            exit();
        }
        return $this->twig->render('Article/addArticle.html.twig');
    }

    public function editArticle(int $id): string
    {
        session_start();
        $this->checkSessionUser();
        $user = $this->user;
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($id);
        if (!$user || !$article || $article['blog_user_id'] !== $user['id']) {
            ///////////// Redirige message d'erreur A FAIRE
            return $this->twig->render('error/permission_denied.html.twig');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $image = $_POST['image'];
            // Met à jour l'article dans BDD
            $articleManager->update([
                'id' => $article['id'],
                'title' => $title,
                'content' => $content,
                'image' => $image,
            ]);
            // Redirige vers la page de l'article après l'édition
            header('Location: /article/' . $id);
            exit();
        }
        return $this->twig->render('Article/editArticle.html.twig', ['article' => $article]);
    }

    public function deleteArticle(int $id): string
    {
        session_start();
        $this->checkSessionUser();
        $user = $this->user;
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($id);
        if (!$user || !$article || $article['blog_user_id'] !== $user['id']) {
            ///////////// Redirige message d'erreur A FAIRE
            return $this->twig->render('error/permission_denied.html.twig');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Supprime l'article de la base de données
            $articleManager->delete($id);
            // Redirige vers accueil
            header('Location: /');
            exit();
        }
        return $this->twig->render('Article/deleteArticle.html.twig', ['article' => $article]);
    }
}
