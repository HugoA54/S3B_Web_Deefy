<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

// Action gérant la connexion d'un utilisateur à son compte (déjà crée donc)
class SigninAction extends Action
{

    public function execute(): string
    {
        // Permets à l'utilisateur de rentrer les données nécessaires à l'authentification
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<HTML
            <form method="POST" action="?action=signin">
                <label for="username">Email :</label>
                <input type="email" id="username" name="username" required><br>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required><br>
                <input type="submit" value="Se connecter">
            </form>
            HTML;
        // Tentative de connexion au compte que l'utilisateur a écrit dans le form précédent
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            try {
                // Gestion des erreurs de connexions dans AuthnProvider, qui lance une exception si problème
                $user = AuthnProvider::signin($email, $password);

                $_SESSION['user'] = $user;

                return "<p>Authentification réussie. Bienvenue, {$user['email']} !</p>";
            } catch (AuthnException $e) {
                 $msg = $e->getMessage();

                return <<<HTML
                <div class="info-message">
                    <p>$msg</p>
                    <a href="?action=signin" class="btn">Réessayer</a>
                </div>
                HTML;            }

        }
        return "<p>Méthode HTTP non supportée. Utilisez GET ou POST.</p>";
    }
}

