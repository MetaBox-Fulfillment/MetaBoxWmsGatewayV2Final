<?php

namespace App\Services\Auth;

use App\Domain\Auth\Authenticator;
use App\Domain\DTOs\User\LoginDTO;
use App\Http\Requests\User\LoginRequest;
use App\Models\User\User;
use Illuminate\Http\Request;

final readonly class LoginService
{
    public function __construct(
        private Authenticator $authenticator
    ) {}

    public function login(LoginRequest $request, LoginDTO $dto): User
    {
        $user = $this->authenticator->attempt($dto);

        if (! $user) {
            abort(422, 'Hibás email cím vagy jelszó.');
        }

        $request->session()->regenerate();

        return $user;
    }
}
