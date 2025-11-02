<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authz;

// Action permettant de gérer la suppression de Playlists
class DeletePlaylistAction extends Action
{
    public function execute(): string
    {   

        if (!isset($_GET['id']))
            return "<p>Aucune playlist sélectionnée.</p>";

        // Vérification des autorisations de l'utilisateur
        $id = (int) $_GET['id'];
        $authz = new Authz();
        if (!$authz->checkPlaylistOwner($id))
            return "<p>Accès refusé.</p>";

        $repo = DeefyRepository::getInstance();
        $pdo = $repo->getPDO();

        // Suppression de la playlist et de ses liens dans la BD
        $pdo->prepare("DELETE FROM playlist2track WHERE id_pl = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM user2playlist WHERE id_pl = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM playlist WHERE id = ?")->execute([$id]);

        // Destruction de la variable playlist courante si jamais la playlist supprimée était la playlist courante
        if (isset($_SESSION['current_playlist']) && (int) $_SESSION['current_playlist']['id'] === (int) $id) {
            unset($_SESSION['current_playlist']);
        }


        return <<<HTML
            <p>Playlist supprimée avec succès.</p>
            <a href="?action=display-playlists">Retour à mes playlists</a>
        HTML;
    }
}
