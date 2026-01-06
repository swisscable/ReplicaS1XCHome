<?php

declare(strict_types=1);

use App\Bootstrap\ContainerFactory;
use App\Modules\CronModule\CronService;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__), '.env');
$dotenv->safeLoad();

$container = (new ContainerFactory())->build($_ENV);

$service = new CronService($container->get(Psr\Log\LoggerInterface::class));

$task = $argv[1] ?? 'daily';

switch ($task) {
    case 'daily':
        $service->runDailyMaintenance();
        echo "Daily maintenance completed.\n";
        break;
    default:
        echo "Unknown task. Available: daily\n";
        exit(1);
}
