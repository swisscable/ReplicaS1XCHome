<?php

declare(strict_types=1);

namespace App\Modules\MediaModule;

use PDO;

final class MediaRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listLiveStreams(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, name, stream_url, category, logo_url FROM streams WHERE type = "live"'
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listVodStreams(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, name, stream_url, category, logo_url FROM streams WHERE type = "vod"'
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listSeries(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, name, metadata FROM series'
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
