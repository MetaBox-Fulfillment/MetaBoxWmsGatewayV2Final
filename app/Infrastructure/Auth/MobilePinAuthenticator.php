<?php

namespace App\Infrastructure\Auth;

use App\Domain\Auth\PinAuthenticator;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;

class MobilePinAuthenticator implements PinAuthenticator
{
    public function attemptWithPin(string $pin): ?User
    {
        // Keressük meg a felhasználót a PIN kód alapján
        // Mivel a PIN kód egyedi és hash-elve van tárolva, végig kell iterálni
        return User::whereNotNull('pin_code')
            ->get()
            ->first(function (User $user) use ($pin) {
                return Hash::check($pin, $user->pin_code);
            });
    }
}
