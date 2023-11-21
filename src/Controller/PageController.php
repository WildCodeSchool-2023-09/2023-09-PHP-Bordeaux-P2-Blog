<?php

namespace App\Controller;

use App\Model\ArticleManager;

class PageController extends AbstractController
{
    public function confidentialite(): string
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        return $this->twig->render('Home/confidentialite.html.twig', ['userId' => $userId]);
    }

    public function propos(): string
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        return $this->twig->render('Home/propos.html.twig', ['userId' => $userId]);
    }
}
