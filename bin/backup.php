<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__), '.env');
$dotenv->safeLoad();

$backupPath = getenv('BACKUP_PATH') ?: __DIR__ . '/../storage/backups';
if (!is_dir($backupPath)) {
    mkdir($backupPath, 0775, true);
}

$date = date('Ymd_His');
$driver = getenv('DB_DRIVER') ?: 'mysql';

if ($driver === 'sqlite') {
    $sqlitePath = getenv('SQLITE_PATH') ?: __DIR__ . '/../storage/iptv.sqlite';
    $destination = sprintf('%s/iptv_%s.sqlite', rtrim($backupPath, '/'), $date);
    if (!copy($sqlitePath, $destination)) {
        fwrite(STDERR, "Failed to copy SQLite database.\n");
        exit(1);
    }
    echo "SQLite backup written to {$destination}\n";
    exit(0);
}

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME') ?: 'iptv';
$user = getenv('DB_USER') ?: 'iptv';
$pass = getenv('DB_PASS') ?: '';

$destination = sprintf('%s/iptv_%s.sql', rtrim($backupPath, '/'), $date);
$command = sprintf(
    'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
    escapeshellarg($host),
    escapeshellarg($port),
    escapeshellarg($user),
    escapeshellarg($pass),
    escapeshellarg($name),
    escapeshellarg($destination)
);

passthru($command, $exitCode);
if ($exitCode !== 0) {
    fwrite(STDERR, "Backup failed.\n");
    exit($exitCode);
}

echo "Database backup written to {$destination}\n";
