<?php

class Database {
    private $mysqli;

    public function __construct() {
        $config = include(__DIR__ . '/../config/config.php');
        $this->mysqli = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    public function query($sql) {
        return $this->mysqli->query($sql);
    }

    public function prepare($sql) {
        return $this->mysqli->prepare($sql);
    }

    public function close() {
        $this->mysqli->close();
    }
}
