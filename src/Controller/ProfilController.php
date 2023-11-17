<?php

namespace App\Controller;

use App\Model\ProfilManager;
use App\Model\ArticleManager;
use App\Model\CommentManager;

class ProfilController extends AbstractController
{
    public function profil()
    {
        if (isset($_SESSION['user_id'])) {
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            $profilManager = new ProfilManager();
            $getUserId = $profilManager->getUserById($userId);
            $nombreNotifications = $profilManager->getNotificationsNbr();

            // Récupère les articles de l'utilisateur
            $articleManager = new ArticleManager();
            $articles = $articleManager->getArticlesByUserId($userId);

            echo $this->twig->render(
                'Blog_user/profil.html.twig',
                ['userId' => $getUserId, 'articles' => $articles, 'nombreNotifications' => $nombreNotifications]
            );
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
                // L'utilisateur est authentifié, enregistre les infos dans la session.
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
                'title' => $_POST['title'],
                'description' => $_POST['description'],
            ];

            // Traitement de l'image de profil
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileInfo = pathinfo($_FILES['image']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($extension, $allowedExtensions)) {
                    // Définir le chemin où le fichier sera stocké
                    $uploadDir = __DIR__ . '/../../public/assets/images/uploaded/';

                    // Créer le dossier de destination s'il n'existe pas
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $uploadPath = $uploadDir . uniqid('profile_image_') . '.' . $extension;

                    // Déplacer le fichier téléchargé vers le dossier de destination
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        // Stocker le chemin relatif du fichier dans les données
                        $data['image'] = '/assets/images/uploaded/' . basename($uploadPath);
                    } else {
                        // Gestion de l'erreur (échec du déplacement du fichier)
                        die('Une erreur est survenue lors du téléchargement du fichier.');
                    }
                } else {
                    // Gestion de l'erreur (extension non autorisée)
                    die('L\'extension du fichier n\'est pas autorisée.');
                }
            }

            $profilManager = new ProfilManager();
            $profilManager->addUser($data);
            header('Location: /profil');
            exit();
        }

        echo $this->twig->render('Blog_user/register.html.twig');
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];

            $profilManager = new ProfilManager();
            $user = $profilManager->getUserByEmail($email);

            if ($user) {
                $profilManager->resetPassword($user['id'], password_hash($newPassword, PASSWORD_DEFAULT));

                $_SESSION['new_password'] = $newPassword;

                echo $this->twig->render('Blog_user/password_reset_success.html.twig', ['newPassword' => $newPassword]);
                exit();
            } else {
                // L'utilisateur n'est pas connecté => page de connexion
                header('Location: /login');
                exit();
            }
        }
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        echo $this->twig->render('Blog_user/forgot_password.html.twig', ['userId' => $userId]);
    }
}
