<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class AddEmptyPlaylistAction extends Action
{
    public function execute(): string
    {
        $html = "<h2>Créer une playlist vide</h2>";

        if($_SERVER['REQUEST_METHOD'] === 'GET') {
              $html .= <<<HTML
        <form method="POST" action="?action=add-empty-playlist">
            <label for="name">Nom de la playlist :</label><br>
            <input type="text" name="name" id="name" required><br><br>
            <input type="submit" value="Créer la playlist">
        </form>
        HTML;

        return $html;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
            if ($name === '') {
                $html .= "Le nom de la playlist est obligatoire.";
            } else {
                $repo = DeefyRepository::getInstance();
                $ok = $repo->saveEmptyPlaylist($name);

                if ($ok) {
                    $html .= $name . "</strong> créée avec succès.</p>";
                } else {
                    $html .= "Erreur lors de la création de la playlist";
                }
            }
        }

  

        return $html;
    }
}
