<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)

//mettre dans le navigateur: http://localhost:8000/profil?authorId=1

return [
    '' => ['ArticleController', 'showAllArticlesWithAuthors'],
    'profil' => ['ProfilController', 'displayUserArticles', ['authorId']],
    'show' => ['ArticleController', 'showArticle', ['id']],
    'register' => ['UserController', 'register'],
    'login' => ['UserController', 'login'],
    'add' => ['ArticleController', 'addArticle'],
    'edit' => ['ArticleController', 'editArticle', ['id']],
    'delete' => ['ArticleController', 'deleteArticle', ['id']],
];
