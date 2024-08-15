<?php

class BikeStation {
    private $database;

    public function __construct($db) {
        $this->database = $db;
    }

    public function saveStations($stations) {
        foreach ($stations as $station) {

            $stmt = $this->database->prepare("DELETE FROM bike_stations WHERE station_name = ?");
            $stmt->bind_param("s", $station['name']);
            $stmt->execute();

            $stmt = $this->database->prepare("INSERT INTO bike_stations (station_name, latitude, longitude, available_bikes, timestamp) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sddis", $station['name'], $station['lat'], $station['lng'], $station['bikes'], date('Y-m-d H:i:s'));
            $stmt->execute();
        }
    }

    public function getStations($search = '', $minBikes = 0) {
    $sql = "
        SELECT * FROM bike_stations AS bs
        WHERE bs.timestamp = (
            SELECT MAX(sub_bs.timestamp)
            FROM bike_stations AS sub_bs
            WHERE sub_bs.station_name = bs.station_name
        )
        AND bs.station_name LIKE ?
        AND bs.available_bikes >= ?
        ORDER BY bs.timestamp DESC
    ";
    $stmt = $this->database->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bind_param("si", $searchParam, $minBikes);
    $stmt->execute();
    return $stmt->get_result();
}

}
