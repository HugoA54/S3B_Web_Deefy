<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList {

    private $nom;
    public $nbPistes;
    public $dureeTotale;
    protected $pistes = [];

    public function __construct(string $nom, array $pistes = []) {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->dureeTotale = 0;
        foreach($pistes as $piste){
            $this->dureeTotale += $piste->duree; 
        }
    }

    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: invalid property");
    }
}
?>