<?php

namespace iutnc\deefy\audio\lists;


class Album extends AudioList {
    private string $artist;
    private string $datesortie;

    public function __construct(string $name, array $tracks, string $artist = "", string $datesortie = "") {
   
        parent::__construct($name, $tracks);
        $this->artist = $artist;
        $this->datesortie = $datesortie;
    }

    public function setArtist(string $artist): void {
        $this->artist = $artist;
    }

    public function setDateSortie(string $date): void {
        $this->datesortie = $date;
    }
}
?>
