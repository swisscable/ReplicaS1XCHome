<?php

declare(strict_types=1);

use App\Bootstrap\App;
use App\Bootstrap\ContainerFactory;
use App\Bootstrap\Router;
use App\Modules\ApiModule\ApiRoutes;
use App\Modules\ApiModule\ApiController;
use App\Infrastructure\Validation\InputValidator;
use App\Infrastructure\Security\JwtService;
use App\Modules\AuthModule\AuthService;
use App\Modules\MediaModule\MediaRepository;
use App\Modules\MediaModule\MediaService;
use App\Modules\UserModule\UserRepository;
use App\Modules\UserModule\UserService;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__), '.env');
$dotenv->safeLoad();

$container = (new ContainerFactory())->build($_ENV);

$apiController = new ApiController(
    new AuthService(new JwtService($container->get(App\Infrastructure\Config\Config::class)), $container->get(App\Infrastructure\Config\Config::class)),
    new UserService(new UserRepository($container->get(PDO::class))),
    new MediaService(new MediaRepository($container->get(PDO::class)), $container->get(App\Infrastructure\Config\Config::class)),
    $container->get(App\Infrastructure\RateLimiter\RateLimiter::class),
    new InputValidator(),
    $container->get(App\Infrastructure\Config\Config::class),
    $container->get(Psr\Log\LoggerInterface::class)
);

$app = new App(
    $container->get(App\Infrastructure\Config\Config::class),
    new Router(),
    new ApiRoutes($apiController, $container->get(App\Infrastructure\Config\Config::class))
);

$app->run();
