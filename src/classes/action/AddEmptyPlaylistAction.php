<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;

class AddEmptyPlaylistAction extends Action
{
    public function execute(): string
    {
        $html = "<h2>Créer une playlist vide</h2>";
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<HTML
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
            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);

            if ($name === '') {
                return "<p>Le nom de la playlist est obligatoire.</p>";
            }


            $user = AuthnProvider::getSignedInUser();
            $repo = DeefyRepository::getInstance();
            $pdo = $repo->getPDO();

            $ok = $repo->saveEmptyPlaylist($name);

            if ($ok) {
                $playlistId = $repo->saveEmptyPlaylist($name);

                if ($playlistId !== null) {
                    $_SESSION['current_playlist'] = [
                        'id' => $playlistId,
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


        }
        return $html;

    }

}
