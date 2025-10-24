<?php

namespace iutnc\deefy\action;

session_start();

use iutnc\deefy\audio\tracks\AlbumTrack;

class AddPodcastTrackAction extends Action {

    public function execute(): string {

        // Si on affiche le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<HTML
                <form method="post" action="?action=add-track" enctype="multipart/form-data">
                    <label for="track-name">Nom de la track :</label>
                    <input type="text" id="track-name" name="track-name" required>

                    <label for="track-id">ID de la piste :</label>
                    <input type="number" id="track-id" name="track-id" required>

                    <label for="userfile">Fichier audio (.mp3) :</label>
                    <input type="file" id="userfile" name="userfile" accept=".mp3,audio/mpeg" required>

                    <button type="submit">Créer</button>
                </form>
            HTML;
        }

        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
               if(!isset($_SESSION['playlist'])) {
            return "<p>La playlist n'existe pas.</p>";
        }
            $name = filter_var($_POST["track-name"], FILTER_SANITIZE_STRING);
            $id = filter_var($_POST["track-id"], FILTER_SANITIZE_NUMBER_INT);

            if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {

                $file = $_FILES['userfile'];
                $tmpName = $file['tmp_name'];
                $fileName = $file['name'];
                $fileType = $file['type'];

                if (substr($fileName, -4) === '.mp3' && $fileType === 'audio/mpeg') {

                    if (str_ends_with($fileName, '.php')) {
                        return "Fichier interdit (.php).";
                    }

                    $newName = "track_" . rand() . '.mp3';
                    $destination = __DIR__ . '/../audio/' . $newName;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        return "Erreur : impossible de sauvegarder le fichier.";
                    }

                $audioPath = __DIR__ . '/../audio/' . $newName;
                $t1 = new AlbumTrack($audioPath, (int)$id);

                        $_SESSION['playlist']->ajouterPiste($t1);
                
                    return <<<HTML
                        Track créée avec succès !<br>
                        <a href="?action=add-track">Ajouter encore une piste</a>
                    HTML;
                } else {
                    return "Erreur : le fichier doit être un MP3 valide.";
                }
            } else {
                return "Erreur lors de l'upload du fichier.";
            }
        }

        return "Méthode non supportée.";
    }
}
