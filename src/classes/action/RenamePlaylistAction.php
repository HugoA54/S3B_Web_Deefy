<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authz;

class RenamePlaylistAction extends Action
{
    public function execute(): string
    {
        if (!isset($_GET['id'])) return "<p>Aucune playlist sélectionnée.</p>";

        $id = (int) $_GET['id'];
        $authz = new Authz();
        if (!$authz->checkPlaylistOwner($id)) return "<p>Accès refusé.</p>";

        $repo = DeefyRepository::getInstance();
        $pdo = $repo->getPDO();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newName = filter_var(trim($_POST['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $stmt = $pdo->prepare("UPDATE playlist SET nom = ? WHERE id = ?");
            $stmt->execute([$newName, $id]);
            $_SESSION['current_playlist']['nom'] = $newName;
            return "<p>Playlist renommée en <strong>{$newName}</strong>.</p>
                    <a href='?action=display-playlist&id={$id}'>Retour</a>";
        }

        return <<<HTML
            <h2>Renommer la playlist</h2>
            <form method="POST">
                <label for="name">Nouveau nom :</label><br>
                <input type="text" name="name" id="name" required><br><br>
                <input type="submit" value="Renommer">
            </form>
            <a href="?action=display-playlist&id={$id}">Annuler</a>
        HTML;
    }
}
