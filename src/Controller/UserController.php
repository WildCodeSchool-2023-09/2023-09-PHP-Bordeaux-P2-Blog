<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function login()
    {
        session_start(); // Initialise la session

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $credentials = array_map('trim', $_POST);

            // Valide l'e-mail
            if (!filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "L'adresse e-mail n'est pas valide.";
                $errors[] = $error;
            }

            $userManager = new UserManager();

            $user = $userManager->selectOneByEmail($credentials['email']);

            if ($user && password_verify($credentials['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: /');
                exit();
            } else {
                $error = "L'adresse e-mail ou le mot de passe est incorrect.";
                $errors[] = $error;
                return $this->twig->render('Article/login.html.twig', ['errors' => $errors]);
            }
        }
        return $this->twig->render('Article/login.html.twig');
    }

    public function logout()
    {
        session_start(); // Initialise la session

        // Détruit l'index 'user_id' de la superglobale $_SESSION
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }

        // Redirige vers la page d'accueil
        header('Location: /');
        exit();
    }

    public function register()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $credentials = $_POST;

            // Valide les données du formulaire
            if (empty($credentials['email']) || !filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse e-mail n'est pas valide.";
            }
            if (strlen($credentials['name']) > 255) {
                $errors[] = "Le nom d'utilisateur est trop long.";
            }
            if (strlen($credentials['password']) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            }

            // Pas d'erreurs = inscription
            if (empty($errors)) {
                $userManager = new UserManager();

                // Vérifie si l'e-mail est déjà utilisé
                $existingUser = $userManager->selectOneByEmail($credentials['email']);
                if ($existingUser) {
                    $errors[] = "Cet e-mail est déjà utilisé par un autre utilisateur.";
                } else {
                    // Insert l'utilisateur
                    if ($userManager->insert($credentials)) {
                        // Récupère l'utilisateur
                        $user = $userManager->selectOneByEmail($credentials['email']);

                        // Crée une variable de session pour l'utilisateur
                        $_SESSION['user_id'] = $user['id'];

                        // Redirige vers la page du profil de l'utilisateur nouvellement inscrit
                        header('Location: /profil?authorId=' . $user['id']);
                        exit();
                    } else {
                        $errors[] = "Une erreur est survenue lors de l'enregistrement de l'utilisateur.";
                    }
                }
            }
        }

        return $this->twig->render('Article/register.html.twig', ['errors' => $errors]);
    }
}
