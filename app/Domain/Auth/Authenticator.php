<?php

namespace App\Domain\Auth;

use App\Domain\DTOs\User\LoginDTO;
use App\Models\User\User;

interface Authenticator
{
    public function attempt(LoginDTO $dto): ?User;
}
