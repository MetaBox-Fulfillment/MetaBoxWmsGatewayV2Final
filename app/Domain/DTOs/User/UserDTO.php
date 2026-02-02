<?php

namespace App\Domain\DTOs\User;

use App\Domain\Enums\UserStatusEnum;
use App\Domain\Enums\UserTypeEnum;

final readonly class UserDTO
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $password,
        public UserTypeEnum $type,
        public UserStatusEnum $status,
        public ?int $partner_id,
        public ?int $worker_id,
    ) {}
}
