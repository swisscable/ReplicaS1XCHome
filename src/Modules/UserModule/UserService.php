<?php

declare(strict_types=1);

namespace App\Modules\UserModule;

final class UserService
{
    public function __construct(private UserRepository $repository)
    {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function authenticate(string $username, string $password): ?array
    {
        return $this->repository->findByCredentials($username, $password);
    }
}
