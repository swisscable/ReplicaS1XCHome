<?php

declare(strict_types=1);

namespace App\Modules\AuthModule;

use App\Infrastructure\Config\Config;
use App\Infrastructure\Security\JwtService;

final class AuthService
{
    public function __construct(private JwtService $jwtService, private Config $config)
    {
    }

    /**
     * @param array<string, mixed> $user
     * @return array<string, mixed>
     */
    public function buildAuthPayload(array $user): array
    {
        $token = $this->jwtService->issueToken([
            'sub' => (string) $user['id'],
            'username' => $user['username'],
        ]);

        return [
            'user_info' => [
                'auth' => 1,
                'username' => $user['username'],
                'status' => $user['status'],
                'exp_date' => $user['expires_at'],
                'is_trial' => $user['is_trial'],
                'active_cons' => 0,
                'created_at' => $user['created_at'],
                'max_connections' => $user['max_connections'],
                'password' => $user['password'],
                'allowed_output_formats' => ['ts', 'm3u8'],
                'jwt' => $token,
            ],
            'server_info' => [
                'url' => $this->config->getString('APP_URL', 'http://localhost:8080'),
                'timezone' => date_default_timezone_get(),
                'timestamp_now' => time(),
                'time_now' => date('Y-m-d H:i:s'),
            ],
        ];
    }
}
