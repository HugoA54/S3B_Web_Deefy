<?php

namespace iutnc\deefy\action;
session_start();

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

            $email = $_POST['username'] ?? '';
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

        // --- 3️⃣ Si autre méthode HTTP ---
        return "<p>Méthode HTTP non supportée. Utilisez GET ou POST.</p>";
    }
}
