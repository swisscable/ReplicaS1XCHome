<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

final class Request
{
    /** @var array<string, string> */
    private array $query;

    /** @var array<string, mixed> */
    private array $body;

    /** @var array<string, string> */
    private array $headers;

    private string $method;

    private string $path;

    public function __construct()
    {
        $this->query = $_GET;
        $this->body = $_POST;
        $this->headers = $this->collectHeaders();
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    }

    public function getMethod(): string
    {
        return strtoupper($this->method);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQueryParam(string $key, ?string $default = null): ?string
    {
        $value = $this->query[$key] ?? $default;
        return is_string($value) ? $value : $default;
    }

    public function getBodyParam(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function getHeader(string $key, ?string $default = null): ?string
    {
        $normalized = strtolower($key);
        foreach ($this->headers as $header => $value) {
            if (strtolower($header) === $normalized) {
                return $value;
            }
        }
        return $default;
    }

    /**
     * @return array<string, string>
     */
    private function collectHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $header = str_replace('_', '-', substr($key, 5));
                $headers[$header] = (string) $value;
            }
        }
        return $headers;
    }
}
