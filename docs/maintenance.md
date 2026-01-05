# Manual de mantenimiento

Este manual describe tareas recurrentes para operación 24/7/365.

## 1) Logs

- Ruta configurada en `LOG_PATH` (por defecto `/var/log/iptv/app.log`).
- Rotación recomendada con `logrotate`.

Ejemplo `logrotate`:

```
/var/log/iptv/*.log {
  daily
  rotate 14
  compress
  missingok
  notifempty
  create 0640 www-data www-data
}
```

## 2) Backups

Ejecutar backup manual:

```bash
php bin/backup.php
```

Planificar backups:

```bash
crontab -e
# ejemplo diario a las 02:00
0 2 * * * /usr/bin/php /opt/iptv-server/bin/backup.php
```

## 3) Cron/automatización

Ejecutar mantenimiento diario:

```bash
php bin/cron.php daily
```

Planificar con cron:

```
30 3 * * * /usr/bin/php /opt/iptv-server/bin/cron.php daily
```

## 4) Actualizaciones

```bash
cd /opt/iptv-server
composer install --no-dev --optimize-autoloader
```

Reiniciar servicio:

```bash
sudo systemctl restart iptv-server.service
```

## 5) Salud del servicio

- `GET /health`
- `GET /metrics` (si `METRICS_ENABLED=true`)

## 6) Base de datos

- Revisar espacio y rendimiento en MariaDB.
- Ejecutar migraciones tras cambios de esquema:

```bash
composer run migrate
```

## 7) Redis/Cache

Si usas Redis, valida disponibilidad:

```bash
redis-cli ping
```

## 8) Seguridad

- Forzar HTTPS con `ENABLE_HTTPS_REDIRECT=true`.
- Actualizar `JWT_SECRET` periódicamente.
- Verificar permisos en `/var/log/iptv`, `/var/cache/iptv`, `/var/backups/iptv`.

## 9) Explicación de variables críticas del `.env`

A continuación se explica línea por línea qué significa cada variable crítica y cómo elegir su valor. Para editar el archivo:

```bash
cp config/.env.example .env
nano .env
```

### APP_URL

- **Qué es:** la URL base que usan los clientes (Xtream Codes) para acceder al servidor.
- **Cómo se elige:** IP local o dominio interno + puerto.
- **Ejemplo:**
  ```
  APP_URL=http://192.168.1.50:8080
  ```

### DB_DRIVER

- **Qué es:** motor de base de datos a utilizar.
- **Valores posibles:** `mysql` o `sqlite`.
- **Ejemplo (MariaDB/MySQL):**
  ```
  DB_DRIVER=mysql
  ```
- **Ejemplo (SQLite):**
  ```
  DB_DRIVER=sqlite
  ```

### DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS

- **Qué son:** datos de conexión a la base de datos (si usas `mysql`).
- **Cómo se eligen:**
  - `DB_NAME` y `DB_USER` los defines tú al crear la base de datos/usuario.
  - `DB_PASS` es la contraseña que tú estableces para ese usuario.
- **Ejemplo:**
  ```
  DB_HOST=localhost
  DB_PORT=3306
  DB_NAME=iptv
  DB_USER=iptv
  DB_PASS=mi_clave_super_segura
  ```

### JWT_SECRET

- **Qué es:** clave secreta para firmar los tokens JWT.
- **Cómo se elige:** una cadena larga y aleatoria (mínimo 32 caracteres).
- **Ejemplo:**
  ```
  JWT_SECRET=V9Nn2kQqH0Wm1X2zLr98sK0PzLx7kQmP
  ```

### CACHE_DRIVER

- **Qué es:** sistema de caché.
- **Valores posibles:** `redis` o `file`.
- **Ejemplo (Redis):**
  ```
  CACHE_DRIVER=redis
  ```
- **Ejemplo (archivos):**
  ```
  CACHE_DRIVER=file
  ```

### LOG_PATH

- **Qué es:** ruta del archivo de logs.
- **Cómo se elige:** debe ser escribible por el usuario del servicio.
- **Ejemplo:**
  ```
  LOG_PATH=/var/log/iptv/app.log
  ```
