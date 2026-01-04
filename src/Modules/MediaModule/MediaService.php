<?php

declare(strict_types=1);

namespace App\Modules\MediaModule;

use App\Infrastructure\Config\Config;

final class MediaService
{
    public function __construct(private MediaRepository $repository, private Config $config)
    {
    }

    /**
     * @param int $userId
     * @return array<int, array<string, mixed>>
     */
    public function listLiveStreams(int $userId): array
    {
        return $this->repository->listLiveStreams();
    }

    /**
     * @param int $userId
     * @return array<int, array<string, mixed>>
     */
    public function listVodStreams(int $userId): array
    {
        return $this->repository->listVodStreams();
    }

    /**
     * @param int $userId
     * @return array<int, array<string, mixed>>
     */
    public function listSeries(int $userId): array
    {
        return $this->repository->listSeries();
    }

    /**
     * @param array<string, mixed> $user
     */
    public function buildPlaylist(array $user, string $output): string
    {
        $baseUrl = rtrim($this->config->getString('APP_URL', 'http://localhost:8080'), '/');
        $streams = $this->repository->listLiveStreams();

        $lines = ["#EXTM3U"];
        foreach ($streams as $stream) {
            $lines[] = sprintf(
                '#EXTINF:-1 tvg-id="%s" tvg-logo="%s" group-title="%s",%s',
                $stream['id'] ?? '',
                $stream['logo_url'] ?? '',
                $stream['category'] ?? 'General',
                $stream['name'] ?? 'Stream'
            );
            $lines[] = sprintf(
                '%s/live/%s/%s/%s.%s',
                $baseUrl,
                $user['username'],
                $user['password'],
                $stream['id'],
                $output
            );
        }

        return implode("\n", $lines) . "\n";
    }

    public function buildEpg(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?><tv></tv>';
    }

    /**
     * @return array<string, mixed>
     */
    public function serverInfo(): array
    {
        return [
            'url' => $this->config->getString('APP_URL', 'http://localhost:8080'),
            'timezone' => date_default_timezone_get(),
            'timestamp_now' => time(),
            'time_now' => date('Y-m-d H:i:s'),
        ];
    }
}
