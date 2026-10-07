<?php

declare(strict_types=1);

class ShowTableContr extends ShowTableModel {

    /**
     * Fetch the table in a single query (the table is guaranteed to exist
     * by includes/bootstrap.inc.php).
     */
    protected function retrieveTable(string $table): array {
        return parent::getData($table);
    }
}