<?php

declare(strict_types=1);

namespace App\Infrastructure\Logging;

use App\Infrastructure\Config\Config;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    public function create(Config $config): LoggerInterface
    {
        $path = $config->getString('LOG_PATH', __DIR__ . '/../../../logs/app.log');
        $levelName = strtoupper($config->getString('LOG_LEVEL', 'INFO'));
        $level = Logger::toMonologLevel($levelName);

        $logger = new Logger('iptv');
        $logger->pushHandler(new StreamHandler($path, $level));

        return $logger;
    }
}
