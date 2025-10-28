<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\repository\DeefyRepository;

class UserStatsAction extends Action
{
    public function execute(): string
    {

        if (!isset($_SESSION['user'])) {
            return <<<HTML
        <div class="info-message">
            <p>Vous devez être connecté pour accéder à cette fonctionnalité.</p>
            <a href="?action=signin" class="btn">Se connecter</a> 
            <a href="?action=add-user" class="btn">Créer un compte</a>
        </div>
    HTML;
        }
        $user = AuthnProvider::getSignedInUser();
        $repo = DeefyRepository::getInstance();
        $pdo = $repo->getPDO();

        $stmt = $pdo->prepare("
                SELECT 
                    COUNT(DISTINCT p.id) AS nb_playlists,
                    COUNT(pt.id_track) AS nb_tracks
                FROM playlist p
                LEFT JOIN playlist2track pt ON p.id = pt.id_pl
                JOIN user2playlist u2p ON u2p.id_pl = p.id
                WHERE u2p.id_user = ?
            ");
        $stmt->execute([$user['id']]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        $nbPlaylists = (int) $data['nb_playlists'];
        $nbTracks = (int) $data['nb_tracks'];

        return <<<HTML
                <h2>Statistiques utilisateur</h2>
                <p>Email : {$user['email']}</p>
                <p>Nombre de playlists : {$nbPlaylists}</p>
                <p>Nombre total de pistes : {$nbTracks}</p>
                <a href="?action=display-playlists">Mes playlists</a> |
                <a href="?action=default">Accueil</a>
            HTML;


    }
}
