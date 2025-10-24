<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class bdAddTrackAction extends Action
{
    public function execute(): string
    {
        $html = "<h2>Ajouter une nouvelle track</h2>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $genre = $_POST['genre'] ?? '';
            $duree = (int)($_POST['duree'] ?? 0);
            $filename = $_POST['filename'] ?? '';
            $type = $_POST['type'] ?? '';

            $artiste_album = $_POST['artiste_album'] ;
            $titre_album = $_POST['titre_album'] ;
            $annee_album = !empty($_POST['annee_album']) ? (int)$_POST['annee_album'] : null;
            $numero_album = !empty($_POST['numero_album']) ? (int)$_POST['numero_album'] : null;
            $auteur_podcast = $_POST['auteur_podcast'] ;
            $date_podcast = $_POST['date_podcast'] ;

            $repo = DeefyRepository::getInstance();
            $result = $repo->saveTrack(
                $titre,
                $genre,
                $duree,
                $filename,
                $type,
                $artiste_album,
                $titre_album,
                $annee_album,
                $numero_album,
                $auteur_podcast,
                $date_podcast
            );

            if ($result) {
                $html .= "Track ajoutée avec succès !";
            } else {
                $html .= "Erreur lors de l’ajout de la track.";
            }
        }

        $html .= <<<HTML
        <form method="POST" action="?action=bd-add-track">
            <label for="titre">Titre :</label><br>
            <input type="text" name="titre" id="titre" required><br><br>

            <label for="genre">Genre :</label><br>
            <input type="text" name="genre" id="genre" required><br><br>

            <label for="duree">Durée (secondes) :</label><br>
            <input type="number" name="duree" id="duree" required><br><br>

            <label for="filename">Nom du fichier audio :</label><br>
            <input type="text" name="filename" id="filename" required><br><br>

            <label for="type">Type :</label><br>
            <select name="type" id="type" required>
                <option value="music">Music</option>
                <option value="podcast">Podcast</option>
            </select><br><br>

            <fieldset>
                <legend>Champs spécifiques (optionnels)</legend>

                <label>Artiste de l'album :</label><br>
                <input type="text" name="artiste_album"><br><br>

                <label>Titre de l'album :</label><br>
                <input type="text" name="titre_album"><br><br>

                <label>Année de l'album :</label><br>
                <input type="number" name="annee_album"><br><br>

                <label>Numéro dans l'album :</label><br>
                <input type="number" name="numero_album"><br><br>

                <label>Auteur du podcast :</label><br>
                <input type="text" name="auteur_podcast"><br><br>

                <label>Date du podcast :</label><br>
                <input type="date" name="date_podcast"><br><br>
            </fieldset>

            <input type="submit" value="Enregistrer la track">
        </form>
        HTML;

        return $html;
    }
}
