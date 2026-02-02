<?php

namespace App\Infrastructure\Persistence;

use App\Domain\DTOs\User\UserDTO;
use App\Domain\Repositories\User\UserRepository;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepository
{
    public function paginate(
        ?string $search = null,
        ?string $type = null,
        ?string $status = null,
        int $per_page = 15,
    ): LengthAwarePaginator {
        return User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($type, fn($query, $type) => $query->where('type', $type))
            ->when($status, fn($query, $status) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate($per_page);
    }

    public function create(UserDTO $dto): User
    {
        return User::create([
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name,
            'email' => $dto->email,
            'password' => $dto->password,
            'type' => $dto->type,
            'status' => $dto->status,
            'partner_id' => $dto->partner_id,
            'worker_id' => $dto->worker_id,
        ]);
    }
}
