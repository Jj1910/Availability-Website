<?php

declare(strict_types=1);

Class ShowTableContr extends ShowTableModel {
    
    protected function isValidTable (string $table) {
        try {
            parent::getData($table);
            return true;
        } catch (PDOException $e) {
            echo "Error getting table: " . $e->getMessage();
            return false; 
        }
    }

    protected function retrieveTable (string $table){
        if ($this->isValidTable($table)) {
            return parent::getData($table);
        } else {
            return "";
        }
    }
}