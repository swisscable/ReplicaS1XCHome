<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

final class InputValidator
{
    public function requireString(?string $value, string $fieldName): string
    {
        if ($value === null || trim($value) === '') {
            throw new \InvalidArgumentException(sprintf('Missing required field: %s', $fieldName));
        }

        return trim($value);
    }

    public function sanitizeIdentifier(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9_-]/', '', $value) ?? '';
    }
}
