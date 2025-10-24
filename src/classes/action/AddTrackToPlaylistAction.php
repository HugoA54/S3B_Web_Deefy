<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class AddTrackToPlaylistAction extends Action
{
    public function execute(): string
    {
        $html = "<h2>Ajouter une track à une playlist</h2>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trackId = (int)($_POST['track_id'] ?? 0);
            $playlistId = (int)($_POST['playlist_id'] ?? 0);
            $no_piste_dans_liste = (int)($_POST['no_piste_dans_liste'] ?? 1);

            if ($trackId <= 0 || $playlistId <= 0) {
                $html .= "Vous devez spécifier une track et une playlist valides";
            } else {
                $repo = DeefyRepository::getInstance();
                $ok = $repo->addTrackToPlaylist($trackId, $playlistId, $no_piste_dans_liste);

                if ($ok) {
                    $html .= "Track <strong>$trackId</strong> ajoutée à la playlist <strong>$playlistId</strong> avec succès !";
                } else {
                    $html .= "Erreur lors de l’ajout de la track à la playlist.";
                }
            }
        }

        // --- Formulaire HTML ---
        $html .= <<<HTML
        <form method="POST" action="?action=add-track-to-playlist">
            <label for="playlist_id">ID de la playlist :</label><br>
            <input type="number" name="playlist_id" id="playlist_id" required><br><br>

            <label for="track_id">ID de la track :</label><br>
            <input type="number" name="track_id" id="track_id" required><br><br>

            <label for="no_piste_dans_liste">Numéro dans la playlist (optionnel) :</label><br>
            <input type="number" name="no_piste_dans_liste" id="no_piste_dans_liste" value="1"><br><br>

            <input type="submit" value="Ajouter la track à la playlist">
        </form>
        HTML;

        return $html;
    }
}
