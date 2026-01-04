<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Infrastructure\Config\Config;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class JwtService
{
    public function __construct(private Config $config)
    {
    }

    /**
     * @param array<string, mixed> $claims
     */
    public function issueToken(array $claims): string
    {
        $secret = $this->config->getString('JWT_SECRET');
        $ttl = $this->config->getInt('JWT_TTL_SECONDS', 3600);

        $payload = array_merge($claims, [
            'iat' => time(),
            'exp' => time() + $ttl,
        ]);

        return JWT::encode($payload, $secret, 'HS256');
    }

    /**
     * @return array<string, mixed>
     */
    public function decodeToken(string $token): array
    {
        $secret = $this->config->getString('JWT_SECRET');
        return (array) JWT::decode($token, new Key($secret, 'HS256'));
    }
}
