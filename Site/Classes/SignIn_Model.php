<?php

declare(strict_types=1);

class SignInModel extends Dbh {

    /** Failed logins allowed from one IP within the throttle window. */
    private const MAX_ATTEMPTS = 5;
    private const WINDOW_MINUTES = 15;

    protected function getUser(string $username): array {
        $query = "SELECT * FROM users WHERE username = :username;";
        $stmt = parent::connect()->prepare($query);
        $stmt->execute(["username" => $username]);

        $result = $stmt->fetch();
        return is_array($result) ? $result : [];
    }

    protected function isThrottled(string $ip): bool {
        $pdo = parent::connect();

        $pdo->prepare("DELETE FROM login_attempts WHERE created_at < (NOW() - INTERVAL " . self::WINDOW_MINUTES . " MINUTE)")->execute();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = :ip AND created_at >= (NOW() - INTERVAL " . self::WINDOW_MINUTES . " MINUTE)");
        $stmt->execute(["ip" => $ip]);

        return (int)$stmt->fetchColumn() >= self::MAX_ATTEMPTS;
    }

    protected function recordFailedAttempt(string $ip): void {
        parent::connect()
            ->prepare("INSERT INTO login_attempts (ip_address) VALUES (:ip)")
            ->execute(["ip" => $ip]);
    }

    protected function clearFailedAttempts(string $ip): void {
        parent::connect()
            ->prepare("DELETE FROM login_attempts WHERE ip_address = :ip")
            ->execute(["ip" => $ip]);
    }
}