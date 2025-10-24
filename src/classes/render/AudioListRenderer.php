<?php
namespace iutnc\deefy\render;



use iutnc\deefy\audio\lists\AudioList;

class AudioListRenderer implements Renderer{

    private $audioList;
        public function __construct(AudioList $audioList){
            $this->audioList = $audioList;
        }
    

    public function render(int $s): string
    {
        $res = "";
                $res .= "<b>";   
         $res .= $this->audioList->__get('nom');
                 $res .= "</b>";      
        
         $res .= "<br> Pistes: <br>";

        foreach($this->audioList->__get('pistes') as $piste){
            $res .= "- ";
            $res .= $piste->__get('titre'); 
                    $res .= "<br>";   

        }
        $res .= "Nombre de pistes: " . $this->audioList->__get('nbPistes');
                $res .= " ";   
        $res .= "Durée totale: " . $this->audioList->__get('dureeTotale') . " secondes";
        return $res;
    }

     public function renderCompact(): string{
            return "Compact";
     }
     public function renderLong(): string{
        return "Long";
     }
    
}

?>