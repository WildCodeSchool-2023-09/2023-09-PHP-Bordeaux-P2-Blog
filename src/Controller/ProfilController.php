<?php

namespace App\Controller;

use App\Model\ProfilManager;

class ProfilController extends AbstractController
{
    public function displayUserArticles()
    {
        session_start();

        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            // Redirige login si pas connecté
            header('Location: /login');
            exit();
        }

        // Récupére ID de l'utilisateur connecté
        $authorId = (int)$_SESSION['user_id'];

        $profilManager = new ProfilManager();
        $articles = $profilManager->getArticlesByUserId($authorId);

        return $this->twig->render('Article/profil.html.twig', ['articles' => $articles]);
    }
}
