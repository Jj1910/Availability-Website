<?php

declare(strict_types=1);

use function PHPSTORM_META\type;

Class ShowTableView extends ShowTableContr {
    private $table;
    
    public function __construct(string $table) {
        $this->table = $table;
    }

    private function getTable(string $table) {
        return parent::retrieveTable($table);
    }

    public function showTable() {
        $table = $this->getTable($this->table);
        ob_start(); ?>
        <table border = '2'>
        <th>Username</th>
        <th>Monday Start Time</th>
        <th>Monday End Time</th>
        <th>Tuesday Start Time</th>
        <th>Tuesday End Time</th>
        <th>Wednesday Start Time</th>
        <th>Wednesday End Time</th>
        <th>Thursday Start Time</th>
        <th>Thursday End Time</th>
        <th>Friday Start Time</th>
        <th>Friday End Time</th>
        <th>User's ID</th>
        <?php
        foreach ($table as $array => $user) {
            echo "<tr>";
            foreach ($user as $header => $value){
                if ($value === null){
                    echo "<td>null</td>";
                } else if (is_int($value)){
                    echo "<td>" . $value . "</td>";
                } else {
                echo "<td>" . htmlspecialchars($value) . "</td>";
                }
            }
            echo "</tr>";
        }
        ?>
    </table>
    <?php 
    return ob_get_clean();
    }
}