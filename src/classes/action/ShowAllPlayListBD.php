<?php

declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class ShowAllPlayListBD extends Action
{
    public function execute(): string
    {
        $repo = DeefyRepository::getInstance();
        $playlists = $repo->findAllPlaylists(); // méthode qu'on va ajouter juste après

        if (empty($playlists)) {
            return <<<HTML
                <div class="info">
                    <p>Aucune playlist n’a été trouvée dans la base de données.</p>
                </div>
                <a href="?action=default">Retour à l'accueil</a>
            HTML;
        }

        $html = <<<HTML
            <h2>Liste de toutes les playlists</h2>
            <table border="1" cellpadding="6" cellspacing="0">
                <tr style="background-color: #ddd;">
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Action</th>
                </tr>
        HTML;

        foreach ($playlists as $pl) {
            $id = filter_var($pl['id'], FILTER_SANITIZE_NUMBER_INT);
            $nom = filter_var($pl['nom'], FILTER_SANITIZE_STRING);
            $html .= <<<HTML
                <tr>
                    <td>{$id}</td>
                    <td>{$nom}</td>
                    <td><a href="?action=display-playlist&id={$id}">Afficher</a></td>
                </tr>
            HTML;
        }

        $html .= "</table><br><a href='?action=default'>Retour à l'accueil</a>";

        return $html;
    }
}
