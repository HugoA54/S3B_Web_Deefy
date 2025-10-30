<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class ChangePasswordAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['user'])) {
            return <<<HTML
                <div class="info-message">
                    <p>Vous devez être connecté pour changer votre mot de passe.</p>
                    <a href="?action=signin" class="btn">Se connecter</a>
                </div>
            HTML;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<HTML
                <h2>Changer le mot de passe</h2>
                <form method="POST" action="?action=change-password">
                    <label for="old_password">Ancien mot de passe :</label><br>
                    <input type="password" name="old_password" id="old_password" required><br><br>

                    <label for="new_password">Nouveau mot de passe :</label><br>
                    <input type="password" name="new_password" id="new_password" required><br><br>

                    <label for="confirm_password">Confirmer le nouveau mot de passe :</label><br>
                    <input type="password" name="confirm_password" id="confirm_password" required><br><br>

                    <input type="submit" value="Changer le mot de passe">
                </form>
                <br>
                <a href="?action=user-stats">Retour au profil</a>
            HTML;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $_POST['old_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($new !== $confirm) {
                return "Les nouveaux mots de passe ne correspondent pas.";
            }

            if (strlen($new) < 10) {
                return "Le nouveau mot de passe doit contenir au moins 10 caractères.";
            }

            $user = $_SESSION['user'];
            $repo = DeefyRepository::getInstance();
            $pdo = $repo->getPDO();

            $stmt = $pdo->prepare("SELECT passwd FROM user WHERE id = ?");
            $stmt->execute([$user['id']]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$data || !password_verify($old, $data['passwd'])) {
                return "Ancien mot de passe incorrect.";
            }

            $newHash = password_hash($new, PASSWORD_BCRYPT);
            $update = $pdo->prepare("UPDATE user SET passwd = ? WHERE id = ?");
            $update->execute([$newHash, $user['id']]);

            return <<<HTML
                <p>Mot de passe mis à jour avec succès.</p>
                <a href="?action=user-stats">Retour au profil</a>
            HTML;
        }

        return "<p>Erreur de requête.</p>";
    }
}
