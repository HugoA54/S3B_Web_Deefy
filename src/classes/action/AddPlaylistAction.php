<?php

namespace iutnc\deefy\action;
session_start();

use iutnc\deefy\audio\lists\Playlist;
class AddPlaylistAction extends Action {

    public function execute(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<HTML
                <form method="post" action="?action=add-playlist">
                    <label for="playlist-name">Nom de la playlist :</label>
                    <input type="text" id="playlist-name" name="playlist-name" required>
                    <button type="submit">Créer</button>
                </form>
            HTML;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_var($_POST['playlist-name'], FILTER_SANITIZE_STRING);
            $_SESSION['playlist'] = new Playlist($name);
            return <<<HTML
                Playlist créée !<br>
                <a href="?action=add-track">Ajouter une piste</a>
            HTML;        }
        return "Méthode non supportée.";
    }
    
}