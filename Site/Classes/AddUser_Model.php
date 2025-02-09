<?php

declare(strict_types=1);

class AddUserModel extends Dbh {
    private $username;
    private $pwd;
    private $email;
    private $isAdmin;

    protected function get_username(string $username) {
        $query = "SELECT username FROM users WHERE username = :username;";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    protected function get_email(string $email) {
        $query = "SELECT email FROM users WHERE email = :email;";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    protected function AddUserToDB(string $username, string $pwd, string $email, bool $isAdmin) {
        if($isAdmin){
            $query = "INSERT INTO users (username, pwd, email, is_admin) VALUES (:username, :pwd, :email, :isAdmin);";
            $stmt = parent::connect()->prepare($query);
            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":pwd", $hashedPwd);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":isAdmin", $isAdmin);
            $stmt->execute();
        } else {
            $query = "INSERT INTO users (username, pwd, email) VALUES (:username, :pwd, :email);";
            $stmt = parent::connect()->prepare($query);
            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":pwd", $hashedPwd);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $query = "SELECT id FROM users WHERE username=:username";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $userid = $stmt->fetch(PDO::FETCH_ASSOC);
                
            $query = "INSERT INTO availability (username, user_id) VALUES (:username, :userid);";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":userid", $userid["id"]);
            $stmt->execute();
        }
    }
}