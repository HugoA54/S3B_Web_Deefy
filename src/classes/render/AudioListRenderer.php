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
        $res .= "<p>Pistes :</p>";

        foreach ($this->audioList->__get('pistes') as $piste) {
            $titre = htmlspecialchars($piste->__get('titre'));
            $duree = htmlspecialchars($piste->__get('duree') ?? '');
    if ($piste instanceof \iutnc\deefy\audio\tracks\PodcastTrack) {
        $artiste = $piste->creator;
    } else {
        $artiste = $piste->artiste;
    }            $fichier = htmlspecialchars($piste->__get('fichier') ?? '');

            $res .= "
            <div class='track-item' style='margin-bottom: 15px;'>
                🎵 $titre" . 
                (!empty($artiste) ? " – $artiste" : "") . 
                (!empty($duree) ? " ({$duree}s)" : "") . "<br>
                <audio controls preload='none' style='width: 300px; margin-top: 5px;'>
                    <source src='$fichier' type='audio/mpeg'>
                    Votre navigateur ne supporte pas la lecture audio.
                </audio>
            </div>";
        }

        $res .= "<p>Nombre de pistes : " . $this->audioList->__get('nbPistes') . "</p>";
        $res .= "<p>Durée totale : " . $this->audioList->__get('dureeTotale') . " secondes</p>";
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
