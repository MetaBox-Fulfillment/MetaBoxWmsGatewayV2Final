<?php

namespace App\Gateway\Routing;

use App\Gateway\Contracts\RouteResolver;
use App\Gateway\Contracts\ServiceRegistry;
use App\Gateway\DTOs\ForwardTargetDTO;
use Illuminate\Http\Request;

final readonly class ConfigRouteResolver implements RouteResolver
{
    public function __construct(
        private ServiceRegistry $registry
    ) {}

    public function resolve(Request $request): ?ForwardTargetDTO
    {
        $path = ltrim($request->path(), '/');
        $routes = config('microservices.routes', []);

        foreach ($routes as $route) {
            $prefix = $route['match']['prefix'] ?? null;
            if (!$prefix) {
                continue;
            }

            if (!str_starts_with($path, ltrim($prefix, '/')) && !str_starts_with($path, 'api/'.ltrim($prefix, '/'))) {
                continue;
            }

            $service = $route['service'];

            $strip = ltrim((string) ($route['strip_prefix'] ?? $prefix), '/');

            $normalized = $path;
            if (str_starts_with($normalized, 'api/')) {
                $normalized = substr($normalized, 4);
            }

            if ($strip !== '' && str_starts_with($normalized, $strip)) {
                $normalized = ltrim(substr($normalized, strlen($strip)), '/');
            }

            $target_path = '/'.ltrim($normalized, '/');

            return new ForwardTargetDTO(
                service: $service,
                base_url: $this->registry->getBaseUrl($service),
                path: $target_path,
                timeout_seconds: $this->registry->getTimeout($service),
            );
        }

        return null;
    }
}
