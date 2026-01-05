<?php

declare(strict_types=1);

namespace App\Modules\ApiModule;

use App\Infrastructure\Config\Config;
use App\Infrastructure\RateLimiter\RateLimiter;
use App\Modules\AuthModule\AuthService;
use App\Modules\MediaModule\MediaService;
use App\Modules\UserModule\UserService;

final class ApiRoutes
{
    public function __construct(
        private ApiController $controller,
        private Config $config
    ) {
    }

    /**
     * @return array<string, callable>
     */
    public function routes(): array
    {
        return [
            'GET /health' => [$this->controller, 'health'],
            'GET /player_api.php' => [$this->controller, 'playerApi'],
            'GET /get.php' => [$this->controller, 'playlist'],
            'GET /xmltv.php' => [$this->controller, 'epg'],
            'GET /metrics' => [$this->controller, 'metrics'],
        ];
    }
}
