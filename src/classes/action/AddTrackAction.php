<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

class AddTrackAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['current_playlist'])) {
            return <<<HTML
                <p>Aucune playlist courante n’est sélectionnée.</p>
                <a href="?action=mes-playlists">Voir mes playlists</a>
            HTML;
        }

        $playlistId = $_SESSION['current_playlist']['id'];
        $playlistNom = htmlspecialchars($_SESSION['current_playlist']['nom']);

        $html = "<h2>Ajouter une nouvelle piste à la playlist : {$playlistNom}</h2>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_FILES['userfile']) || $_FILES['userfile']['error'] !== UPLOAD_ERR_OK) {
                return "<p>Erreur lors du transfert du fichier.</p>";
            }

            $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
            $file = $_FILES['userfile'];

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Web_projet/S3B_Web_Deefy/audio/';
            if (!is_dir($uploadDir)) {
                return "<p>Dossier audio introuvable : {$uploadDir}</p>";
            }

            $newName = 'track_' . uniqid() . '.mp3';
            $destination = $uploadDir . $newName;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                return "<p>Erreur : impossible de sauvegarder le fichier.</p>";
            }

            $audioPath = 'audio/' . $newName;

            if ($type === 'podcast') {
                $track = new PodcastTrack("Podcast", $audioPath);
            } else {
                $track = new AlbumTrack($audioPath, 0);
            }

            $repo = DeefyRepository::getInstance();
            $ok = $repo->saveTrack($track);

            if ($ok) {
                $trackId = $repo->getPDO()->lastInsertId();
                $repo->addTrackToPlaylist((int)$trackId, (int)$playlistId, 1);
                return <<<HTML
                    <p>Piste ajoutée avec succès à la playlist <strong>{$playlistNom}</strong>.</p>
                    <a href="?action=display-playlist&id={$playlistId}">Retour à la playlist</a>
                HTML;
            } else {
                return "<p>Erreur lors de l’enregistrement de la piste.</p>";
            }
        }

        $html .= <<<HTML
            <form method="POST" enctype="multipart/form-data">
                <label for="type">Type de piste :</label><br>
                <select name="type" id="type">
                    <option value="music">Musique (AlbumTrack)</option>
                    <option value="podcast">Podcast (PodcastTrack)</option>
                </select><br><br>
                <label for="userfile">Fichier audio (.mp3) :</label><br>
                <input type="file" name="userfile" id="userfile" accept=".mp3,audio/mpeg" required><br><br>

                <input type="submit" value="Ajouter la piste">
            </form>
            <br>
            <a href="?action=display-playlist&id={$playlistId}">Retour à la playlist</a>
        HTML;

        return $html;
    }
}
