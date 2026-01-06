<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

final class Router
{
    /**
     * @param array<string, callable(Request, Response): void> $routes
     */
    public function dispatch(Request $request, Response $response, array $routes): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) use ($routes): void {
            foreach ($routes as $route => $handler) {
                [$method, $path] = explode(' ', $route, 2);
                $collector->addRoute($method, $path, $handler);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $response->json(['error' => 'Not found'], 404);
                return;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response->json(['error' => 'Method not allowed'], 405);
                return;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $handler($request, $response, $routeInfo[2]);
                return;
        }
    }
}
