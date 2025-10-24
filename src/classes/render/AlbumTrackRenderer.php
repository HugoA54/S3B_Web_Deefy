<?php

declare(strict_types=1);

namespace iutnc\deefy\render;


use iutnc\deefy\audio\tracks\AlbumTrack;

class AlbumTrackRenderer extends AudioTrackRenderer
{
    public function __construct(AlbumTrack $track)
    {
        parent::__construct($track);
    }

    public function renderCompact(): string
    {
        return "<p>{$this->track->__get('titre')} - {$this->track->__get('artiste')}</p>";
    }

    public function renderLong(): string
    {
        return "
            <div>
                <h2>{$this->track->titre}</h2>
                <p>Artiste: {$this->track->artiste}</p>
                <p>Album: {$this->track->album}</p>
                <p>Durée: {$this->track->duree} secondes</p>
                <audio controls src='{$this->track->fichier}'></audio>
            </div>
        ";
    }
}
