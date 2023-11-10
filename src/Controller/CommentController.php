<?php

namespace App\Controller;

use App\Model\CommentManager;
use Exception;

class CommentController extends AbstractController
{
    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $articleId = $_POST['article_id'];
            $contentKey = 'content_' . $articleId;
            $content = $_POST[$contentKey];

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
                } else {
                    // Gérer l'erreur d'ajout du commentaire
                    // Vous pourriez vouloir consigner cette erreur et/ou afficher un message à l'utilisateur
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentId = $_POST['comment_id'];

            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];

                $commentManager = new CommentManager();

                try {
                    if ($commentManager->isUserCommentOwner($commentId, $userId)) {
                        $success = $commentManager->deleteComment($commentId, $userId);
                        if ($success) {
                            // Redirection vers la page précédente ou la page de l'article
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                            exit();
                        } else {
                            // Gérer l'échec de la suppression du commentaire
                        }
                    } else {
                        // L'utilisateur n'est pas autorisé à supprimer ce commentaire
                        throw new Exception("Vous n'avez pas la permission de supprimer ce commentaire.");
                    }
                } catch (Exception $e) {
                    // Gérer l'exception, par exemple en affichant un message d'erreur à l'utilisateur
                    // et consigner l'erreur si nécessaire
                }
            } else {
                // L'utilisateur n'est pas connecté
                header('Location: /login');
                exit();
            }
        }

        // Si la méthode n'est pas POST, rediriger vers l'accueil ou afficher une erreur
        header('Location: /');
        exit();
    }
}
