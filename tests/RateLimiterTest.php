<?php

declare(strict_types=1);

namespace App\Tests;

use App\Infrastructure\Cache\FileCache;
use App\Infrastructure\RateLimiter\RateLimiter;
use PHPUnit\Framework\TestCase;

final class RateLimiterTest extends TestCase
{
    public function testRateLimiterBlocksAfterLimit(): void
    {
        $cachePath = sys_get_temp_dir() . '/iptv_cache_' . uniqid();
        $cache = new FileCache($cachePath);
        $limiter = new RateLimiter($cache);

        $blocked = false;
        for ($i = 0; $i < 3; $i++) {
            $blocked = $limiter->tooManyAttempts('user:1', 2, 60);
        }

        self::assertTrue($blocked);
    }
}
