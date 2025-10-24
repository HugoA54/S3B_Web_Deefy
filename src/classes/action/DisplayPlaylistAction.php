<?php
namespace iutnc\deefy\action;
session_start();

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