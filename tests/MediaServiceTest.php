<?php

declare(strict_types=1);

namespace App\Tests;

use App\Infrastructure\Config\Config;
use App\Modules\MediaModule\MediaRepository;
use App\Modules\MediaModule\MediaService;
use PHPUnit\Framework\TestCase;

final class MediaServiceTest extends TestCase
{
    public function testBuildPlaylistIncludesStreams(): void
    {
        $repository = $this->createMock(MediaRepository::class);
        $repository->method('listLiveStreams')->willReturn([
            ['id' => 101, 'name' => 'News', 'category' => 'Info', 'logo_url' => ''],
        ]);

        $service = new MediaService($repository, new Config(['APP_URL' => 'http://localhost:8080']));

        $playlist = $service->buildPlaylist([
            'username' => 'demo',
            'password' => 'secret',
        ], 'ts');

        self::assertStringContainsString('#EXTM3U', $playlist);
        self::assertStringContainsString('News', $playlist);
        self::assertStringContainsString('/live/demo/secret/101.ts', $playlist);
    }
}
