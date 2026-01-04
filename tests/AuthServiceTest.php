<?php

declare(strict_types=1);

namespace App\Tests;

use App\Infrastructure\Config\Config;
use App\Infrastructure\Security\JwtService;
use App\Modules\AuthModule\AuthService;
use PHPUnit\Framework\TestCase;

final class AuthServiceTest extends TestCase
{
    public function testBuildAuthPayloadContainsJwt(): void
    {
        $config = new Config([
            'JWT_SECRET' => 'test_secret',
            'JWT_TTL_SECONDS' => 3600,
            'APP_URL' => 'http://localhost:8080',
        ]);

        $service = new AuthService(new JwtService($config), $config);

        $payload = $service->buildAuthPayload([
            'id' => 1,
            'username' => 'demo',
            'password' => 'hash',
            'status' => 'active',
            'expires_at' => time() + 3600,
            'is_trial' => 0,
            'created_at' => time(),
            'max_connections' => 1,
        ]);

        self::assertSame(1, $payload['user_info']['auth']);
        self::assertNotEmpty($payload['user_info']['jwt']);
    }
}
