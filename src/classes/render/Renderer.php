<?php
namespace iutnc\deefy\render;

interface Renderer{
    public function render(int $selector): string;
        public function renderCompact(): string;

        public function renderLong(): string;



 
}


?>