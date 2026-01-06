<?php

declare(strict_types=1);

namespace App\Modules\ApiModule;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use Psr\Container\ContainerInterface;

final class ApiRoutes
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    /**
     * @return array<string, callable>
     */
    public function routes(): array
    {
        return [
            'GET /health' => function (Request $request, Response $response): void {
                $response->json(['status' => 'ok', 'timestamp' => time()]);
            },
            'GET /player_api.php' => function (Request $request, Response $response): void {
                $this->container->get(ApiController::class)->playerApi($request, $response);
            },
            'GET /get.php' => function (Request $request, Response $response): void {
                $this->container->get(ApiController::class)->playlist($request, $response);
            },
            'GET /xmltv.php' => function (Request $request, Response $response): void {
                $this->container->get(ApiController::class)->epg($request, $response);
            },
            'GET /metrics' => function (Request $request, Response $response): void {
                $this->container->get(ApiController::class)->metrics($request, $response);
            },
        ];
    }
}
