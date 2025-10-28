<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authz;

class DeletePlaylistAction extends Action
{
    public function execute(): string
    {
        if (!isset($_GET['id']))
            return "<p>Aucune playlist sélectionnée.</p>";

        $id = (int) $_GET['id'];
        $authz = new Authz();
        if (!$authz->checkPlaylistOwner($id))
            return "<p>Accès refusé.</p>";

        $repo = DeefyRepository::getInstance();
        $pdo = $repo->getPDO();

        $pdo->prepare("DELETE FROM playlist2track WHERE id_pl = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM user2playlist WHERE id_pl = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM playlist WHERE id = ?")->execute([$id]);

        if (isset($_SESSION['current_playlist']) && (int) $_SESSION['current_playlist']['id'] === (int) $id) {
            unset($_SESSION['current_playlist']);
        }


        return <<<HTML
            <p>Playlist supprimée avec succès.</p>
            <a href="?action=mes-playlists">Retour à mes playlists</a>
        HTML;
    }
}
