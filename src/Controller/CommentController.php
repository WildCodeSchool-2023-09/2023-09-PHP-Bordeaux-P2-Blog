<?php

namespace App\Controller;

use App\Model\CommentManager;
use App\Model\ArticleManager;

class CommentController extends AbstractController
{
    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $articleId = $_POST['article_id'];
            $content = $_POST['content_' . $articleId];

            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];

                $data = [
                    'article_id' => $articleId,
                    'blog_user_id' => $userId,
                    'content' => $content,
                ];

                $commentManager = new CommentManager();
                $commentId = $commentManager->addComment($data);

                if ($commentId) {
                    // Redirection vers la page de l'article après l'ajout du commentaire
                    header('Location: /show?id=' . $articleId);
                    exit();
                }
            } else {
                // L'utilisateur n'est pas connecté, redirige vers la page de connexion
                header('Location: /login');
                exit();
            }
        }

        // Si la méthode n'est pas POST, rediriger vers l'accueil ou afficher une erreur
        header('Location: /');
        exit();
    }

    public function deleteCommentById(int $commentId)
    {
        $commentManager = new CommentManager();
        $comment = $commentManager->selectOneById($commentId);

        if (!$comment) {
            return $this->twig->render('Error/index.html.twig', ['message' =>
            'Le commentaire n\'existe pas.']);
        }

        // Récupérer l'article associé au commentaire
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($comment['article_id']);
        // Vérifier si l'utilisateur est connecté et est l'auteur du commentaire ou l'auteur de l'article
        $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
        if ($userID && ($userID === $comment['blog_user_id'] || $userID === $article['blog_user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $success = $commentManager->deleteComment($commentId);
                if ($success) {
                    // Vérifier si 'article_id' est présent dans $_POST
                    if (isset($comment['article_id'])) {
                        $articleId = $comment['article_id'];
                        header('Location: /show?id=' . $articleId);
                        exit();
                    }
                }
            }
            return $this->twig->render('Comment/delete.html.twig', ['comment' => $comment]);
        } else {
            return $this->twig->render('Error/index.html.twig', ['message' =>
            'Vous n\'êtes pas autorisé à supprimer ce commentaire. 
            Vous devez être connecté ou être l\'auteur du commentaire ou de l\'article.']);
        }
    }
}
