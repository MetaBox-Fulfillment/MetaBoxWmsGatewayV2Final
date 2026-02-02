<?php

namespace App\Gateway\Contracts;

interface ServiceRegistry
{
    public function getBaseUrl(string $service): string;
    public function getTimeout(string $service): int;
    public function getApiKey(string $service): ?string;
    public function getApiKeyHeader(string $service): string;
}
