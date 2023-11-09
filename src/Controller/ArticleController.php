<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\CommentManager;

class ArticleController extends AbstractController
{
    public function showAllArticles()
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();
        echo $this->twig->render('Home/index.html.twig', ['articles' => $articles]);
    }

    public function showAllArticlesByUserID($userId)
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getArticlesByUserId($userId);

        echo $this->twig->render('profil.html.twig', ['articles' => $articles]);
    }

    public function showArticleById($articleId)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);

        $commentManager = new CommentManager();
        $comments = $commentManager->getCommentsByArticleId($articleId);
        echo $this->twig->render('Article/show.html.twig', ['article' => $article, 'comments' => $comments]);
    }

    public function addArticle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Valide les données du formulaire
            $title = $_POST['title'];
            $content = $_POST['content'];
            $image = $_POST['image'];

            // Vérifie si l'utilisateur est connecté
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];

                $data = [
                    'title' => $title,
                    'content' => $content,
                    'image' => $image,
                    'blog_user_id' => $userId,
                ];

                $articleManager = new ArticleManager();
                $articleManager->addArticle($data);
                // Redirige l'utilisateur vers la page de son profil
                header('Location: /profil');
            } else {
                // L'utilisateur n'est pas connecté=>vers la page de connexion.
                header('Location: /login');
            }
        }

        echo $this->twig->render('Article/add.html.twig');
    }


    public function editArticleById($articleId)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);

        if (!$article) {
            // cas où l'article n'existe pas => vers une page d'erreur à faire
        }

        // Vérifie si l'utilisateur est connecté et est l'auteur de l'article
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $article['blog_user_id']) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Valide les données du formulaire
                $title = $_POST['title'];
                $content = $_POST['content'];
                $image = $_POST['image'];

                $data = [
                    'title' => $title,
                    'content' => $content,
                    'image' => $image,
                ];

                $articleManager->editArticle($articleId, $data);

                // Redirige l'utilisateur vers sa page de profil
                header('Location: /profil');
                exit();
            }

            echo $this->twig->render('edit.html.twig', ['article' => $article]);
        } else {
            // L'utilisateur n'est pas autorisé à éditer cet article => page d'erreur à faire
        }
    }


    public function deleteArticleById($articleId)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);

        if (!$article) {
            // cas où l'article n'existe pas => vers une page d'erreur à faire
        }

        // Vérifie si l'utilisateur est connecté et est l'auteur de l'article
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $article['blog_user_id']) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $articleManager->deleteArticle($articleId);
                // Redirige l'utilisateur vers sa page de profil
                header('Location: /profil');
                exit();
            }

            echo $this->twig->render('delete.html.twig', ['article' => $article]);
        } else {
            // L'utilisateur n'est pas autorisé à éditer cet article => page d'erreur à faire
        }
    }
}
