<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Infrastructure\Config\Config;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use App\Modules\ApiModule\ApiRoutes;

final class App
{
    public function __construct(
        private Config $config,
        private Router $router,
        private ApiRoutes $apiRoutes
    ) {
    }

    public function run(): void
    {
        $request = new Request();
        $response = new Response();

        if ($this->config->getBool('ENABLE_HTTPS_REDIRECT', false)) {
            $this->enforceHttps($request);
        }

        $routes = $this->apiRoutes->routes();
        $this->router->dispatch($request, $response, $routes);
    }

    private function enforceHttps(Request $request): void
    {
        $proto = $request->getHeader('X-Forwarded-Proto') ?? '';
        $https = $_SERVER['HTTPS'] ?? '';
        if ($proto !== 'https' && $https !== 'on') {
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $path = $_SERVER['REQUEST_URI'] ?? '/';
            header('Location: https://' . $host . $path, true, 301);
            exit;
        }
    }
}
