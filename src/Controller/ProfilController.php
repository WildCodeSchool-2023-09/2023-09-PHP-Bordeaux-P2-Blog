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
            $articles = $articleManager->getAllArticles();

            $articles = array_map(function ($article) {
                if (isset($article['categories'])) {
                    $article['categories'] = explode(',', $article['categories']);
                }
                return $article;
            }, $articles);

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
            $errors = [];
            $data = [
                'name' => $_POST['name'],
                'password' => $_POST['password'],
                'email' => $_POST['email'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
            ];

            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Please upload a valid image.';
            } else {
                $fileInfo = pathinfo($_FILES['image']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($extension, $allowedExtensions)) {
                    $uploadDir = __DIR__ . '/../../public/assets/images/uploaded/';

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $uploadPath = $uploadDir . uniqid('profile_image_') . '.' . $extension;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $data['image'] = '/assets/images/uploaded/' . basename($uploadPath);
                    } else {
                        $errors[] = 'An error occurred while uploading the image.';
                    }
                } else {
                    $errors[] = 'Invalid image file format. Please use JPG, JPEG, PNG, or GIF.';
                }
            }

            if (!empty($errors)) {
                echo $this->twig->render('Error/index.html.twig', ['message' => implode('<br>', $errors)]);
                exit();
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
