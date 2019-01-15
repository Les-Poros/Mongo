<?php
require '../vendor/autoload.php';
include '../getMongo.php';

$db = $client->map;

$lieu=$db->lieu;
$resVel=$lieu->find([ 'Type' => 'Velib']);
$items = '"velibs" : [';
foreach ($resVel as $entry) {
    $items .= <<<EOD
    { "y_lieu" : "{$entry['y']}",
      "x_lieu" : "{$entry['x']}",
      "Titre_lieu"  : "{$entry['Nom']}",
      "VeloDispo_lieu"    : "{$entry['VeloDispo']}",
      "EmplacementDispo_lieu"    : "{$entry['EmplacementDispo']}"},
EOD;
    
}
  // On enlève la dernière virgule
  if ($items != ''){
     $items = substr($items, 0, -1);
  }
  $items .= ']';

    // Ecriture de la liste des velibs en format JSON
    header('Content-type: application/json');
    ?>
    {   
        "items": {
                  <?php echo $items;?>
            }
    }