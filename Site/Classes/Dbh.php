<?php

class Dbh {
    private $host = "localhost";
    private $dbname = "availability_site";
    private $dbusername = "root";
    private $dbpassword = "";

    protected function connect () {
        try {
            $pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->dbusername, $this->dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }
}