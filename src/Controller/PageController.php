<?php

namespace App\Controller;

use App\Model\ArticleManager;

class PageController extends AbstractController
{
    public function confidentialite(): string
    {
        return $this->twig->render('Home/confidentialite.html.twig');
    }

    public function propos(): string
    {
        return $this->twig->render('Home/propos.html.twig');
    }
}
