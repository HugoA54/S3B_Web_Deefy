<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';
require_once __DIR__ . '/src/classes/dispatch/Dispatcher.php';



use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\repository\DeefyRepository;



try {
DeefyRepository::setConfig(__DIR__ . '/Config.db.ini');
$repo = DeefyRepository::getInstance();
$pdo = $repo->getPDO();

$dispatcher = new iutnc\deefy\dispatch\Dispatcher();
$dispatcher->run();



   

} catch (InvalidPropertyNameException $e) {
    echo "Erreur de propriété : " . $e->getMessage();
} catch (InvalidPropertyValueException $e) {
    echo "Erreur de valeur : " . $e->getMessage();
}
