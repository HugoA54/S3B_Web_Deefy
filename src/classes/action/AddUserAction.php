<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class AddUserAction extends Action {

    public function execute(): string {

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<HTML
            <form method="post" action="?action=add-user">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required><br>

                <button type="submit">Créer le compte</button>
            </form>
            HTML;
        }

        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            try {
                AuthnProvider::register($email, $password);
                return "<p>Inscription réussie pour <strong>$email</strong>. Vous pouvez maintenant vous connecter.</p>";
            } catch (AuthnException $e) {
                return$e->getMessage();
            } 
        }

        return "<p>Méthode HTTP non supportée. Utilisez GET ou POST.</p>";
    }
}
