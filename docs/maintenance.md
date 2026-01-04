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
