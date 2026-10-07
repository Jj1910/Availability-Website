<?php

declare(strict_types=1);

class ShowTableModel extends Dbh {

    /* Only these tables may ever be rendered. */
    private const ALLOWED_TABLES = ["availability"];

    protected function getData(string $tableName): array {
        if (!in_array($tableName, self::ALLOWED_TABLES, true)) {
            throw new InvalidArgumentException("Unknown table: " . $tableName);
        }

        $stmt = parent::connect()->prepare("SELECT * FROM `{$tableName}`");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}