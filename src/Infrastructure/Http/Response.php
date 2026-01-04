<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

final class Response
{
    public function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function text(string $payload, int $status = 200, string $contentType = 'text/plain'): void
    {
        http_response_code($status);
        header('Content-Type: ' . $contentType);
        echo $payload;
    }
}
