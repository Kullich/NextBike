<?php

class NextbikeAPI {
    private $apiUrl = "https://nextbike.net/maps/nextbike-official.json?city=703";

    public function fetchData() {
        $jsonData = file_get_contents($this->apiUrl);
        return json_decode($jsonData, true);
    }
}
