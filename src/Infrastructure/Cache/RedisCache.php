<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use Redis;

final class RedisCache implements CacheInterface
{
    public function __construct(private Redis $redis)
    {
    }

    public function get(string $key): mixed
    {
        $value = $this->redis->get($key);
        return $value === false ? null : json_decode($value, true);
    }

    public function set(string $key, mixed $value, int $ttlSeconds = 3600): void
    {
        $payload = json_encode($value);
        $this->redis->setex($key, $ttlSeconds, $payload ?: '');
    }

    public function delete(string $key): void
    {
        $this->redis->del($key);
    }
}
