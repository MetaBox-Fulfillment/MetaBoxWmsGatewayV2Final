<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SetPinRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User\User;
use App\Services\Auth\PinService;
use App\Services\User\UserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        private readonly UserManagementService $service,
        private readonly PinService $pin_service,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $users = $this->service->getUsers(
            search: $request->query('search'),
            type: $request->query('type'),
            status: $request->query('status'),
            per_page: (int) $request->query('per_page', 15),
        );

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->service->createUser(
            actor: $request->user(),
            data: $request->toDto()
        );

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function setPin(SetPinRequest $request, User $user): JsonResponse
    {
        $this->pin_service->setPin(
            $user,
            $request->string('pin')->toString()
        );

        return response()->json([
            'message' => 'PIN kód sikeresen beállítva.',
        ]);
    }

    public function removePin(User $user): JsonResponse
    {
        $this->pin_service->removePin($user);

        return response()->json([
            'message' => 'PIN kód sikeresen törölve.',
        ]);
    }
}
