<?php

declare(strict_types=1);

class SignInModel extends Dbh {

    protected function getUser(string $username) {
        $query = "SELECT * FROM users WHERE username = :username;";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}