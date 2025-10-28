<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class AddTrackToPlaylistAction extends Action
{
    public function execute(): string
    {
        $html = "<h2>Ajouter une track à une playlist</h2>";

        $repo = DeefyRepository::getInstance();
        $playlists = $repo->findAllPlaylists();
        $tracks = $repo->findAllTrack();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trackId = filter_var($_POST['track_id'], FILTER_SANITIZE_NUMBER_INT);
            $playlistId = filter_var($_POST['playlist_id'], FILTER_SANITIZE_NUMBER_INT);
            $no_piste_dans_liste = filter_var($_POST['no_piste_dans_liste'], FILTER_SANITIZE_NUMBER_INT);

            if ($trackId <= 0 || $playlistId <= 0) {
                $html .= "Vous devez sélectionner une track et une playlist valides";
            } else {
                $ok = $repo->addTrackToPlaylist($trackId, $playlistId, $no_piste_dans_liste);

                if ($ok) {
                    $html .= "Track $trackId ajoutée à la playlist $playlistId avec succès !";
                } else {
                    $html .= "Erreur lors de l’ajout de la track à la playlist";
                }
            }
        }

        $playlistOptions = "";
        foreach ($playlists as $pl) {
            $id = $pl['id'];
            $nom = $pl['nom'];
            $playlistOptions .= "<option value=\"$id\">$nom (ID $id)</option>";
        }

        $trackOptions = "";
        foreach ($tracks as $tr) {
                $id = $pl['id'];
                $nom = $pl['titre'];
                $playlistOptions .= "<option value=\"$id\">$titre (ID $id)</option>";
        }

        $html .= <<<HTML
        <form method="POST" action="?action=add-track-to-playlist">
            <label for="playlist_id"><strong>Choisir une playlist :</strong></label><br>
            <select name="playlist_id" id="playlist_id" required>
                <option value="">-- Sélectionner une playlist --</option>
                $playlistOptions
            </select><br><br>

            <label for="track_id"><strong>Choisir une track :</strong></label><br>
            <select name="track_id" id="track_id" required>
                <option value="">-- Sélectionner une track --</option>
                $trackOptions
            </select><br><br>

            <label for="no_piste_dans_liste">Numéro dans la playlist (optionnel) :</label><br>
            <input type="number" name="no_piste_dans_liste" id="no_piste_dans_liste" value="1"><br><br>

            <input type="submit" value="Ajouter la track à la playlist">
        </form>
        HTML;

        return $html;
    }
}
