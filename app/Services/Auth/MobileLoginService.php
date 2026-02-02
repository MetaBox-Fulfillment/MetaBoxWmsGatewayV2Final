<?php

namespace App\Services\Auth;

use App\Domain\Auth\PinAuthenticator;
use App\Models\User\User;

final readonly class MobileLoginService
{
    public function __construct(
        private PinAuthenticator $authenticator
    ) {}

    public function login(string $pin): array
    {
        $user = $this->authenticator->attemptWithPin($pin);

        if (! $user) {
            abort(422, 'Hibás PIN kód.');
        }

        $token = $user->createToken('mobile-app', ['mobile'])->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
