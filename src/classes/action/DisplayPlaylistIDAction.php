<?php

namespace iutnc\deefy\action;
session_start();

use iutnc\deefy\auth\Authz;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistIDAction extends Action {

    public function execute(): string {
        
        if (!isset($_GET['id'])) {
            return $this->formulaire();
        }
        
        $playlistId = (int) $_GET['id'];
        
        $authz = new Authz();
        if (!$authz->checkPlaylistOwner($playlistId)) {
            return <<<HTML
                <div class='error'>
                    Accès refusé : vous n'avez pas les droits pour afficher cette playlist.
                </div>
                {$this->formulaire()}
            HTML;
        }
        
        $repo = DeefyRepository::getInstance();
        $playlist = $repo->findPlaylistById($playlistId);
        
        if ($playlist === null) {
            return <<<HTML
                <div class='error'>
                    Playlist non trouvée (ID: {$playlistId})
                </div>
                {$this->formulaire()}
            HTML;
        }
        
        $renderer = new AudioListRenderer($playlist);
        $html = $renderer->render(1);
        
        $html .= <<<HTML
            <br><br>
            <a href="?action=display-playlist">Afficher une autre playlist</a> | 
            <a href="?action=default">Retour à l'accueil</a>
        HTML;
        
        return $html;
    }
    
    private function formulaire(): string {
        return <<<HTML
            <h2>Afficher une playlist</h2>
            <form method="GET" action="">
                <input type="hidden" name="action" value="display-playlist">
                <label for="playlist-id">ID de la playlist :</label>
                <input type="number" id="playlist-id" name="id" min="1" required>
                <button type="submit">Afficher</button>
            </form>
            <br>
            <a href="?action=default">Retour à l'accueil</a>
        HTML;
    }
}
?>