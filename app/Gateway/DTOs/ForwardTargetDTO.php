<?php

namespace App\Gateway\DTOs;

final readonly class ForwardTargetDTO
{
    public function __construct(
        public string $service,
        public string $base_url,
        public string $path,
        public int $timeout_seconds,
    ) {}
}
