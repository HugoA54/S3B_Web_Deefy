<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class SigninAction extends Action {

    public function execute(): string {

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
        }

        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            try {
                $user = AuthnProvider::signin($email, $password);

                $_SESSION['user'] = $user;

                return "<p>Authentification réussie. Bienvenue, {$user['email']} !</p>";
            } 
 catch (AuthnException $e) {
    return "{$e->getMessage()}";
}

        }
        return "<p>Méthode HTTP non supportée. Utilisez GET ou POST.</p>";
    }
}
