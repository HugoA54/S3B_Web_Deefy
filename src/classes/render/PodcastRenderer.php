<?php

declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\PodcastTrack;




class PodcastRenderer extends AudioTrackRenderer
{
    public function __construct(PodcastTrack $track)
    {
        parent::__construct($track);
    }

    public function renderCompact(): string
    {
        return "<p>{$this->track->__get('titre')} - {$this->track->__get('creator')}</p>";
    }

    public function renderLong(): string
    {
        return "
            <div>
                <h2>{$this->track->titre}</h2>
                <p>Auteur: {$this->track->creator}</p>
                <p>Date: {$this->track->date}</p>
                <p>Durée: {$this->track->duree} secondes</p>
                <audio controls src='{$this->track->fichier}'></audio>
            </div>
        ";
    }
}
