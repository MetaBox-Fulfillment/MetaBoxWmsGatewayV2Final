<?php

namespace App\Domain\DTOs\User;

class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember,
    ) {}
}
