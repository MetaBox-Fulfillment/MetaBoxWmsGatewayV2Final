<?php

namespace App\Domain\Repositories\User;

use App\Domain\DTOs\User\UserDTO;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepository
{
    public function paginate(
        ?string $search = null,
        ?string $type = null,
        ?string $status = null,
        int $per_page = 15,
    ): LengthAwarePaginator;

    public function create(UserDTO $dto): User;
}
