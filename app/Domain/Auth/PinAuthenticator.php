<?php

namespace App\Domain\Auth;

use App\Models\User\User;

interface PinAuthenticator
{
    public function attemptWithPin(string $pin): ?User;
}
