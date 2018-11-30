
<?php
require '../vendor/autoload.php';
$client = new MongoDB\Client("mongodb://192.168.99.100:27017");

$db = $client->map;

$lieu = $db->lieu;

$resPark = $lieu->find(['Type' => 'Parking']);
$items = '"parkings" : [';
foreach ($resPark as $entry) {
    $items .= <<<EOD
    { "y_lieu" : "{$entry['y']}",
      "x_lieu" : "{$entry['x']}",
      "Titre_lieu"  : "{$entry['Nom']}",
      "Capacite_lieu"    : "{$entry['Capacite']}",
      "Places_lieu"    : "{$entry['Places']}"},
EOD;

}
// On enlève la dernière virgule
if ($items != '') {
    $items = substr($items, 0, -1);
}
$items .= ']';

// Ecriture de la liste des parkings en format JSON
header('Content-type: application/json');
?>
    {
        "items": {
                  <?php echo $items; ?>
            }
    }