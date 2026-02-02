<?php

namespace App\Services\Auth;

use App\Domain\Auth\AuthContext;
use App\Models\User\User;

final readonly class AuthService
{
    public function __construct(
        private AuthContext $auth_context
    ) {}

    public function me(): User
    {
        $user = $this->auth_context->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        return $user;
    }
}
