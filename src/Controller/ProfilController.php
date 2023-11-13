<?php

namespace App\Controller;

use App\Model\ProfilManager;
use App\Model\ArticleManager;

class ProfilController extends AbstractController
{
    public function profil()
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            $profilManager = new ProfilManager();
            $user = $profilManager->getUserById($userId);
            $nombreNotifications = $profilManager->getNotificationsNbr();

            // Récupére les articles de l'utilisateur
            $articleManager = new ArticleManager();
            $articles = $articleManager->getArticlesByUserId($userId);

            echo $this->twig->render('Blog_user/profil.html.twig', ['user' => $user, 'articles' => $articles, 'nombreNotifications' => $nombreNotifications ]);
        } else {
            // L'utilisateur n'est pas connecté => page de connexion
            header('Location: /login');
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $profilManager = new ProfilManager();
            $user = $profilManager->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // L'utilisateur est authentifié, enregistre les info dans la session.
                $_SESSION['user_id'] = $user['id'];
                // Redirige l'utilisateur vers sa page de profil
                header('Location: /profil');
                exit();
            } else {
                // L'authentification a échoué => page d'erreur à faire
            }
        }

        echo $this->twig->render('Blog_user/login.html.twig');
    }

    public function logout()
    {
        // Déconnecte l'utilisateur en supprimant les informations de session.
        session_unset();
        session_destroy();
        // Redirige l'utilisateur vers la page d'accueil
        header('Location: /');
        exit();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Valide les données du formulaire.

            $data = [
                'name' => $_POST['name'],
                'password' => $_POST['password'],
                'email' => $_POST['email'],
                'image' => $_POST['image'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
            ];

            $profilManager = new ProfilManager();
            $profilManager->addUser($data);
            header('Location: /profil');
            exit();
        }
        echo $this->twig->render('Blog_user/register.html.twig');
    }
}
