<?php
namespace iutnc\deefy\action;

class DefaultAction extends Action
{
    public function execute(): string
    {
        return <<<HTML
            <h2>🎵 Bienvenue sur Deefy 🎵</h2>
            <p>
                Votre plateforme musicale universitaire.<br>
                Créez des playlists, ajoutez des pistes et gérez vos podcasts simplement.
            </p>

              <hr>
              <p class ='accueil'>
            BOUDOUAH Ilias - ANTZORN Hugo <br>
            Projet Deefy BUT Informatique 2025
        </p>
        HTML;
    }
}
