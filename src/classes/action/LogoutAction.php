<?php
namespace iutnc\deefy\action;

// Action permettant de gérer la déconnexion d'un utilisateur
class LogoutAction extends Action
{
    public function execute(): string
    {
        session_unset();
        session_destroy();

        return <<<HTML
            <p>Vous avez été déconnecté avec succès.</p>
            <a href="?action=signin">Se reconnecter</a> |
            <a href="?action=default">Accueil</a>
        HTML;
    }
}
