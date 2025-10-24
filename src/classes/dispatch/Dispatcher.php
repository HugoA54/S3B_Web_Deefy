<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\SigninAction;
use iutnc\deefy\action\DisplayPlaylistIDAction;
use iutnc\deefy\action\bdAddTrackAction;
use iutnc\deefy\action\AddEmptyPlaylistAction;
use iutnc\deefy\action\AddTrackToPlaylistAction;
use iutnc\deefy\action\ShowAllPlayListBD;

class Dispatcher
{

    private string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void
    {
        $html = '';

        switch ($this->action) {
            case 'default':
                $actionInstance = new DefaultAction();
                $html = $actionInstance->execute();
                break;

            case 'playlist':
                $actionInstance = new DisplayPlaylistAction();
                $html = $actionInstance->execute();
                break;

            case 'add-playlist':
                $actionInstance = new AddPlaylistAction();
                $html = $actionInstance->execute();
                break;

            case 'add-track':
                $actionInstance = new AddPodcastTrackAction();
                $html = $actionInstance->execute();
                break;
            case 'add-user':
                $actionInstance = new AddUserAction();
                $html = $actionInstance->execute();
                break;
            case 'signin':
                $actionInstance = new SigninAction();
                $html = $actionInstance->execute();
                break;
            case 'display-playlist':
                $actionInstance = new DisplayPlaylistIDAction();
                $html = $actionInstance->execute();
                break;
            case 'bd-add-track':
                $actionInstance = new bdAddTrackAction();
                $html = $actionInstance->execute();
                break;
            case 'add-empty-playlist':
                $action = new AddEmptyPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-track-to-playlist':
                $action = new \iutnc\deefy\action\AddTrackToPlaylistAction();
                $html = $action->execute();
                break;
                case 'display-all-playlists':
    $action = new ShowAllPlayListBD();
    $html = $action->execute();
    break;




        }

        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        $fullPage = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css" />
    <title>Deefy</title>
</head>


<body>
<div class="top-links">
    <a href="?action=signin">Connexion</a>
    <a href="?action=add-user">Inscription</a>
</div>

<div class="header">
    <h1>Deefy</h1>
        <img src="images/logo.png" alt="Logo Deefy" class="logo">
</div>

  <div class="options_menu">
        <h2>Menu</h2>
        <div class="menu_sans_bd">
            <p> Option sans base de données :</p>
            <a href="?action=add-playlist">Créer une playlist</a><br>
            <a href="?action=add-track">Ajouter une piste</a><br>
            <a href="?action=playlist">Afficher la playlist</a><br>
      </div>
        <div class="menu_avec_bd">
                        <p> Option avec base de données :</p>
                <a href="?action=display-all-playlists">Afficher toutes les playlists</a><br>
                <a href="?action=display-playlist">Chercher une playlist avec l'id</a><br>
                <a href="?action=bd-add-track">Ajouter une track à la BD</a> <br>
                <a href="?action=add-empty-playlist">Créer une playlist vide</a> <br>
                <a href="?action=add-track-to-playlist">Ajouter une track à une playlist</a>
      </div>
         <br>
    </div>
    <div class="content">
        $html
    </div>
</body>
</html>
HTML;

        echo $fullPage;

    }
}

?>