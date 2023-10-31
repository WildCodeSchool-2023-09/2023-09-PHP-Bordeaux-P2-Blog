<?php

namespace App\Controller;

use App\Model\ProfilManager;

class ProfilController extends AbstractController
{
    public function displayUserArticles(int $authorId)
    {
        $profilManager = new ProfilManager();
        $articles = $profilManager->getArticlesByUserId($authorId);

        return $this->twig->render('Article/profil.html.twig', ['articles' => $articles]);
    }
}
