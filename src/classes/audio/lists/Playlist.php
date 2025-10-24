<?php

namespace iutnc\deefy\audio\lists;

class Playlist extends AudioList
{
    public function __construct(string $name, array $pistes = [])
    {
        parent::__construct($name, $pistes);
    }

    public function ajouterPiste($piste): void
    {
        $this->pistes[] = $piste;
        $this->nbPistes++;
        $this->dureeTotale += $piste->duree;
    }

    public function supprimerPiste(int $index): void
    {
        $this->dureeTotale -= $this->pistes[$index]->duree;
        array_splice($this->pistes, $index, 1);
        $this->nbPistes = count($this->pistes);
    }

    public function ajouterPistes(array $nouvellesPistes): void
    {
        foreach ($nouvellesPistes as $piste) {
            $exists = false;
            foreach ($this->pistes as $p) {
                if ($p->fichier === $piste->fichier) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $this->ajouterPiste($piste);
            }
        }
    }


}