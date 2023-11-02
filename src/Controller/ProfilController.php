<?php

namespace App\Controller;

use App\Model\ProfilManager;

class ProfilController extends AbstractController
{
    public function displayUserArticles(int $authorId)
    {
        session_start();

        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            // sinon
            header('Location: /login');
            exit();
        }

        $authorId = $_GET['authorId'];

        $profilManager = new ProfilManager();
        $articles = $profilManager->getArticlesByUserId($authorId);

        return $this->twig->render('Article/profil.html.twig', ['articles' => $articles]);
    }
}
