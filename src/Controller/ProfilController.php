<?php

namespace App\Controller;

use App\Model\ProfilManager;

class ProfilController extends AbstractController
{
    public function displayUserArticles()
    {
        
        // utilisateur connecté ?
        if (!isset($_SESSION['user_id'])) {
            // Redirige login si pas connecté
            header('Location: /login');
            exit();
        }

        // Récupére ID de l'utilisateur co
        $authorId = (int)$_SESSION['user_id'];

        $profilManager = new ProfilManager();
        $articles = $profilManager->getArticlesByUserId($authorId);

        return $this->twig->render('Article/profil.html.twig', ['articles' => $articles]);
    }
}
