<?php

declare(strict_types=1);

namespace App\Infrastructure\RateLimiter;

use App\Infrastructure\Cache\CacheInterface;

final class RateLimiter
{
    public function __construct(private CacheInterface $cache)
    {
    }

    public function tooManyAttempts(string $key, int $maxAttempts, int $windowSeconds): bool
    {
        $data = $this->cache->get($key) ?? ['count' => 0, 'reset_at' => time() + $windowSeconds];

        if (time() > $data['reset_at']) {
            $data = ['count' => 0, 'reset_at' => time() + $windowSeconds];
        }

        $data['count']++;
        $this->cache->set($key, $data, $windowSeconds);

        return $data['count'] > $maxAttempts;
    }
}
