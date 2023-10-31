<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function login()
    {
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
        // Détruit l'index 'user_id' de la superglobale $_SESSION
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }

        // Redirige page accueil
        header('Location: /');
        exit();
    }

    public function register()
    {
        $errors = []; // tableau pour stocker les erreurs

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupére les données du formulaire
            $credentials = $_POST;

            // Valide les données du formulaire
            if (empty($credentials['email']) || !filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse e-mail n'est pas valide.";
            }
            if (empty($credentials['password'])) {
                $errors[] = "Le mot de passe est requis.";
            }

            // Pas d'erreurs = inscription
            if (empty($errors)) {
                $userManager = new UserManager();
                if ($userManager->insert($credentials)) {
                    // Récupére l'utilisateur fraîchement inscrit
                    $user = $userManager->selectOneByEmail($credentials['email']);

                    // Crée une variable de session pour l'utilisateur
                    $_SESSION['user_id'] = $user['id'];

                    return $this->twig->render('Article/index.html.twig');
                }
            } else {
                // erreurs = vue
                return $this->twig->render('Article/register.html.twig', ['errors' => $errors]);
            }
        }

        return $this->twig->render('Article/register.html.twig');
    }
}











    //         if (empty($errors)) {
    //             $userManager = new UserManager();
    //             if ($userManager->insert($credentials)) {
    //                 return $this->login();
    //             }
    //         } else {
    //             // erreurs = vue
    //             return $this->twig->render('Article/register.html.twig', ['errors' => $errors]);
    //         }
    //     }

    //     return $this->twig->render('Article/register.html.twig');
    // }}