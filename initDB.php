<?php

require 'vendor/autoload.php';
include './getMongo.php';

$db = $client->map;

$lieu = $db->lieu;

$lieu->drop();

$json = file_get_contents('https://geoservices.grand-nancy.org/arcgis/rest/services/public/VOIRIE_Parking/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=nom%2Cadresse%2Cplaces%2Ccapacite&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson');
$obj = json_decode($json, true);

foreach ($obj['features'] as $entry) {
    $lieu->insertOne(['Nom' => $entry['attributes']['NOM'], 'x' => $entry['geometry']['y'], 'y' => $entry['geometry']['x'], 'Type' => 'Parking', "Theme" => "Déplacement", 'Capacite' => $entry['attributes']['CAPACITE'], 'Places' => $entry['attributes']['PLACES']]);
}

$json = file_get_contents('https://api.jcdecaux.com/vls/v1/stations?contract=Nancy&apiKey=2f6ddc1dcd0d6a64d5b52dab10d2bfdeb03fc9e0');
$obj = json_decode($json, true);

foreach ($obj as $entry) {
    $lieu->insertOne(['Nom' => explode('-', $entry['name'])[1], 'x' => $entry['position']["lat"], 'y' => $entry['position']["lng"], 'Type' => 'Velib', "Theme" => "Déplacement", 'VeloDispo' => $entry['available_bikes'], 'EmplacementDispo' => $entry['available_bike_stands']]);
}

$parkingProche = array();
$velibProche = array();
foreach ($lieu->find() as $li) {
    if (round(get_distance_m($li['x'], $li['y'], 48.694239, 6.198446) / 1000, 3) < 0.5) {
        if ($li["Type"] == "Parking") {
            array_push($parkingProche, $li["Nom"]);
        }

        if ($li["Type"] == "Velib") {
            array_push($velibProche, $li["Nom"]);
        }

    }
}
$lieu->insertOne(['Nom' => "l'autre canal", 'x' => 48.694239, 'y' => 6.198446, 'Type' => 'Concert', "Theme" => "Musique", "parkingProche" => $parkingProche, "velibProche" => $velibProche, "Capacite" => "1300"]);

$parkingProche = array();
$velibProche = array();
foreach ($lieu->find() as $li) {
    if (round(get_distance_m($li['x'], $li['y'], 48.68585656828219, 6.1835336912475356) / 1000, 3) < 0.5) {
        if ($li["Type"] == "Parking") {
            array_push($parkingProche, $li["Nom"]);
        }

        if ($li["Type"] == "Velib") {
            array_push($velibProche, $li["Nom"]);
        }

    }
}
$lieu->insertOne(['Nom' => "Position actuelle", 'x' => 48.68585656828219, 'y' => 6.1835336912475356, 'Type' => 'ActualPosition', "parkingProche" => $parkingProche, "velibProche" => $velibProche]);

function get_distance_m($lat1, $lng1, $lat2, $lng2)
{
    $earth_radius = 6378137; // Terre = sphère de 6378km de rayon
    $rlo1 = deg2rad($lng1);
    $rla1 = deg2rad($lat1);
    $rlo2 = deg2rad($lng2);
    $rla2 = deg2rad($lat2);
    $dlo = ($rlo2 - $rlo1) / 2;
    $dla = ($rla2 - $rla1) / 2;
    $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
    ));
    $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return ($earth_radius * $d);
}
