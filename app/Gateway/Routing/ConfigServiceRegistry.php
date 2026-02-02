<?php

namespace App\Gateway\Routing;

use App\Gateway\Contracts\ServiceRegistry;
use RuntimeException;

final class ConfigServiceRegistry implements ServiceRegistry
{
    private function service(string $service): array
    {
        $cfg = config("microservices.services.{$service}");
        if (!is_array($cfg)) {
            throw new RuntimeException("Unknown microservice: {$service}");
        }
        return $cfg;
    }

    public function getBaseUrl(string $service): string
    {
        return rtrim($this->service($service)['base_url'], '/');
    }

    public function getTimeout(string $service): int
    {
        return (int) ($this->service($service)['timeout'] ?? 10);
    }

    public function getApiKey(string $service): ?string
    {
        return $this->service($service)['api_key'] ?? null;
    }

    public function getApiKeyHeader(string $service): string
    {
        return $this->service($service)['api_key_header'] ?? 'X-API-Key';
    }
}
