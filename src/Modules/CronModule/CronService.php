<?php

declare(strict_types=1);

namespace App\Modules\CronModule;

use Psr\Log\LoggerInterface;

final class CronService
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function runDailyMaintenance(): void
    {
        $this->logger->info('Running daily maintenance tasks.');
        // Placeholder for cleanup, expired user deactivation, EPG refresh, etc.
    }
}
