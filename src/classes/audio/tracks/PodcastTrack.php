<?php

declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;


class PodcastTrack
{
    private string $titre;
    private string $creator;
    private string $date;
    private string $genre;
    private int $duree;
    private string $fichier;

    public function __construct(string $titre, string $fichier)
    {
        $this->fichier = $fichier;

        $getID3 = new \getID3();
        $info = $getID3->analyze($fichier);

        $this->titre  = $info['tags']['id3v2']['title'][0]  ?? basename($fichier);
        $this->creator = $info['tags']['id3v2']['artist'][0] ?? "Inconnu";
        $this->date   = $info['tags']['id3v2']['year'][0]   ?? date("Y");
        $this->genre  = $info['tags']['id3v2']['genre'][0]  ?? "Podcast";
        $this->duree  = isset($info['playtime_seconds']) ? (int)$info['playtime_seconds'] : 0;
    }

    public function __toString(): string
    {
        return json_encode(get_object_vars($this));
    }


    public function __get(string $at):mixed {
 if (property_exists ($this, $at)) return $this->$at;
 throw new \Exception ("$at: invalid property");
 }

 public function __set(string $at, mixed $value): void {
    if (property_exists($this, $at)) {
        $this->$at = $value;
    } else {
        throw new \Exception("$at: invalid property");
    }
}

}
