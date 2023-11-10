<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)

//mettre dans le navigateur: http://localhost:8000/profil?authorId=1

return [
    '' => ['ArticleController', 'showAllArticles'],
    'profil' => ['ProfilController', 'profil'],
    'article' => ['ArticleController', 'index'],
    'article/edit' => ['ArticleController', 'editArticleById', ['id']],
    'show' => ['ArticleController', 'showArticleById', ['id']],
    'article/add' => ['ArticleController', 'addArticle'],
    'article/delete' => ['ArticleController', 'deleteArticleById', ['id']],
    'login' => ['ProfilController', 'login'],
    'logout' => ['ProfilController', 'logout'],
    'register' => ['ProfilController', 'register'],
    'comment/add' => ['CommentController', 'addComment'],
    'comment/delete' => ['CommentController', 'deleteComment'],
    'forgot_password' => ['ProfilController', 'forgotPassword'],
];
