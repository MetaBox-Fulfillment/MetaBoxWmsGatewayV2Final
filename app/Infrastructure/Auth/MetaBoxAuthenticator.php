<?php

namespace App\Infrastructure\Auth;

use App\Domain\Auth\Authenticator;
use App\Domain\DTOs\User\LoginDTO;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class MetaBoxAuthenticator implements Authenticator
{
    public function attempt(LoginDTO $dto): ?User
    {
        if (! Auth::attempt(
            ['email' => $dto->email, 'password' => $dto->password],
            $dto->remember
        )) {
            return null;
        }

        /** @var User $user */
        return Auth::user();
    }
}
