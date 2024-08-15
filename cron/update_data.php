<?php

require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/nextbikeAPI.php';
require_once __DIR__ . '/../src/bikestation.php';

$db = new Database();
$api = new NextbikeAPI();
$stationManager = new BikeStation($db);

$data = $api->fetchData();
$stations = $data['countries'][0]['cities'][0]['places'];

$stationManager->saveStations($stations);

$db->close();
