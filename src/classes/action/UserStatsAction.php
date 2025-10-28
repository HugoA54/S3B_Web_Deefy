<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\repository\DeefyRepository;

class UserStatsAction extends Action
{
    public function execute(): string
    {
        try {
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
                <p><strong>Email :</strong> {$user['email']}</p>
                <p><strong>Nombre de playlists :</strong> {$nbPlaylists}</p>
                <p><strong>Nombre total de pistes :</strong> {$nbTracks}</p>
                <a href="?action=mes-playlists">Mes playlists</a> |
                <a href="?action=default">Accueil</a>
            HTML;

        } catch (\Throwable $e) {
            return "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
