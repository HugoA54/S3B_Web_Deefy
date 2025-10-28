<?php

declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;

class MesPlaylistsAction extends Action
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
                SELECT p.id, p.nom
                FROM playlist p
                JOIN user2playlist u2p ON u2p.id_pl = p.id
                WHERE u2p.id_user = ?
                ORDER BY p.id ASC
            ");
        $stmt->execute([$user['id']]);
        $playlists = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($playlists)) {
            return <<<HTML
                    <div class="info">
                        <p>Vous n’avez encore aucune playlist enregistrée.</p>
                    </div>
                    <a href="?action=add-empty-playlist">Créer une playlist</a>
                HTML;
        }

        $html = <<<HTML
                <h2>Mes playlists</h2>
                <table border="1" cellpadding="6" cellspacing="0">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Action</th>
                        <th>Gérer</th>
                    </tr>
            HTML;

        foreach ($playlists as $pl) {
            $id = htmlspecialchars((string) $pl['id']);
            $nom = htmlspecialchars($pl['nom']);
            $html .= <<<HTML
                    <tr>
                        <td>{$id}</td>
                        <td>{$nom}</td>
                        <td><a href="?action=display-playlist&id={$id}">Ouvrir</a></td>
                         <td>
                        <a href="?action=rename-playlist&id={$id}">Renommer</a> |
                        <a href="?action=delete-playlist&id={$id}" onclick="return confirm('Supprimer cette playlist ?');">Supprimer</a>
                        </td>

                    </tr>
                HTML;
        }

        $html .= "</table><br><a href='?action=default'>Retour à l'accueil</a>";
        return $html;


    }
}
