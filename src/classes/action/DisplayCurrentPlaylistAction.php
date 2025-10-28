<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListRenderer;

class DisplayCurrentPlaylistAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['current_playlist'])) {
            return <<<HTML
                <p>Aucune playlist courante n’est sélectionnée.</p>
                <a href="?action=display-playlists">Voir mes playlists</a> |
                <a href="?action=add-empty-playlist">Créer une playlist</a>
            HTML;
        }

        $playlistInfo = $_SESSION['current_playlist'];
        $playlistId = (int) $playlistInfo['id'];
        $playlistName = $playlistInfo['nom'];

        $repo = DeefyRepository::getInstance();
        $playlist = $repo->findPlaylistById($playlistId);

        if ($playlist === null) {
            return <<<HTML
                <p>La playlist courante (ID {$playlistId}) n’existe plus en base.</p>
                <a href="?action=display-playlists">Retour à mes playlists</a>
            HTML;
        }
        $renderer = new AudioListRenderer($playlist);
        $html = "<h2>Playlist courante : {$playlistName}</h2>";
        $html .= $renderer->render(1);

        $html .= <<<HTML
            <br>
            <a href="?action=add-track">Ajouter une piste</a> |
            <a href="?action=display-playlists">Mes playlists</a> |
            <a href="?action=default">Accueil</a>
        HTML;

        return $html;
    }
}
