# Backups & Recovery

## Manual Backup

```bash
php bin/backup.php
```

Backups are stored in `BACKUP_PATH`.

## Recovery

- **MySQL/MariaDB**: `mysql < backup.sql`
- **SQLite**: replace `SQLITE_PATH` with a backup copy
