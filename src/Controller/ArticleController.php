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

                if (!empty($_POST['new_category'])) {
                    $newCategoryId = $categoryManager->addCategory($_POST['new_category']);
                    $categoryManager->addCategoryToArticle($articleId, $newCategoryId);
                }
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


    public function editArticleById($articleId) {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);
    
        if (!$article) {
            echo $this->twig->render('Error/index.html.twig', ['message' => 'L\'article n\'existe pas.']);
            exit();
        }
    
        $categoryManager = new CategoryManager();
        $allCategories = $categoryManager->selectAll();
    
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $article['blog_user_id']) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $image = $_POST['image'];
    
                $data = [
                    'title' => $title,
                    'content' => $content,
                    'image' => $image,
                ];
    
                $articleManager->editArticle($articleId, $data);
    
                // Supprime toutes les associations de catégories existantes pour cet article
                $categoryManager->deleteAllCategoriesFromArticle($articleId);
    
                // Traiter et ajouter la nouvelle catégorie
                if (!empty($_POST['new_category'])) {
                    $newCategoryName = trim($_POST['new_category']);
                    if (!$categoryManager->doesCategoryExist($newCategoryName)) {
                        $newCategoryId = $categoryManager->addCategory($newCategoryName);
                        $categoryManager->addCategoryToArticle($articleId, $newCategoryId);
                    }
                } else if (isset($_POST['categories']) && is_array($_POST['categories'])) {
                    // Traiter les catégories existantes sélectionnées
                    foreach ($_POST['categories'] as $categoryId) {
                        $categoryManager->addCategoryToArticle($articleId, $categoryId);
                    }
                }
    
                header('Location: /profil');
                exit();
            }
    
            $userId = $_SESSION['user_id'];
    
            // Récupère les catégories actuelles pour l'affichage dans le formulaire
            $currentCategories = $categoryManager->getCategoriesByArticleId($articleId);
    
            return $this->twig->render('Article/edit.html.twig', [
                'article' => $article,
                'userId' => $userId,
                'allCategories' => $allCategories,
                'currentCategories' => array_column($currentCategories, 'id')
            ]);
        } else {
            echo $this->twig->render('Error/index.html.twig', ['message' => 'Vous n\'êtes pas autorisé à éditer cet article. Vous devez être connecté et être l\'auteur de l\'article.']);
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
