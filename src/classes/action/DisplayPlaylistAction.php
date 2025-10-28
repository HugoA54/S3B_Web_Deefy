<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\Authz;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action {

    public function execute(): string {

        if (!isset($_GET['id'])) {
            return "<p>Aucune playlist spécifiée.</p><a href='?action=mes-playlists'>Retour</a>";
        }

        $playlistId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        if ($playlistId <= 0) {
            return "<p>ID de playlist invalide.</p>";
        }

        $authz = new Authz();
        if (!$authz->checkPlaylistOwner($playlistId)) {
            return <<<HTML
                <div class='error'>
                    Accès refusé : vous n'avez pas les droits pour afficher cette playlist.
                </div>
                <a href='?action=mes-playlists'>Retour à mes playlists</a>
            HTML;
        }

        // Récupère la playlist depuis la BDD
        $repo = DeefyRepository::getInstance();
        $playlist = $repo->findPlaylistById($playlistId);

        if ($playlist === null) {
            return <<<HTML
                <div class='error'>
                    Playlist non trouvée (ID: {$playlistId})
                </div>
                <a href='?action=mes-playlists'>Retour</a>
            HTML;
        }

        $_SESSION['current_playlist'] = [
            'id' => $playlistId,
            'nom' => $playlist->__get('nom')
        ];

        // Affichage via le renderer
        $renderer = new AudioListRenderer($playlist);
        $html = $renderer->render(1);

        $html .= <<<HTML
            <br><br>
            <a href="?action=add-track">Ajouter une piste à cette playlist</a> |
            <a href="?action=mes-playlists">Retour à mes playlists</a> |
            <a href="?action=default">Accueil</a>
        HTML;

        return $html;
    }
}
