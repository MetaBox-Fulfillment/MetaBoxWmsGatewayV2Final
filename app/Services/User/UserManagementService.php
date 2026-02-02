<?php

namespace App\Services\User;

use App\Domain\DTOs\User\UserDTO;
use App\Domain\Repositories\User\UserRepository;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

readonly class UserManagementService
{
    public function __construct(private UserRepository $user_repository) {}

    public function getUsers(
        ?string $search = null,
        ?string $type = null,
        ?string $status = null,
        int $per_page = 15,
    ): LengthAwarePaginator {
        return $this->user_repository->paginate(
            search: $search,
            type: $type,
            status: $status,
            per_page: $per_page,
        );
    }

    public function createUser(?User $actor, UserDTO $data): User
    {
        return DB::transaction(function () use ($data) {
            return $this->user_repository->create($data);
        }, 3);

    }
}
