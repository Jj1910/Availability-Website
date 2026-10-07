<?php

declare(strict_types=1);

class AddUserModel extends Dbh {

    protected function get_username(string $username): array {
        $query = "SELECT username FROM users WHERE username = :username;";
        $stmt = parent::connect()->prepare($query);
        $stmt->execute(["username" => $username]);

        $result = $stmt->fetch();
        return is_array($result) ? $result : [];
    }

    protected function get_email(string $email): array {
        $query = "SELECT email FROM users WHERE email = :email;";
        $stmt = parent::connect()->prepare($query);
        $stmt->execute(["email" => $email]);

        $result = $stmt->fetch();
        return is_array($result) ? $result : [];
    }

    /**
     * Insert the user and (for non-admins) their availability row
     * atomically.
     */
    protected function addUserToDB(string $username, string $pwd, string $email, bool $isAdmin): void {
        $pdo = parent::connect();
        $pdo->beginTransaction();

        try {
            $hashedPwd = password_hash($pwd, defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO users (username, pwd, email, is_admin)
                 VALUES (:username, :pwd, :email, :isAdmin)"
            );
            $stmt->execute([
                "username" => $username,
                "pwd" => $hashedPwd,
                "email" => $email,
                "isAdmin" => $isAdmin ? 1 : 0,
            ]);

            if (!$isAdmin) {
                $userId = (int)$pdo->lastInsertId();
                $stmt = $pdo->prepare(
                    "INSERT INTO availability (username, user_id) VALUES (:username, :userId)"
                );
                $stmt->execute(["username" => $username, "userId" => $userId]);
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}