<?php

declare(strict_types=1);

/**
 * Self-healing database bootstrap.
 *
 * Included (via require_once) by every page and POST handler that touches
 * the database. On each request it ensures, idempotently:
 *   1. the database and all tables/indexes exist (CREATE ... IF NOT EXISTS),
 *   2. a default admin account exists (seeded from ADMIN_USER /
 *      ADMIN_PASSWORD env vars, never overwrites an existing admin).
 *
 * A fresh clone therefore works with zero manual SQL, and the site recovers
 * automatically if someone drops a table. Keep the DDL in sync with
 * init-sql/01-init.sh (which handles first-time volume creation).
 */

require_once __DIR__ . '/../Classes/Dbh.php';

/* Never leak stack traces / DB details to the browser. */
set_exception_handler(static function (Throwable $e): void {
    http_response_code(500);
    error_log(get_class($e) . ': ' . $e->getMessage());
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8">'
        . '<link rel="stylesheet" href="css/main.css"></head><body>'
        . '<h1>Availability Site</h1>'
        . '<div class="alert alert-error">Something went wrong on the server. Please try again.</div>'
        . '</body></html>';
});

function bootstrap_database(): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    $dbh = new Dbh();
    $dbname = Dbh::dbname();

    /* Best-effort: create the database itself. Fails silently when the
     * app user lacks the privilege (the docker entrypoint already created
     * the database in that case). */
    try {
        $dbh->connectToServer()
            ->exec('CREATE DATABASE IF NOT EXISTS `' . preg_replace('/[^A-Za-z0-9_]/', '', $dbname)
                . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    } catch (PDOException $e) {
        error_log('bootstrap: could not ensure database exists: ' . $e->getMessage());
    }

    $pdo = $dbh->connect();

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL,
            pwd VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            is_admin TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY uq_users_username (username),
            UNIQUE KEY uq_users_email (email)
        ) ENGINE=InnoDB'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS availability (
            username VARCHAR(50) NOT NULL,
            mondayStartTime VARCHAR(50) DEFAULT NULL,
            mondayEndTime VARCHAR(50) DEFAULT NULL,
            tuesdayStartTime VARCHAR(50) DEFAULT NULL,
            tuesdayEndTime VARCHAR(50) DEFAULT NULL,
            wednesdayStartTime VARCHAR(50) DEFAULT NULL,
            wednesdayEndTime VARCHAR(50) DEFAULT NULL,
            thursdayStartTime VARCHAR(50) DEFAULT NULL,
            thursdayEndTime VARCHAR(50) DEFAULT NULL,
            fridayStartTime VARCHAR(50) DEFAULT NULL,
            fridayEndTime VARCHAR(50) DEFAULT NULL,
            user_id INT(11) DEFAULT NULL,
            PRIMARY KEY (username),
            CONSTRAINT fk_availability_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        ) ENGINE=InnoDB'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS login_attempts (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            ip_address VARCHAR(45) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_login_attempts_ip_time (ip_address, created_at)
        ) ENGINE=InnoDB'
    );

    /* Seed the default admin (idempotent: only if no admin exists yet). */
    $adminUser = Dbh::env('ADMIN_USER', 'admin');
    $adminPass = Dbh::env('ADMIN_PASSWORD', 'admin123');

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE is_admin = 1');
    $stmt->execute();

    if ((int)$stmt->fetchColumn() === 0) {
        $insert = $pdo->prepare(
            'INSERT INTO users (username, pwd, email, is_admin)
             VALUES (:username, :pwd, :email, 1)'
        );
        $insert->execute([
            'username' => $adminUser,
            'pwd' => password_hash($adminPass, defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT),
            'email' => $adminUser . '@example.com',
        ]);
    }
}

bootstrap_database();