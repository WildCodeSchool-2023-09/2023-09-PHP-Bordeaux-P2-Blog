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


        $articles = array_map(function ($article) {
            if (isset($article['categories'])) {
                $article['categories'] = explode(',', $article['categories']);
            }
            return $article;
        }, $articles);


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


    public function editArticleById($articleId)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);

        if (!$article) {
            return $this->renderError('L\'article n\'existe pas.');
        }

        if (!$this->isUserAuthorized($_SESSION['user_id'], $article['blog_user_id'])) {
            return $this->renderError('Vous n\'êtes pas autorisé à éditer cet article. ' .
                'Vous devez être connecté et être l\'auteur de l\'article.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processArticleUpdate($articleId, $_POST);
            header('Location: /profil');
            exit();
        }

        return $this->renderEditForm($articleId);
    }

    private function renderError($message)
    {
        return $this->twig->render('Error/index.html.twig', ['message' => $message]);
    }

    private function isUserAuthorized($userId, $articleUserId)
    {
        return isset($userId) && $userId === $articleUserId;
    }

    private function processArticleUpdate($articleId, $postData)
    {
        $articleManager = new ArticleManager();
        $categoryManager = new CategoryManager();

        $data = [
            'title' => $postData['title'],
            'content' => $postData['content'],
        ];

        // Vérifie si la clé 'image' existe dans $postData avant de l'ajouter à $data
        if (isset($postData['image'])) {
            $data['image'] = $postData['image'];
        }

        $articleManager->editArticle($articleId, $data);
        $this->updateArticleCategories($articleId, $postData, $categoryManager);
    }


    private function updateArticleCategories($articleId, $postData, $categoryManager)
    {
        $categoryManager->deleteAllCategoriesFromArticle($articleId);

        if (!empty($postData['new_category'])) {
            $this->addNewCategory($articleId, trim($postData['new_category']), $categoryManager);
        } elseif (isset($postData['categories']) && is_array($postData['categories'])) {
            foreach ($postData['categories'] as $categoryId) {
                $categoryManager->addCategoryToArticle($articleId, $categoryId);
            }
        }
    }

    private function addNewCategory($articleId, $newCategoryName, $categoryManager)
    {
        if (!$categoryManager->doesCategoryExist($newCategoryName)) {
            $newCategoryId = $categoryManager->addCategory($newCategoryName);
            $categoryManager->addCategoryToArticle($articleId, $newCategoryId);
        }
    }

    private function renderEditForm($articleId)
    {
        $articleManager = new ArticleManager();

        $categoryManager = new CategoryManager();
        $allCategories = $categoryManager->selectAll();
        $currentCategories = $categoryManager->getCategoriesByArticleId($articleId);

        return $this->twig->render('Article/edit.html.twig', [
            'article' => $articleManager->getArticleById($articleId),
            'userId' => $_SESSION['user_id'],
            'allCategories' => $allCategories,
            'currentCategories' => array_column($currentCategories, 'id')
        ]);
    }


    public function deleteArticleById($articleId)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);

        if (!$article) {
            return $this->twig->render('Error/index.html.twig', [
                'message' => 'L\'article n\'existe pas.'
            ]);
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

            return $this->twig->render('Article/delete.html.twig', [
                'article' => $article,
                'userId' => $userId
            ]);
        } else {
            return $this->twig->render('Error/index.html.twig', [
                'message' => 'Vous n\'êtes pas autorisé à supprimer cet article.
                Vous devez être connecté et être l\'auteur de l\'article.'
            ]);
        }
    }

    public function searchByCategoryName(string $searchTerm)
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getArticlesByCategoryName($searchTerm);

        if (empty($articles)) {
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            return $this->twig->render('Error/index.html.twig', [
                'message' => 'Aucun article trouvé pour la catégorie : ' . $searchTerm,
                'userId' => $userId
            ]);
        }
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        return $this->twig->render('Article/search_results.html.twig', [
            'articles' => $articles,
            'searchTerm' => $searchTerm,
            'userId' => $userId
        ]);
    }
}
// A newline has been added here at the end of the file
