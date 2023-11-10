<?php

namespace App\Controller;

use App\Model\CommentManager;

class CommentController extends AbstractController
{
    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $articleId = $_POST['article_id'];
            $content = $_POST['content'];

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

    public function deleteComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $commentId = $_POST['comment_id'];
            $commentManager = new CommentManager();
            $comment = $commentManager->selectOneById($commentId);

            if ($_SESSION['user_id'] === $comment['blog_user_id']) {
                $success = $commentManager->deleteComment($commentId, $_SESSION['user_id']);
                if ($success) {
                    // Vérifier si 'article_id' est présent dans $_POST
                    if (isset($_POST['article_id'])) {
                        $articleId = $_POST['article_id'];
                        header('Location: /article/show?id=' . $articleId);
                        exit();
                    }
                }
            }
        } else {
            // L'utilisateur n'est pas connecté ou la méthode n'est pas POST
            header('Location: /login');
            exit();
        }
    }
}
