<?php

namespace App\Domain\Auth;

use App\Models\User\User;

interface  AuthContext
{
    public function user(): ?User;
}
