<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

class bdAddTrackAction extends Action
{
    public function execute(): string
    {
        $html = "<h2>Ajouter une nouvelle piste audio</h2>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_FILES['userfile']) || $_FILES['userfile']['error'] !== UPLOAD_ERR_OK) {
                return "<p>Erreur lors du transfert du fichier.</p>";
            }

            $type = $_POST['type'] ?? 'music';
            $file = $_FILES['userfile'];

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Web_projet/S3B_Web_Deefy/audio/';
            $newName = 'track_' . rand() . '.mp3';
            $destination = $uploadDir . $newName;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                return "<p>Erreur : impossible de sauvegarder le fichier.</p>";
            }

            $audioPath = 'audio/' . $newName;

            if ($type === 'podcast') {
                $track = new PodcastTrack("podcast", $audioPath);
            } else {
                $track = new AlbumTrack($audioPath, 0);
            }

            $repo = DeefyRepository::getInstance();
            $ok = $repo->saveTrack($track);

            if ($ok) {
                $html .= "Piste enregistrée avec succès !";
            } else {
                $html .= "Erreur lors de l’enregistrement.";
            }
        }

        $html .= <<<HTML
        <form method="POST" action="?action=bd-add-track" enctype="multipart/form-data">
            <label for="type">Type de piste :</label>
            <select name="type" id="type">
                <option value="music">Musique (AlbumTrack)</option>
                <option value="podcast">Podcast (PodcastTrack)</option>
            </select><br><br>

            <label for="userfile">Fichier audio (.mp3) :</label><br>
            <input type="file" name="userfile" id="userfile" accept=".mp3,audio/mpeg" required><br><br>

            <input type="submit" value="Ajouter la piste">
        </form>
        HTML;

        return $html;
    }
}
