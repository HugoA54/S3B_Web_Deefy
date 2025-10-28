<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;

class AddEmptyPlaylistAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['user'])) {
            return <<<HTML
                <div class="info-message">
                    <p>Vous devez être connecté pour accéder à cette fonctionnalité.</p>
                    <a href="?action=signin" class="btn">Se connecter</a>
                    <a href="?action=add-user" class="btn">Créer un compte</a>
                </div>
            HTML;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<HTML
                <h2>Créer une playlist vide</h2>
                <form method="POST" action="?action=add-empty-playlist">
                    <label for="name">Nom de la playlist :</label><br>
                    <input type="text" name="name" id="name" required><br><br>
                    <input type="submit" value="Créer la playlist">
                </form>
                <br>
                <a href="?action=display-playlists">Retour à mes playlists</a>
            HTML;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($name === '') {
                return "<p>Le nom de la playlist est obligatoire.</p>";
            }

            $user = AuthnProvider::getSignedInUser();
            $repo = DeefyRepository::getInstance();

            $playlistId = $repo->saveEmptyPlaylist($name);

            if ($playlistId !== null) {
                $_SESSION['current_playlist'] = [
                    'id'  => $playlistId,
                    'nom' => $name
                ];

                return <<<HTML
                    <p>La playlist {$name} a été créée avec succès et est maintenant la playlist courante.</p>
                    <a href="?action=display-playlist&id={$playlistId}">Voir la playlist</a> |
                    <a href="?action=add-track">Ajouter une piste</a> |
                    <a href="?action=display-playlists">Retour à mes playlists</a>
                HTML;
            }

            return "<p>Erreur lors de la création de la playlist.</p>";
        }

        return "<p>Erreur de requête.</p>";
    }
}
