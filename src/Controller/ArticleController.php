<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\CategoryManager;
use App\Model\CommentManager;

class ArticleController extends AbstractController
{
    public function showAllArticles()
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        return $this->twig->render('Home/index.html.twig', ['articles' => $articles, 'userId' => $userId]);
    }

    public function showAllArticlesByUserID($userId)
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getArticlesByUserId($userId);

        return $this->twig->render('profil.html.twig', ['articles' => $articles]);
    }

    public function showArticleById(int $articleId)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $categoryManager = new CategoryManager();
        $categories = $categoryManager->getCategoriesByArticleId($articleId);

        $commentManager = new CommentManager();
        $comments = $commentManager->getCommentsByArticleId($articleId);

        return $this->twig->render('Article/show.html.twig', [
            'article' => $article, 
            'comments' => $comments, 
            'userId' => $userId,
            'categories' => $categories
        ]);
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
                $articleId = $articleManager->addArticle($data);
                $categoryManager = new CategoryManager();

                // Vérifie si une nouvelle catégorie est fournie
                if (!empty($_POST['new_category'])) {
                    // Ajouter la nouvelle catégorie
                    $newCategoryId = $categoryManager->addCategory($_POST['new_category']);
                    // Associer la nouvelle catégorie à l'article
                    $categoryManager->addCategoryToArticle($articleId, $newCategoryId);
                }
    
                // Gestion des catégories existantes
                if (isset($_POST['categories'])) {
                    foreach ($_POST['categories'] as $categoryId) {
                        $categoryManager->addCategoryToArticle($articleId, $categoryId);
                    }
                }
                header('Location: /profil');
            } else {
                header('Location: /login');
            }
        }

        // Affichage du formulaire avec les catégories
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        return $this->twig->render('Article/add.html.twig', ['userId' => $userId, 'categories' => $categories]);
    }


    public function editArticleById($articleId)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);

        if (!$article) {
            echo $this->twig->render('Error/index.html.twig', ['message' =>
            'L\'article n\'existe pas.']);
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

            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            echo $this->twig->render('Article/edit.html.twig', ['article' => $article, 'userId' => $userId]);
        } else {
            echo $this->twig->render('Error/index.html.twig', ['message' =>
            'Vous n\'êtes pas autorisé à éditer cet article. 
            Vous devez être connecté et être l\'auteur de l\'article.']);
        }
    }

    public function deleteArticleById($articleId)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);

        if (!$article) {
            echo $this->twig->render('Error/index.html.twig', ['message' =>
            'L\'article n\'existe pas.']);
        }

        // Vérifie si l'utilisateur est connecté et est l'auteur de l'article
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $article['blog_user_id']) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $articleManager->deleteArticle($articleId);
                // Redirige l'utilisateur vers sa page de profil
                header('Location: /profil');
                exit();
            }
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            echo $this->twig->render('Article/delete.html.twig', ['article' => $article, 'userId' => $userId]);
        } else {
            echo $this->twig->render('Error/index.html.twig', ['message' =>
            'Vous n\'êtes pas autorisé à supprimer cet article.
            Vous devez être connecté et être l\'auteur de l\'article.']);
        }
    }
}
// Add a newline at the end of the file
