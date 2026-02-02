<?php

namespace App\Infrastructure\Auth;

use App\Domain\Auth\AuthContext;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class MtbxAuthContext implements AuthContext
{
    public function user(): ?User
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user;
    }
}
