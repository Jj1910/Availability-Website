<?php

declare(strict_types=1);

class ShowTableModel extends Dbh {

    protected function getData(string $tableName) {
        $query = "SELECT * FROM " . $tableName . ";";
        $stmt = parent::connect()->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}