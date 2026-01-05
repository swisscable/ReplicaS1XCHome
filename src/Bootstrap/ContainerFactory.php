<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Infrastructure\Cache\CacheInterface;
use App\Infrastructure\Cache\FileCache;
use App\Infrastructure\Cache\RedisCache;
use App\Infrastructure\Config\Config;
use App\Infrastructure\Database\ConnectionFactory;
use App\Infrastructure\Logging\LoggerFactory;
use App\Infrastructure\RateLimiter\RateLimiter;
use DI\ContainerBuilder;
use PDO;
use Redis;

final class ContainerFactory
{
    public function build(array $env): \Psr\Container\ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            Config::class => fn () => new Config($env),
            PDO::class => function (Config $config): PDO {
                return (new ConnectionFactory())->create($config);
            },
            CacheInterface::class => function (Config $config): CacheInterface {
                $driver = $config->getString('CACHE_DRIVER', 'file');
                if ($driver === 'redis' && extension_loaded('redis')) {
                    $redis = new Redis();
                    $redis->connect(
                        $config->getString('REDIS_HOST', '127.0.0.1'),
                        $config->getInt('REDIS_PORT', 6379)
                    );
                    $redis->select($config->getInt('REDIS_DB', 0));
                    return new RedisCache($redis);
                }

                return new FileCache($config->getString('CACHE_PATH', __DIR__ . '/../../../cache'));
            },
            RateLimiter::class => fn (CacheInterface $cache) => new RateLimiter($cache),
            \Psr\Log\LoggerInterface::class => function (Config $config) {
                return (new LoggerFactory())->create($config);
            },
        ]);

        return $builder->build();
    }
}
