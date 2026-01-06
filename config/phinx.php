<?php

declare(strict_types=1);

return [
    'paths' => [
        'migrations' => __DIR__ . '/../storage/migrations',
        'seeds' => __DIR__ . '/../storage/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'production',
        'production' => [
            'adapter' => getenv('DB_DRIVER') ?: 'mysql',
            'host' => getenv('DB_HOST') ?: 'localhost',
            'name' => getenv('DB_NAME') ?: 'iptv',
            'user' => getenv('DB_USER') ?: 'iptv',
            'pass' => getenv('DB_PASS') ?: '',
            'port' => (int) (getenv('DB_PORT') ?: 3306),
            'charset' => 'utf8mb4',
        ],
        'sqlite' => [
            'adapter' => 'sqlite',
            'name' => getenv('SQLITE_PATH') ?: __DIR__ . '/../storage/iptv.sqlite',
        ],
    ],
];
