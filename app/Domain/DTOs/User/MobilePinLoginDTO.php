<?php

namespace App\Domain\DTOs\User;

final readonly class MobilePinLoginDTO
{
    public function __construct(
        public string $identifier,
        public string $pin,
        public string $device_name,
    ) {}
}
