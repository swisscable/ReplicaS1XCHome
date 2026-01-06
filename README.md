# Open IPTV Server (Xtream Codes-Compatible)

A modular, headless, Debian 13-ready PHP 8.2+ IPTV server that implements the **Xtream Codes Player API** compatible endpoints for authentication, playlist generation, streaming URLs, and optional DLNA-friendly output.

> ✅ **No external endpoints** are hardcoded. All hosts, ports, and credentials are local and configurable via `.env` and config files.

## Key Features

- Xtream Codes API-compatible endpoints (`/player_api.php`, `/get.php`, `/xmltv.php`, `/player_api.php?action=get_live_streams`, etc.)
- Modular architecture with independent components per domain
- Strong security posture: input validation, prepared statements, JWT auth, CSRF tokens for state-changing routes
- Logging with Monolog, configuration via dotenv, and clean separation of concerns
- MySQL/MariaDB primary + SQLite fallback
- Phinx-ready migrations
- Rate limiting, caching, and automation (cron)
- Debian 13 headless server friendly

## Architecture (ASCII)

```
+------------------------+       +--------------------------+
|      public/index.php  | ----> |  Bootstrap/App Router    |
+------------------------+       +--------------------------+
                |                               |
                v                               v
     +-------------------+        +----------------------------+
     | ApiModule          |        | Auth/User/Media Modules    |
     | - Xtream endpoints |        | - Domain logic & services  |
     +-------------------+        +----------------------------+
                |                               |
                v                               v
     +-------------------+        +----------------------------+
     | Infrastructure    | <----> | Database/Cache/Logging      |
     | - DB, Cache, JWT  |        | Rate limiting, Metrics      |
     +-------------------+        +----------------------------+
```

## Repository Layout

```
bin/                 CLI utilities
config/              Configuration and .env example
public/              Web entry point (API)
src/                 Core application code
  Bootstrap/         Application bootstrapping
  Infrastructure/    Shared services (DB, cache, logging, JWT)
  Modules/           Feature modules (API/Auth/User/Media/Cron)
    ApiModule/
    AuthModule/
    UserModule/
    MediaModule/
    CronModule/
tests/               PHPUnit tests
docs/                Extra documentation and API specs
```

## Installation (Debian 13 Headless)

See the full guide in `docs/installation.md`.

### 1) Install system packages

```bash
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl php8.2-mysql php8.2-sqlite3 \
  mariadb-server redis-server git unzip
```

### 2) Install Composer

```bash
sudo apt install -y composer
```

### 3) Clone & install dependencies

```bash
git clone <your-repo-url> iptv-server
cd iptv-server
composer install
```

### 4) Configure

```bash
cp config/.env.example .env
```

Edit `.env` and set your database credentials, JWT secret, and hostname.

### 5) Database migration

```bash
composer run migrate
```

### 6) Run (development)

```bash
composer run serve
```

For production, use **systemd** or **Supervisor**. See `docs/systemd.md`.

## Example Usage (Xtream Codes)

- Authenticate:
  - `GET /player_api.php?username=<user>&password=<pass>`
- Generate playlist:
  - `GET /get.php?username=<user>&password=<pass>&type=m3u_plus&output=ts`
- Stream URL:
  - `http://<host>:<port>/live/<user>/<pass>/<stream_id>.ts`

## Configuration

See `config/.env.example` for all variables.

## Security Notes

- HTTPS enforcement is configurable and recommended for all public-facing deployments.
- Rate limiting is enabled by default for authentication endpoints.
- JWT tokens are signed with `JWT_SECRET`.

## Testing & Quality

```bash
composer run test
composer run lint
composer run stan
```

## Module Documentation

Each module has a dedicated README in its directory with detailed usage, inputs, outputs, and integration notes.

## API Documentation

OpenAPI spec is in `docs/openapi.yaml`.

## Operations Manuals

- Installation: `docs/installation.md`\n- Maintenance: `docs/maintenance.md`

## License

MIT
