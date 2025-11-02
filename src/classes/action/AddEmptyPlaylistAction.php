<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;

// Action permettant de gérer l'ajout des playlists vides
class AddEmptyPlaylistAction extends Action
{
    public function execute(): string
    {
        // Si utilisateur non connecté, on lui en informe
        if (!isset($_SESSION['user'])) {
            return <<<HTML
                <div class="info-message">
                    <p>Vous devez être connecté pour accéder à cette fonctionnalité.</p>
                    <a href="?action=signin" class="btn">Se connecter</a>
                    <a href="?action=add-user" class="btn">Créer un compte</a>
                </div>
            HTML;
        }

        // Permets à l'utilisateur de rentrer les données de sa nouvelle playlist vide
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<HTML
                <h2>Créer une playlist vide</h2>
                <form method="POST" action="?action=add-empty-playlist">
                    <label for="name">Nom de la playlist :</label><br>
                    <input type="text" name="name" id="name" required><br><br>
                    <input type="submit" value="Créer la playlist">
                </form>
                <br>
                <a href="?action=display-playlists">Retour à mes playlists</a>
            HTML;
        }
        
        // Envoie les données pour créer la playlist une fois que l'utiliseur appuye sur le submit du form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification que l'utilisateur a mis un nom correct à la playlist avec un filtre
            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Vérification que l'utilisateur a mis un nom non vide
            if ($name === '') {
                return "<p>Le nom de la playlist est obligatoire.</p>";
            }

            $user = AuthnProvider::getSignedInUser();
            $repo = DeefyRepository::getInstance();

            // Sauvegarde de la playlist vide dans la BD
            $playlistId = $repo->saveEmptyPlaylist($name);

            // Si tout s'est bien passé et que la playlist a été sauvegardé dans la BD
            if ($playlistId !== null) {
                // Mis à jour de la playlist courante
                $_SESSION['current_playlist'] = [
                    'id'  => $playlistId,
                    'nom' => $name
                ];

                return <<<HTML
                    <p>La playlist {$name} a été créée avec succès et est maintenant la playlist courante.</p>
                    <a href="?action=display-playlist&id={$playlistId}">Voir la playlist</a> |
                    <a href="?action=add-track">Ajouter une piste</a> |
                    <a href="?action=display-playlists">Retour à mes playlists</a>
                HTML;
            }

            return "<p>Erreur lors de la création de la playlist.</p>";
        }

        return "<p>Erreur de requête.</p>";
    }
}
