<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

final class FileCache implements CacheInterface
{
    public function __construct(private string $cachePath)
    {
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0775, true);
        }
    }

    public function get(string $key): mixed
    {
        $path = $this->pathForKey($key);
        if (!is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return null;
        }

        $payload = json_decode($contents, true);
        if (!is_array($payload) || !isset($payload['expires_at'])) {
            return null;
        }

        if (time() > (int) $payload['expires_at']) {
            @unlink($path);
            return null;
        }

        return $payload['value'] ?? null;
    }

    public function set(string $key, mixed $value, int $ttlSeconds = 3600): void
    {
        $path = $this->pathForKey($key);
        $payload = [
            'expires_at' => time() + $ttlSeconds,
            'value' => $value,
        ];
        file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT));
    }

    public function delete(string $key): void
    {
        $path = $this->pathForKey($key);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function pathForKey(string $key): string
    {
        return rtrim($this->cachePath, '/') . '/' . hash('sha256', $key) . '.json';
    }
}
