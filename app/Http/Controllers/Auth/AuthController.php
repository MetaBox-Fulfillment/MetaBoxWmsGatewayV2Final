<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $auth_service,
        private readonly LoginService $login_service
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->login_service->login(
            $request,
            $request->toDto()
        );

        return response()->json([
            'message' => 'Sikeres bejelentkezés.',
            'user' => new UserResource($user),
        ]);
    }

    public function me(): JsonResponse
    {
        $user = $this->auth_service->me();

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }
}
