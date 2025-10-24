<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack
{
    public function __construct(string $fichier, int $numeroPiste)
    {
        parent::__construct($fichier);
        $this->numeroPiste = $numeroPiste;
    }

    public function __toString(): string
    {
        return parent::__toString();
    }
}
