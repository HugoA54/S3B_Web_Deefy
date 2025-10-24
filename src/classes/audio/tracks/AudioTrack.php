<?php
namespace iutnc\deefy\audio\tracks;




use \iutnc\deefy\exception\InvalidPropertyNameException;
use \iutnc\deefy\exception\InvalidPropertyValueException;


class AudioTrack
{
    private string $titre;
    private string $artiste;
    private string $album;
    private string $annee;
    protected int $numeroPiste;
    private string $genre;
    private int $duree;       // en secondes
    private string $fichier;

    public function __construct(string $fichier)
    {
        $this->fichier = $fichier;

        $getID3 = new \getID3();
        $info = $getID3->analyze($fichier);
        $tags = $info['tags']['id3v2'] ?? [];

        $this->titre       = $tags['title'][0]         ?? basename($fichier);
        $this->artiste     = $tags['artist'][0]        ?? "Inconnu";
        $this->album       = $tags['album'][0]         ?? "";
        $this->annee       = $tags['year'][0]          ?? "";
        $this->numeroPiste = (int)($tags['track_number'][0] ?? 0);
        $this->genre       = $tags['genre'][0]         ?? "";
        $this->duree       = isset($info['playtime_seconds']) ? (int)$info['playtime_seconds'] : 0;
    }

    public function __get(string $at): mixed
    {
        if (!property_exists($this, $at)) {
            throw new InvalidPropertyNameException("$at: invalid property");
        }
        return $this->$at;
    }

    public function __set(string $at, mixed $value): void
    {
        if (!property_exists($this, $at)) {
            throw new InvalidPropertyNameException("$at: invalid property");
        }

        if (in_array($at, ["titre", "fichier", "album", "numeroPiste"])) {
            throw new InvalidPropertyNameException("Propriété non modifiable : $at");
        }

        if ($at === "duree" && $value < 0) {
            throw new InvalidPropertyValueException("Durée négative interdite");
        }

        $this->$at = $value;
    }

    public function __toString(): string
    {
        return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
    }
}
