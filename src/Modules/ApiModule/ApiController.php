<?php

declare(strict_types=1);

namespace App\Modules\ApiModule;

use App\Infrastructure\Config\Config;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use App\Infrastructure\RateLimiter\RateLimiter;
use App\Infrastructure\Validation\InputValidator;
use App\Modules\AuthModule\AuthService;
use App\Modules\MediaModule\MediaService;
use App\Modules\UserModule\UserService;
use Psr\Log\LoggerInterface;

final class ApiController
{
    public function __construct(
        private AuthService $authService,
        private UserService $userService,
        private MediaService $mediaService,
        private RateLimiter $rateLimiter,
        private InputValidator $validator,
        private Config $config,
        private LoggerInterface $logger
    ) {
    }

    public function health(Request $request, Response $response): void
    {
        $response->json(['status' => 'ok', 'timestamp' => time()]);
    }

    public function playerApi(Request $request, Response $response): void
    {
        $username = $request->getQueryParam('username');
        $password = $request->getQueryParam('password');
        $action = $request->getQueryParam('action');

        try {
            $username = $this->validator->requireString($username, 'username');
            $password = $this->validator->requireString($password, 'password');
        } catch (\InvalidArgumentException $exception) {
            $response->json(['error' => $exception->getMessage()], 400);
            return;
        }

        $rateKey = sprintf('auth:%s', $username);
        $limit = $this->config->getInt('RATE_LIMIT_PER_MINUTE', 60);
        if ($this->rateLimiter->tooManyAttempts($rateKey, $limit, 60)) {
            $response->json(['error' => 'Too many attempts'], 429);
            return;
        }

        $user = $this->userService->authenticate($username, $password);
        if ($user === null) {
            $response->json(['user_info' => ['auth' => 0], 'server_info' => $this->mediaService->serverInfo()]);
            return;
        }

        if ($action === null) {
            $payload = $this->authService->buildAuthPayload($user);
            $response->json($payload);
            return;
        }

        $payload = match ($action) {
            'get_live_streams' => $this->mediaService->listLiveStreams($user['id']),
            'get_vod_streams' => $this->mediaService->listVodStreams($user['id']),
            'get_series' => $this->mediaService->listSeries($user['id']),
            default => ['error' => 'Unsupported action'],
        };

        $response->json($payload);
    }

    public function playlist(Request $request, Response $response): void
    {
        $username = $request->getQueryParam('username');
        $password = $request->getQueryParam('password');
        $output = $request->getQueryParam('output', 'ts');

        try {
            $username = $this->validator->requireString($username, 'username');
            $password = $this->validator->requireString($password, 'password');
        } catch (\InvalidArgumentException $exception) {
            $response->text('#EXTM3U\n# Invalid credentials', 400);
            return;
        }

        $user = $this->userService->authenticate($username, $password);
        if ($user === null) {
            $response->text('#EXTM3U\n# Authentication failed', 401);
            return;
        }

        $playlist = $this->mediaService->buildPlaylist($user, $output);
        $response->text($playlist, 200, 'audio/x-mpegurl');
    }

    public function epg(Request $request, Response $response): void
    {
        $username = $request->getQueryParam('username');
        $password = $request->getQueryParam('password');

        try {
            $username = $this->validator->requireString($username, 'username');
            $password = $this->validator->requireString($password, 'password');
        } catch (\InvalidArgumentException $exception) {
            $response->text('<?xml version="1.0" encoding="UTF-8"?><tv></tv>', 400, 'application/xml');
            return;
        }

        $user = $this->userService->authenticate($username, $password);
        if ($user === null) {
            $response->text('<?xml version="1.0" encoding="UTF-8"?><tv></tv>', 401, 'application/xml');
            return;
        }

        $epg = $this->mediaService->buildEpg();
        $response->text($epg, 200, 'application/xml');
    }

    public function metrics(Request $request, Response $response): void
    {
        if (!$this->config->getBool('METRICS_ENABLED', false)) {
            $response->text('metrics disabled', 404);
            return;
        }

        $response->text("iptv_up 1\n", 200, 'text/plain; version=0.0.4');
    }
}
