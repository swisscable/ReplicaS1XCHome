<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use App\Infrastructure\Config\Config;
use PDO;
use PDOException;

final class ConnectionFactory
{
    public function create(Config $config): PDO
    {
        $driver = $config->getString('DB_DRIVER', 'mysql');

        if ($driver === 'sqlite') {
            $path = $config->getString('SQLITE_PATH', __DIR__ . '/../../../storage/iptv.sqlite');
            $pdo = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $pdo->exec('PRAGMA foreign_keys = ON');
            return $pdo;
        }

        $host = $config->getString('DB_HOST', 'localhost');
        $port = $config->getInt('DB_PORT', 3306);
        $db = $config->getString('DB_NAME', 'iptv');
        $user = $config->getString('DB_USER', 'iptv');
        $pass = $config->getString('DB_PASS', '');

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $db);

        try {
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new PDOException('Database connection failed. Verify credentials and availability.', (int) $exception->getCode());
        }
    }
}
