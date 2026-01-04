<?php

declare(strict_types=1);

namespace App\Tests;

use App\Infrastructure\Config\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function testReadsTypedValues(): void
    {
        $config = new Config([
            'APP_DEBUG' => 'true',
            'PORT' => '8080',
            'NAME' => 'iptv',
        ]);

        self::assertTrue($config->getBool('APP_DEBUG'));
        self::assertSame(8080, $config->getInt('PORT'));
        self::assertSame('iptv', $config->getString('NAME'));
    }
}
