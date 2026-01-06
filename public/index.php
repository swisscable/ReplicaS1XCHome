<?php

declare(strict_types=1);

use App\Bootstrap\App;
use App\Bootstrap\ContainerFactory;
use App\Bootstrap\Router;
use App\Modules\ApiModule\ApiRoutes;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__), '.env');
$dotenv->safeLoad();

$container = (new ContainerFactory())->build($_ENV);

$app = new App(
    $container->get(App\Infrastructure\Config\Config::class),
    new Router(),
    new ApiRoutes($container)
);

$app->run();
