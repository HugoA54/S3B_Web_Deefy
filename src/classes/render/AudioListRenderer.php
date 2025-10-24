<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;

class AudioListRenderer implements Renderer
{
    private AudioList $audioList;

    public function __construct(AudioList $audioList)
    {
        $this->audioList = $audioList;
    }

    public function render(int $s): string
    {
        $res = "<div class='audio-list'>";
        $res .= "<h2>" . htmlspecialchars($this->audioList->__get('nom')) . "</h2>";
        $res .= "<p><strong>Pistes :</strong></p>";

        foreach ($this->audioList->__get('pistes') as $piste) {
            $titre = htmlspecialchars($piste->__get('titre'));
            $duree = htmlspecialchars($piste->__get('duree') ?? '');
            $artiste = htmlspecialchars($piste->__get('artiste') ?? '');
            $fichier = htmlspecialchars($piste->__get('fichier') ?? '');

            $res .= "
            <div class='track-item' style='margin-bottom: 15px;'>
                🎵 <strong>$titre</strong>" . 
                (!empty($artiste) ? " – $artiste" : "") . 
                (!empty($duree) ? " ({$duree}s)" : "") . "<br>
                <audio controls preload='none' style='width: 300px; margin-top: 5px;'>
                    <source src='$fichier' type='audio/mpeg'>
                    Votre navigateur ne supporte pas la lecture audio.
                </audio>
            </div>";
        }

        $res .= "<p><strong>Nombre de pistes :</strong> " . $this->audioList->__get('nbPistes') . "</p>";
        $res .= "<p><strong>Durée totale :</strong> " . $this->audioList->__get('dureeTotale') . " secondes</p>";
        $res .= "</div>";

        return $res;
    }

    public function renderCompact(): string
    {
        return "Compact";
    }

    public function renderLong(): string
    {
        return "Long";
    }
}
?>
