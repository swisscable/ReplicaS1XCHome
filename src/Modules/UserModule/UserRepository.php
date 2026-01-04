<?php

declare(strict_types=1);

namespace App\Modules\UserModule;

use PDO;

final class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByCredentials(string $username, string $password): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, username, password, status, expires_at, is_trial, created_at, max_connections
             FROM users
             WHERE username = :username AND password = :password AND status = "active"'
        );
        $statement->execute([
            'username' => $username,
            'password' => hash('sha256', $password),
        ]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listActiveUsers(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, username, status, expires_at, is_trial, created_at, max_connections FROM users'
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
