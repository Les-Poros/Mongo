<?php
require '../vendor/autoload.php';
include '../getMongo.php';

$db = $client->map;

$lieu = $db->lieu;

$resCan = $lieu->find(['Nom' => "l'autre canal"]);
$items = '';
foreach ($resCan as $Can) {
    $items .= <<<EOD
    {
      "y_lieu" : "{$Can['y']}",
      "x_lieu" : "{$Can['x']}",
      "Titre_lieu"  : "{$Can['Nom']}",
      "Capacite_lieu"    : "{$Can['Capacite']}",
      "parkingProche_lieu"  : [
EOD;
    foreach ($Can["parkingProche"] as $nomParkProche) {
        $resParkProch = $lieu->find(['Nom' => $nomParkProche]);
        foreach ($resParkProch as $ParkProch) {
            $items .= <<<EOD
            { "y_lieu" : "{$ParkProch['y']}",
              "x_lieu" : "{$ParkProch['x']}",
              "Titre_lieu"  : "{$ParkProch['Nom']}",
              "Capacite_lieu"    : "{$ParkProch['Capacite']}",
              "Places_lieu"    : "{$ParkProch['Places']}"},
EOD;
        }
    }
    // On enlève la dernière virgule
    if ($items != '') {
        $items = substr($items, 0, -1);
    }
    $items .= '],
    "velibProche_lieu"  : [';
    foreach ($Can["velibProche"] as $nomVelibProche) {
        $resVelibProch = $lieu->find(['Nom' => $nomVelibProche]);
        foreach ($resVelibProch as $VelibProch) {
            $items .= <<<EOD
                { "y_lieu" : "{$VelibProch['y']}",
                  "x_lieu" : "{$VelibProch['x']}",
                  "Titre_lieu"  : "{$VelibProch['Nom']}",
                  "VeloDispo_lieu"    : "{$VelibProch['VeloDispo']}",
                  "EmplacementDispo_lieu"    : "{$VelibProch['EmplacementDispo']}"},
EOD;
        }
    }
}
// On enlève la dernière virgule
if ($items != '') {
    $items = substr($items, 0, -1);
}
$items .= '] },';

$resAct = $lieu->find(['Nom' => "Position actuelle"]);
foreach ($resAct as $Act) {
    $items .= <<<EOD
    {
      "y_lieu" : "{$Act['y']}",
      "x_lieu" : "{$Act['x']}",
      "Titre_lieu"  : "{$Act['Nom']}",
      "parkingProche_lieu"  : [],
    "velibProche_lieu"  : [
EOD;
    foreach ($Act["velibProche"] as $nomVelibProche) {
        $resVelibProch = $lieu->find(['Nom' => $nomVelibProche]);
        foreach ($resVelibProch as $VelibProch) {
            $items .= <<<EOD
                { "y_lieu" : "{$VelibProch['y']}",
                  "x_lieu" : "{$VelibProch['x']}",
                  "Titre_lieu"  : "{$VelibProch['Nom']}",
                  "VeloDispo_lieu"    : "{$VelibProch['VeloDispo']}",
                  "EmplacementDispo_lieu"    : "{$VelibProch['EmplacementDispo']}"},
EOD;
        }
    }
}
// On enlève la dernière virgule
if ($items != '') {
    $items = substr($items, 0, -1);
}
$items .= '] }';

// Ecriture de la liste en format JSON
header('Content-type: application/json');
?>
{
    "items": [
              <?php echo $items; ?>
        ]
}