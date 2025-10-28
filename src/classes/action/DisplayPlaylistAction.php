<?php
namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        if(!isset($_SESSION['playlist'])) {
            return "<p>La playlist n'existe pas.</p>";
        }
    $rendererPlaylist = new AudioListRenderer($_SESSION['playlist']);
    return $rendererPlaylist->render(1);
}

    }
    

?>