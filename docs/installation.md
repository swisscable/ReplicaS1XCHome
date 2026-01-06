# Manual de instalación (Debian 13, servidor headless)

Este manual describe la instalación completa del servidor IPTV en Debian 13 sin interfaz gráfica.

## 1) Requisitos del sistema

- Debian 13 (headless)
- Acceso root o sudo
- Red local funcional

## 2) Paquetes del sistema

```bash
sudo apt update
sudo apt install -y \
  php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl php8.2-mysql php8.2-sqlite3 \
  mariadb-server redis-server composer git unzip
```

> Nota: `redis-server` es opcional si prefieres cache de archivos.

## 3) Clonar el repositorio

```bash
git clone <TU_REPO> /opt/iptv-server
cd /opt/iptv-server
```

## 4) Dependencias PHP (Composer)

```bash
composer install --no-dev --optimize-autoloader
```

## 5) Configuración (.env)

```bash
cp config/.env.example .env
```

Edita `.env` con tus valores locales. Campos críticos:

- `APP_URL`
- `DB_DRIVER` (`mysql` o `sqlite`)
- `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
- `JWT_SECRET`
- `CACHE_DRIVER` (`redis` o `file`)
- `LOG_PATH`

## 6) Base de datos (MariaDB recomendado)

### 6.1 Crear base de datos y usuario

```bash
sudo mysql -e "CREATE DATABASE iptv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'iptv'@'localhost' IDENTIFIED BY 'change_me';"
sudo mysql -e "GRANT ALL PRIVILEGES ON iptv.* TO 'iptv'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

Actualiza `.env` con la contraseña real.

### 6.2 Ejecutar migraciones

```bash
composer run migrate
```

## 7) Permisos y rutas

```bash
sudo mkdir -p /var/log/iptv /var/cache/iptv /var/backups/iptv
sudo chown -R www-data:www-data /var/log/iptv /var/cache/iptv /var/backups/iptv
```

Ajusta `LOG_PATH`, `CACHE_PATH`, `BACKUP_PATH` en `.env` si es necesario.

## 8) Arranque con systemd

Crea `/etc/systemd/system/iptv-server.service`:

```ini
[Unit]
Description=IPTV Server
After=network.target mariadb.service

[Service]
Type=simple
User=www-data
WorkingDirectory=/opt/iptv-server
EnvironmentFile=/opt/iptv-server/.env
ExecStart=/usr/bin/php -S 0.0.0.0:8080 -t /opt/iptv-server/public
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

Habilitar el servicio:

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now iptv-server.service
```

## 9) Pruebas rápidas

```bash
curl http://localhost:8080/health
```

Debe responder con un JSON de estado.

## 10) Uso con clientes Xtream

- Auth: `GET /player_api.php?username=<user>&password=<pass>`
- Playlist: `GET /get.php?username=<user>&password=<pass>&type=m3u_plus&output=ts`
- EPG: `GET /xmltv.php?username=<user>&password=<pass>`
