#!/bin/sh
# Runs automatically (once) when the MySQL data volume is created for the
# first time, via the official image's /docker-entrypoint-initdb.d hook.
#
# The same DDL is re-applied idempotently on every request by
# Site/includes/bootstrap.inc.php, so the schema self-heals even if a
# table is dropped while the data volume persists. Keep both in sync.
set -e

DB="${MYSQL_DATABASE:-availability_site}"

# Belt-and-braces: the entrypoint already creates the database from
# MYSQL_DATABASE, but create it anyway in case it is missing.
mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" <<-SQL
    CREATE DATABASE IF NOT EXISTS \`$DB\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SQL

mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" "$DB" <<-SQL
    CREATE TABLE IF NOT EXISTS users (
        id INT(11) NOT NULL AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL,
        pwd VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        is_admin TINYINT(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (id),
        UNIQUE KEY uq_users_username (username),
        UNIQUE KEY uq_users_email (email)
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS availability (
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
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS login_attempts (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        ip_address VARCHAR(45) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_login_attempts_ip_time (ip_address, created_at)
    ) ENGINE=InnoDB;
SQL