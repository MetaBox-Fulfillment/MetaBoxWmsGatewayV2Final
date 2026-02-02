<?php

namespace App\Services\Auth;

use App\Models\User\User;

final readonly class PinService
{
    public function setPin(User $user, string $pin): void
    {
        $user->update(['pin_code' => $pin]);
    }

    public function removePin(User $user): void
    {
        $user->update(['pin_code' => null]);
    }

    public function hasPin(User $user): bool
    {
        return $user->pin_code !== null;
    }
}
