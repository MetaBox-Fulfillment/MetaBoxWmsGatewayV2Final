<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\MobileLoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\MobileLoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileAuthController extends Controller
{
    public function __construct(
        private readonly MobileLoginService $login_service,
    ) {}

    public function login(MobileLoginRequest $request): JsonResponse
    {
        $result = $this->login_service->login(
            $request->string('pin')->toString()
        );

        return response()->json([
            'message' => 'Sikeres bejelentkezés.',
            'token' => $result['token'],
            'user' => new UserResource($result['user']),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->login_service->logout($request->user());

        return response()->json([
            'message' => 'Sikeres kijelentkezés.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }
}
