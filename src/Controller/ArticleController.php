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
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticlesWithAuthors();

        return $this->twig->render('Article/index.html.twig', ['articles' => $articles]);
    }
}
