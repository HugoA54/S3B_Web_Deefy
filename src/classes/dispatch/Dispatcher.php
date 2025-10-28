<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\MesPlaylistsAction;
use iutnc\deefy\action\SigninAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddTrackAction;
use iutnc\deefy\action\DisplayCurrentPlaylistAction;
use iutnc\deefy\action\AddEmptyPlaylistAction;
use iutnc\deefy\action\LogoutAction;
use iutnc\deefy\action\RenamePlaylistAction;
use iutnc\deefy\action\DeletePlaylistAction;
use iutnc\deefy\action\UserStatsAction;


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
            case 'display-playlists':
                $actionInstance = new MesPlaylistsAction();
                $html = $actionInstance->execute();
                break;
            case 'signin':
                $actionInstance = new SigninAction();
                $html = $actionInstance->execute();
                break;
            case 'add-user':
                $actionInstance = new AddUserAction();
                $html = $actionInstance->execute();
                break;
            case 'display-playlist':
                $actionInstance = new DisplayPlaylistAction();
                $html = $actionInstance->execute();
                break;
            case 'add-track':
                $actionInstance = new AddTrackAction();
                $html = $actionInstance->execute();
                break;
            case 'display-current-playlist':
                $actionInstance = new DisplayCurrentPlaylistAction();
                $html = $actionInstance->execute();
                break;
            case 'add-empty-playlist':
                $actionInstance = new AddEmptyPlaylistAction();
                $html = $actionInstance->execute();
                break;
            case 'logout':
                $action = new LogoutAction();
                $html = $action->execute();
                break;
            case 'rename-playlist':
                $action = new RenamePlaylistAction();
                $html = $action->execute();
                break;
            case 'delete-playlist':
                $action = new DeletePlaylistAction();
                $html = $action->execute();
                break;
            case 'user-stats':
                $action = new UserStatsAction();
                $html = $action->execute();
                break;


        }

        $this->renderPage($html);
    }

private function renderPage(string $html): void
{
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user']['email'];
        $topLinks = <<<HTML
            <a href="?action=user-stats">Profil</a> 
            <a href="?action=logout">Déconnexion</a>
        HTML;
    } else {
        $topLinks = <<<HTML
            <a href="?action=signin">Connexion</a> 
            <a href="?action=add-user">Inscription</a>
        HTML;
    }

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
    {$topLinks}
</div>

<div class="header">
    <h1>Deefy</h1>
    <a href="?action=default"><img src="images/logo.png" alt="Logo Deefy" class="logo"></a>
</div>

<div class="options_menu">
    <h2>Menu</h2><br>
    <div class="options">
        <a href="?action=default">Accueil</a><br>
        <a href="?action=display-playlists">Mes playlists</a><br>
        <a href="?action=add-empty-playlist">Créer une nouvelle playlist</a><br>
        <a href="?action=display-current-playlist">Afficher la playlist courante</a><br>
        <a href="?action=add-track">Ajouter une piste à la playlist courante</a><br>
    </div>
</div>

<div class="content">
    {$html}
</div>

</body>
</html>
HTML;

    echo $fullPage;
}
}
?>

