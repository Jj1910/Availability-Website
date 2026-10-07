# Availability-Website

A small availability-tracking web app: admins create users, every user submits
their weekly availability (Mon–Fri), and admins can view the full table.

Built with plain PHP 8.3 and MySQL 8, containerized with Docker Compose.

## Prerequisites

- Docker (with the Compose plugin)

## Quick start

```bash
git clone https://github.com/Jj1910/Availability-Website.git
cd Availability-Website
docker compose up --build -d
```

Then open **http://localhost** and log in with the default admin account:

| Username | Password |
|----------|----------|
| `admin`  | `admin123` |

> **Change this password immediately** for anything beyond local testing.

## Configuration

Everything is driven by environment variables. Defaults live in
`docker-compose.yml`; you can override them by creating a `.env` file in the
project root (a template is included).

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_PORT` | `80` | Host port the web app is published on |
| `MYSQL_USER` | `availability` | MySQL username |
| `MYSQL_PASSWORD` | `availability_dev_pw` | MySQL password |
| `MYSQL_DATABASE` | `availability_site` | Database name |
| `ADMIN_USER` | `admin` | Username of the auto-created default admin |
| `ADMIN_PASSWORD` | `admin123` | Password of the auto-created default admin |
| `TZ` | `UTC` | Timezone for both containers |

## How the database is set up (no manual SQL needed)

- `init-sql/01-init.sh` runs automatically when a **fresh** database volume is
  created: it creates the schema, the `login_attempts` throttle table, and the
  default admin user from `ADMIN_USER` / `ADMIN_PASSWORD`.
- On every request, `Site/includes/bootstrap.inc.php` verifies the schema and
  re-creates anything missing (self-healing), so a pre-existing volume without
  the tables still comes up correctly.
- The default admin is only created if **no admin exists yet**, so it is never
  overwritten after you change the password or create other admins.

### Wipe everything and start over

```bash
docker compose down -v   # removes the database volume
docker compose up --build -d
```

## Security features

- All queries use PDO prepared statements (no SQL injection).
- Passwords hashed with `bcrypt`.
- CSRF token required on every form POST.
- Login throttling (per IP) after repeated failed attempts.
- Session cookies: `HttpOnly`, `SameSite=Lax`, `Secure` when served over HTTPS.
- Session ID regenerated on login/logout.
- All output escaped with `htmlspecialchars`.
- MySQL port bound to `127.0.0.1` only (not exposed to the network).
- PHP errors are not leaked to the browser.
- Table rendering allow-listed to known tables.

## Production notes

The bundled defaults are **development** defaults:

- Set strong, unique values for `MYSQL_PASSWORD` and `ADMIN_PASSWORD`.
- Serve over HTTPS (a reverse proxy such as Caddy/Nginx) so the `Secure`
  cookie flag is actually applied.
- Consider a firewall rule to keep the app off the public internet if it is
  only for internal use.

## Useful commands

```bash
docker compose ps                 # status
docker compose logs -f            # logs
docker compose down -v            # stop + remove database
docker compose exec app php -l Site/Classes/Dbh.php   # lint a file
```
