<?php

declare(strict_types=1);

/**
 * PDO connection factory.
 *
 * Configuration comes from environment variables (see docker-compose.yml):
 *   DB_HOST, DB_NAME, DB_USER, DB_PASS
 * Connections are cached per DSN for the lifetime of the request, so
 * multiple queries in one request reuse a single connection.
 */
class Dbh {
    /** @var array<string, PDO> one connection per DSN, per request */
    private static array $connections = [];

    public static function env(string $name, string $default = ''): string {
        $value = getenv($name);

        return ($value === false || $value === '') ? $default : $value;
    }

    public static function host(): string {
        return self::env('DB_HOST', 'AvailabilityDB');
    }

    public static function dbname(): string {
        return self::env('DB_NAME', 'availability_site');
    }

    public static function dbuser(): string {
        return self::env('DB_USER', 'availability');
    }

    public static function dbpass(): string {
        return self::env('DB_PASS', '');
    }

    /**
     * Return a PDO connection to $dbName (default: the app database).
     */
    public function connect(?string $dbName = null): PDO {
        return self::pdoFor($dbName ?? self::dbname());
    }

    /**
     * Connect to the server without selecting a database
     * (used for CREATE DATABASE and other server-level DDL).
     */
    public function connectToServer(): PDO {
        return self::pdoFor(null);
    }

    private static function pdoFor(?string $dbName): PDO {
        $dsn = 'mysql:host=' . self::host()
            . ($dbName !== null ? ';dbname=' . $dbName : '')
            . ';charset=utf8mb4';

        if (!isset(self::$connections[$dsn])) {
            self::$connections[$dsn] = new PDO($dsn, self::dbuser(), self::dbpass(), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return self::$connections[$dsn];
    }
}