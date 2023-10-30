<?php

namespace App\Controller;

use App\Model\ArticleManager;

class ArticleController extends AbstractController
{
    /**
     * List all article with author name
     */
    public function showAllArticlesWithAuthors(): string
    {
        $ArticleManager = new ArticleManager();
        $Articles = $ArticleManager->getAllArticlesWithAuthors();

        return $this->twig->render('Article/index.html.twig', ['Articles' => $Articles]);
    }
}
