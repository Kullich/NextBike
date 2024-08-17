<?php
//update.php

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/BikeStation.php';

$db = new Database();
$stationManager = new BikeStation($db);

$search = isset($_GET['search']) ? $_GET['search'] : '';
$minBikes = isset($_GET['min_bikes']) ? (int)$_GET['min_bikes'] : 0;

// Získání stanic s filtrováním
$stations = $stationManager->getStations($search, $minBikes);

$data = [];

while ($row = $stations->fetch_assoc()) {
    $data[] = [
        'station_name' => $row['station_name'],
        'available_bikes' => $row['available_bikes'],
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude'],
        'timestamp' => date('H:i', strtotime($row['timestamp'])),
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
