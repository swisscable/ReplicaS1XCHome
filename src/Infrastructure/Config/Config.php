<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

final class Config
{
    /** @var array<string, mixed> */
    private array $values;

    /**
     * @param array<string, mixed> $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function getString(string $key, string $default = ''): string
    {
        $value = $this->values[$key] ?? $default;
        return is_string($value) ? $value : $default;
    }

    public function getInt(string $key, int $default = 0): int
    {
        $value = $this->values[$key] ?? $default;
        return is_numeric($value) ? (int) $value : $default;
    }

    public function getBool(string $key, bool $default = false): bool
    {
        $value = $this->values[$key] ?? $default;
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default;
        }

        return $default;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->values;
    }
}
